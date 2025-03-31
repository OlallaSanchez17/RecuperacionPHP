<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $isAdmin = isset($_POST['admin']) ? 1 : 0;

        // Aquí puedes agregar la lógica para guardar los datos en una base de datos o realizar otras acciones
        echo "Correo: " . htmlspecialchars($email) . "<br>";
        echo "Contraseña: " . htmlspecialchars($password) . "<br>";
        echo "¿Es administrador?: " . ($isAdmin ? "Sí" : "No") . "<br>";
    } else {
        ?>
        <form action="registrar_usuario.php" method="post">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="admin">¿Es administrador?</label>
            <input type="checkbox" id="admin" name="admin"><br><br>

            <input type="submit" value="Registrar">
        </form>
        <?php
    }
    ?>
</body>

</html>


//AQUÍ EL HTML Y CSS PARA REGISTRAR UN Usuario
//IGUAL QUE EL LOGIN, PERO CON MAS DATOS
//EL ACTION LLEVARÁ A registrar_usuario.php para almacenarlo
//en la base de datos (parecido a validar_login.php)