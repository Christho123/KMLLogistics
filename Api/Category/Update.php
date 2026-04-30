<?php
declare(strict_types=1);

// =========================================================
// API: CATEGORY UPDATE
// Endpoint AJAX para actualizar categorias existentes.
// =========================================================

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('PUT');

try {
    $payload = getRequestPayload();
    $idCategoria = filter_input(INPUT_GET, 'id_categoria', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]) ?: requestInt($payload, 'id_categoria', 1);
    $controller = new CategoryController();

    if (!$idCategoria) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar una categoria valida para actualizar.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $currentResponse = $controller->getCategory($idCategoria);

    if (!$currentResponse['success']) {
        http_response_code((int) ($currentResponse['status_code'] ?? 404));
        unset($currentResponse['status_code']);
        echo json_encode($currentResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $currentCategory = $currentResponse['category'];
    $nombreCategoria = array_key_exists('nombre_categoria', $payload)
        ? requestString($payload, 'nombre_categoria')
        : (string) ($currentCategory['nombre_categoria'] ?? '');
    $descripcion = array_key_exists('descripcion', $payload)
        ? requestString($payload, 'descripcion')
        : (string) ($currentCategory['descripcion'] ?? '');
    $estado = array_key_exists('estado', $payload)
        ? requestInt($payload, 'estado')
        : (int) ($currentCategory['estado'] ?? 1);

    if (!$idCategoria || $nombreCategoria === '' || $descripcion === '' || ($estado !== 0 && $estado !== 1)) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes completar correctamente los datos de la categoria antes de guardar los cambios.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

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
        'message' => 'Ocurrio un problema al actualizar la categoria.',
    ], JSON_UNESCAPED_UNICODE);
}
