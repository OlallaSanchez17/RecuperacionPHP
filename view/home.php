<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido/a</title>
</head>
<body>
    <h2>¡Bienvenido/a, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
</body>
</html>