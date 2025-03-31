<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = ($_POST['clave']);



    if ($usuario == "admin" && $clave == ('1234')) {
        $_SESSION['usuario'] = $usuario;
        header("Location: bienvenido.php");
        exit();
    } else {
        header("Location: login.php?error=Credenciales incorrectas");
        exit();
    }
}
?>