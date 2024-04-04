<?php
//Definimos la clase "Database" que gestiona la conexión a la base de datos
class Database {
    private $host = "localhost";
    private $database_name = "venta_autos";
    private $username = "root";
    private $password = "";

    // Propiedad pública para mantener la conexión a la base de datos
    public $conn;

    // Método para obtener/establecer una conexión a la base de datos MySQL
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn; // Retorna el objeto de conexión si fue exitoso, o null en caso contrario
    }
}