<?php
declare(strict_types=1);

// =========================================================
// API: BRAND LIST INACTIVE
// Endpoint AJAX para listar marcas inactivas.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $search = trim((string) ($_GET['search'] ?? ''));
    $controller = new BrandController();
    echo json_encode($controller->listInactiveBrands($search)); // Asumiendo un metodo listInactiveBrands en el controlador
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al cargar el listado de marcas inactivas.']);
}


