<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="registro.css">
</head>

<body>
    <?php


    if (isset($_POST['submit'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $contraseña = $_POST['contraseña'];
        $correo = $_POST['correo'];
        $comunidad = $_POST['comunidad'];

        // echo "Nombre: " . $nombre . "<br>";
        // echo "Apellidos: " . $apellidos . "<br>";
        // echo "Contraseña: " . $contraseña . "<br>";
        // echo "Correo electrónico: " . $correo . "<br>";
        // echo "Comunidad autónoma: " . $comunidad . "<br>";
    
    } else {
        ?>
        <form action="registrar_usuario.php" method="post">
            <div id="login">
                <table width="10%" height="10%">
                    <tr>
                        <td>
                            Usuario:
                            <input type="text" name="nombre" required>
                            <br>
                            Apellido:
                            <input type="text" name="apellidos" required>
                            <br>
                            Correo electrónico:
                            <input type="email" name="correo" required placeholder="@">
                            Contraseña:
                            <input type="password" name="contraseña" required>
                            <br>
                            <br>
                            Comunidad autónoma:
                            <select name="comunidad" id="comunidad" required>
                                <option value="0">Escoja una de estas opciones</option>
                                <option value="Andalucía">Andalucía</option>
                                <option value="Aragón">Aragón</option>
                                <option value="Islas Baleares">Islas Baleares</option>
                                <option value="Canarias">Canarias</option>
                                <option value="Cantabria">Cantabria</option>
                                <option value="Castilla-La Mancha">Castilla-La Mancha</option>
                                <option value="Castilla y León">Castilla y León</option>
                                <option value="Cataluña">Cataluña</option>
                                <option value="Comunidad de Madrid">Comunidad de Madrid</option>
                                <option value="Comunidad Foral de Navarra">Comunidad Foral de Navarra</option>
                                <option value="Comunidad Valenciana">Comunidad Valenciana</option>
                                <option value="Extremadura">Extremadura</option>
                                <option value="Galicia">Galicia</option>
                                <option value="País Vasco">País Vasco</option>
                                <option value="Principado de Asturias">Principado de Asturias</option>
                                <option value="Región de Murcia">Región de Murcia</option>
                                <option value="La Rioja">La Rioja</option>
                                <option value="Ceuta">Ceuta</option>
                                <option value="Melilla">Melilla</option>
                            </select>
                            <br>
                        </td>
                    </tr>
                </table>
                <br>
                <input type="submit" name="submit" value="Enviar">
        </form>
        <?php
    }
    ?>
</body>

</html>

<!-- 
//AQUÍ EL HTML Y CSS PARA REGISTRAR UN Usuario
//IGUAL QUE EL LOGIN, PERO CON MAS DATOS
//EL ACTION LLEVARÁ A registrar_usuario.php para almacenarlo
//en la base de datos (parecido a validar_login.php) -->