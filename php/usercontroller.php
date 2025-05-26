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
        $password = "1234";
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
        $usuario = $_POST["username"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $foto = null;

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

            if ($stmt->execute()) {
                $_SESSION['logged'] = true;
                $_SESSION['username'] = $usuario;
                $_SESSION['email'] = $email;
                $_SESSION['rol'] = $rol;
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['error_message'] = "No se pudo registrar el usuario.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
        }

        $redirect = ($rol === "admin") ? "../profileadmin.php" : "../profileuser.php";
        header("Location: $redirect");
        exit;
    }



    // Método para iniciar sesión
    public function login(): void
    {
        $email = htmlspecialchars($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error_message'] = "Debe ingresar correo y contraseña.";
            header("Location: ../login.php"); // Ajusta la ruta
            exit();
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['error_message'] = "Correo o contraseña incorrectos.";
                header("Location: ../login.php");
                exit();
            }

            if (!password_verify($password, $user["password"])) {
                $_SESSION['error_message'] = "Correo o contraseña incorrectos.";
                header("Location: ../login.php");
                exit();
            }

            // Login exitoso
            $_SESSION["logged"] = true; // Cambiado a 'logged' para ser consistente
            $_SESSION["email"] = $user["email"];
            $_SESSION["username"] = $user["usuario"];
            $_SESSION["rol"] = $user["rol"];
            $_SESSION["profile_image"] = $user["foto"] ?? null;

            header("Location: ../index.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error en el sistema. Por favor intente más tarde.";
            error_log("Login error: " . $e->getMessage()); // Log del error
            header("Location: ../login.php");
            exit();
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
            echo "No hay sesión activa.";
            return;
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
                    echo "Error al intentar eliminar la cuenta.";
                }
            } else {
                echo "Usuario no encontrado.";
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        }
    }


    public function updatePassword(): void
    {
        session_start();

        // Verificar si hay una sesión activa
        if (!isset($_SESSION['email'])) {
            $_SESSION['error_message'] = "Debes iniciar sesión para cambiar la contraseña";
            header("Location: ../login.php");
            exit();
        }

        // Verificar que se enviaron las contraseñas necesarias
        if (!isset($_POST['current_password']) || !isset($_POST['new_password']) || !isset($_POST['confirm_password'])) {
            $_SESSION['error_message'] = "Todos los campos son obligatorios";
            $redirect = ($_SESSION['rol'] === "admin") ? "../profileadmin.php" : "../profileuser.php";
            header("Location: $redirect");
            exit();
        }

        $email = $_SESSION['email'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validar que las nuevas contraseñas coincidan
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error_message'] = "Las nuevas contraseñas no coinciden";
            $redirect = ($_SESSION['rol'] === "admin") ? "../profileadmin.php" : "../profileuser.php";
            header("Location: $redirect");
            exit();
        }

        try {
            // Obtener la contraseña actual del usuario
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['error_message'] = "Usuario no encontrado";
                header("Location: ../login.php");
                exit();
            }

            // Verificar que la contraseña actual sea correcta
            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['error_message'] = "La contraseña actual es incorrecta";
                $redirect = ($_SESSION['rol'] === "admin") ? "../profileadmin.php" : "../profileuser.php";
                header("Location: $redirect");
                exit();
            }

            // Hashear la nueva contraseña
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $updateStmt = $this->conn->prepare("UPDATE users SET password = :password WHERE email = :email");
            $updateStmt->bindParam(":password", $newPasswordHash);
            $updateStmt->bindParam(":email", $email);

            if ($updateStmt->execute()) {
                $_SESSION['success_message'] = "Contraseña actualizada correctamente";
            } else {
                $_SESSION['error_message'] = "Error al actualizar la contraseña";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error en la base de datos: " . $e->getMessage();
        }

        // Redirigir a la página de perfil correspondiente
        $redirect = ($_SESSION['rol'] === "admin") ? "../profileadmin.php" : "../profileuser.php";
        header("Location: $redirect");
        exit();
    }
}
