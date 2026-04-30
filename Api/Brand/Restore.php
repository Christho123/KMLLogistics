<?php
declare(strict_types=1);

// =========================================================
// API: BRAND RESTORE
// Endpoint AJAX para restaurar marcas inactivas.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('PUT');

try {
    $payload = getRequestPayload();
    $idMarca = requestInt($payload, 'id_marca', 1);

    if (!$idMarca) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'ID de marca no proporcionado para restaurar.']);
        exit;
    }

    echo json_encode((new BrandController())->restoreBrand($idMarca)); // Asumiendo un metodo restoreBrand en el controlador
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al restaurar la marca.']);
}



