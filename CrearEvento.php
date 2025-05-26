<?php
session_start();
require_once 'php/eventcontroller.php'; // Asegúrate de que este archivo contiene la clase EventController

// Mostrar mensajes de sesión si existen
if (isset($_SESSION['mensaje'])) {
    echo '<div class="alert alert-success">'.$_SESSION['mensaje'].'</div>';
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}

// Ejecutar controlador si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $controller = new EventController();
    try {
        $controller->handleRequest(); // Esto detectará 'action' => 'add' y ejecutará addEvent()
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error interno: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Evento</title>
  <link rel="stylesheet" href="../DAW1-ProyectoTransversal/css/CrearEvento.css">
</head>
<body>
  <header>
    <nav>
      <div class="nav-container">
        <div class="nav-logo">SP MOTORS</div>
        <div class="nav-links">
          <div class="dropdown">
            <button class="menu-btn">≡</button>
            <div class="dropdown-content">
              <a href="../DAW1-ProyectoTransversal/index.php">Menu</a>
              <a href="../DAW1-ProyectoTransversal/Calendario.php">Calendario de eventos</a>
              <a href="../DAW1-ProyectoTransversal/tickets.php">Venta de tickets</a>
              <a href="../DAW1-ProyectoTransversal/Comunidad.php">Comunidad</a>
              <a href="../DAW1-ProyectoTransversal/Noticias.php">Noticias</a>
            </div>
          </div>
          <a href="../DAW1-ProyectoTransversal/login.php" class="login-btn">Iniciar Sesión</a>
        </div>
      </div>
    </nav>
  </header>

  <form action="CrearEvento.php" method="post" enctype="multipart/form-data">
    <h2>Crear Nuevo Evento</h2>
    <input type="hidden" name="action" value="add">

    <div class="form-group">
      <label for="nombre_evento">Nombre del Evento:</label>
      <input type="text" id="nombre_evento" name="nombre_evento" required>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
      </div>

      <div class="form-group">
        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" required>
      </div>
    </div>

    <div class="form-group">
      <label for="ubicacion">Ubicación:</label>
      <input type="text" id="ubicacion" name="ubicacion" required>
    </div>

    <div class="form-group">
      <label for="categoria">Categoría:</label>
      <select id="categoria" name="categoria" required>
        <option value="Competiciones">Competiciones</option>
        <option value="Concentraciones">Concentraciones</option>
        <option value="Ferias">Ferias</option>
        <option value="Eventos">Eventos</option>
        <option value="Tuning">Tuning</option>
        <option value="Otros">Otros</option>
      </select>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="total_tickets">Total de Tickets:</label>
        <input type="number" id="total_tickets" name="total_tickets" min="1" required>
      </div>

      <div class="form-group">
        <label for="precio">Precio por Ticket (€):</label>
        <input type="number" step="0.01" id="precio" name="precio" min="0">
      </div>
    </div>

    <div class="form-group">
      <label for="organizador">Organizador:</label>
      <input type="text" id="organizador" name="organizador">
    </div>

    <div class="form-group">
      <label for="imagen">Imagen del Evento:</label>
      <input type="file" id="imagen" name="imagen" accept="image/*">
    </div>

    <div class="form-group">
      <label for="descripcion">Descripción:</label>
      <textarea id="descripcion" name="descripcion" rows="5" required></textarea>
    </div>

    <button type="submit">Crear Evento</button>
  </form>
</body>
</html>
