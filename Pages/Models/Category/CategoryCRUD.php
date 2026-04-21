<?php

declare(strict_types=1);

// Acceso a datos para categorias.
class CategoryCRUD
{
    private PDO $connection;

    // Inicializa la conexion.
    public function __construct()
    {
        $this->connection = getConnection();
    }

    // Lista categorias activas por borrado logico, desde la mas reciente.
    public function listCategories(): array
    {
        $statement = $this->connection->query(
            'SELECT id_categoria, nombre_categoria, descripcion, estado, created_at, updated_at, deleted_at
             FROM categorias
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC, id_categoria DESC'
        );

        return $statement->fetchAll();
    }
}
