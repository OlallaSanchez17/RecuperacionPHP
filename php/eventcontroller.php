<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event = new eventcontroller();

    // Detectar qué acción se solicitó
    if (isset($_POST["add"])) {
        $user->register();
    }

    if (isset($_POST["read"])) {
        $user->register();
    }

    if (isset($_POST["update"])) {
        $user->register();
    }

    if (isset($_POST["delete"])) {
        $user->register();
    }
}

class eventcontroller
{
    private $conn;

    // Constructor para la conexión con la base de datos
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "spmotors";
        $tbname = "users";

        // Crear la base de datos si no existe
        $this->conn = new mysqli($servername, $username, $password);

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        $this->conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

        // Seleccionar la base de datos
        $this->conn->select_db($dbname);

        // Crear tabla si no existe 
        $sqltb = "CREATE TABLE IF NOT EXISTS $tbname (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(50) NOT NULL UNIQUE,  
            nombre VARCHAR(50) NOT NULL,
            apellidos VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,           
            foto blob NOT NULL UNIQUE
        )";

        if (!$this->conn->query($sqltb)) {
            echo "Error al crear la tabla: " . $this->conn->error;
        }
    }

    // Método para añadir un evento
    public function add(): void {}

    // Método para leer en evento
    public function read(): void {}

    // Método para actualizar un evento
    public function update(): void {}

    // Método para borrar un evento
    public function delete(): void {}

    
}
