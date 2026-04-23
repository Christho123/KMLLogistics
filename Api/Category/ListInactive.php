<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $search = trim((string) ($_GET['search'] ?? ''));
    $controller = new CategoryController();
    echo json_encode($controller->listInactiveCategories($search), JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'No se pudo cargar el listado de categorias inactivas.',
        'error' => $exception->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
