<?php
declare(strict_types=1);

// =========================================================
// API: AUDIT LIST
// Endpoint JSON para listar auditorias con paginacion y busqueda.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $pageSize = max(1, (int) ($_GET['page_size'] ?? 10));
    $search = trim((string) ($_GET['search'] ?? ''));
    echo json_encode((new AuditController())->listAudits($page, $pageSize, $search), JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()], JSON_UNESCAPED_UNICODE);
}


