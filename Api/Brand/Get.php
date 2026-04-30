<?php
declare(strict_types=1);

// =========================================================
// API: BRAND GET
// Endpoint AJAX para consultar el detalle de una marca.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('GET');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idMarca = filter_input(INPUT_GET, 'id_marca', FILTER_VALIDATE_INT);
    if (!$idMarca) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'ID de marca no valido.']);
        exit;
    }
    $controller = new BrandController();
    $response = $controller->getBrand($idMarca);
    if (!$response['success']) {
        http_response_code((int)($response['status_code'] ?? 404));
    }
    echo json_encode($response);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al obtener la marca.']);
}


