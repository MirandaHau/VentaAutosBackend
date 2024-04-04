<?php
// Encabezados HTTP necesarios para permitir el acceso cruzado y especificar que el contenido devuelto será JSON
header("Access-Control-Allow-Origin: *");// Permite solicitudes desde cualquier origen.
header("Content-Type: application/json; charset=UTF-8");//contenido de respuesta como JSON

// se incluyen los archivos necesarios para la conexión a la base de datos y el modelo Auto
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/auto.php';

$database = new Database();//instancia y conexion con DataBase
$db = $database->getConnection();

$auto = new Auto($db);//se crea la instancia del modelo auto y pasamos la conexion a la DB

$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : die("ID del cliente no proporcionado.");

// método readByCliente para obtener todos los autos asociados al ID del cliente proporcionado
$stmt = $auto->readByCliente($cliente_id);
$num = $stmt->rowCount();//se cuenta el número de filas y autos retornados

if($num > 0) {
    //// se verifica si se encontraron autos para el cliente especificado
    $autos_arr = array();
    $autos_arr["Registros:"] = array();//inicia el arreglo con la información del auto

    // Recorre cada fila/auto encontrado y agrega su información al arreglo
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $auto_item = array( //arreglo con la información del auto
            "id" => $id,
            "marca" => $marca,
            "modelo" => $modelo,
            "anio" => $anio,
            "no_serie" => $no_serie,
            "cliente_id" => $cliente_id
        );

        array_push($autos_arr["Registros:"], $auto_item); //se agrega el auto al arreglo de registors
    }

    http_response_code(200);//respuestas de estado HTTP
    echo json_encode($autos_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "NO SE ENCONTRARON AUTOS PARA EL ID DEL CLIENTE."));
}
?>