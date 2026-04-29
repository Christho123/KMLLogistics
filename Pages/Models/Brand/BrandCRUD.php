<?php
// =========================================================
// MODELO: BRAND CRUD
// Acceso a datos del modulo Brand usando PDO y SP.
// =========================================================

declare(strict_types=1);

class BrandCRUD
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
            // Recorre cualquier result set extra generado por MySQL.
        }
        $statement->closeCursor();
    }

    // --- METODOS DE NEGOCIO ---

    public function listBrands(int $page = 1, int $pageSize = 10, string $search = ''): array
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $offset = ($page - 1) * $pageSize;
        
        // El SP debe devolver tambien el nombre del proveedor haciendo un JOIN interno
        $brands = $this->callProcedureFetchAll('sp_marca_listar_activas', [$offset, $pageSize, trim($search)]);
        $totalRow = $this->callProcedureFetchOne('sp_marca_contar_activas', [trim($search)]);
        
        return [
            'brands' => $brands,
            'total' => (int) ($totalRow['total'] ?? 0),
        ];
    }

    public function listInactiveBrands(string $search = ''): array
    {
        $search = trim($search);
        return $this->callProcedureFetchAll('sp_marca_listar_inactivas', [$search]);
    }

    public function create(Brand $brand): int
    {
        $row = $this->callProcedureFetchOne('sp_marca_crear', [
            $brand->nombreMarca,
            $brand->idProveedor, // Enviamos el ID del proveedor
            $brand->estado,
        ]);
        return (int) ($row['id_marca'] ?? 0);
    }

    public function update(Brand $brand): bool
    {
        $row = $this->callProcedureFetchOne('sp_marca_actualizar', [
            $brand->idMarca,
            $brand->nombreMarca,
            $brand->idProveedor, // Actualizamos la relacion si es necesario
            $brand->estado,
        ]);
        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function delete(int $idMarca): bool
    {
        $row = $this->callProcedureFetchOne('sp_marca_eliminar_logico', [$idMarca]);
        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function restore(int $idMarca): bool
    {
        $row = $this->callProcedureFetchOne('sp_marca_restaurar', [$idMarca]);
        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    public function hardDelete(int $idMarca): array
    {
        $row = $this->callProcedureFetchOne('sp_marca_eliminar_definitivo', [$idMarca]);
        return [
            'deleted_brand' => ((int) ($row['deleted_brand'] ?? 0)) === 1,
            'deleted_products' => (int) ($row['deleted_products'] ?? 0),
        ];
    }

    public function findBrandById(int $idMarca): ?array
    {
        return $this->callProcedureFetchOne('sp_marca_obtener_activa_por_id', [$idMarca]);
    }

    public function findAnyBrandById(int $idMarca): ?array
    {
        return $this->callProcedureFetchOne('sp_marca_obtener_por_id', [$idMarca]);
    }

    public function existsByName(string $nombreMarca, ?int $excludeId = null): bool
    {
        $row = $this->callProcedureFetchOne('sp_marca_existe_nombre', [trim($nombreMarca), $excludeId]);
        return (int) ($row['total'] ?? 0) > 0;
    }
    public function listSuppliers(): array
    {
        return $this->callProcedureFetchAll('sp_marca_listar_proveedores_activos');
    }
}
