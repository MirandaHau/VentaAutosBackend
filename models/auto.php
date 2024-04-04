<?php
//Clase Auto para interactuar con la tabla de autos en la base de datos
class Auto {
    // Propiedades de la clase para la conexión a la base de datos y el nombre de la tabla
    private $conn;
    private $table_name = "Autos";

    // Propiedades del objeto AUTO
    public $id;
    public $marca;
    public $modelo;
    public $anio;
    public $no_serie;
    public $cliente_id;

    // Constructor que recibe un objeto de conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para leer todos los autos de la DB, ejecuta la consulta SQL para seleccionar todos los registros
    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para crear un auto nuevo, consulta SQL
    function create() {
        $query = "INSERT INTO " . $this->table_name . " (marca, modelo, anio, no_serie, cliente_id) VALUES (:marca, :modelo, :anio, :no_serie, :cliente_id)";
        $stmt = $this->conn->prepare($query);
    
        // Limpia y asigna los valores a los parámetros nombrados
        $data = [
            ':marca' => htmlspecialchars(strip_tags($this->marca)),
            ':modelo' => htmlspecialchars(strip_tags($this->modelo)),
            ':anio' => htmlspecialchars(strip_tags($this->anio)),
            ':no_serie' => htmlspecialchars(strip_tags($this->no_serie)),
            ':cliente_id' => htmlspecialchars(strip_tags($this->cliente_id))
        ];

        if($stmt->execute($data)) {
            return true;
        } else {
            return false;
        }
    }    

    // Método para actualizar un auto, consulta SQL
    function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET marca=:marca, modelo=:modelo, anio=:anio, no_serie=:no_serie, cliente_id=:cliente_id
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Limpia los valores y los vincula a los parámetros nombrados de la consulta
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->modelo = htmlspecialchars(strip_tags($this->modelo));
        $this->anio = htmlspecialchars(strip_tags($this->anio));
        $this->no_serie = htmlspecialchars(strip_tags($this->no_serie));
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular valores
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":modelo", $this->modelo);
        $stmt->bindParam(":anio", $this->anio);
        $stmt->bindParam(":no_serie", $this->no_serie);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método liminar un auto por su ID
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        //Limpia el ID del auto para evitar inyección SQL y lo vincula al parámetro de la consulta
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método leer un solo auto por su ID, SQL
    function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Vincular id del auto a buscar y ejecuta la consulta
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC); //Recupera el registro y asigna los valores a las propiedades del objeto

        //Repite el proceso para cada propiedad del objeto
        if ($row) {
            $this->marca = $row['marca'];
            $this->modelo = $row['modelo'];
            $this->anio = $row['anio'];
            $this->no_serie = $row['no_serie'];
            $this->cliente_id = $row['cliente_id'];
        }
    }
    // Leer autos de un cliente por medio de su ID, con consulta SQL
    function readByCliente($cliente_id) {
        $query = "SELECT
                    id, marca, modelo, anio, no_serie, cliente_id
                FROM
                    " . $this->table_name . "
                WHERE
                    cliente_id = ?
                ORDER BY
                    anio DESC";
    
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $cliente_id);
        $stmt->execute();
    
        return $stmt;
    }  
}