<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['email'];
    $clave = ($_POST['password']);
    $admin = ($_POST['admin']);

    if ($usuario == "example@gmail.com" && $clave == ('1234') ) {
        $_SESSION['usuario'] = $usuario;
        header("Location: bienvenidoregistro.php");
        exit();
    } else {
        header("Location: login.php?error=Credenciales incorrectas");
        exit();
    }
}
?>