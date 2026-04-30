<?php
declare(strict_types=1);

// =========================================================
// API: BRAND CREATE
// Endpoint AJAX para registrar marcas nuevas.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    if (empty($_POST['nombre_marca']) || empty($_POST['id_proveedor'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'El nombre y el proveedor son obligatorios.']);
        exit;
    }

    $nombreMarca = (string) $_POST['nombre_marca'];
    $idProveedor = (int) $_POST['id_proveedor'];
    $estado = (int) $_POST['estado']; // Asumiendo que 'estado' tambien se envia en el formulario de creacion
    $controller = new BrandController();
    echo json_encode($controller->createBrand($nombreMarca, $idProveedor, $estado)); // Asumiendo un metodo storeBrand en el controlador
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al registrar la marca.']);
}


