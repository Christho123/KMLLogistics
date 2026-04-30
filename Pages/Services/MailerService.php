<?php
declare(strict_types=1);

// =========================================================
// SERVICIO: MAILER
// Envio de correos con PHPMailer.
// =========================================================



require_once dirname(__DIR__) . '/Config/Mail.php';
require_once dirname(__DIR__) . '/PHPMailer/src/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/src/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailerService
{
    // Envia codigos de verificacion de email y cambio de password.
    public function sendCode(string $toEmail, string $toName, string $code, string $subject): bool
    {
        $config = getMailConfig();

        if (trim((string) $config['username']) === '' || trim((string) $config['password']) === '') {
            throw new RuntimeException('Configura el usuario y password SMTP en Pages/Config/Mail.php antes de enviar codigos.');
        }

        $mail = $this->buildMailer($config);
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $this->buildCodeTemplate($toName, $code, $subject);
        $mail->AltBody = 'Tu codigo de KMLLogistics es: ' . $code . '. Tiene vigencia de 5 minutos.';

        return $mail->send();
    }

    // Envia al correo administrador un resumen de acciones registradas por auditoria.
    public function sendAdminActionNotification(string $adminEmail, array $action): bool
    {
        $config = getMailConfig();
        $mail = $this->buildMailer($config);
        $mail->addAddress($adminEmail, 'Administrador KMLLogistics');
        $mail->isHTML(true);
        $mail->Subject = 'KMLLogistics - Nueva accion registrada';
        $mail->Body = $this->buildAdminActionTemplate($action);
        $mail->AltBody = sprintf(
            "Nueva accion registrada\nUsuario: %s\nCorreo: %s\nModulo: %s\nAccion: %s\nDescripcion: %s\nFecha: %s",
            (string) ($action['usuario'] ?? 'Sistema'),
            (string) ($action['correo'] ?? ''),
            (string) ($action['modulo'] ?? ''),
            (string) ($action['accion'] ?? ''),
            (string) ($action['descripcion'] ?? ''),
            (string) ($action['fecha'] ?? '')
        );

        return $mail->send();
    }

    // Centraliza la configuracion SMTP para que todos los correos usen el mismo canal.
    private function buildMailer(array $config): PHPMailer
    {
        if (trim((string) $config['username']) === '' || trim((string) $config['password']) === '') {
            throw new RuntimeException('Configura el usuario y password SMTP en Pages/Config/Mail.php antes de enviar correos.');
        }

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isSMTP();
        $mail->Host = (string) $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = (string) $config['username'];
        $mail->Password = (string) $config['password'];
        $mail->Port = (int) $config['port'];
        $mail->SMTPSecure = (string) $config['encryption'];
        $mail->Timeout = (int) ($config['timeout'] ?? 8);
        $mail->SMTPDebug = !empty($config['debug']) ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
        $mail->setFrom((string) $config['from_email'], (string) $config['from_name']);
        $mail->Sender = (string) $config['from_email'];
        $mail->addReplyTo((string) $config['from_email'], (string) $config['from_name']);
        $mail->XMailer = 'KMLLogistics Mailer';

        return $mail;
    }

    // Plantilla HTML para codigos de seguridad enviados al usuario.
    private function buildCodeTemplate(string $name, string $code, string $subject): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
        $safeCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');

        return '
            <div style="font-family:Arial,sans-serif;background:#f8fafc;padding:24px;color:#0f172a">
                <div style="max-width:520px;margin:auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:24px">
                    <h2 style="margin:0 0 8px;color:#0f172a">KMLLogistics</h2>
                    <p style="margin:0 0 18px;color:#475569">Hola ' . $safeName . ', solicitaste: ' . $safeSubject . '.</p>
                    <div style="font-size:32px;font-weight:700;letter-spacing:6px;background:#fff7ed;color:#b45309;text-align:center;border-radius:12px;padding:18px;margin-bottom:18px">' . $safeCode . '</div>
                    <p style="margin:0;color:#475569">Este codigo vence en 5 minutos. Si no solicitaste esta accion, puedes ignorar este correo.</p>
                </div>
            </div>';
    }

    // Plantilla HTML para notificaciones de acciones del sistema al administrador.
    private function buildAdminActionTemplate(array $action): string
    {
        $safe = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $datos = $action['datos'] ?? [];
        $datosText = $datos === [] ? 'Sin datos adicionales' : json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return '
            <div style="font-family:Arial,sans-serif;background:#f8fafc;padding:24px;color:#0f172a">
                <div style="max-width:620px;margin:auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;padding:24px">
                    <h2 style="margin:0 0 8px;color:#0f172a">KMLLogistics</h2>
                    <p style="margin:0 0 18px;color:#475569">Se registro una nueva accion dentro del sistema.</p>
                    <table style="width:100%;border-collapse:collapse;color:#0f172a">
                        <tr><td style="padding:8px;border-bottom:1px solid #e2e8f0;font-weight:700">Usuario</td><td style="padding:8px;border-bottom:1px solid #e2e8f0">' . $safe($action['usuario'] ?? 'Sistema') . '</td></tr>
                        <tr><td style="padding:8px;border-bottom:1px solid #e2e8f0;font-weight:700">Correo</td><td style="padding:8px;border-bottom:1px solid #e2e8f0">' . $safe($action['correo'] ?? '-') . '</td></tr>
                        <tr><td style="padding:8px;border-bottom:1px solid #e2e8f0;font-weight:700">Modulo</td><td style="padding:8px;border-bottom:1px solid #e2e8f0">' . $safe($action['modulo'] ?? '') . '</td></tr>
                        <tr><td style="padding:8px;border-bottom:1px solid #e2e8f0;font-weight:700">Accion</td><td style="padding:8px;border-bottom:1px solid #e2e8f0">' . $safe($action['accion'] ?? '') . '</td></tr>
                        <tr><td style="padding:8px;border-bottom:1px solid #e2e8f0;font-weight:700">Fecha</td><td style="padding:8px;border-bottom:1px solid #e2e8f0">' . $safe($action['fecha'] ?? '') . '</td></tr>
                    </table>
                    <p style="margin:18px 0 8px;font-weight:700">Descripcion</p>
                    <p style="margin:0 0 18px;color:#475569">' . $safe($action['descripcion'] ?? '') . '</p>
                    <pre style="white-space:pre-wrap;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px;color:#334155">' . $safe($datosText) . '</pre>
                </div>
            </div>';
    }
}

