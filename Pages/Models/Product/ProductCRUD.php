<?php
// =========================================================
// MODELO: PRODUCT CRUD
// Acceso a datos del modulo Product usando PDO y SP.
// =========================================================

declare(strict_types=1);

class ProductCRUD
{
    private PDO $connection;

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

        return $rows;
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

    public function listProducts(int $page = 1, int $pageSize = 10, string $search = ''): array
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $offset = ($page - 1) * $pageSize;
        $search = trim($search);

        $products = $this->callProcedureFetchAll('sp_producto_listar_activas', [$offset, $pageSize, $search]);
        $totalRow = $this->callProcedureFetchOne('sp_producto_contar_activas', [$search]);

        return [
            'products' => $products,
            'total' => (int) ($totalRow['total'] ?? 0),
        ];
    }

    public function listInactiveProducts(string $search = ''): array
    {
        return $this->callProcedureFetchAll('sp_producto_listar_inactivas', [trim($search)]);
    }

    public function findProductById(int $idProducto): ?array
    {
        return $this->callProcedureFetchOne('sp_producto_obtener_activa_por_id', [$idProducto]);
    }

    public function findAnyProductById(int $idProducto): ?array
    {
        return $this->callProcedureFetchOne('sp_producto_obtener_por_id', [$idProducto]);
    }

    public function create(Product $product): int
    {
        $row = $this->callProcedureFetchOne('sp_producto_crear', [
            $product->producto,
            $product->costo,
            $product->ganancia,
            $product->precio,
            $product->stock,
            $product->foto,
            $product->idCategoria,
            $product->idMarca,
            $product->estado,
        ]);

        return (int) ($row['id_producto'] ?? 0);
    }

    public function update(Product $product): bool
    {
        $row = $this->callProcedureFetchOne('sp_producto_actualizar', [
            $product->idProducto,
            $product->producto,
            $product->costo,
            $product->ganancia,
            $product->precio,
            $product->stock,
            $product->foto,
            $product->idCategoria,
            $product->idMarca,
            $product->estado,
        ]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function delete(int $idProducto): bool
    {
        $row = $this->callProcedureFetchOne('sp_producto_eliminar_logico', [$idProducto]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function restore(int $idProducto): bool
    {
        $row = $this->callProcedureFetchOne('sp_producto_restaurar', [$idProducto]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function hardDelete(int $idProducto): bool
    {
        $row = $this->callProcedureFetchOne('sp_producto_eliminar_definitivo', [$idProducto]);

        return ((int) ($row['deleted_product'] ?? 0)) === 1;
    }

    public function existsByName(string $producto, ?int $excludeId = null): bool
    {
        $row = $this->callProcedureFetchOne('sp_producto_existe_nombre', [trim($producto), $excludeId]);

        return (int) ($row['total'] ?? 0) > 0;
    }

    public function listCategories(): array
    {
        return $this->callProcedureFetchAll('sp_producto_listar_categorias_activas');
    }

    public function listBrands(): array
    {
        return $this->callProcedureFetchAll('sp_producto_listar_marcas_activas');
    }
}
