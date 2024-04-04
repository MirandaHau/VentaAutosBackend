<?php
//Se establece los encabezados HTTP para permitir la comunicación entre diferentes orígenes y definir el contenido como JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusión de los archivos necesarios para la conexión a la base de datos y el modelo del auto
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/auto.php';

$database = new Database(); // creación de una nueva instancia de la clase Database y se establece una conexión
$db = $database->getConnection();

$auto = new Auto($db); //nueva instancia de la clase auto, pasando la conexión a la DB

$data = json_decode(file_get_contents("php://input")); // se obtiene los datos enviados en la solicitud y se decodifica de JSON a un objeto PHP

// verificación y asignación de valores a las propiedades del objeto Auto
if(!empty($data->marca) && !empty($data->modelo)) {
    $auto->marca = $data->marca;
    $auto->modelo = $data->modelo;
    $auto->anio = $data->anio;
    $auto->no_serie = $data->no_serie;
    $auto->cliente_id = $data->cliente_id;
    
    //creación de nuevo registro, con respuesta HTTP
    if($auto->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Auto creado exitosamente."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo crear el auto."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "No se pudo crear el auto. Datos incompletos."));
}
?>