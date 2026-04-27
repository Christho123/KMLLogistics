<?php
// =========================================================
// API: TIPO DOCUMENTO UPDATE
// Endpoint AJAX para actualizar tipos de documento existentes.
// =========================================================

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once dirname(__DIR__, 2) . '/Pages/Includes/Load classes/Load classes.php';

try {
    $idTipoDocumento = filter_input(INPUT_POST, 'id_tipo_documento', FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
        ],
    ]);
    $nombreTipoDocumento = trim((string) ($_POST['nombre_tipo_documento'] ?? ''));
    $descripcion = trim((string) ($_POST['descripcion'] ?? ''));
    $estado = filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT);

    if (!$idTipoDocumento || $nombreTipoDocumento === '' || ($estado !== 0 && $estado !== 1)) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Debes completar correctamente los datos del tipo de documento antes de guardar los cambios.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $controller = new TipoDocumentoController();
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
