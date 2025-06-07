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
    <style>
        body {
            background-color: #000; /* negro */
            color: #FFA500; /* naranja */
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        a {
            color: #FFA500;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #FFA500;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #FFA500;
            color: #000; /* texto negro en encabezado */
        }
        tr:nth-child(even) {
            background-color: #1a1a1a;
        }
        button {
            background-color: #FFA500;
            color: #000;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 5px;
            border-radius: 3px;
        }
        button:hover {
            background-color: #cc8400;
        }
        form {
            display: inline;
        }
        h1 {
            border-bottom: 2px solid #FFA500;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Lista de Eventos</h1>
    <a href="CrearEvento.html">Crear nuevo evento</a>
        <button onclick="window.location.href='index.php'" style="
        background-color: #FFA500;
        color: #000;
        border: none;
        padding: 8px 16px;
        font-weight: bold;
        margin-left: 20px;
        cursor: pointer;
        border-radius: 3px;
    ">Página Principal</button>
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
                <form action="php/eventcontroller.php" method="post" onsubmit="return confirm('¿Eliminar este evento?')">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                    <button type="submit">Eliminar</button>
                </form>
                <form action="editar_evento.php" method="get">
                    <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                    <button type="submit">Editar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
