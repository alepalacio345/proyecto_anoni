<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviar_recibo_santa_paula($correo_residente, $residente, $inmueble, $archivo_pdf) {
    
    $correo_condominio = "saullarapalacio@gmail.com";
    $password_app = "fqxp bxsn gvvl pzyz"; 
    
    echo "Enviando correo a {$correo_residente}...\n";

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $correo_condominio;
        $mail->Password   = $password_app;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        // Destinatarios y remitente
        $mail->setFrom($correo_condominio, 'Condominio Santa Paula');
        $mail->addAddress($correo_residente);

        // Adjuntamos el PDF
        $mail->addAttachment($archivo_pdf);

        // Contenido en HTML
        $mail->isHTML(true);
        $mail->Subject = "✅ Recibo de Pago Confirmado - {$inmueble}";
        
        $cuerpo_html = "
        <div style=\"font-family: Arial, sans-serif; color: #333333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-top: 4px solid #1C3F60;\">
            <h2 style=\"color: #1C3F60;\">Confirmación de Pago - Condominio Santa Paula</h2>
            <p>Estimado/a <b>{$residente}</b> (Apto. <b>{$inmueble}</b>),</p>
            <p>Por medio del presente correo, la Administración del Condominio Santa Paula le confirma que su reciente pago ha sido verificado y procesado exitosamente en nuestro sistema.</p>
            <p>Adjunto a este mensaje encontrará su <b>Recibo Electrónico Oficial</b> en formato PDF para sus controles personales.</p>
            <br>
            <p style=\"font-size: 12px; color: #7F8C8D;\">
                * Por favor, no responda a este correo automatizado.
            </p>
            <hr style=\"border: none; border-top: 1px solid #eee; margin-top: 20px;\">
            <p style=\"text-align: center; font-size: 11px; color: #95A5A6;\">
                Sistema Automatizado de Gestión<br>
                <b>Condominio Santa Paula</b>
            </p>
        </div>
        ";
        
        $mail->Body = $cuerpo_html;

        $mail->send();
        echo "📧 ¡Éxito! Correo enviado correctamente al inmueble {$inmueble}.\n";
        return true;
        
    } catch (Exception $e) {
        echo "❌ Ocurrió un error al enviar el correo: {$mail->ErrorInfo}\n";
        return false;
    }
}
?>