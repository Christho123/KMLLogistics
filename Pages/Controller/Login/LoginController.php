<?php
// =========================================================
// CONTROLADOR: LOGIN
// Maneja autenticacion y mensajes del inicio de sesion.
// =========================================================

declare(strict_types=1);

// Controlador del inicio de sesion.
// Tecnologia asociada: MVC + POO.
class LoginController
{
    private UserCRUD $userCRUD;

    // Inicializa acceso a usuarios.
    public function __construct()
    {
        $this->userCRUD = new UserCRUD();
    }

    // Procesa login y mensajes de estado.
    // Metodo clave para validar credenciales y crear la sesion del usuario.
    public function handleRequest(): array
    {
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }

        $message = $this->getStatusMessage($_GET['status'] ?? '');
        $messageType = $message === '' ? '' : 'info';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                return [
                    'message' => 'Completa correo y password.',
                    'message_type' => 'danger',
                ];
            }

            $user = $this->userCRUD->findUserByEmail($email);

            if (!$user || (int) $user['estado'] !== 1 || !password_verify($password, $user['password_hash'])) {
                return [
                    'message' => 'Credenciales incorrectas.',
                    'message_type' => 'danger',
                ];
            }

            // Guarda datos basicos en sesion.
            $_SESSION['user'] = [
                'id_usuario' => $user['id_usuario'],
                'nombres' => $user['nombres'],
                'apellidos' => $user['apellidos'],
                'correo' => $user['correo'],
                'rol' => $user['rol'],
            ];

            header('Location: index.php?status=login_ok');
            exit;
        }

        return [
            'message' => $message,
            'message_type' => $messageType,
        ];
    }

    // Traduce estados de ruta a mensajes visibles.
    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            'registered' => 'Registro completado. Ahora inicia sesion.',
            #'logout' => 'Sesion cerrada correctamente.',
            default => '',
        };
    }
}
