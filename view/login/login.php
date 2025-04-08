<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];


    $usuario_valido = "usuario@ejemplo.com";
    $clave_valida = "123456";


    if ($correo === $usuario_valido && $contraseña === $clave_valida) {

        session_start();
        $_SESSION['usuario'] = $correo;
        echo "Inicio de sesión exitoso. ¡Bienvenido, $correo!";
    } else {
        echo "Correo o contraseña incorrectos.";
    }
} else {
    echo "Acceso no autorizado.";
}
