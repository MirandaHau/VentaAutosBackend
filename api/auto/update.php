<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/auto.php';

$database = new Database();
$db = $database->getConnection();

$auto = new Auto($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
    $auto->id = $data->id;
    $auto->marca = $data->marca;
    $auto->modelo = $data->modelo;
    $auto->anio = $data->anio;
    $auto->no_serie = $data->no_serie;
    $auto->cliente_id = $data->cliente_id;

    if($auto->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Auto actualizado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo actualizar el auto."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "No se pudo actualizar el auto. Datos incompletos."));
}
?>