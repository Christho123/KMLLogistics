<?php
declare(strict_types=1);

// =========================================================
// API: BRAND UPDATE
// Endpoint AJAX para actualizar marcas existentes.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('PUT');

try {
    $payload = getRequestPayload();
    $idMarca = requestInt($payload, 'id_marca', 1);
    $nombreMarca = requestString($payload, 'nombre_marca');
    $idProveedor = requestInt($payload, 'id_proveedor');
    $estado = requestInt($payload, 'estado') ?? 0;

    if (!$idMarca || $nombreMarca === '' || !$idProveedor) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'ID, nombre y proveedor de la marca son obligatorios.']);
        exit;
    }

    echo json_encode((new BrandController())->updateBrand($idMarca, $nombreMarca, $idProveedor, $estado));
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la marca.']);
}



