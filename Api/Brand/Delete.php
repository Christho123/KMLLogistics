<?php
declare(strict_types=1);

// =========================================================
// API: BRAND DELETE
// Endpoint AJAX para eliminar logicamente marcas activas.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('DELETE');

try {
    $payload = getRequestPayload();
    $idMarca = requestInt($payload, 'id_marca', 1);

    if (!$idMarca) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'ID de marca no proporcionado para eliminar.']);
        exit;
    }

    echo json_encode((new BrandController())->deleteBrand($idMarca)); // Asumiendo un metodo deleteBrand en el controlador
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la marca.']);
}



