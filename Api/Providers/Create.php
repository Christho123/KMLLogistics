<?php
declare(strict_types=1);

// =========================================================
// API: PROVIDERS CREATE
// Endpoint AJAX para registrar proveedores.
// =========================================================

header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2).'/Pages/Includes/Load classes/Load classes.php';
require_once dirname(__DIR__) . '/RequestJsonHelper.php';
requireApiMethod('POST');
try {
    $payload = getRequestPayload();
    $razonSocial = requestString($payload, 'razon_social');
    $nombre_Comercial = requestString($payload, 'nombre_comercial');
    $idTipoDocumento = requestInt($payload, 'id_tipo_documento');
    $numeroDocumento = requestString($payload, 'numero_documento');
    $telefono = requestString($payload, 'telefono');
    $correo = requestString($payload, 'correo');
    $direccion = requestString($payload, 'direccion');
    $contacto = requestString($payload, 'contacto');
    $estado = requestInt($payload, 'estado');
   
    if(
        $razonSocial ===''||
        $idTipoDocumento === null||
        $numeroDocumento ===''||
        ($estado!== 0 && $estado !==1)
  
    ){
        http_response_code(422);
        echo json_encode([
            'success'=>false,
            'message'=>'Completa correctamente los datos obligatorios del proovedor',
            
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $controller = new ProviderController();
    $response=$controller->createProvider(
        $razonSocial,
        $nombre_Comercial,
        $idTipoDocumento,
        $numeroDocumento,
        $telefono,
        $correo,
        $direccion,
            $contacto,
        $estado
    );
    if(!$response['success']){
        http_response_code((int)($response['status_code']?? 409));

    }
    unset($response['status_code']);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}catch(Throwable $exception){
    http_response_code(500);
    echo json_encode([
        'success'=>false,
        'message'=>'Ocurrio un problema al registrar el proovedor',
    ], JSON_UNESCAPED_UNICODE);
}


