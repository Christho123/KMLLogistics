<?php
declare(strict_types=1);

// =========================================================
// API: CATEGORY DELETE
// Endpoint AJAX para eliminacion logica de categorias.
// =========================================================

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('DELETE');

try {
    $payload = getRequestPayload();
    $idCategoria = filter_input(INPUT_GET, 'id_categoria', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]) ?: requestInt($payload, 'id_categoria', 1);

    if (!$idCategoria) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar una categoria valida para eliminar.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new CategoryController();
    $response = $controller->deleteCategory($idCategoria);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al eliminar la categoria.',
    ], JSON_UNESCAPED_UNICODE);
}
