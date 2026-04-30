<?php
declare(strict_types=1);

// =========================================================
// API: BRAND CREATE
// Endpoint AJAX para registrar marcas nuevas.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('POST');

try {
    $payload = getRequestPayload();
    $nombreMarca = requestString($payload, 'nombre_marca');
    $idProveedor = requestInt($payload, 'id_proveedor');
    $estado = requestInt($payload, 'estado') ?? 0;

    if ($nombreMarca === '' || !$idProveedor) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'El nombre y el proveedor son obligatorios.']);
        exit;
    }

    $controller = new BrandController();
    echo json_encode($controller->createBrand($nombreMarca, $idProveedor, $estado)); // Asumiendo un metodo storeBrand en el controlador
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al registrar la marca.']);
}



