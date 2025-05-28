<?php
require_once 'php/eventcontroller.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM eventos_coches WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$evento) {
        echo "Evento no encontrado.";
        exit;
    }
} else {
    echo "ID no especificado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Evento</title>
</head>
<body>
    <h1>Actualizar Evento de Coches</h1>
    <form action="controlador.php" method="POST">
        <input type="hidden" name="accion" value="actualizar">
        <input type="hidden" name="id" value="<?= $evento['id'] ?>">
        <label>Nombre del Evento:</label><br>
        <input type="text" name="nombre" value="<?= htmlspecialchars($evento['nombre']) ?>" required><br>
        <label>Fecha:</label><br>
        <input type="date" name="fecha" value="<?= $evento['fecha'] ?>" required><br>
        <label>Ubicación:</label><br>
        <input type="text" name="ubicacion" value="<?= htmlspecialchars($evento['ubicacion']) ?>" required><br>
        <label>Descripción:</label><br>
        <textarea name="descripcion" required><?= htmlspecialchars($evento['descripcion']) ?></textarea><br>
        <button type="submit">Actualizar Evento</button>
    </form>
</body>
</html>
