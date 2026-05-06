<?php

function verificar_pago_movil($ref_usuario, $ruta_archivo) {
    echo "\n--- Iniciando búsqueda en el Banco para la Ref: $ref_usuario ---\n";
    $ref_usuario = trim($ref_usuario);

    if (!file_exists($ruta_archivo)) {
        echo "⚠️ ¡Error! No se pudo encontrar el archivo '$ruta_archivo'.\n";
        return null;
    }

    $archivo = fopen($ruta_archivo, "r");
    if ($archivo) {
        while (($linea = fgets($archivo)) !== false) {
            $linea = trim($linea);
            
            // Saltamos líneas vacías o el encabezado
            if (empty($linea) || strpos($linea, "Fecha") !== false) {
                continue;
            }

            // Separamos por espacios (equivalente al split() de Python)
            $columnas = preg_split('/\s+/', $linea);
            
            if (count($columnas) < 4) {
                continue;
            }

            $fecha_archivo = $columnas[0];
            $ref_archivo = $columnas[1];
            
            // Extraemos el monto (penúltima columna) y quitamos el '+'
            $monto_archivo = str_replace('+', '', $columnas[count($columnas) - 2]);

            // Unimos el resto de las columnas para la descripción
            $descripcion_array = array_slice($columnas, 2, count($columnas) - 4);
            $descripcion_completa = strtoupper(implode(" ", $descripcion_array));

            // Validamos tipo de operación
            $es_pago_valido = (strpos($descripcion_completa, "PAGO") !== false || 
                               strpos($descripcion_completa, "TRF") !== false || 
                               strpos($descripcion_completa, "TRANSFERENCIA") !== false);

            if (!$es_pago_valido) {
                continue;
            }

            $tipo_coincidencia = null;

            // Prioridades de coincidencia
            if ($ref_usuario === $ref_archivo) {
                $tipo_coincidencia = "Coincidencia Exacta";
            } elseif (strlen($ref_usuario) >= 6 && strlen($ref_archivo) >= 6) {
                $ultimos_6_usuario = substr($ref_usuario, -6);
                if (str_ends_with($ref_archivo, $ultimos_6_usuario)) {
                    $tipo_coincidencia = "Últimos 6 dígitos";
                }
            } elseif (strlen($ref_usuario) >= 4 && strlen($ref_archivo) >= 4) {
                $ultimos_4_usuario = substr($ref_usuario, -4);
                if (str_ends_with($ref_archivo, $ultimos_4_usuario)) {
                    $tipo_coincidencia = "Últimos 4 dígitos";
                }
            }

            if ($tipo_coincidencia) {
                echo "✅ ¡PAGO ENCONTRADO EN EL ESTADO DE CUENTA!\n";
                fclose($archivo);
                
                // Devolvemos el array (diccionario) con los datos
                return [
                    "fecha" => $fecha_archivo,
                    "referencia" => $ref_archivo,
                    "monto" => $monto_archivo
                ];
            }
        }
        fclose($archivo);
    }

    echo "❌ No se encontró ningún Pago con la referencia: $ref_usuario\n";
    return null;
}
?>