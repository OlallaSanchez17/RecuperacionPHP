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
    public function add(): void
    {
        // Validar y sanitizar los datos de entrada
        $usuario = htmlspecialchars($_POST["usuario"] ?? '');
        $nombre = htmlspecialchars($_POST["nombre"] ?? '');
        $apellidos = htmlspecialchars($_POST["apellidos"] ?? '');
        $telefono = htmlspecialchars($_POST["telefono"] ?? '');
        $email = filter_var($_POST["email"] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"] ?? '';

        // Validar campos obligatorios
        if (empty($usuario) || empty($nombre) || empty($apellidos) || empty($email) || empty($password)) {
            throw new Exception("Todos los campos son obligatorios");
        }

        // Hashear la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Preparar y ejecutar la consulta
        $stmt = $this->conn->prepare("INSERT INTO users (usuario, nombre, apellidos, telefono, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $usuario, $nombre, $apellidos, $telefono, $email, $hashedPassword);

        if (!$stmt->execute()) {
            throw new Exception("Error al crear el usuario: " . $stmt->error);
        }
    }

    public function edit(): void {}

    // Método para leer en evento
    public function read(): array
    {
        $id = filter_var($_POST["id"] ?? 0, FILTER_VALIDATE_INT);

        if (!$id) {
            throw new Exception("ID de usuario no válido");
        }

        $stmt = $this->conn->prepare("SELECT id, usuario, nombre, apellidos, telefono, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            throw new Exception("Usuario no encontrado");
        }

        return $user;
    }

    public function readall(): void {}

    // Método para actualizar un evento
    public function update(): void {}

    // Método para borrar un evento
    public function delete(): void {}
}
