<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el controlador
require_once 'php/eventcontroller.php';

// Instanciar el controlador y obtener los eventos
$controller = new eventcontroller();
$eventos = $controller->getEvents();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eventos Disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .evento {
            background: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .evento img {
            max-width: 300px;
            height: auto;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Lista de Eventos</h1>

    <?php if (count($eventos) > 0): ?>
        <?php foreach ($eventos as $evento): ?>
            <div class="evento">
                <h2><?= htmlspecialchars($evento['nombre_evento']) ?></h2>
                <p><strong>Fecha:</strong> <?= htmlspecialchars($evento['fecha']) ?> a las <?= htmlspecialchars($evento['hora']) ?></p>
                <p><strong>Ubicación:</strong> <?= htmlspecialchars($evento['ubicacion']) ?></p>
                <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($evento['descripcion'])) ?></p>
                <p><strong>Categoría:</strong> <?= htmlspecialchars($evento['categoria']) ?></p>
                <p><strong>Precio:</strong> €<?= htmlspecialchars($evento['precio']) ?></p>
                <p><strong>Organizador:</strong> <?= htmlspecialchars($evento['organizador']) ?></p>
                <?php if (!empty($evento['imagen'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($evento['imagen']) ?>" alt="Imagen del evento">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay eventos disponibles.</p>
    <?php endif; ?>
</body>

</html>