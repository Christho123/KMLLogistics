<?php
declare(strict_types=1);

// =========================================================
// CONTROLADOR: PROFILE
// Gestiona configuracion del perfil del usuario.
// =========================================================



class ProfileController
{
    private UserCRUD $userCRUD;

    public function __construct()
    {
        $this->userCRUD = new UserCRUD();
    }

    public function handleRequest(): array
    {
        $this->requireSession();
        $profile = $this->userCRUD->findProfileById((int) $_SESSION['user']['id_usuario']);

        return [
            'current_user' => $_SESSION['user'],
            'profile' => $profile,
            'document_types' => $this->userCRUD->getDocumentTypes(),
            'roles' => ['usuario', 'Admin', 'Administrador'],
        ];
    }

    public function getProfile(): array
    {
        $this->requireSession();
        $profile = $this->userCRUD->findProfileById((int) $_SESSION['user']['id_usuario']);

        if ($profile === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'No se encontro el perfil del usuario.',
            ];
        }

        return [
            'success' => true,
            'profile' => $profile,
        ];
    }

    public function updateProfile(array $payload): array
    {
        $this->requireSession();
        $idUsuario = (int) $_SESSION['user']['id_usuario'];
        $nombres = trim((string) ($payload['nombres'] ?? ''));
        $apellidos = trim((string) ($payload['apellidos'] ?? ''));
        $correo = trim((string) ($payload['correo'] ?? ''));
        $idTipoDocumento = (int) ($payload['id_tipo_documento'] ?? 0);
        $numeroDocumento = trim((string) ($payload['numero_documento'] ?? ''));
        $rol = trim((string) ($payload['rol'] ?? 'usuario'));

        if ($nombres === '' || $apellidos === '' || $correo === '' || $idTipoDocumento <= 0 || $numeroDocumento === '' || $rol === '') {
            return [
                'success' => false,
                'status_code' => 422,
                'message' => 'Completa correctamente todos los datos del perfil.',
            ];
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'status_code' => 422,
                'message' => 'Ingresa un correo valido.',
            ];
        }

        $this->userCRUD->updateProfile($idUsuario, $nombres, $apellidos, $correo, $idTipoDocumento, $numeroDocumento, $rol);
        $profile = $this->userCRUD->findProfileById($idUsuario);
        $this->syncSession($profile);
        AuditLogger::log('Perfil', 'Actualizar perfil', 'El usuario actualizo sus datos personales.', ['id_usuario' => $idUsuario]);

        return [
            'success' => true,
            'message' => 'Perfil actualizado correctamente.',
            'profile' => $profile,
        ];
    }

    public function updatePhoto(?array $file): array
    {
        $this->requireSession();
        $idUsuario = (int) $_SESSION['user']['id_usuario'];
        $profile = $this->userCRUD->findProfileById($idUsuario);

        if (!$file || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return [
                'success' => false,
                'status_code' => 422,
                'message' => 'Selecciona una foto para tu perfil.',
            ];
        }

        $photo = $this->saveUserPhoto($file, (string) ($profile['nombres'] ?? 'usuario'), (string) ($profile['apellidos'] ?? ''));
        $this->deleteExistingPhoto((string) ($profile['foto'] ?? ''));
        $this->userCRUD->updatePhoto($idUsuario, $photo);
        $profile = $this->userCRUD->findProfileById($idUsuario);
        $this->syncSession($profile);
        AuditLogger::log('Perfil', 'Actualizar foto', 'El usuario actualizo su foto de perfil.', ['id_usuario' => $idUsuario]);

        return [
            'success' => true,
            'message' => 'Foto actualizada correctamente.',
            'profile' => $profile,
        ];
    }

    public function deletePhoto(): array
    {
        $this->requireSession();
        $idUsuario = (int) $_SESSION['user']['id_usuario'];
        $profile = $this->userCRUD->findProfileById($idUsuario);
        $this->deleteExistingPhoto((string) ($profile['foto'] ?? ''));
        $this->userCRUD->updatePhoto($idUsuario, null);
        $profile = $this->userCRUD->findProfileById($idUsuario);
        $this->syncSession($profile);
        AuditLogger::log('Perfil', 'Eliminar foto', 'El usuario elimino su foto de perfil.', ['id_usuario' => $idUsuario]);

        return [
            'success' => true,
            'message' => 'Foto eliminada correctamente.',
            'profile' => $profile,
        ];
    }

    public function sendCode(string $type): array
    {
        $this->requireSession();
        $idUsuario = (int) $_SESSION['user']['id_usuario'];
        $profile = $this->userCRUD->findProfileById($idUsuario);

        if ($profile === null) {
            return ['success' => false, 'status_code' => 404, 'message' => 'No se encontro el perfil.'];
        }

        if ($type === 'email' && (int) ($profile['email_verificado'] ?? 0) === 1) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Este email ya esta verificado.',
            ];
        }

        $code = (string) random_int(100000, 999999);
        $this->userCRUD->createVerificationCode($idUsuario, $type, password_hash($code, PASSWORD_DEFAULT), (string) $profile['correo']);

        $subject = $type === 'password' ? 'Cambio de password' : 'Verificacion de email';
        (new MailerService())->sendCode((string) $profile['correo'], (string) $profile['nombres'], $code, $subject);
        AuditLogger::log('Perfil', 'Enviar codigo', 'Se envio un codigo para ' . $subject . '.', ['tipo' => $type]);

        return [
            'success' => true,
            'message' => 'Codigo enviado. Revisa tu correo.',
            'expires_in' => 300,
        ];
    }

    public function changePassword(string $code, string $password, string $confirmPassword): array
    {
        $this->requireSession();

        if (strlen($password) < 6 || $password !== $confirmPassword) {
            return [
                'success' => false,
                'status_code' => 422,
                'message' => 'La password debe tener al menos 6 caracteres y coincidir con la confirmacion.',
            ];
        }

        $validatedCode = $this->validateCode('password', $code);

        if (!$validatedCode['success']) {
            return $validatedCode;
        }

        $this->userCRUD->changePassword((int) $_SESSION['user']['id_usuario'], $password);
        $this->userCRUD->markCodeUsed((int) $validatedCode['id_codigo']);
        AuditLogger::log('Perfil', 'Cambiar password', 'El usuario cambio su password.');

        return [
            'success' => true,
            'message' => 'Password actualizada correctamente.',
        ];
    }

    public function confirmEmail(string $code): array
    {
        $this->requireSession();
        $validatedCode = $this->validateCode('email', $code);

        if (!$validatedCode['success']) {
            return $validatedCode;
        }

        $this->userCRUD->verifyEmail((int) $_SESSION['user']['id_usuario']);
        $this->userCRUD->markCodeUsed((int) $validatedCode['id_codigo']);
        $profile = $this->userCRUD->findProfileById((int) $_SESSION['user']['id_usuario']);
        $this->syncSession($profile);
        AuditLogger::log('Perfil', 'Verificar email', 'El usuario verifico su correo.');

        return [
            'success' => true,
            'message' => 'Email verificado correctamente.',
            'profile' => $profile,
        ];
    }

    private function requireSession(): void
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            exit('Sesion requerida.');
        }
    }

    private function validateCode(string $type, string $code): array
    {
        $row = $this->userCRUD->getLatestCode((int) $_SESSION['user']['id_usuario'], $type);

        if ($row === null || !password_verify(trim($code), (string) $row['codigo_hash'])) {
            return [
                'success' => false,
                'status_code' => 422,
                'message' => 'El codigo no es valido o ya vencio.',
            ];
        }

        return [
            'success' => true,
            'id_codigo' => (int) $row['id_codigo'],
        ];
    }

    private function syncSession(?array $profile): void
    {
        if ($profile === null) {
            return;
        }

        $_SESSION['user'] = [
            'id_usuario' => $profile['id_usuario'],
            'nombres' => $profile['nombres'],
            'apellidos' => $profile['apellidos'],
            'correo' => $profile['correo'],
            'rol' => $profile['rol'],
            'foto' => $profile['foto'] ?? null,
            'email_verificado' => $profile['email_verificado'] ?? 0,
        ];
    }

    private function saveUserPhoto(array $file, string $nombres, string $apellidos): string
    {
        if ((int) ($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('No se pudo subir la foto seleccionada.');
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        $mime = mime_content_type((string) $file['tmp_name']);

        if (!isset($allowed[$mime])) {
            throw new RuntimeException('La foto debe ser JPG, PNG o WEBP.');
        }

        $baseName = strtolower(trim($nombres . '-' . $apellidos));
        $baseName = preg_replace('/[^a-z0-9]+/i', '-', $baseName) ?: 'usuario';
        $fileName = trim($baseName, '-') . '-' . time() . '.' . $allowed[$mime];
        $relativePath = 'Pages/Images/Users/' . $fileName;
        $targetPath = dirname(__DIR__, 2) . '/Images/Users/' . $fileName;

        if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
            throw new RuntimeException('No se pudo guardar la foto de perfil.');
        }

        return $relativePath;
    }

    private function deleteExistingPhoto(string $photo): void
    {
        if ($photo === '' || !str_starts_with($photo, 'Pages/Images/Users/')) {
            return;
        }

        $path = dirname(__DIR__, 3) . '/' . $photo;

        if (is_file($path)) {
            unlink($path);
        }
    }
}

