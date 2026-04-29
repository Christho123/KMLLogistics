<?php
// =========================================================
// API: PROVIDERS UPDATE
// Endpoint AJAX para actualizar proveedores existentes.
// =========================================================

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idProveedor = filter_input(INPUT_POST, 'id_proveedor', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);

    $razonSocial = trim((string) ($_POST['razon_social'] ?? ''));
    $nombreComercial = trim((string) ($_POST['nombre_comercial'] ?? ''));
    $idTipoDocumento = filter_input(INPUT_POST, 'id_tipo_documento', FILTER_VALIDATE_INT);
    $numeroDocumento = trim((string) ($_POST['numero_documento'] ?? ''));
    $telefono = trim((string) ($_POST['telefono'] ?? ''));
    $correo = trim((string) ($_POST['correo'] ?? ''));
    $direccion = trim((string) ($_POST['direccion'] ?? ''));
    $contacto = trim((string) ($_POST['contacto'] ?? ''));
    $estado = filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT);

    if (
        !$idProveedor ||
        $razonSocial === '' ||
        !$idTipoDocumento ||
        $numeroDocumento === '' ||
        ($estado !== 0 && $estado !== 1)
    ) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes completar correctamente los datos del proveedor antes de guardar.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new ProviderController();

    $response = $controller->updateProvider(
        $idProveedor,
        $razonSocial,
        $nombreComercial,
        $idTipoDocumento,
        $numeroDocumento,
        $telefono,
        $correo,
        $direccion,
        $contacto,
        $estado
    );

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al actualizar el proveedor.',
    ], JSON_UNESCAPED_UNICODE);
}