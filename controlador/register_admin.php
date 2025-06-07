
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
</head>
<body>
    <h2>Registro de Administrador</h2>
    <form action="/php/usercontroller.php" method="POST" enctype="multipart/form-data">
        <label for="username">Nombre de usuario:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="email">Correo electrónico:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="profile_image">Imagen de perfil:</label><br>
        <input type="file" id="profile_image" name="profile_image"><br><br>
        
        <input type="hidden" name="rol" value="admin">
        
        <input type="submit" value="Registrar">
    </form>
</body>
</html>
