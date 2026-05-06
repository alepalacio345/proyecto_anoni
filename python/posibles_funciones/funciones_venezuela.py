from pypdf import PdfReader

# 1. Variables
ref_usuario = input("Ingresa la referencia del pago: ").strip()
archivo_pdf = "persona_bdv_cuenta_0750.pdf"
encontrado = False

print(f"\n--- Analizando archivo con PyPDF: {ref_usuario} ---\n")

try:
    # Abrimos el PDF
    reader = PdfReader(archivo_pdf)

    # Recorremos cada página
    for i, page in enumerate(reader.pages):
        print(f"[Sistema] Escaneando texto de la Página {i+1}...")
        
        # Extraemos TODO el texto de la página
        texto = page.extract_text()
        
        if texto:
            # Dividimos el texto en "palabras" separadas por espacios o saltos de línea
            palabras = texto.replace('\n', ' ').split(' ')
            
            # Limpiamos las palabras para quedarnos solo con posibles referencias
            # (Quitamos comas, puntos, letras extrañas si las hay)
            for palabra in palabras:
                # Limpieza básica: quitar puntos finales o comas pegadas
                candidato = palabra.strip().replace('.', '').replace(',', '')
                
                # FILTRO: Una referencia bancaria suele ser solo números y tener cierta longitud
                # (Ajusta esto si tus referencias tienen letras)
                if not candidato.isdigit(): 
                    continue
                if len(candidato) < 4: # Ignoramos números muy cortos como "1", "20", etc.
                    continue

                # --- AQUI APLICAMOS TU LÓGICA DE COMPARACIÓN ---

                print(candidato)

                # 1. Coincidencia Exacta
                if ref_usuario == candidato:
                    print(f"✅ ¡PAGO ENCONTRADO! (Exacto)")
                    print(f"   Ref. en PDF: {candidato}")
                    print(f"   Página: {i+1}")
                    encontrado = True
                    break

                # 2. Coincidencia Parcial (Interbancario - Últimos 4)
                # Validamos que el candidato sea largo (ej. > 8 dígitos) para no confundir con años (2025) o montos.
                elif len(ref_usuario) >= 4 and len(candidato) >= 8:
                    ultimos_4_usuario = ref_usuario[-4:]
                    
                    if candidato.endswith(ultimos_4_usuario):
                        print(f"✅ ¡PAGO ENCONTRADO! (Por últimos 4 dígitos)")
                        print(f"   Ref. Usuario: {ref_usuario}")
                        print(f"   Ref. en PDF:  {candidato}")
                        print(f"   Página: {i+1}")
                        encontrado = True
                        break
        
        if encontrado:
            break

except FileNotFoundError:
    print("Error: No se encontró el archivo PDF. Verifica el nombre.")
except Exception as e:
    print(f"Ocurrió un error inesperado: {e}")

if not encontrado:
    print("\n❌ Resultado: No se encontró el pago en ninguna página.")

    

