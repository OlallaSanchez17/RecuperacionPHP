<?php
require_once 'php/eventcontroller.php';

$stmt = $pdo->query("SELECT * FROM eventos_coches");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Eventos de Coches</title>
</head>
<body>
    <h1>Eventos de Coches</h1>
    <table border="1">
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
                <form action="controlador.php" method="POST" style="display:inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                    <button type="submit">Eliminar</button>
                </form>
                <form action="actualizar_evento.php" method="GET" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                    <button type="submit">Editar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
