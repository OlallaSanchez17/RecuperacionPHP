<?php
// Incluir correctamente el controlador
require_once __DIR__ . '/eventcontroller.php';


// Verificar que id_evento existe y es un número
if (!isset($_GET['id_evento']) || !is_numeric($_GET['id_evento'])) {
    die("ID de evento inválido o parámetro faltante.");
}

// Crear controlador y obtener datos del evento
$controller = new EventController();
$eventoJson = $controller->getEventById((int)$_GET['id_evento']);
if (!$eventoJson) {
    die("Evento no encontrado.");
}
$evento = json_decode($eventoJson, true);
if (!$evento) {
    die("Error al decodificar datos del evento.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Editar Evento</title>
</head>
<body>
  <h1>Editar Evento</h1>

  <!-- Cambié la acción a la ruta correcta y con extensión .php -->
  <form action="../DAW1-ProyectoTransversal/php/eventcontroller.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="id_evento" value="<?= htmlspecialchars($evento['id_evento']) ?>" />

    <label>Nombre:</label>
    <input type="text" name="nombre_evento" value="<?= htmlspecialchars($evento['nombre_evento']) ?>" required /><br />

    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?= htmlspecialchars($evento['fecha']) ?>" required /><br />

    <label>Hora:</label>
    <input type="time" name="hora" value="<?= htmlspecialchars($evento['hora']) ?>" required /><br />

    <label>Ubicación:</label>
    <input type="text" name="ubicacion" value="<?= htmlspecialchars($evento['ubicacion']) ?>" required /><br />

    <label>Descripción:</label>
    <textarea name="descripcion" required><?= htmlspecialchars($evento['descripcion']) ?></textarea><br />

    <label>Categoría:</label>
    <select name="categoria" required>
      <?php
      $categorias = ["Competiciones", "Concentraciones", "Ferias", "Eventos", "Tuning", "Otros"];
      foreach ($categorias as $cat) {
          $selected = ($evento['categoria'] === $cat) ? "selected" : "";
          echo "<option value=\"$cat\" $selected>$cat</option>";
      }
      ?>
    </select><br />

    <label>Total tickets:</label>
    <input type="number" name="total_tickets" value="<?= htmlspecialchars($evento['total_tickets']) ?>" min="1" required /><br />

    <label>Precio (€):</label>
    <input type="number" name="precio" value="<?= htmlspecialchars($evento['precio']) ?>" step="0.01" /><br />

    <label>Organizador:</label>
    <input type="text" name="organizador" value="<?= htmlspecialchars($evento['organizador']) ?>" /><br />

    <label>Estado:</label>
    <select name="estado_evento">
      <?php
      $estados = ["En planificación", "Confirmado", "Cancelado"];
      foreach ($estados as $estado) {
          $selected = ($evento['estado_evento'] === $estado) ? "selected" : "";
          echo "<option value=\"$estado\" $selected>$estado</option>";
      }
      ?>
    </select><br />

    <label>Imagen:</label>
    <input type="file" name="imagen" accept="image/*" /><br />
    <small>Deja en blanco si no quieres cambiar la imagen actual</small><br /><br />

    <button type="submit">Actualizar Evento</button>
  </form>
</body>
</html>
