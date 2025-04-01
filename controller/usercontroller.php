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

    private function connectToDatabase()
    {
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "spmotors"; // Asegúrate de cambiar esto al nombre de tu base de datos

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            // $sql = "CREATE DATABASE myDB";
            // if ($conn->query($sql) === TRUE) {
            //     echo "Database created successfully";
            // } else {
            //     echo "Error creating database: " . $conn->error;
            // }

            echo "Connected successfully";
        }
        $conn->close();

    }


    public function __construct()
    {

    }

    public function login(): void
    {
        $conn = $this->connectToDatabase();
        echo "<p>Login button is clicked and called.</p>";

        



    }

    public function logout(): void
    {
        $conn = $this->connectToDatabase();
        echo "<p>Logout button is clicked and called.</p>";

        

    }

    public function register(): void
    {
        $conn = $this->connectToDatabase();
        echo "<p>Register button is clicked and called.</p>";


        

    }



}

// $host = "localhost";
// $usuario = "root"; 
// $password = "";  
// $base_de_datos = "registro_usuarios"; 

// $conn = new mysqli($host, $usuario, $password, $base_de_datos);

// if ($conn->connect_error) {
//     die("Error de conexión: " . $conn->connect_error);
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $nombre = trim($_POST["nombre"]);
//     $apellidos = trim($_POST["apellidos"]);
//     $contraseña = password_hash($_POST["contraseña"], PASSWORD_BCRYPT); 
//     $correo = trim($_POST["correo"]);

//     $sql_check = "SELECT * FROM usuarios WHERE correo = ?";
//     $stmt_check = $conn->prepare($sql_check);
//     $stmt_check->bind_param("s", $correo);
//     $stmt_check->execute();
//     $resultado = $stmt_check->get_result();

//     if ($resultado->num_rows > 0) {
//         echo "Este correo ya está registrado.";
//     } else {

//         $sql = "INSERT INTO usuarios (nombre, apellidos, contraseña, correo) VALUES (?, ?, ?, ?)";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("ssss", $nombre, $apellidos, $contraseña, $correo);

//         if ($stmt->execute()) {
//             echo "Registro exitoso. <a href='login.html'>Inicia sesión</a>";
//         } else {
//             echo "Error al registrar: " . $stmt->error;
//         }

//         $stmt->close();
//     }
//     $stmt_check->close();
// }
// $conn->close();

?>