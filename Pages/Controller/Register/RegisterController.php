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

        $documentTypes = $this->userCRUD->getDocumentTypes();
        $formData = [
            'nombres' => trim($_POST['nombres'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'id_tipo_documento' => (int) ($_POST['id_tipo_documento'] ?? 0),
            'numero_documento' => trim($_POST['numero_documento'] ?? ''),
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User(
                $formData['nombres'],
                $formData['apellidos'],
                $formData['correo'],
                $formData['id_tipo_documento'],
                $formData['numero_documento'],
                $_POST['password'] ?? ''
            );
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (
                $user->nombres === '' ||
                $user->apellidos === '' ||
                $user->correo === '' ||
                $user->idTipoDocumento <= 0 ||
                $user->numeroDocumento === '' ||
                $user->password === '' ||
                $confirmPassword === ''
            ) {
                return [
                    'message' => 'Completa todos los campos del registro.',
                    'message_type' => 'danger',
                    'document_types' => $documentTypes,
                    'form_data' => $formData,
                ];
            }

            if (!filter_var($user->correo, FILTER_VALIDATE_EMAIL)) {
                return [
                    'message' => 'Ingresa un correo valido.',
                    'message_type' => 'danger',
                    'document_types' => $documentTypes,
                    'form_data' => $formData,
                ];
            }

            if (strlen($user->password) < 6) {
                return [
                    'message' => 'La password debe tener al menos 6 caracteres.',
                    'message_type' => 'danger',
                    'document_types' => $documentTypes,
                    'form_data' => $formData,
                ];
            }

            if ($user->password !== $confirmPassword) {
                return [
                    'message' => 'Las passwords no coinciden.',
                    'message_type' => 'danger',
                    'document_types' => $documentTypes,
                    'form_data' => $formData,
                ];
            }

            if ($this->userCRUD->findUserByEmail($user->correo)) {
                return [
                    'message' => 'Ese correo ya esta registrado.',
                    'message_type' => 'warning',
                    'document_types' => $documentTypes,
                    'form_data' => $formData,
                ];
            }

            $this->userCRUD->register($user);
            header('Location: index.php?page=login&status=registered');
            exit;
        }

        return [
            'message' => '',
            'message_type' => '',
            'document_types' => $documentTypes,
            'form_data' => $formData,
        ];
    }
}
