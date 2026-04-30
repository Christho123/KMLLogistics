<?php
declare(strict_types=1);

// =========================================================
// API: FLUSH ADMIN NOTIFICATIONS
// Envia notificaciones administrativas pendientes fuera del CRUD principal.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('POST');

$queueDir = dirname(__DIR__, 2) . '/Pages/Storage/AdminNotifications';
$logFile = $queueDir . '/worker.log';
$processed = 0;
$failed = 0;

function logAdminNotificationFlush(string $logFile, string $message): void
{
    @file_put_contents(
        $logFile,
        '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL,
        FILE_APPEND
    );
}

try {
    if (!is_dir($queueDir)) {
        echo json_encode(['success' => true, 'processed' => 0, 'failed' => 0], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $files = glob($queueDir . '/notification_*.json') ?: [];
    sort($files);
    $files = array_slice($files, 0, 5);

    foreach ($files as $file) {
        $lockFile = $file . '.lock';

        if (!@rename($file, $lockFile)) {
            continue;
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
            $processed += 1;
            logAdminNotificationFlush($logFile, 'Correo enviado por flush HTTP a ' . $adminEmail . ' para accion ' . (string) ($action['accion'] ?? ''));
        } catch (Throwable $exception) {
            $failed += 1;
            @rename($lockFile, $file);
            logAdminNotificationFlush($logFile, 'Error en flush HTTP: ' . $exception->getMessage() . ' | Payload: ' . $file);
        }
    }

    echo json_encode(['success' => true, 'processed' => $processed, 'failed' => $failed], JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);
    logAdminNotificationFlush($logFile, 'Error general en flush HTTP: ' . $exception->getMessage());
    echo json_encode(['success' => false, 'message' => 'No se pudieron enviar las notificaciones pendientes.'], JSON_UNESCAPED_UNICODE);
}
