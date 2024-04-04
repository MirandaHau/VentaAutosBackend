<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//echo __DIR__ . '/../../models/auto.php';
//exit();

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/cliente.php';

$database = new Database();
$db = $database->getConnection();

$cliente = new Cliente($db);

$stmt = $cliente->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $clientes_arr = array();
    $clientes_arr["Registros:"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $cliente_item = array(
            "id" => $id,
            "nombre" => $nombre,
            "email" => $email
        );

        array_push($clientes_arr["Registros:"], $cliente_item);
    }

    http_response_code(200);
    echo json_encode($clientes_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No se encontraron clientes."));
}
?>