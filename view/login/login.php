<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Regístrate</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="contenedor">
        <h2>Inciar Sesión</h2>
        <form action="../../controller/usercontroller.php" method="post">                    

            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo" required placeholder="@">

            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" required>


            <input id="submit" type="submit" name="login" value="Enviar" >
        </form>
    </div>

</body>
</html>
