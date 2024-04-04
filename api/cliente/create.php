<?php
//Se establece los encabezados HTTP para permitir la comunicación entre diferentes orígenes y definir el contenido como JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclusión de los archivos necesarios para la conexión a la base de datos y el modelo del cliente
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/cliente.php';

$database = new Database(); // creación de una nueva instancia de la clase Database y se establece una conexión
$db = $database->getConnection();

$cliente = new Cliente($db);//nueva instancia de la clase cliente, pasando la conexión a la DB

$data = json_decode(file_get_contents("php://input"));// se obtiene los datos enviados en la solicitud y se decodifica de JSON a un objeto PHP

// verificación y asignación de valores a las propiedades del objeto Cliente
if (!empty($data->nombre) && !empty($data->email)) {
    $cliente->nombre = $data->nombre;
    $cliente->email = $data->email;

    //creación de nuevo registro, con respuesta HTTP
    if ($cliente->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Cliente creado exitosamente."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo crear el cliente."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "No se pudo crear el cliente. Datos incompletos."));
}
?>