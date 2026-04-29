<?php
declare(strict_types=1);

// =========================================================
// MODELO: USER CRUD
// Acceso a datos del modulo de usuarios con PDO.
// =========================================================



// Acceso a datos para usuarios.
// Tecnologia asociada: PDO + MySQL + POO.
class UserCRUD
{
    private PDO $connection;

    // Inicializa la conexion.
    public function __construct()
    {
        $this->connection = getConnection();
    }

    private function callProcedureFetchAll(string $procedureName, array $parameters = []): array
    {
        $statement = $this->prepareProcedureCall($procedureName, $parameters);
        $statement->execute($parameters);
        $rows = $statement->fetchAll();
        $this->closeProcedureCursor($statement);

        return $rows ?: [];
    }

    private function callProcedureFetchOne(string $procedureName, array $parameters = []): ?array
    {
        $statement = $this->prepareProcedureCall($procedureName, $parameters);
        $statement->execute($parameters);
        $row = $statement->fetch();
        $this->closeProcedureCursor($statement);

        return $row === false ? null : $row;
    }

    private function prepareProcedureCall(string $procedureName, array $parameters = []): PDOStatement
    {
        $placeholders = implode(', ', array_fill(0, count($parameters), '?'));
        $sql = 'CALL ' . $procedureName . '(' . $placeholders . ')';

        return $this->connection->prepare($sql);
    }

    private function closeProcedureCursor(PDOStatement $statement): void
    {
        while ($statement->nextRowset()) {
            // Libera cualquier result set extra devuelto por MySQL.
        }

        $statement->closeCursor();
    }

    // Busca un usuario por correo.
    // Metodo clave para autenticar el login.
    public function findUserByEmail(string $email): ?array
    {
        return $this->callProcedureFetchOne('sp_usuario_obtener_por_correo', [trim($email)]);
    }

    // Lista los tipos de documento activos para el registro.
    public function getDocumentTypes(): array
    {
        return $this->callProcedureFetchAll('sp_tipo_documento_listar_activos_para_select');
    }

    // Verifica si el tipo de documento existe y sigue activo.
    public function findActiveDocumentTypeById(int $idTipoDocumento): ?array
    {
        return $this->callProcedureFetchOne('sp_tipo_documento_obtener_activo_para_select_por_id', [$idTipoDocumento]);
    }

    // Registra un usuario aplicando password_hash.
    // Metodo clave para persistir nuevos usuarios de forma segura.
    public function register(User $user): bool
    {
        $row = $this->callProcedureFetchOne('sp_usuario_registrar', [
            $user->nombres,
            $user->apellidos,
            $user->correo,
            $user->idTipoDocumento,
            $user->numeroDocumento,
            password_hash($user->password, PASSWORD_DEFAULT),
            'usuario',
            1,
        ]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function findProfileById(int $idUsuario): ?array
    {
        return $this->callProcedureFetchOne('sp_usuario_obtener_perfil', [$idUsuario]);
    }

    public function updateProfile(
        int $idUsuario,
        string $nombres,
        string $apellidos,
        string $correo,
        int $idTipoDocumento,
        string $numeroDocumento,
        string $rol
    ): bool {
        $row = $this->callProcedureFetchOne('sp_usuario_actualizar_perfil', [
            $idUsuario,
            trim($nombres),
            trim($apellidos),
            trim($correo),
            $idTipoDocumento,
            trim($numeroDocumento),
            trim($rol),
        ]);

        return (int) ($row['affected_rows'] ?? 0) >= 0;
    }

    public function updatePhoto(int $idUsuario, ?string $foto): bool
    {
        $row = $this->callProcedureFetchOne('sp_usuario_actualizar_foto', [$idUsuario, $foto]);

        return (int) ($row['affected_rows'] ?? 0) >= 0;
    }

    public function createVerificationCode(int $idUsuario, string $type, string $codeHash, string $destinationEmail): int
    {
        $row = $this->callProcedureFetchOne('sp_usuario_codigo_crear', [
            $idUsuario,
            $type,
            $codeHash,
            $destinationEmail,
        ]);

        return (int) ($row['id_codigo'] ?? 0);
    }

    public function getLatestCode(int $idUsuario, string $type): ?array
    {
        return $this->callProcedureFetchOne('sp_usuario_codigo_obtener_vigente', [$idUsuario, $type]);
    }

    public function markCodeUsed(int $idCode): bool
    {
        $row = $this->callProcedureFetchOne('sp_usuario_codigo_marcar_usado', [$idCode]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function changePassword(int $idUsuario, string $password): bool
    {
        $row = $this->callProcedureFetchOne('sp_usuario_cambiar_password', [
            $idUsuario,
            password_hash($password, PASSWORD_DEFAULT),
        ]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function verifyEmail(int $idUsuario): bool
    {
        $row = $this->callProcedureFetchOne('sp_usuario_verificar_email', [$idUsuario]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }
}

