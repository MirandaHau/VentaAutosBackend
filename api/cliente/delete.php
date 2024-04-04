<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/cliente.php';

$database = new Database();
$db = $database->getConnection();

$cliente = new Cliente($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $cliente->id = $data->id;

    if ($cliente->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Cliente eliminado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo eliminar el cliente."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "No se pudo eliminar el cliente. Datos incompletos."));
}
?>