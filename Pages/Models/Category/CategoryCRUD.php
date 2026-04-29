<?php
declare(strict_types=1);

// =========================================================
// MODELO: CATEGORY CRUD
// Acceso a datos del modulo Category usando PDO y SP.
// =========================================================



// Acceso a datos para categorias.
// Tecnologia asociada: PDO + MySQL + procedimientos almacenados.
class CategoryCRUD
{
    private PDO $connection;

    // Inicializa la conexion.
    public function __construct()
    {
        $this->connection = getConnection();
    }

    // Ejecuta un procedimiento almacenado y devuelve todas las filas.
    private function callProcedureFetchAll(string $procedureName, array $parameters = []): array
    {
        $statement = $this->prepareProcedureCall($procedureName, $parameters);
        $statement->execute($parameters);
        $rows = $statement->fetchAll();
        $this->closeProcedureCursor($statement);

        return $rows;
    }

    // Ejecuta un procedimiento almacenado y devuelve una sola fila.
    private function callProcedureFetchOne(string $procedureName, array $parameters = []): ?array
    {
        $statement = $this->prepareProcedureCall($procedureName, $parameters);
        $statement->execute($parameters);
        $row = $statement->fetch();
        $this->closeProcedureCursor($statement);

        return $row === false ? null : $row;
    }

    // Prepara una llamada segura a procedimiento almacenado.
    // Este punto conecta PHP con la capa SQL definida en la BD.
    private function prepareProcedureCall(string $procedureName, array $parameters = []): PDOStatement
    {
        $placeholders = implode(', ', array_fill(0, count($parameters), '?'));
        $sql = 'CALL ' . $procedureName . '(' . $placeholders . ')';

        return $this->connection->prepare($sql);
    }

    // Cierra todos los result sets pendientes de un CALL.
    private function closeProcedureCursor(PDOStatement $statement): void
    {
        while ($statement->nextRowset()) {
            // Recorre cualquier result set extra generado por MySQL.
        }

        $statement->closeCursor();
    }

    // Lista categorias activas por borrado logico, desde la mas reciente, con paginacion.
    // Metodo clave para el listado AJAX de la vista Category.
    public function listCategories(int $page = 1, int $pageSize = 10, string $search = ''): array
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $offset = ($page - 1) * $pageSize;
        $search = trim($search);
        $categories = $this->callProcedureFetchAll('sp_categoria_listar_activas', [$offset, $pageSize, $search]);
        $totalRow = $this->callProcedureFetchOne('sp_categoria_contar_activas', [$search]);
        $total = (int) ($totalRow['total'] ?? 0);

        return [
            'categories' => $categories,
            'total' => $total,
        ];
    }

    // Lista categorias inactivas o eliminadas logicamente con filtro por id y nombre.
    public function listInactiveCategories(string $search = ''): array
    {
        $search = trim($search);

        return $this->callProcedureFetchAll('sp_categoria_listar_inactivas', [$search]);
    }

    // Busca una categoria activa por id.
    public function findCategoryById(int $idCategoria): ?array
    {
        return $this->callProcedureFetchOne('sp_categoria_obtener_activa_por_id', [$idCategoria]);
    }

    // Busca una categoria incluso si esta inactiva.
    public function findAnyCategoryById(int $idCategoria): ?array
    {
        return $this->callProcedureFetchOne('sp_categoria_obtener_por_id', [$idCategoria]);
    }

    // Registra una categoria nueva.
    public function create(Category $category): int
    {
        $row = $this->callProcedureFetchOne('sp_categoria_crear', [
            $category->nombreCategoria,
            $category->descripcion,
            $category->estado,
        ]);

        return (int) ($row['id_categoria'] ?? 0);
    }

    // Actualiza una categoria existente.
    public function update(Category $category): bool
    {
        $row = $this->callProcedureFetchOne('sp_categoria_actualizar', [
            $category->idCategoria,
            $category->nombreCategoria,
            $category->descripcion,
            $category->estado,
        ]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // Elimina logicamente una categoria.
    public function delete(int $idCategoria): bool
    {
        $row = $this->callProcedureFetchOne('sp_categoria_eliminar_logico', [$idCategoria]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // Restaura una categoria eliminada logicamente.
    public function restore(int $idCategoria): bool
    {
        $row = $this->callProcedureFetchOne('sp_categoria_restaurar', [$idCategoria]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // Elimina definitivamente la categoria y sus productos asociados.
    public function hardDelete(int $idCategoria): array
    {
        $row = $this->callProcedureFetchOne('sp_categoria_eliminar_definitivo', [$idCategoria]);

        return [
            'deleted_category' => ((int) ($row['deleted_category'] ?? 0)) === 1,
            'deleted_products' => (int) ($row['deleted_products'] ?? 0),
        ];
    }

    // Valida si el nombre ya existe en otra categoria activa.
    public function existsByName(string $nombreCategoria, ?int $excludeId = null): bool
    {
        $row = $this->callProcedureFetchOne('sp_categoria_existe_nombre', [
            trim($nombreCategoria),
            $excludeId,
        ]);

        return (int) ($row['total'] ?? 0) > 0;
    }
}

