<?php
declare(strict_types=1);

// =========================================================
// SERVICIO: AUDIT LOGGER
// Registra acciones del usuario mediante SP.
// =========================================================



class AuditLogger
{
    // Registra la accion en BD y dispara la notificacion administrativa por correo.
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

        self::notifyAdmin($modulo, $accion, $descripcion, $datos, $currentUser);
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

    // Usa el mismo MailerService validado para codigos y evita romper el flujo principal.
    private static function sendAdminNotification(string $adminEmail, array $action): void
    {
        $sender = static function () use ($adminEmail, $action): void {
            try {
                (new MailerService())->sendAdminActionNotification($adminEmail, $action);
            } catch (Throwable) {
                // El correo al admin no debe bloquear la accion principal.
            }
        };

        if (function_exists('fastcgi_finish_request')) {
            register_shutdown_function($sender);
            return;
        }

        $sender();
    }
}

