<?php
declare(strict_types=1);

// =========================================================
// API: TIPO DOCUMENTO GET
// Endpoint AJAX para consultar el detalle de un tipo de documento.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idTipoDocumento = filter_input(INPUT_GET, 'id_tipo_documento', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);

    if (!$idTipoDocumento) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar un tipo de documento valido.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new TipoDocumentoController();
    $response = $controller->getDocumentType($idTipoDocumento);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 404));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al consultar el detalle del tipo de documento.',
    ], JSON_UNESCAPED_UNICODE);
}

