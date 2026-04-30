<?php
declare(strict_types=1);

// =========================================================
// API: PROVIDERS LIST
// Endpoint AJAX para listado paginado de proveedores activos.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('GET');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $allowedPageSizes = [10, 20, 50];

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 1,
            'min_range' => 1,
        ],
    ]);

    $pageSize = filter_input(INPUT_GET, 'page_size', FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 10,
            'min_range' => 1,
        ],
    ]);

    $search = trim((string) ($_GET['search'] ?? ''));

    if (!in_array($pageSize, $allowedPageSizes, true)) {
        $pageSize = 10;
    }

    $controller = new ProviderController();

    echo json_encode(
        $controller->listProviders($page, $pageSize, $search),
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al cargar el listado de proveedores.',
    ], JSON_UNESCAPED_UNICODE);
}

