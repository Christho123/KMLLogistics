<?php
declare(strict_types=1);

// =========================================================
// API: PRODUCT DELETE
// Endpoint AJAX para eliminacion logica de productos.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('DELETE');

try {
    $payload = getRequestPayload();
    $idProducto = requestInt($payload, 'id_producto', 1);

    if (!$idProducto) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar un producto valido para eliminar.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new ProductController();
    $response = $controller->deleteProduct($idProducto);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al eliminar el producto.',
    ], JSON_UNESCAPED_UNICODE);
}


