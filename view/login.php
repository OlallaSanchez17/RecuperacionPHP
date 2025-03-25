<?php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inicio de Sesión</title>

</head>

<body>


    <h2>Inicio de Sesión</h2>
    <form action="login.php" method="post">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            title="Introduce un correo electrónico válido">
        <br><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required minlength="8"
            title="La contraseña debe tener al menos 8 caracteres">
        <br><br>
        <button type="submit">Enviar</button>
    </form>

</body>

</html>