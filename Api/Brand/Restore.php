<?php
declare(strict_types=1);

// =========================================================
// API: BRAND RESTORE
// Endpoint AJAX para restaurar marcas inactivas.
// =========================================================




header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    if (empty($_POST['id_marca'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'ID de marca no proporcionado para restaurar.']);
        exit;
    }

    $idMarca = (int) $_POST['id_marca'];
    echo json_encode((new BrandController())->restoreBrand($idMarca)); // Asumiendo un mÃ©todo restoreBrand en el controlador
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al restaurar la marca.']);
}


