<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
</head>

<body>
    <h1>Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
    <p>Rol: <?php echo $_SESSION['rol']; ?></p>

    <form action="php/usercontroller.php" method="POST">
        <button type="submit" name="logout" id="logout" class="button">Cerrar Sesión</button>
    </form>
    <form action="php/usercontroller.php" method="POST">
        <button type="submit" name="delete_account" id="logout" class="button">Eliminar Cuenta</button>
    </form>


</body>

</html>