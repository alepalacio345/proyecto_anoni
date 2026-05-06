import yagmail
from crear_archivo import generar_recibo_santa_paula

def enviar_recibo_santa_paula(correo_residente, residente, inmueble, archivo_pdf):

    correo_condominio = "saullarapalacio@gmail.com"
    password_app = "fqxp bxsn gvvl pzyz"
    
    print(f"Enviando correo a {correo_residente}...")

    try:
        # Iniciamos la conexión con Gmail
        yag = yagmail.SMTP(correo_condominio, password_app)

        # 2. Diseño del mensaje en HTML
        # Usamos los mismos colores del PDF para mantener la identidad visual
        cuerpo_html = f"""
        <div style="font-family: Arial, sans-serif; color: #333333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-top: 4px solid #1C3F60;">
            
            <h2 style="color: #1C3F60;">Confirmación de Pago - Condominio Santa Paula</h2>
            
            <p>Estimado/a <b>{residente}</b> (Apto. <b>{inmueble}</b>),</p>
            
            <p>Por medio del presente correo, la Administración del Condominio Santa Paula le confirma que su reciente pago ha sido verificado y procesado exitosamente en nuestro sistema.</p>
            
            <p>Adjunto a este mensaje encontrará su <b>Recibo Electrónico Oficial</b> en formato PDF para sus controles personales.</p>
            
            <br>
            <p style="font-size: 12px; color: #7F8C8D;">
                * Por favor, no responda a este correo automatizado. Si tiene alguna duda, comuníquese directamente con la Junta de Condominio.
            </p>
            
            <hr style="border: none; border-top: 1px solid #eee; margin-top: 20px;">
            <p style="text-align: center; font-size: 11px; color: #95A5A6;">
                Sistema Automatizado de Gestión<br>
                <b>Condominio Santa Paula</b>
            </p>
        </div>
        """

        # 3. Enviamos el correo (Asunto, Mensaje en HTML y el PDF adjunto)
        yag.send(
            to=correo_residente,
            subject=f"✅ Recibo de Pago Confirmado - {inmueble}",
            contents=[cuerpo_html, archivo_pdf] # Pasamos el diseño y la ruta del archivo
        )
        
        print(f"📧 ¡Éxito! Correo enviado correctamente al inmueble {inmueble}.")
        return True

    except Exception as e:
        print(f"❌ Ocurrió un error al enviar el correo: {e}")
        return False
