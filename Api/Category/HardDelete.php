<?php
// =========================================================
// API: CATEGORY HARD DELETE
// Endpoint AJAX para eliminacion definitiva de categorias.
// =========================================================

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idCategoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);

    if (!$idCategoria) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar una categoria valida para eliminar definitivamente.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new CategoryController();
    $response = $controller->hardDeleteCategory($idCategoria);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al eliminar definitivamente la categoria.',
    ], JSON_UNESCAPED_UNICODE);
}
