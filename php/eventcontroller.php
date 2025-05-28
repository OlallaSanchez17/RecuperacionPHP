<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spmotors";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <- corregido aquí
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Sanitizar datos
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$accion = $_POST['accion'] ?? ($_GET['accion'] ?? '');

try {
    if ($accion === 'crear') {
        if (empty($_POST['nombre']) || empty($_POST['fecha']) || empty($_POST['ubicacion'])) {
            throw new Exception("Todos los campos obligatorios deben completarse");
        }

        $stmt = $pdo->prepare("INSERT INTO eventos_coches (nombre, fecha, ubicacion, descripcion) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            sanitizeInput($_POST['nombre']),
            sanitizeInput($_POST['fecha']),
            sanitizeInput($_POST['ubicacion']),
            sanitizeInput($_POST['descripcion'] ?? '')
        ]);
        header("Location: ../verevento.php");
        exit();

    } elseif ($accion === 'eliminar') {
        if (empty($_POST['id'])) {
            throw new Exception("ID no proporcionado para eliminar");
        }

        $stmt = $pdo->prepare("DELETE FROM eventos_coches WHERE id = ?");
        $stmt->execute([sanitizeInput($_POST['id'])]);
        header("Location: ../verevento.php");
        exit();

    } elseif ($accion === 'actualizar') {
        if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['fecha']) || empty($_POST['ubicacion'])) {
            throw new Exception("Todos los campos obligatorios deben completarse");
        }

        $stmt = $pdo->prepare("UPDATE eventos_coches SET nombre = ?, fecha = ?, ubicacion = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([
            sanitizeInput($_POST['nombre']),
            sanitizeInput($_POST['fecha']),
            sanitizeInput($_POST['ubicacion']),
            sanitizeInput($_POST['descripcion'] ?? ''),
            sanitizeInput($_POST['id'])
        ]);
        header("Location: ../verevento.php");
        exit();

    } else {
        throw new Exception("Acción no válida.");
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
