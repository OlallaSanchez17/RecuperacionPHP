<?php
session_start();
$usuario_logueado = isset($_SESSION['logged']) && $_SESSION['logged'] === true;
$rol = $_SESSION['rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP MOTORS - Concesionario de Vehículos</title>
    <link rel="stylesheet" href="../DAW1-ProyectoTransversal/css/index.css">
</head>

<body>
    <header>
        <nav>
            <div class="nav-container">
                <div class="nav-logo">SP MOTORS</div>
                <div class="nav-links">
                    <div class="dropdown">
                        <?php if ($usuario_logueado): ?>

                            <button class="menu-btn">≡</button>
                            <div class="dropdown-content">
                                <a href="../DAW1-ProyectoTransversal/Calendario.html">Calendario de eventos</a>
                                <a href="../DAW1-ProyectoTransversal/Coches.html">Coches en venta</a>
                                <a href="../DAW1-ProyectoTransversal/Comunidad.html">Comunidad</a>
                                <a href="../DAW1-ProyectoTransversal/Noticias.html">Noticias</a>
                            </div>
                    </div>
                <?php endif; ?>


                <?php if (!$usuario_logueado): ?>
                    <a href="../DAW1-ProyectoTransversal/login.html" class="login-btn">Iniciar Sesión</a>
                <?php else: ?>
                    <?php if ($rol === 'admin'): ?>
                        <a href="../DAW1-ProyectoTransversal/profileadmin.php" class="login-btn">Mi Perfil</a>
                    <?php else: ?>
                        <a href="../DAW1-ProyectoTransversal/profileuser.php" class="login-btn">Mi Perfil</a>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
        </nav>

        <div class="hero-content">
            <img class="main-logo" src="../DAW1-ProyectoTransversal/imgs/logocoches2.png" alt="Logo SP MOTORS">
            <h1 class="logo-text">SP MOTORS</h1>
            <p class="slogan">Excelencia automotriz con más de 15 años de experiencia en el mercado de vehículos premium</p>
        </div>
    </header>
</body>

</html>