<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['email'];
    $clave = ($_POST['password']);
    $admin = ($_POST['admin']);

    echo "bienevenido ". $usuario;

}
?>

