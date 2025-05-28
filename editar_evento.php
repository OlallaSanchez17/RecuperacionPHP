<?php
// Conexión a la base de datos
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

// Verificar si se pasó el ID por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de evento no proporcionado.");
}

$id = $_GET['id'];

// Obtener datos del evento
$stmt = $pdo->prepare("SELECT * FROM eventos_coches WHERE id = ?");
$stmt->execute([$id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    die("Evento no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <style>
        body {
            background-color: #000; /* negro */
            color: #FFA500; /* naranja */
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            border-bottom: 2px solid #FFA500;
            padding-bottom: 10px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            max-width: 500px;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #FFA500;
            border-radius: 4px;
            background-color: #1a1a1a;
            color: #FFA500;
            font-size: 1em;
        }
        textarea {
            resize: vertical;
        }
        button {
            margin-top: 20px;
            background-color: #FFA500;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #cc8400;
        }
        a {
            margin-left: 15px;
            color: #FFA500;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <h1>Editar Evento de Coche</h1>
    <form action="php/eventcontroller.php" method="post">
        <input type="hidden" name="accion" value="actualizar">
        <input type="hidden" name="id" value="<?= htmlspecialchars($evento['id']) ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required value="<?= htmlspecialchars($evento['nombre']) ?>">

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required value="<?= htmlspecialchars($evento['fecha']) ?>">

        <label for="ubicacion">Ubicación:</label>
        <input type="text" name="ubicacion" id="ubicacion" required value="<?= htmlspecialchars($evento['ubicacion']) ?>">

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" rows="4"><?= htmlspecialchars($evento['descripcion']) ?></textarea>

        <button type="submit">Guardar Cambios</button>
        <a href="verevento.php">Cancelar</a>
    </form>
</body>
</html>
