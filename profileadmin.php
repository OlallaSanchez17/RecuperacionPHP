<?php
session_start();

// Conexión a la base de datos para obtener la imagen
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "spmotors";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT foto FROM users WHERE usuario = :usuario");
    $stmt->bindParam(":usuario", $_SESSION['username']);
    $stmt->execute();

    $foto = null;
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $foto = $row['foto'];
    }
} catch (PDOException $e) {
    echo "Error al obtener la imagen: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport initial-scale=1.0">
    <title>Perfil de Administrador</title>
</head>

<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <p>Rol: <?php echo htmlspecialchars($_SESSION['rol']); ?></p>

    <?php if ($foto): ?>
        <p>Imagen de perfil:</p>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($foto); ?>" alt="Foto de perfil" style="max-width:200px;">
    <?php else: ?>
        <p>No hay imagen de perfil disponible.</p>
    <?php endif; ?>
    <form action="php/usercontroller.php" method="POST">
        <button type="submit" name="logout" id="logout" class="button">Cerrar Sesión</button>
    </form>
    <form action="php/usercontroller.php" method="POST">
        <button type="submit" name="delete_account" id="logout" class="button">Eliminar Cuenta</button>
    </form>
    <form action="php/eventcontroller.php" method="POST">
        <button type="submit" name="delete_account" id="logout" class="button">Adminisitrar eventos</button>
    </form>
</body>

</html>