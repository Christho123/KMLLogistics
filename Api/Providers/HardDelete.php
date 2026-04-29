<?php
// =========================================================
// API: PROVIDERS HARD DELETE
// Endpoint AJAX para eliminacion definitiva de proveedores.
// =========================================================

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idProveedor = filter_input(INPUT_POST, 'id_proveedor', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);

    if (!$idProveedor) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar un proveedor valido para eliminar definitivamente.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new ProviderController();
    $response = $controller->hardDeleteProvider($idProveedor);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al eliminar definitivamente el proveedor.',
    ], JSON_UNESCAPED_UNICODE);
}