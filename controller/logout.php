<?php
session_start(); // Necesario para acceder a la sesión
session_unset(); // Borra todas las variables de sesión
session_destroy(); // Destruye la sesión

// Redirige al login o a la página principal
header("Location: login.php");
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>

</head>
<body>


<form action="logout.php" method="POST">
  <button type="submit">Cerrar sesión</button>
</form>
</body>
</html>
