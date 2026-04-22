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

        $stats = $this->getCategoryStats();

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
            'total' => (int) ($stats['total'] ?? 0),
            'latest_id' => (int) ($stats['latest_id'] ?? 0),
        ];
    }

    // Devuelve un resumen ligero para detectar cambios sin traer toda la tabla.
    public function getCategoryStats(): array
    {
        $statement = $this->connection->query(
            'SELECT COUNT(*) AS total, COALESCE(MAX(id_categoria), 0) AS latest_id
             FROM categorias
             WHERE deleted_at IS NULL'
        );

        return $statement->fetch() ?: [
            'total' => 0,
            'latest_id' => 0,
        ];
    }
}