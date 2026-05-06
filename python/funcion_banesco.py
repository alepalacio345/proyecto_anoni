def verificar_pago_movil(ref_usuario, ruta_archivo):
    """
    Busca el pago en el TXT y, si lo encuentra, devuelve un DICCIONARIO 
    con los datos del pago. Si no lo encuentra, devuelve None.
    """
    print(f"\n--- Iniciando búsqueda en el Banco para la Ref: {ref_usuario} ---")
    ref_usuario = ref_usuario.strip()

    try:
        with open(ruta_archivo, 'r', encoding='utf-8') as archivo:
            for linea in archivo:
                linea = linea.strip()
                if not linea or "Fecha" in linea:
                    continue
                
                columnas = linea.split()
                if len(columnas) < 4:
                    continue
                
                fecha_archivo = columnas[0]
                ref_archivo = columnas[1]
                
                # El monto en Banesco es la penúltima columna, le quitamos el símbolo '+'
                monto_archivo = columnas[-2].replace('+', '')
                
                descripcion_completa = " ".join(columnas[2:]).upper()

                es_pago_valido = ("PAGO" in descripcion_completa or 
                                  "TRF" in descripcion_completa or 
                                  "TRANSFERENCIA" in descripcion_completa)
                
                if not es_pago_valido:
                    continue

                tipo_coincidencia = None

                if ref_usuario == ref_archivo:
                    tipo_coincidencia = "Coincidencia Exacta"
                elif len(ref_usuario) >= 6 and len(ref_archivo) >= 6:
                    ultimos_6_usuario = ref_usuario[-6:]
                    if ref_archivo.endswith(ultimos_6_usuario):
                        tipo_coincidencia = "Últimos 6 dígitos"
                elif len(ref_usuario) >= 4 and len(ref_archivo) >= 4:
                    ultimos_4_usuario = ref_usuario[-4:]
                    if ref_archivo.endswith(ultimos_4_usuario):
                        tipo_coincidencia = "Últimos 4 dígitos"

                if tipo_coincidencia:
                    print("✅ ¡PAGO ENCONTRADO EN EL ESTADO DE CUENTA!")
                    
                    # EN LUGAR DE LLAMAR FUNCIONES AQUÍ, DEVOLVEMOS LOS DATOS
                    return {
                        "fecha": fecha_archivo,
                        "referencia": ref_archivo,
                        "monto": monto_archivo
                    }

        print(f"❌ No se encontró ningún Pago con la referencia: {ref_usuario}")
        return None # Retornamos None (Nada) para indicar que falló

    except FileNotFoundError:
        print(f"⚠️ ¡Error! No se pudo encontrar el archivo '{ruta_archivo}'.")
        return None