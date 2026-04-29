<?php
declare(strict_types=1);

// =========================================================
// MODELO: AUDIT CRUD
// Acceso a datos del modulo auditoria usando SP.
// =========================================================



class AuditCRUD
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

        return $rows ?: [];
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
        return $this->connection->prepare('CALL ' . $procedureName . '(' . $placeholders . ')');
    }

    private function closeProcedureCursor(PDOStatement $statement): void
    {
        while ($statement->nextRowset()) {
        }

        $statement->closeCursor();
    }

    public function create(Audit $audit): bool
    {
        $row = $this->callProcedureFetchOne('sp_audit_registrar', [
            $audit->idUsuario,
            $audit->modulo,
            $audit->accion,
            $audit->descripcion,
            $audit->datos,
        ]);

        return (int) ($row['id_audit'] ?? 0) > 0;
    }

    public function listAudits(int $page, int $pageSize, string $search): array
    {
        $offset = (max(1, $page) - 1) * max(1, $pageSize);
        $audits = $this->callProcedureFetchAll('sp_audit_listar_activas', [$offset, $pageSize, trim($search)]);
        $totalRow = $this->callProcedureFetchOne('sp_audit_contar_activas', [trim($search)]);

        return [
            'audits' => $audits,
            'total' => (int) ($totalRow['total'] ?? 0),
        ];
    }

    public function findAuditById(int $idAudit): ?array
    {
        return $this->callProcedureFetchOne('sp_audit_obtener_activa_por_id', [$idAudit]);
    }
}

