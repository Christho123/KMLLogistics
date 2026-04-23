<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idCategoria = filter_input(INPUT_GET, 'id_categoria', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);

    if (!$idCategoria) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar una categoria valida.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new CategoryController();
    $response = $controller->getCategory($idCategoria);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 404));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'No se pudo obtener el detalle de la categoria.',
        'error' => $exception->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
