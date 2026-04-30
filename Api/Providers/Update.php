<?php
declare(strict_types=1);

// =========================================================
// API: PROVIDERS UPDATE
// Endpoint AJAX para actualizar proveedores existentes.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('PUT');

try {
    $payload = getRequestPayload();
    $idProveedor = requestInt($payload, 'id_proveedor', 1);
    $razonSocial = requestString($payload, 'razon_social');
    $nombreComercial = requestString($payload, 'nombre_comercial');
    $idTipoDocumento = requestInt($payload, 'id_tipo_documento');
    $numeroDocumento = requestString($payload, 'numero_documento');
    $telefono = requestString($payload, 'telefono');
    $correo = requestString($payload, 'correo');
    $direccion = requestString($payload, 'direccion');
    $contacto = requestString($payload, 'contacto');
    $estado = requestInt($payload, 'estado');

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

