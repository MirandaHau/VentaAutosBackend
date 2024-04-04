<?php
//HEADERS:Establece los encabezados HTTP para permitir el acceso desde cualquier origen y especifica el tipo de contenido como JSON.
header("Access-Control-Allow-Origin: *");// Permite el acceso desde cualquier origen
header("Content-Type: application/json; charset=UTF-8");// Especifica el tipo de contenido como JSON

//Inclusión de archivos necesarios para la conexión a la base de datos
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/auto.php';; //definición del modelo auto

$database = new Database(); // creación de una nueva instancia de la clase Database
$db = $database->getConnection(); //conexión a la base de datos

$auto = new Auto($db); //crea una instancia del modelo de Auto

//Intenta obtener el ID del cliente desde la URL. Si no se proporciona, termina el script y muestra un mensaje
$auto->id = isset($_GET['id']) ? $_GET['id'] : die("ID del auto no proporcionado.");

$auto->readOne();//se lee la información del ID proporcionado

if($auto->marca != null) {
    // Crear un arreglo con la información del auto
    $auto_arr = array(
        "id" => $auto->id,
        "marca" => $auto->marca,
        "modelo" => $auto->modelo,
        "anio" => $auto->anio,
        "no_serie" => $auto->no_serie,
        "cliente_id" => $auto->cliente_id
    );

    // Establece el código de respuesta HTTP y devuelve la información del auto en formato JSON
    http_response_code(200);
    echo json_encode($auto_arr);
} else {
    // Si el auto no se encuentra, establece el código de respuesta HTTP y devuelve un mensaje de error en formato JSON
    http_response_code(404);
    echo json_encode(array("message" => "Auto no encontrado."));
}
?>