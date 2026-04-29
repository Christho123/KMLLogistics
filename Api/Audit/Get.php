<?php
declare(strict_types=1);

// =========================================================
// API: AUDIT GET
// Endpoint JSON para obtener el detalle de una auditoria.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $response = (new AuditController())->getAudit((int) ($_GET['id_audit'] ?? 0));
    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 404));
    }
    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()], JSON_UNESCAPED_UNICODE);
}


