from funcion_banesco import verificar_pago_movil
from crear_archivo import generar_recibo_santa_paula
from correo import enviar_recibo_santa_paula

print("==================================================")
print(" SISTEMA DE COBROS - CONDOMINIO SANTA PAULA ")
print("==================================================\n")

# 1. Datos de Entrada (Simulando lo que vendría de una Base de Datos o Interfaz Web)
archivo_txt = "V007114263.txt" 
correo_residente = input("Correo del residente: ")
residente = input("Nombre del propietario: ")
inmueble = input("Ingresa Inmueble (Ej. 12-B): ")
ref_usuario = input("Ingresa la referencia bancaria del pago: ")

# 2. El Director manda al Detective a buscar el pago
datos_del_pago = verificar_pago_movil(ref_usuario, archivo_txt)

# 3. Condicional: Si el detective devolvió datos (no es None), hacemos la magia
if datos_del_pago is not None:
    print("\n--- INICIANDO PROCESO DE FACTURACIÓN ---")
    
    # Extraemos los datos valiosos que nos devolvió el Banco
    fecha_banco = datos_del_pago["fecha"]
    ref_banco = datos_del_pago["referencia"]
    monto_banco = datos_del_pago["monto"]
    
    # Mandamos al Diseñador a crear el PDF con esos datos
    ruta_pdf_creado = generar_recibo_santa_paula(
        residente=residente,
        inmueble=inmueble,
        fecha=fecha_banco,
        referencia=ref_banco,
        monto=monto_banco
    )
    
    # Si el PDF se creó con éxito, mandamos al Mensajero a enviar el correo
    if ruta_pdf_creado:
        enviar_recibo_santa_paula(
            correo_residente=correo_residente,
            residente=residente,
            inmueble=inmueble,
            archivo_pdf=ruta_pdf_creado
        )
else:
    print("\n⚠️ Proceso detenido: No se generará el recibo ni se enviará correo.")

print("\n--- FIN DEL PROGRAMA ---")