<?php
declare(strict_types=1);

// =========================================================
// SERVICIO: AUDIT LOGGER
// Registra acciones del usuario mediante SP.
// =========================================================



class AuditLogger
{
    // Registra la accion en BD y agenda el correo administrativo sin bloquear el CRUD.
    public static function log(string $modulo, string $accion, string $descripcion, array $datos = []): void
    {
        $currentUser = $_SESSION['user'] ?? null;

        try {
            $idUsuario = (int) ($currentUser['id_usuario'] ?? 0);
            $jsonDatos = $datos === [] ? null : json_encode($datos, JSON_UNESCAPED_UNICODE);

            $auditCRUD = new AuditCRUD();
            $auditCRUD->create(new Audit($idUsuario, $modulo, $accion, $descripcion, $jsonDatos));
        } catch (Throwable) {
            // La auditoria no debe bloquear la accion principal del usuario.
        }

        if (self::shouldNotifyAdmin()) {
            self::notifyAdmin($modulo, $accion, $descripcion, $datos, $currentUser);
        }
    }

    private static function shouldNotifyAdmin(): bool
    {
        try {
            $config = getMailConfig();

            return !empty($config['notify_admin_actions']);
        } catch (Throwable) {
            return false;
        }
    }

    // Construye el resumen de la accion para el correo admin configurado.
    private static function notifyAdmin(string $modulo, string $accion, string $descripcion, array $datos, ?array $currentUser): void
    {
        try {
            $config = getMailConfig();
            $adminEmail = trim((string) ($config['username'] ?? $config['from_email'] ?? ''));

            if ($adminEmail === '') {
                return;
            }

            $action = [
                'usuario' => self::resolveActorName($currentUser),
                'correo' => (string) ($currentUser['correo'] ?? ''),
                'modulo' => $modulo,
                'accion' => $accion,
                'descripcion' => $descripcion,
                'datos' => $datos,
                'fecha' => date('Y-m-d H:i:s'),
            ];

            self::sendAdminNotification($adminEmail, $action);
        } catch (Throwable) {
            // El correo al admin no debe bloquear la accion principal.
        }
    }

    // Si no hay sesion, la accion se reporta como Invitado.
    private static function resolveActorName(?array $currentUser): string
    {
        if ($currentUser === null) {
            return 'Invitado';
        }

        return trim((string) (($currentUser['nombres'] ?? '') . ' ' . ($currentUser['apellidos'] ?? ''))) ?: 'Usuario autenticado';
    }

    // Agenda el envio en un proceso PHP aparte para no hacer esperar al AJAX.
    private static function sendAdminNotification(string $adminEmail, array $action): void
    {
        if (self::dispatchBackgroundNotification($adminEmail, $action)) {
            return;
        }

        try {
            (new MailerService())->sendAdminActionNotification($adminEmail, $action);
        } catch (Throwable) {
            // El correo al admin no debe bloquear la accion principal.
        }
    }

    private static function dispatchBackgroundNotification(string $adminEmail, array $action): bool
    {
        try {
            $queueDir = dirname(__DIR__) . '/Storage/AdminNotifications';

            if (!is_dir($queueDir) && !mkdir($queueDir, 0775, true) && !is_dir($queueDir)) {
                return false;
            }

            $payload = [
                'admin_email' => $adminEmail,
                'action' => $action,
            ];
            $payloadFile = $queueDir . '/notification_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.json';

            if (file_put_contents($payloadFile, json_encode($payload, JSON_UNESCAPED_UNICODE)) === false) {
                return false;
            }

            $worker = __DIR__ . '/AdminNotificationWorker.php';
            $phpBinary = self::resolvePhpBinary();

            if ($phpBinary === '' || !is_file($worker)) {
                return false;
            }

            $command = self::buildBackgroundCommand($phpBinary, $worker, $payloadFile);

            if ($command === '') {
                return false;
            }

            $process = @popen($command, 'r');

            if (is_resource($process)) {
                pclose($process);
                return true;
            }

            @exec($command . ' > NUL 2>&1');
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private static function buildBackgroundCommand(string $phpBinary, string $worker, string $payloadFile): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return 'cmd /C start "" /B '
                . escapeshellarg($phpBinary) . ' '
                . escapeshellarg($worker) . ' '
                . escapeshellarg($payloadFile);
        }

        return escapeshellarg($phpBinary) . ' '
            . escapeshellarg($worker) . ' '
            . escapeshellarg($payloadFile) . ' > /dev/null 2>&1 &';
    }

    private static function resolvePhpBinary(): string
    {
        $candidates = [
            dirname((string) ($_SERVER['DOCUMENT_ROOT'] ?? '')) . '/php/php.exe',
            dirname(__DIR__, 4) . '/php/php.exe',
            'C:/xampp/php/php.exe',
            defined('PHP_BINARY') ? PHP_BINARY : '',
        ];

        foreach ($candidates as $candidate) {
            if ($candidate !== '' && is_file($candidate)) {
                return $candidate;
            }
        }

        return '';
    }
}

