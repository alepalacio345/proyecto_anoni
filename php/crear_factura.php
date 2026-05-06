<?php
// Requerimos el autoload de Composer
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function generar_recibo_santa_paula($residente, $inmueble, $fecha, $referencia, $monto) {
    
    // Configuramos Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    $html_recibo = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"utf-8\">
        <style>
            @page { margin: 2cm; }
            body { font-family: Helvetica, Arial, sans-serif; color: #333333; font-size: 13px; }
            .header-table { width: 100%; border-bottom: 2px solid #1C3F60; padding-bottom: 15px; margin-bottom: 30px; }
            .title { font-size: 26px; color: #1C3F60; font-weight: bold; }
            .subtitle { font-size: 13px; color: #7F8C8D; }
            .info-table { width: 100%; margin-bottom: 40px; }
            .info-table td { padding: 5px; vertical-align: top; }
            .highlight-box { background-color: #F8F9FA; border: 1px solid #BDC3C7; padding: 15px; text-align: center; font-size: 18px; color: #1C3F60; font-weight: bold; }
            .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
            .details-table th { background-color: #1C3F60; color: #FFFFFF; padding: 10px; text-align: left; font-weight: bold; }
            .details-table td { padding: 12px 10px; border-bottom: 1px solid #ECF0F1; }
            .footer { text-align: center; font-size: 11px; color: #95A5A6; margin-top: 50px; border-top: 1px solid #ECF0F1; padding-top: 15px; }
        </style>
    </head>
    <body>
        <table class=\"header-table\">
            <tr>
                <td width=\"60%\">
                    <div class=\"title\">CONDOMINIO SANTA PAULA</div>
                    <div class=\"subtitle\">Junta de Condominio y Administración</div>
                </td>
                <td width=\"40%\" style=\"text-align: right;\">
                    <div style=\"font-size: 20px; color: #1C3F60;\"><b>RECIBO DE PAGO</b></div>
                    <div style=\"color: #E74C3C; font-size: 14px;\"><b>Ref: #{$referencia}</b></div>
                </td>
            </tr>
        </table>
        <table class=\"info-table\">
            <tr>
                <td width=\"65%\">
                    <p><b>Hemos recibido de:</b><br>{$residente}</p>
                    <p><b>Inmueble :</b><br>{$inmueble}</p>
                    <p><b>Fecha de Verificación:</b><br>{$fecha}</p>
                </td>
                <td width=\"35%\">
                    <div class=\"highlight-box\">
                        <span style=\"font-size: 12px; color: #7F8C8D;\">MONTO TOTAL</span><br>
                        Bs. {$monto}
                    </div>
                </td>
            </tr>
        </table>
        <table class=\"details-table\">
            <thead>
                <tr>
                    <th width=\"75%\">Descripción del Concepto</th>
                    <th width=\"25%\" style=\"text-align: right;\">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Abono a cuota de mantenimiento de condominio (Pago Móvil / Transferencia)</td>
                    <td style=\"text-align: right;\">Bs. {$monto}</td>
                </tr>
            </tbody>
        </table>
        <div class=\"footer\">
            <p>Este documento es un comprobante electrónico de pago válido para sus controles administrativos.</p>
            <p><b>Sistema Automatizado Santa Paula</b></p>
        </div>
    </body>
    </html>
    ";
    
    // Renderizamos el HTML como PDF
    $dompdf->loadHtml($html_recibo);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();

    $nombre_archivo = "Recibo_SantaPaula_Apto{$inmueble}_Ref{$referencia}.pdf";
    $output = $dompdf->output();

    // Guardamos el archivo
    if (file_put_contents($nombre_archivo, $output)) {
        echo "✅ ¡Recibo creado y guardado como: {$nombre_archivo}!\n";
        return $nombre_archivo;
    } else {
        echo "❌ Hubo un error al generar el PDF del condominio.\n";
        return null;
    }
}
?>