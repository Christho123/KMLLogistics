<?php
declare(strict_types=1);

// =========================================================
// API: TIPO DOCUMENTO HARD DELETE
// Endpoint AJAX para eliminacion definitiva de tipos de documento.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('DELETE');

try {
    $payload = getRequestPayload();
    $idTipoDocumento = filter_input(INPUT_GET, 'id_tipo_documento', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]) ?: requestInt($payload, 'id_tipo_documento', 1);

    if (!$idTipoDocumento) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar un tipo de documento valido para eliminar definitivamente.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new TipoDocumentoController();
    $response = $controller->hardDeleteDocumentType($idTipoDocumento);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al eliminar definitivamente el tipo de documento.',
    ], JSON_UNESCAPED_UNICODE);
}


