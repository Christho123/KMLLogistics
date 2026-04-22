<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

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

    if (!in_array($pageSize, $allowedPageSizes, true)) {
        $pageSize = 10;
    }

    $categoryCRUD = new CategoryCRUD();
    $result = $categoryCRUD->listCategories($page, $pageSize);
    $total = (int) ($result['total'] ?? 0);
    $totalPages = max(1, (int) ceil($total / $pageSize));
    $currentPage = min($page, $totalPages);

    if ($currentPage !== $page) {
        $result = $categoryCRUD->listCategories($currentPage, $pageSize);
    }

    echo json_encode([
        'success' => true,
        'categories' => $result['categories'] ?? [],
        'pagination' => [
            'page' => $currentPage,
            'page_size' => $pageSize,
            'total' => $total,
            'total_pages' => $totalPages,
        ],
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'No se pudo cargar el listado de categorias.',
        'error' => $exception->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}