<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new usercontroller();

    if (isset($_POST["register_user"])) {
        echo "<p>Register user button is clicked. </p>";
        $_SESSION["isAdmin"] = false;
        $user->register("user");
    }

    if (isset($_POST["register_admin"])) {
        echo "<p>Register admin button is clicked. </p>";
        $_SESSION["isAdmin"] = true;
        $user->register("admin");
    }

    if (isset($_POST["login"])) {
        echo "<p>Loggin button is clicked. </p>";
        $user->login();
    }

    if (isset($_POST["logout"])) {
        echo "<p>Logout button is clicked. </p>";
        $user->logout();
    }

    if (isset($_POST["delete_account"])) {
        echo "<p>Delete user button is clicked. </p>";
        $user->delete();
    }

    if (isset($_POST["update_datauser"])) {
        echo "<p>Update password button is clicked. </p>";
        $user->updateDataUser();
    }

    if (isset($_POST["update_password"])) {
        echo "<p>Update password button is clicked. </p>";
        $user->updatePassword();
    }
}

class usercontroller
{
    private $conn;

    // Constructor para la conexión con la base de datos
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "spmotors";

        try {
            // Conexión inicial sin base de datos
            $pdo = new PDO("mysql:host=$servername", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Crear la base de datos si no existe
            $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Conectar ahora a la base de datos
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Crear la tabla si no existe
            $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            rol VARCHAR(20) NOT NULL,
            foto LONGBLOB
        )";

            $this->conn->exec($sql);
        } catch (PDOException $e) {
            die("Error de conexión o creación: " . $e->getMessage());
        }
    }



    public function register($rol): void
    {
        session_start(); // Asegúrate de que la sesión esté iniciada

        $usuario = trim($_POST["username"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $passwordRaw = $_POST["password"] ?? '';
        $foto = null;

        // Validaciones básicas
        if (empty($usuario) || empty($email) || empty($passwordRaw)) {
            $_SESSION['error_message'] = "Todos los campos son obligatorios.";
            header("Location: ../error.php");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "El correo electrónico no es válido.";
            header("Location: ../error.php");
            exit;
        }

        $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

        // Solo admins pueden subir imagen
        if ($rol === "admin" && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $foto = file_get_contents($_FILES['profile_image']['tmp_name']);
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (usuario, email, password, rol, foto)
                                      VALUES (:usuario, :email, :password, :rol, :foto)");

            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":rol", $rol);
            $stmt->bindParam(":foto", $foto, PDO::PARAM_LOB);

            $stmt->execute();

            $_SESSION['logged'] = true;
            $_SESSION['username'] = $usuario;
            $_SESSION['email'] = $email;
            $_SESSION['rol'] = $rol;

            // Redirigir según el rol
            $redirect = ($rol === "admin") ? "../profileadmin.php" : "../profileuser.php";
            header("Location: $redirect");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error_message'] = "El nombre de usuario o correo ya está registrado.";
            } else {
                $_SESSION['error_message'] = "Error al registrar: " . $e->getMessage();
            }

            header("Location: ../error.php");
            exit;
        }
    }





    // Método para iniciar sesión
    public function login(): void
    {
        session_start();

        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        // Validación de campos vacíos
        if (empty($email) || empty($password)) {
            $_SESSION['error_message'] = "Debe ingresar correo y contraseña.";
            header("Location: ../error.php");
            exit;
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user["password"])) {
                $_SESSION['error_message'] = "Correo o contraseña incorrectos.";
                header("Location: ../error.php");
                exit;
            }

            // Login exitoso
            $_SESSION["logged"] = true;
            $_SESSION["email"] = $user["email"];
            $_SESSION["username"] = $user["usuario"];
            $_SESSION["rol"] = $user["rol"];
            $_SESSION["profile_image"] = $user["foto"] ?? null;

            // Redirigir según el rol
            $redirect = ($user["rol"] === "admin") ? "../profileadmin.php" : "../profileuser.php";
            header("Location: $redirect");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error en el sistema. Por favor, intente más tarde.";
            error_log("Login error: " . $e->getMessage());
            header("Location: ../error.php");
            exit;
        }
    }




    // Método para cerrar sesión
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    public function delete(): void
    {
        session_start();

        if (!isset($_SESSION['email'])) {
            $_SESSION['error_message'] = "No hay sesión activa para eliminar la cuenta.";
            header("Location: ../error.php");
            exit;
        }

        $email = $_SESSION['email'];

        try {
            // Verificar si el usuario existe
            $checkSql = "SELECT * FROM users WHERE email = :email";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Eliminar usuario
                $deleteSql = "DELETE FROM users WHERE email = :email";
                $deleteStmt = $this->conn->prepare($deleteSql);
                $deleteStmt->bindParam(':email', $email);

                if ($deleteStmt->execute()) {
                    session_unset();
                    session_destroy();
                    header("Location: ../index.php");
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error al intentar eliminar la cuenta.";
                    header("Location: ../error.php");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Usuario no encontrado.";
                header("Location: ../error.php");
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error en la base de datos: " . $e->getMessage();
            header("Location: ../error.php");
            exit;
        }
    }

    public function updateDataUser(): void
    {
        session_start();

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['username'])) {
            $_SESSION['error_message'] = "Debes iniciar sesión para actualizar tus datos.";
            header("Location: ../error.php");
            exit;
        }

        $nuevoUsuario = trim($_POST["new_username"] ?? '');
        $nuevoEmail = trim($_POST["new_email"] ?? '');
        $usuarioActual = $_SESSION['username'];

        // Validaciones básicas
        if (empty($nuevoUsuario) || empty($nuevoEmail)) {
            $_SESSION['error_message'] = "Todos los campos son obligatorios.";
            header("Location: ../error.php");
            exit;
        }

        if (!filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "El correo electrónico no es válido.";
            header("Location: ../error.php");
            exit;
        }

        try {
            $stmt = $this->conn->prepare("UPDATE users SET usuario = :nuevoUsuario, email = :nuevoEmail WHERE usuario = :usuarioActual");

            $stmt->bindParam(":nuevoUsuario", $nuevoUsuario);
            $stmt->bindParam(":nuevoEmail", $nuevoEmail);
            $stmt->bindParam(":usuarioActual", $usuarioActual);

            $stmt->execute();

            // Actualizar los datos en la sesión
            $_SESSION['username'] = $nuevoUsuario;
            $_SESSION['email'] = $nuevoEmail;

            header("Location: ../profileuser.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error_message'] = "El nombre de usuario o correo ya está en uso.";
            } else {
                $_SESSION['error_message'] = "Error al actualizar los datos: " . $e->getMessage();
            }

            header("Location: ../error.php");
            exit;
        }
    }


    public function updatePassword(): void
    {
        session_start();

        if (!isset($_SESSION['username'])) {
            $_SESSION['error_message'] = "Debes iniciar sesión para cambiar tu contraseña.";
            header("Location: ../error.php");
            exit;
        }

        $usuario = $_SESSION['username'];
        $passwordActual = $_POST['current_password'] ?? '';
        $nuevaPassword = $_POST['new_password'] ?? '';
        $confirmarPassword = $_POST['confirm_password'] ?? '';

        // Validaciones básicas
        if (empty($passwordActual) || empty($nuevaPassword) || empty($confirmarPassword)) {
            $_SESSION['error_message'] = "Todos los campos son obligatorios.";
            header("Location: ../error.php");
            exit;
        }

        if ($nuevaPassword !== $confirmarPassword) {
            $_SESSION['error_message'] = "Las nuevas contraseñas no coinciden.";
            header("Location: ../error.php");
            exit;
        }

        try {
            // Obtener la contraseña actual de la base de datos
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE usuario = :usuario");
            $stmt->bindParam(":usuario", $usuario);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado || !password_verify($passwordActual, $resultado['password'])) {
                $_SESSION['error_message'] = "La contraseña actual es incorrecta.";
                header("Location: ../error.php");
                exit;
            }

            // Actualizar la contraseña
            $nuevaPasswordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
            $updateStmt = $this->conn->prepare("UPDATE users SET password = :nuevaPassword WHERE usuario = :usuario");
            $updateStmt->bindParam(":nuevaPassword", $nuevaPasswordHash);
            $updateStmt->bindParam(":usuario", $usuario);
            $updateStmt->execute();

            $_SESSION['success_message'] = "Contraseña actualizada correctamente.";
            header("Location: ../profileuser.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error al actualizar la contraseña: " . $e->getMessage();
            header("Location: ../error.php");
            exit;
        }
    }
}
