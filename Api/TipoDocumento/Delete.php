<?php
declare(strict_types=1);

// =========================================================
// API: TIPO DOCUMENTO DELETE
// Endpoint AJAX para eliminacion logica de tipos de documento.
// =========================================================



header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idTipoDocumento = filter_input(INPUT_POST, 'id_tipo_documento', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);

    if (!$idTipoDocumento) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes indicar un tipo de documento valido para eliminar.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new TipoDocumentoController();
    $response = $controller->deleteDocumentType($idTipoDocumento);

    if (!$response['success']) {
        http_response_code((int) ($response['status_code'] ?? 409));
    }

    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Ocurrio un problema al eliminar el tipo de documento.',
    ], JSON_UNESCAPED_UNICODE);
}

