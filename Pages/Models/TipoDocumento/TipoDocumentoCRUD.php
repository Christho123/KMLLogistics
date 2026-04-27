<?php
// =========================================================
// MODELO: TIPO DOCUMENTO CRUD
// Acceso a datos del modulo TipoDocumento usando PDO y SP.
// =========================================================

declare(strict_types=1);

// Acceso a datos para tipos de documento.
// Tecnologia asociada: PDO + MySQL + procedimientos almacenados.
class TipoDocumentoCRUD
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
            // Recorre result sets adicionales devueltos por MySQL.
        }

        $statement->closeCursor();
    }

    // Lista tipos de documento activos con paginacion y filtro.
    public function listDocumentTypes(int $page = 1, int $pageSize = 10, string $search = ''): array
    {
        $page = max(1, $page);
        $pageSize = max(1, $pageSize);
        $offset = ($page - 1) * $pageSize;
        $search = trim($search);
        $documentTypes = $this->callProcedureFetchAll('sp_tipo_documento_listar_activos', [$offset, $pageSize, $search]);
        $totalRow = $this->callProcedureFetchOne('sp_tipo_documento_contar_activos', [$search]);
        $total = (int) ($totalRow['total'] ?? 0);

        return [
            'document_types' => $documentTypes,
            'total' => $total,
        ];
    }

    // Lista tipos de documento inactivos o eliminados logicamente.
    public function listInactiveDocumentTypes(string $search = ''): array
    {
        return $this->callProcedureFetchAll('sp_tipo_documento_listar_inactivos', [trim($search)]);
    }

    // Busca un tipo de documento activo por ID.
    public function findDocumentTypeById(int $idTipoDocumento): ?array
    {
        return $this->callProcedureFetchOne('sp_tipo_documento_obtener_activo_por_id', [$idTipoDocumento]);
    }

    // Busca un tipo de documento incluso si esta inactivo.
    public function findAnyDocumentTypeById(int $idTipoDocumento): ?array
    {
        return $this->callProcedureFetchOne('sp_tipo_documento_obtener_por_id', [$idTipoDocumento]);
    }

    // Registra un nuevo tipo de documento.
    public function create(TipoDocumento $tipoDocumento): int
    {
        $row = $this->callProcedureFetchOne('sp_tipo_documento_crear', [
            $tipoDocumento->nombreTipoDocumento,
            $tipoDocumento->descripcion === '' ? null : $tipoDocumento->descripcion,
            $tipoDocumento->estado,
        ]);

        return (int) ($row['id_tipo_documento'] ?? 0);
    }

    // Actualiza un tipo de documento existente.
    public function update(TipoDocumento $tipoDocumento): bool
    {
        $row = $this->callProcedureFetchOne('sp_tipo_documento_actualizar', [
            $tipoDocumento->idTipoDocumento,
            $tipoDocumento->nombreTipoDocumento,
            $tipoDocumento->descripcion === '' ? null : $tipoDocumento->descripcion,
            $tipoDocumento->estado,
        ]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // Elimina logicamente un tipo de documento.
    public function delete(int $idTipoDocumento): bool
    {
        $row = $this->callProcedureFetchOne('sp_tipo_documento_eliminar_logico', [$idTipoDocumento]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // Restaura un tipo de documento eliminado logicamente.
    public function restore(int $idTipoDocumento): bool
    {
        $row = $this->callProcedureFetchOne('sp_tipo_documento_restaurar', [$idTipoDocumento]);

        return (int) ($row['affected_rows'] ?? 0) > 0;
    }

    // Elimina definitivamente un tipo de documento.
    public function hardDelete(int $idTipoDocumento): array
    {
        $row = $this->callProcedureFetchOne('sp_tipo_documento_eliminar_definitivo', [$idTipoDocumento]);

        return [
            'deleted_document_type' => ((int) ($row['deleted_document_type'] ?? 0)) === 1,
            'blocked_by_dependencies' => ((int) ($row['blocked_by_dependencies'] ?? 0)) === 1,
            'related_providers' => (int) ($row['related_providers'] ?? 0),
            'related_users' => (int) ($row['related_users'] ?? 0),
        ];
    }

    // Valida si el nombre ya existe en otro tipo de documento activo.
    public function existsByName(string $nombreTipoDocumento, ?int $excludeId = null): bool
    {
        $row = $this->callProcedureFetchOne('sp_tipo_documento_existe_nombre', [
            trim($nombreTipoDocumento),
            $excludeId,
        ]);

        return (int) ($row['total'] ?? 0) > 0;
    }
}
