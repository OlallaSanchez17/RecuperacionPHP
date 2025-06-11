<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Eliminación</title>
    <style>
body {
    background-color: #000;
    color: #FFA500;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

body > * {
    max-width: 500px;
    margin: 80px auto;
    padding: 30px;
    background-color: #1a1a1a;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 0 10px rgba(255, 165, 0, 0.2);
}

h2 {
    font-size: 24px;
    margin-bottom: 10px;
    border-bottom: 2px solid #FFA500;
    padding-bottom: 10px;
}

p {
    font-size: 16px;
    margin-bottom: 25px;
}

button {
    background-color: #FFA500;
    color: #000;
    padding: 10px 20px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 10px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #cc8400;
}

form {
    display: inline-block;
}

    </style>
</head>
<body>
    <h2>¿Estás seguro de que deseas eliminar tu cuenta?</h2>
    <p>Esta acción no se puede deshacer.</p>

    <form action=" ../recuperacionphp/controlador/usercontroller.php" method="POST">
        <input type="hidden" name="delete_account" value="1">
        <button type="submit">Sí, eliminar</button>
    </form>

    <form action=" ../profileuser.php" method="GET">
        <button type="submit">Cancelar</button>
    </form>
</body>
</html>
