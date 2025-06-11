<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spmotors";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM eventos_coches ORDER BY fecha DESC");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eventos de Coches</title>
    <link rel="stylesheet" href="../RecuperacionPHP/vista/css/VerEvento.css">

</head>
<body>
    <h1>Lista de Eventos</h1>
    <a href="/RecuperacionPHP/view/html/CreatEvento.html">Crear nuevo evento</a>
<button onclick="window.location.href='../RecuperacionPHP/index.php'">Página Principal</button>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Ubicación</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($eventos as $evento): ?>
        <tr>
            <td><?= htmlspecialchars($evento['nombre']) ?></td>
            <td><?= htmlspecialchars($evento['fecha']) ?></td>
            <td><?= htmlspecialchars($evento['ubicacion']) ?></td>
            <td><?= htmlspecialchars($evento['descripcion']) ?></td>
            <td>
                <form action=" ../eventcontroller.php" method="post" onsubmit="return confirm('¿Eliminar este evento?')">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                    <button type="submit">Eliminar</button>
                </form>
                <form action=" ../editar_evento.php" method="get">
                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                    <button type="submit">Editar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
