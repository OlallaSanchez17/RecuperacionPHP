<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new usercontroller();

    // Detectar qué acción se solicitó
    if (isset($_POST["submit"])) {
        $user->register();
    } elseif (isset($_POST["login"])) {
        $user->login();
    } elseif (isset($_POST["logout"])) {
        $user->logout();
    }
}

class usercontroller
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

        // Crear base de datos si no existe
        $this->conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

        // Seleccionar la base de datos
        $this->conn->select_db($dbname);

        // Crear tabla si no existe (agregar campo 'usuario')
        $sqltb = "CREATE TABLE IF NOT EXISTS $tbname (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(50) NOT NULL UNIQUE,  -- Agregado campo 'usuario'
            nombre VARCHAR(50) NOT NULL,
            apellidos VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )";

        if (!$this->conn->query($sqltb)) {
            echo "Error al crear la tabla: " . $this->conn->error;
        }
    }

    // Método para registrar un nuevo usuario
    public function register(): void
    {
        $usuario = htmlspecialchars($_POST["usuario"] ?? '');  // Agregar campo de usuario
        $name = htmlspecialchars($_POST["nombre"] ?? '');
        $apellidos = htmlspecialchars($_POST["apellidos"] ?? '');
        $telefono = htmlspecialchars($_POST["numero"] ?? '');
        $email = htmlspecialchars($_POST["correo"] ?? '');
        $password = $_POST["contraseña"] ?? '';

        if ($usuario && $name && $apellidos && $email && $password) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "El correo electrónico no es válido.";
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Usamos el campo 'usuario' ahora
            $stmt = $this->conn->prepare("INSERT INTO users (usuario, nombre, apellidos, telefono, email, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $usuario, $name, $apellidos, $telefono, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Redirigir con meta tag
                $url = "http://localhost/DAW1-ProyectoTransversal/"; // Redirigir a la página de inicio
                header($url);
                exit();
            } else {
                echo "Error al registrar usuario: " . $stmt->error;
            }
        } else {
            echo "Todos los campos son obligatorios.";
        }
    }

    // Método para iniciar sesión
    public function login(): void
    {
        $usuario = htmlspecialchars($_POST["usuario"] ?? '');  // Agregar campo de usuario
        $email = htmlspecialchars($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if (($usuario || $email) && $password) {
            if ($usuario) {
                // Buscar por usuario
                $stmt = $this->conn->prepare("SELECT email, password FROM users WHERE usuario = ?");
                $stmt->bind_param("s", $usuario);
            } elseif ($email) {
                // Buscar por email
                $stmt = $this->conn->prepare("SELECT email, password FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
            }

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
        } else {
            echo "Debe ingresar usuario/email y contraseña.";
        }
    }

    // Método para cerrar sesión
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: ../login/login.html");
        exit();
    }
}
