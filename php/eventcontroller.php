<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spmotors";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$accion = $_POST['accion'] ?? '';

if ($accion === 'crear') {
    $stmt = $pdo->prepare("INSERT INTO eventos_coches (nombre, fecha, ubicacion, descripcion) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['nombre'], $_POST['fecha'], $_POST['ubicacion'], $_POST['descripcion']]);
    header("Location: listar_eventos.php");
} elseif ($accion === 'eliminar') {
    $stmt = $pdo->prepare("DELETE FROM eventos_coches WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header("Location: listar_eventos.php");
} elseif ($accion === 'actualizar') {
    $stmt = $pdo->prepare("UPDATE eventos_coches SET nombre = ?, fecha = ?, ubicacion = ?, descripcion = ? WHERE id = ?");
    $stmt->execute([$_POST['nombre'], $_POST['fecha'], $_POST['ubicacion'], $_POST['descripcion'], $_POST['id']]);
    header("Location: listar_eventos.php");
} else {
    echo "Acción no válida.";
}
?>
