<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error de Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            padding: 20px;
        }
        .error-box {
            background-color: #f5c6cb;
            border: 1px solid #f1b0b7;
            padding: 20px;
            border-radius: 5px;
            max-width: 500px;
            margin: auto;
            text-align: center;
        }
        a {
            color: #721c24;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h2>¡Ha ocurrido un error!</h2>
        <p>
            <?php
            echo $_SESSION['error_message'] ?? "Error desconocido.";
            unset($_SESSION['error_message']); // Limpiar el mensaje después de mostrarlo
            ?>
        </p>
        <p><a href="index.php">Volver al registro</a></p>
    </div>
</body>
</html>
