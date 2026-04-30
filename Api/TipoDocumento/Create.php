<?php
declare(strict_types=1);

// =========================================================
// API: TIPO DOCUMENTO CREATE
// Endpoint AJAX para registrar tipos de documento nuevos.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('POST');

try {
    $payload = getRequestPayload();
    $nombreTipoDocumento = requestString($payload, 'nombre_tipo_documento');
    $descripcion = requestString($payload, 'descripcion');
    $estado = requestInt($payload, 'estado');

    if ($nombreTipoDocumento === '' || ($estado !== 0 && $estado !== 1)) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes completar correctamente el nombre y el estado del tipo de documento.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new TipoDocumentoController();
    $response = $controller->createDocumentType($nombreTipoDocumento, $descripcion, $estado);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al registrar el tipo de documento.',
    ], JSON_UNESCAPED_UNICODE);
}


