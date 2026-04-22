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

    // Lista categorias activas por borrado logico, desde la mas reciente, con paginacion.
    public function listCategories(int $page = 1, int $pageSize = 10): array
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $offset = ($page - 1) * $pageSize;

        $totalStatement = $this->connection->query(
            'SELECT COUNT(*) AS total
             FROM categorias
             WHERE deleted_at IS NULL'
        );
        $total = (int) $totalStatement->fetchColumn();

        $statement = $this->connection->prepare(
            'SELECT id_categoria, nombre_categoria, descripcion, estado, created_at, updated_at, deleted_at
             FROM categorias
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC, id_categoria DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue(':limit', $pageSize, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return [
            'categories' => $statement->fetchAll(),
            'total' => $total,
        ];
    }
}