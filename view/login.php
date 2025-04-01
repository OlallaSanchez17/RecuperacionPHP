<?php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <img id="logo" src="images/logo-1.png" > -->
    <link rel="icon" href=".../imgs/logocoches.png">
    <title>Formulario de Inicio de Sesión</title>

</head>

<body>
    <h2>Inicio de Sesión</h2>
    <form action="../controller/usercontroller.php" method="POST">
        <label>Usuario:</label>
        <input type="text" name="usuario" required>
        <br>
        <label>Contraseña:</label>
        <input type="password" name="clave" required>
        <br>
        <button type="submit" name="login">Login</button>
    </form>
</body>

</html>