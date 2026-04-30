<?php
declare(strict_types=1);

// =========================================================
// API: BRAND UPDATE
// Endpoint AJAX para actualizar marcas existentes.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    if (empty($_POST['id_marca']) || empty($_POST['nombre_marca']) || empty($_POST['id_proveedor'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'ID, nombre y proveedor de la marca son obligatorios.']);
        exit;
    }

    $idMarca = (int) $_POST['id_marca'];
    $nombreMarca = (string) $_POST['nombre_marca'];
    $idProveedor = (int) $_POST['id_proveedor'];
    $estado = (int) $_POST['estado']; // Asumiendo que 'estado' tambien se envia en el formulario de actualizacion
    echo json_encode((new BrandController())->updateBrand($idMarca, $nombreMarca, $idProveedor, $estado));
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la marca.']);
}


