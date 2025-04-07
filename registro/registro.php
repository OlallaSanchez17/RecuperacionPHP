<?php

$host = "localhost";
$usuario = "root"; 
$password = "";  
$base_de_datos = "registro_usuarios"; 

$conn = new mysqli($host, $usuario, $password, $transversal);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_BCRYPT); 
    $correo = trim($_POST["correo"]);

    $sql_check = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result();

    if ($resultado->num_rows > 0) {
        echo "Este correo ya está registrado.";
    } else {
    
        $sql = "INSERT INTO usuarios (nombre, contraseña, correo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $contraseña, $correo);
    
        if ($stmt->execute()) {
            echo "Registro exitoso. <a href='login.html'>Inicia sesión</a>";
        } else {
            echo "Error al registrar: " . $stmt->error;
        
        $stmt->close();
    }
    $stmt_check->close();
}
$conn->close();
?>
