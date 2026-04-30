<?php
declare(strict_types=1);

// =========================================================
// WORKER: ADMIN NOTIFICATION
// Envia correos administrativos fuera del request AJAX principal.
// =========================================================



require_once dirname(__DIR__) . '/Config/Mail.php';
require_once __DIR__ . '/MailerService.php';

function writeWorkerLog(string $message): void
{
    $logDir = dirname(__DIR__) . '/Storage/AdminNotifications';

    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }

    @file_put_contents(
        $logDir . '/worker.log',
        '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL,
        FILE_APPEND
    );
}

$payloadFile = (string) ($argv[1] ?? '');

if ($payloadFile === '' || !is_file($payloadFile)) {
    writeWorkerLog('No se encontro payload para enviar.');
    exit(0);
}

$lockFile = $payloadFile . '.lock';

if (!@rename($payloadFile, $lockFile)) {
    exit(0);
}

try {
    $payload = json_decode((string) file_get_contents($lockFile), true);

    if (!is_array($payload)) {
        throw new RuntimeException('Payload invalido.');
    }

    $adminEmail = trim((string) ($payload['admin_email'] ?? ''));
    $action = $payload['action'] ?? [];

    if ($adminEmail === '' || !is_array($action)) {
        throw new RuntimeException('Datos de notificacion incompletos.');
    }

    (new MailerService())->sendAdminActionNotification($adminEmail, $action);
    @unlink($lockFile);
    writeWorkerLog('Correo enviado a ' . $adminEmail . ' para accion ' . (string) ($action['accion'] ?? ''));
} catch (Throwable $exception) {
    // Se conserva el JSON para poder reintentar o revisar el fallo.
    @rename($lockFile, $payloadFile);
    writeWorkerLog('Error enviando notificacion: ' . $exception->getMessage() . ' | Payload: ' . $payloadFile);
    exit(0);
}
