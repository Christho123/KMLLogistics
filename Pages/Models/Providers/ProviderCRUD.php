<?php
// =========================================================
// MODELO: PROVIDER CRUD
// Acceso a datos del modulo Providers usando PDO y SP.
// =========================================================

declare(strict_types=1);

class ProviderCRUD
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = getConnection();
    }

    // =====================================================
    // HELPERS PARA STORED PROCEDURES
    // =====================================================

    private function callProcedureFetchAll(string $procedureName, array $parameters = []): array
    {
        $statement = $this->prepareProcedureCall($procedureName, $parameters);
        $statement->execute($parameters);

        $rows = $statement->fetchAll();

        // 🔥 FIX: evitar array doble [[...]]
        if (isset($rows[0]) && is_array($rows[0]) && isset($rows[0][0])) {
            $rows = $rows[0];
        }

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
        while ($statement->nextRowset()) {}
        $statement->closeCursor();
    }

    // =====================================================
    // LIST
    // =====================================================
    public function listProviders(int $page, int $pageSize, string $search): array
    {
        $offset = ($page - 1) * $pageSize;

        $providers = $this->callProcedureFetchAll(
            'sp_proveedor_listar_activas',
            [$offset, $pageSize, trim($search)]
        );

        $totalRow = $this->callProcedureFetchOne(
            'sp_proveedor_contar_activas',
            [trim($search)]
        );

        return [
            'providers' => $providers,
            'total' => (int) ($totalRow['total'] ?? 0),
        ];
    }

    // =====================================================
    // LIST INACTIVE
    // =====================================================
    public function listInactiveProviders(string $search): array
    {
        return $this->callProcedureFetchAll(
            'sp_proveedor_listar_inactivas',
            [trim($search)]
        );
    }

    // =====================================================
    // GET
    // =====================================================
    public function findProviderById(int $id): ?array
    {
        return $this->callProcedureFetchOne(
            'sp_proveedor_obtener_activa_por_id',
            [$id]
        );
    }

    public function findAnyProviderById(int $id): ?array
    {
        return $this->callProcedureFetchOne(
            'sp_proveedor_obtener_por_id',
            [$id]
        );
    }

    // =====================================================
    // CREATE
    // =====================================================
    public function create(Provider $p): int
    {
        $row = $this->callProcedureFetchOne(
            'sp_proveedor_crear',
            [
                $p->razonSocial,
                $p->nombreComercial,
                $p->idTipoDocumento,
                $p->numeroDocumento,
                $p->telefono,
                $p->correo,
                $p->direccion,
                $p->contacto,
                $p->estado
            ]
        );

        return (int) ($row['id_proveedor'] ?? 0);
    }

    // =====================================================
    // UPDATE
    // =====================================================
    public function update(Provider $p): bool
    {
        $row = $this->callProcedureFetchOne(
            'sp_proveedor_actualizar',
            [
                $p->idProveedor,
                $p->razonSocial,
                $p->nombreComercial,
                $p->idTipoDocumento,
                $p->numeroDocumento,
                $p->telefono,
                $p->correo,
                $p->direccion,
                $p->contacto,
                $p->estado
            ]
        );

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // =====================================================
    // DELETE (LOGICO)
    // =====================================================
    public function delete(int $id): bool
    {
        $row = $this->callProcedureFetchOne(
            'sp_proveedor_eliminar_logico',
            [$id]
        );

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // =====================================================
    // RESTORE
    // =====================================================
    public function restore(int $id): bool
    {
        $row = $this->callProcedureFetchOne(
            'sp_proveedor_restaurar',
            [$id]
        );

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // =====================================================
    // HARD DELETE
    // =====================================================
    public function hardDelete(int $id): array
    {
        $row = $this->callProcedureFetchOne(
            'sp_proveedor_eliminar_definitivo',
            [$id]
        );

        return [
            'deleted_provider' => ((int) ($row['deleted_provider'] ?? 0)) === 1,
        ];
    }

    // =====================================================
    // EXISTS
    // =====================================================
    public function existsByDocument(int $tipo, string $numero, ?int $excludeId = null): bool
    {
        $row = $this->callProcedureFetchOne(
            'sp_proveedor_existe_documento',
            [$tipo, trim($numero), $excludeId]
        );

        return (int) ($row['total'] ?? 0) > 0;
    }
    
}