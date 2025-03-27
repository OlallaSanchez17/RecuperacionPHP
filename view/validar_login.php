<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = md5($_POST['clave']); 

   

    if ($usuario == "admin" && $clave == md5('1234')) {
        $_SESSION['usuario'] = $usuario;
        header("Location: bienvenido.php");
        exit();
    } else {
        header("Location: login.php?error=Credenciales incorrectas");
        exit();
    }
}
?>