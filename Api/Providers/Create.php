<?php
// =========================================================
// API: PROVIDERS CREATE
// Endpoint AJAX para registrar proveedores.
// =========================================================
declare(strict_types=1);
header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__, 2).'/Pages/Includes/Load classes/Load classes.php';
try {
    $razonSocial=trim((string)($_POST['razon_social']??''));
    $nombre_Comercial=trim((String)($_POST['nombre_comercial']??''));
    $idTipoDocumento=filter_input(INPUT_POST, 'id_tipo_documento', FILTER_VALIDATE_INT);
    $numeroDocumento=trim((String)($_POST['numero_documento']??'')); 
    $telefono=trim((String)($_POST['telefono']??'')); 
    $correo=trim((String)($_POST['correo']??'')); 
    $direccion=trim((String)($_POST['direccion']??'')); 
    $contacto=trim((String)($_POST['contacto']??'')); 
    $estado=filter_input(INPUT_POST, 'estado', FILTER_VALIDATE_INT);
   
    if(
        $razonSocial ===''||
        $idTipoDocumento ===false||
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
