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

$auto->id = $data->id;

if($auto->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Auto eliminado."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "No se pudo eliminar el auto."));
}
?>