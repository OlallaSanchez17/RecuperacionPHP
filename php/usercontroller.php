<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new usercontroller();

    if (isset($_POST["submit"])) {
        $user->register();
    }
    if (isset($_POST["login"])) {
        $user->login();
    }
    if (isset($_POST["logout"])) {
        $user->logout();
    }
}

class usercontroller
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "spmotors";
        $tbname = "users";

        $this->conn = new mysqli($servername, $username, $password);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

        $this->conn->select_db($dbname);

        $sqltb = "CREATE TABLE IF NOT EXISTS $tbname (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL,
            apellidos VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,           
            foto blob NOT NULL UNIQUE
        )";

        if (!$this->conn->query($sqltb)) {
            echo "Error creating table: " . $this->conn->error;
        }
    }

    public function register(): void
    {
        $name = $_POST["nombre"] ?? '';
        $apellidos = $_POST["apellidos"] ?? '';
        $telefono = $_POST["telefono"] ?? '';
        $email = $_POST["correo"] ?? '';
        $password = $_POST["contraseña"] ?? '';

        if ($email && $password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("INSERT INTO users (nombre, apellidos, telefono, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $apellidos, $telefono, $email, $hashedPassword);

            if ($stmt->execute()) {
                $url = "http://localhost/VisualStudioCode/DAW1-ProyectoTransversal/view/index/";
                header("Location: " . $url);

                exit();
            } else {
                echo "Error al registrar usuario: " . $stmt->error;
            }
        } else {
            echo "Faltan campos obligatorios.";
        }
    }



    public function login(): void
    {
        $email = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';

        $stmt = $this->conn->prepare("SELECT email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["login"] = true;
                $_SESSION["email"] = $row["email"];
                $url = "http://localhost/VisualStudioCode/DAW1-ProyectoTransversal/view/index/";
                header("Location: " . $url);
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "Usuario no encontrado.";
        }
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: ../login/login.html");
        exit();
    }
}
