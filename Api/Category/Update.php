<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idCategoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);
    $nombreCategoria = trim((string) ($_POST['nombre_categoria'] ?? ''));
    $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
    $estado = filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT);

    if (!$idCategoria || $nombreCategoria === '' || $descripcion === '' || ($estado !== 0 && $estado !== 1)) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Completa correctamente los datos de la categoria.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new CategoryController();
    $response = $controller->updateCategory($idCategoria, $nombreCategoria, $descripcion, $estado);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'No se pudo actualizar la categoria.',
        'error' => $exception->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
