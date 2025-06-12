<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <style>
body {
    background-color: #000;
    color: #FFA500;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
}

body > * {
    max-width: 600px;
    margin: 20px auto;
    padding: 15px 20px;
    background-color: #1a1a1a;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(255, 165, 0, 0.2);
}

a {
    color: #FFA500;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    margin-top: 10px;
}

a:hover {
    text-decoration: underline;
}

.button, button {
    background-color: #FFA500;
    color: #000;
    border: none;
    padding: 8px 16px;
    font-weight: bold;
    margin-top: 15px;
    margin-right: 10px;
    cursor: pointer;
    border-radius: 3px;
    transition: background-color 0.3s ease;
}

.button:hover, button:hover {
    background-color: #cc8400;
}

form {
    display: inline;
}

h1 {
    font-size: 28px;
    border-bottom: 2px solid #FFA500;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

p {
    font-size: 18px;
    margin: 8px 0;
}
</style>
</head>

<body>
    <a href="index.php">Home</a>
    <h1>Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
    <p>Numero Telefono: <?php echo $_SESSION['quantity']; ?></p>
    <p>Rol: <?php echo $_SESSION['rol']; ?></p>

    <form action= "../RecuperacionPHP/controlador/usercontroller.php" method="POST">
        <button type="submit" name="logout" class="button">Cerrar Sesión</button>
    </form>

    <a href="../RecuperacionPHP/vista/html/updateUserData.html">Actualizar Datos</a><br>
    <a href="../RecuperacionPHP/vista/html/updateUserPassword.html">Actualizar Contraseña</a>



    <!-- Redirige a la página de confirmación -->
    <form action="../RecuperacionPHP/confirm_delete.php" method="GET">
        <button type="submit" class="button">Eliminar Cuenta</button>
    </form>
</body>

</html>