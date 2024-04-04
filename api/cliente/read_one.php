<?php
//HEADERS:Establece los encabezados HTTP para permitir el acceso desde cualquier origen y especifica el tipo de contenido como JSON.
header("Access-Control-Allow-Origin: *");// Permite el acceso desde cualquier origen
header("Content-Type: application/json; charset=UTF-8");// Especifica el tipo de contenido como JSON

//Inclusión de archivos necesarios para la conexión a la base de datos
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/cliente.php';//definición del modelo cliente

$database = new Database(); //creación de una nueva instancia de la clase Database
$db = $database->getConnection();//se establece la conexión

$cliente = new Cliente($db);//crea una instancia del modelo de cliente

// Intenta obtener el ID del cliente desde la URL. Si no se proporciona, termina el script y muestra un mensaje
$cliente->id = isset($_GET['id']) ? $_GET['id'] : die("ID del cliente no proporcionado.");

$cliente->readOne();//se lee la información del ID proporcionado

if($cliente->nombre != null) {
    // Crea un arreglo con la información de cliente
    $cliente_arr = array(
        "id" => $cliente->id,
        "nombre" => $cliente->nombre,
        "email" => $cliente->email
    );

    http_response_code(200); //respuestas HTTP
    echo json_encode($cliente_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Cliente no encontrado."));
}
?>