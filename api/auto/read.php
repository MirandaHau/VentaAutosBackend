<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Ajuste en las rutas de inclusión usando __DIR__
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/auto.php';

$database = new Database();
$db = $database->getConnection();

$auto = new Auto($db);

$stmt = $auto->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $autos_arr = array();
    $autos_arr["Registros:"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $auto_item = array(
            "id" => $id,
            "marca" => $marca,
            "modelo" => $modelo,
            "anio" => $anio,
            "no_serie" => $no_serie,
            "cliente_id" => $cliente_id
        );

        array_push($autos_arr["Registros:"], $auto_item);
    }

    http_response_code(200);
    echo json_encode($autos_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No se encontraron autos."));
}
?>