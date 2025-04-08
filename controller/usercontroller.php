<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new usercontroller();

    if (isset($_POST["login"])) {
        echo "<p>Login button is clicked.</p>";
        $user->login();
    }
    if (isset($_POST["logout"])) {
        echo "<p>Logout button is clicked.</p>";
        $user->logout();
    }
    if (isset($_POST["register"])) {
        echo "<p>Register button is clicked.</p>";
        $user->register();
    }
}

class usercontroller
{
    private $conn;
    public function __construct() {}

    private function connectToDatabase()
    {
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "spmotors"; // Asegúrate de cambiar esto al nombre de tu base de datos
        $tbname = "users";
        $this->conn;

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {

            die("Connection failed: " . $conn->connect_error);
        } else {

            echo "Connected successfully";
        }

        $sqldb = "CREATE DATABASE IF NOT EXISTS $dbname";

        if ($conn->query($sqldb) === TRUE) {

            echo "Database created successfully";
        } else {

            echo "Error creating database: " . $conn->error;
        }

        $sqltb = "CREATE TABLE IF NOT EXISTS $tbname (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            password INT
        )";

        if ($conn->query($sqltb) === TRUE) {
            echo "Table MyGuests created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
        }

        $conn->close();
    }



    public function login(): void
    {
        $this->connectToDatabase();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $correo = trim($_POST["email"]);
            $contraseña = $_POST["password"];

            $sql_check = "SELECT * FROM users WHERE correo = ?";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bind_param("s", $correo);
            $stmt_check->execute();
            $resultado = $stmt_check->get_result();

            if ($resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                if (password_verify($contraseña, $row['password'])) {
                    echo "Login exitoso. Bienvenido, " . $row['firstname'] . "!";
                    // Aquí puedes iniciar la sesión del usuario, por ejemplo:
                    // session_start();
                    // $_SESSION['user_id'] = $row['id'];
                } else {
                    echo "Contraseña incorrecta.";
                }
            } else {
                echo "Correo no registrado.";
            }

            $stmt_check->close();
        }
    }


    public function logout(): void
    {
        $conn = $this->connectToDatabase();
        echo "<p>Logout button is clicked and called.</p>";

     
            session_start(); // Start the session to access session variables
            session_unset(); // Remove all session variables
            session_destroy(); // Destroy the session itself
        
            echo "<p>You have been logged out.</p>";
            
            // Optionally, redirect to a login page or home
            header("Location: login.php");
            exit;
        
        

    }

    public function register(): void
    {
        $conn = $this->connectToDatabase();
        echo "<p>Register button is clicked and called.</p>";
    }
}
