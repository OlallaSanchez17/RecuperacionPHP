<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Eliminación</title>
</head>
<body>
    <h2>¿Estás seguro de que deseas eliminar tu cuenta?</h2>
    <p>Esta acción no se puede deshacer.</p>

    <form action="php/usercontroller.php" method="POST">
        <input type="hidden" name="delete_account" value="1">
        <button type="submit">Sí, eliminar</button>
    </form>

    <form action="profileuser.php" method="GET">
        <button type="submit">Cancelar</button>
    </form>
</body>
</html>
