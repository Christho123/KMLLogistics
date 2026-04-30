<?php
declare(strict_types=1);

// =========================================================
// API: TIPO DOCUMENTO UPDATE
// Endpoint AJAX para actualizar tipos de documento existentes.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('PUT');

try {
    $payload = getRequestPayload();
    $idTipoDocumento = filter_input(INPUT_GET, 'id_tipo_documento', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]) ?: requestInt($payload, 'id_tipo_documento', 1);
    $controller = new TipoDocumentoController();

    if (!$idTipoDocumento) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar un tipo de documento valido para actualizar.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $currentResponse = $controller->getDocumentType($idTipoDocumento);

    if (!$currentResponse['success']) {
        http_response_code((int) ($currentResponse['status_code'] ?? 404));
        unset($currentResponse['status_code']);
        echo json_encode($currentResponse, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $currentDocumentType = $currentResponse['document_type'];
    $nombreTipoDocumento = array_key_exists('nombre_tipo_documento', $payload)
        ? requestString($payload, 'nombre_tipo_documento')
        : (string) ($currentDocumentType['nombre_tipo_documento'] ?? '');
    $descripcion = array_key_exists('descripcion', $payload)
        ? requestString($payload, 'descripcion')
        : (string) ($currentDocumentType['descripcion'] ?? '');
    $estado = array_key_exists('estado', $payload)
        ? requestInt($payload, 'estado')
        : (int) ($currentDocumentType['estado'] ?? 1);

    if (!$idTipoDocumento || $nombreTipoDocumento === '' || ($estado !== 0 && $estado !== 1)) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes completar correctamente los datos del tipo de documento antes de guardar los cambios.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $response = $controller->updateDocumentType($idTipoDocumento, $nombreTipoDocumento, $descripcion, $estado);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al actualizar el tipo de documento.',
    ], JSON_UNESCAPED_UNICODE);
}


