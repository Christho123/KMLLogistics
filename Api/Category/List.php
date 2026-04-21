<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $categoryCRUD = new CategoryCRUD();
    $categories = $categoryCRUD->listCategories();

    echo json_encode([
        'success' => true,
        'categories' => $categories,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'No se pudo cargar el listado de categorias.',
        'error' => $exception->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
