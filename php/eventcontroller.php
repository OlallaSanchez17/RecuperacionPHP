<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $controller = new eventcontroller();

    if (isset($_POST["readall"])) {
        echo "<p>ReadAll button is clicked</p>";
        $controller->readall();
    }

    if (isset($_POST["read"])) {
        echo "<p>Read button is clicked</p>";
        $controller->read();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new eventcontroller();

    if (isset($_POST["add"])) {
        echo "<p>Add button is clicked</p>";
        $controller->add();
    }

    if (isset($_POST["edit"])) {
        echo "<p>Edit button is clicked</p>";
        $controller->edit();
    }

    if (isset($_POST["update"])) {
        echo "<p>Update button is clicked</p>";
        $controller->update();
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


        $sqltb = "CREATE TABLE IF NO EXISTS eventos (
        id_evento INT PRIMARY KEY AUTO_INCREMENT,
        nombre_evento VARCHAR(100) NOT NULL,
        fecha DATE NOT NULL,
        ubicacion VARCHAR(150),
        total_tickets INT NOT NULL,
        tickets_vendidos INT DEFAULT 0,
        precio DECIMAL(10, 2) NOT NULL,
        organizador VARCHAR(100),
        estado_evento ENUM('En planificación', 'Confirmado', 'Cancelado') DEFAULT 'En planificación',
    )";


        if (!$this->conn->query($sqltb)) {
            echo "Error al crear la tabla: " . $this->conn->error;
        }
    }

    // Método para añadir un evento
    public function add(): void {}

    public function edit(): void {}

    // Método para leer en evento
    public function read(): void {}

    public function readall(): void {}

    // Método para actualizar un evento
    public function update(): void {}

    // Método para borrar un evento
    public function delete(): void {}
}
