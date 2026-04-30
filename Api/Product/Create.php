<?php
declare(strict_types=1);

// =========================================================
// API: PRODUCT CREATE
// Endpoint AJAX para registrar productos nuevos.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('POST');
require_once __DIR__ . '/ProductImageHelper.php';

try {
    $payload = getRequestPayload();
    $producto = requestString($payload, 'producto');
    $costo = requestFloat($payload, 'costo');
    $ganancia = requestFloat($payload, 'ganancia');
    $stock = requestInt($payload, 'stock');
    $idCategoria = requestInt($payload, 'id_categoria');
    $idMarca = requestInt($payload, 'id_marca');
    $estado = requestInt($payload, 'estado');

    if (
        $producto === '' ||
        $costo === null || $costo <= 0 ||
        $ganancia === null || $ganancia < 0 || $ganancia >= 100 ||
        $stock === null || $stock < 0 ||
        !$idCategoria ||
        !$idMarca ||
        ($estado !== 0 && $estado !== 1)
    ) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Debes completar correctamente los datos del producto.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $foto = isset($_FILES['foto']) ? saveProductImage($_FILES['foto'], $producto) : null;
    $controller = new ProductController();
    $response = $controller->createProduct($producto, (float) $costo, (float) $ganancia, (int) $stock, $foto, (int) $idCategoria, (int) $idMarca, (int) $estado);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $exception instanceof RuntimeException ? $exception->getMessage() : 'Ocurrio un problema al registrar el producto.',
    ], JSON_UNESCAPED_UNICODE);
}


