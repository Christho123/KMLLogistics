<?php
// =========================================================
// MODELO: USER CRUD
// Acceso a datos del modulo de usuarios con PDO.
// =========================================================

declare(strict_types=1);

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

    // Busca un usuario por correo.
    // Metodo clave para autenticar el login.
    public function findUserByEmail(string $email): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT id_usuario, nombres, apellidos, correo, id_tipo_documento, numero_documento, password_hash, rol, estado
             FROM usuarios
             WHERE correo = :correo
             LIMIT 1'
        );
        $statement->bindValue(':correo', trim($email), PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetch();

        return $user ?: null;
    }

    // Lista los tipos de documento activos para el registro.
    public function getDocumentTypes(): array
    {
        $statement = $this->connection->query(
            'SELECT id_tipo_documento, nombre_tipo_documento
             FROM tipo_documentos
             WHERE estado = 1
             ORDER BY nombre_tipo_documento ASC'
        );

        return $statement->fetchAll() ?: [];
    }

    // Registra un usuario aplicando password_hash.
    // Metodo clave para persistir nuevos usuarios de forma segura.
    public function register(User $user): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO usuarios (nombres, apellidos, correo, id_tipo_documento, numero_documento, password_hash, rol, estado)
             VALUES (:nombres, :apellidos, :correo, :id_tipo_documento, :numero_documento, :password_hash, :rol, :estado)'
        );

        return $statement->execute([
            ':nombres' => $user->nombres,
            ':apellidos' => $user->apellidos,
            ':correo' => $user->correo,
            ':id_tipo_documento' => $user->idTipoDocumento,
            ':numero_documento' => $user->numeroDocumento,
            ':password_hash' => password_hash($user->password, PASSWORD_DEFAULT),
            ':rol' => 'usuario',
            ':estado' => 1,
        ]);
    }
}
