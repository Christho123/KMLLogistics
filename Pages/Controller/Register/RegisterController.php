<?php

declare(strict_types=1);

// Controlador del registro de usuarios.
class RegisterController
{
    private UserCRUD $userCRUD;

    // Inicializa acceso a usuarios.
    public function __construct()
    {
        $this->userCRUD = new UserCRUD();
    }

    // Procesa validaciones y alta de usuario.
    public function handleRequest(): array
    {
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User(
                $_POST['nombres'] ?? '',
                $_POST['apellidos'] ?? '',
                $_POST['correo'] ?? '',
                $_POST['password'] ?? ''
            );
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (
                $user->nombres === '' ||
                $user->apellidos === '' ||
                $user->correo === '' ||
                $user->password === '' ||
                $confirmPassword === ''
            ) {
                return [
                    'message' => 'Completa todos los campos del registro.',
                    'message_type' => 'danger',
                ];
            }

            if (!filter_var($user->correo, FILTER_VALIDATE_EMAIL)) {
                return [
                    'message' => 'Ingresa un correo valido.',
                    'message_type' => 'danger',
                ];
            }

            if (strlen($user->password) < 6) {
                return [
                    'message' => 'La password debe tener al menos 6 caracteres.',
                    'message_type' => 'danger',
                ];
            }

            if ($user->password !== $confirmPassword) {
                return [
                    'message' => 'Las passwords no coinciden.',
                    'message_type' => 'danger',
                ];
            }

            if ($this->userCRUD->findUserByEmail($user->correo)) {
                return [
                    'message' => 'Ese correo ya esta registrado.',
                    'message_type' => 'warning',
                ];
            }

            $this->userCRUD->register($user);
            header('Location: index.php?page=login&status=registered');
            exit;
        }

        return [
            'message' => '',
            'message_type' => '',
        ];
    }
}
