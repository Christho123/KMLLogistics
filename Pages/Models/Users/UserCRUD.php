<?php

declare(strict_types=1);

// Acceso a datos para usuarios.
class UserCRUD
{
    private PDO $connection;

    // Inicializa la conexion.
    public function __construct()
    {
        $this->connection = getConnection();
    }

    // Busca un usuario por correo.
    public function findUserByEmail(string $email): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT id_usuario, nombres, apellidos, correo, password_hash, rol, estado
             FROM usuarios
             WHERE correo = :correo
             LIMIT 1'
        );
        $statement->bindValue(':correo', trim($email), PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetch();

        return $user ?: null;
    }

    // Registra un usuario aplicando password_hash.
    public function register(User $user): bool
    {
        $statement = $this->connection->prepare(
            'INSERT INTO usuarios (nombres, apellidos, correo, password_hash, rol, estado)
             VALUES (:nombres, :apellidos, :correo, :password_hash, :rol, :estado)'
        );

        return $statement->execute([
            ':nombres' => $user->nombres,
            ':apellidos' => $user->apellidos,
            ':correo' => $user->correo,
            ':password_hash' => password_hash($user->password, PASSWORD_DEFAULT),
            ':rol' => 'usuario',
            ':estado' => 1,
        ]);
    }
}
