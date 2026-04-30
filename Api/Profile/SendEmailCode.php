<?php
declare(strict_types=1);

// =========================================================
// API: PROFILE SEND EMAIL CODE
// Endpoint AJAX para enviar codigo de verificacion de email.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('POST');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $response = (new ProfileController())->sendCode('email');
    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 422));
    }
    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()], JSON_UNESCAPED_UNICODE);
}



