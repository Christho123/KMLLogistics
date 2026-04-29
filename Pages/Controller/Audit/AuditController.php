<?php
declare(strict_types=1);

// =========================================================
// CONTROLADOR: AUDIT
// Orquesta listado y detalle de auditorias.
// =========================================================



class AuditController
{
    private AuditCRUD $auditCRUD;

    public function __construct()
    {
        $this->auditCRUD = new AuditCRUD();
    }

    public function handleRequest(): array
    {
        $this->requireAdmin();

        return [
            'current_user' => $_SESSION['user'] ?? null,
        ];
    }

    public function listAudits(int $page, int $pageSize, string $search): array
    {
        $this->requireAdmin();
        $result = $this->auditCRUD->listAudits($page, $pageSize, $search);
        $total = (int) ($result['total'] ?? 0);
        $totalPages = max(1, (int) ceil($total / $pageSize));
        $currentPage = min($page, $totalPages);

        if ($currentPage !== $page) {
            $result = $this->auditCRUD->listAudits($currentPage, $pageSize, $search);
        }

        return [
            'success' => true,
            'audits' => $result['audits'] ?? [],
            'pagination' => [
                'page' => $currentPage,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => $totalPages,
            ],
            'search' => $search,
        ];
    }

    public function getAudit(int $idAudit): array
    {
        $this->requireAdmin();
        $audit = $this->auditCRUD->findAuditById($idAudit);

        if ($audit === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La auditoria solicitada no existe o ya no esta disponible.',
            ];
        }

        return [
            'success' => true,
            'audit' => $audit,
        ];
    }

    private function requireAdmin(): void
    {
        $role = (string) ($_SESSION['user']['rol'] ?? '');

        if (!isset($_SESSION['user']) || !in_array($role, ['admin', 'Admin', 'Administrador'], true)) {
            http_response_code(403);
            exit('Acceso solo para administradores.');
        }
    }
}

