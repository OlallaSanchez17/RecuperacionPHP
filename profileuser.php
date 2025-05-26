<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
</head>

<body>
    <a href="index.php">Home</a>
    <h1>Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
    <p>Rol: <?php echo $_SESSION['rol']; ?></p>

    <form action="php/usercontroller.php" method="POST">
        <button type="submit" name="logout" class="button">Cerrar Sesión</button>
    </form>

    <!-- Redirige a la página de confirmación -->
    <form action="confirm_delete.php" method="GET">
        <button type="submit" class="button">Eliminar Cuenta</button>
    </form>
</body>

</html>