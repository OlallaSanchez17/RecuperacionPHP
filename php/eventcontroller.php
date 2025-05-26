<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $controller = new eventcontroller();

    if (isset($_POST["readall"])) {
        echo "<p>ReadAll button is clicked</p>";
        $controller->readall();
    }

    if (isset($_POST["read"])) {
        echo "<p>Read button is clicked</p>";
        $controller->read();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new eventcontroller();

    if (isset($_POST["add"])) {
        echo "<p>Add button is clicked</p>";
        $controller->add();
    }

    if (isset($_POST["edit"])) {
        echo "<p>Edit button is clicked</p>";
        $controller->edit();
    }

    if (isset($_POST["update"])) {
        echo "<p>Update button is clicked</p>";
        $controller->update();
    }
}

session_start();

class EventController
{
    private $conn;

    public function __construct()
    {
        $this->connectDB();
        $this->createTable();
    }

    private function connectDB()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "spmotors";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    private function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS eventos (
            id_evento INT PRIMARY KEY AUTO_INCREMENT,
            nombre_evento VARCHAR(100) NOT NULL,
            fecha DATE NOT NULL,
            hora TIME NOT NULL,
            ubicacion VARCHAR(150) NOT NULL,
            descripcion TEXT NOT NULL,
            categoria ENUM('Competiciones', 'Concentraciones', 'Ferias', 'Eventos', 'Tuning', 'Otros') NOT NULL,
            total_tickets INT NOT NULL,
            tickets_vendidos INT DEFAULT 0,
            precio DECIMAL(10, 2),
            organizador VARCHAR(100),
            imagen VARCHAR(255),
            estado_evento ENUM('En planificación', 'Confirmado', 'Cancelado') DEFAULT 'En planificación',
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (!$this->conn->query($sql)) {
            die("Error al crear tabla: " . $this->conn->error);
        }
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'getAll':
                        $this->getAllEvents();
                        break;
                    case 'getById':
                        $this->getEventById($_GET['id']);
                        break;
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'add':
                        $this->addEvent();
                        break;
                    case 'update':
                        $this->updateEvent();
                        break;
                    case 'delete':
                        $this->deleteEvent();
                        break;
                }
            }
        }
    }

    public function addEvent()
    {
        // Validar y sanitizar datos
        $nombre = $this->sanitize($_POST['nombre_evento']);
        $fecha = $this->sanitize($_POST['fecha']);
        $hora = $this->sanitize($_POST['hora']);
        $ubicacion = $this->sanitize($_POST['ubicacion']);
        $descripcion = $this->sanitize($_POST['descripcion']);
        $categoria = $this->sanitize($_POST['categoria']);
        $total_tickets = (int)$_POST['total_tickets'];
        $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
        $organizador = isset($_POST['organizador']) ? $this->sanitize($_POST['organizador']) : '';

        // Manejo de la imagen
        $imagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $this->uploadImage($_FILES['imagen']);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO eventos 
            (nombre_evento, fecha, hora, ubicacion, descripcion, categoria, total_tickets, precio, organizador, imagen) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssidss", $nombre, $fecha, $hora, $ubicacion, $descripcion, $categoria, $total_tickets, $precio, $organizador, $imagen);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Evento creado exitosamente!";
            header("Location: Calendario.html");
            exit();
        } else {
            $_SESSION['error'] = "Error al crear evento: " . $stmt->error;
            header("Location: CrearEvento.html");
            exit();
        }
        $stmt->close();
    }

    private function uploadImage($file)
    {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validar que es una imagen
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            throw new Exception("El archivo no es una imagen.");
        }

        // Validar tamaño (max 5MB)
        if ($file['size'] > 5000000) {
            throw new Exception("La imagen es demasiado grande (máx 5MB).");
        }

        // Validar formato
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception("Solo se permiten formatos JPG, JPEG, PNG y GIF.");
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;
        } else {
            throw new Exception("Error al subir la imagen.");
        }
    }

    public function getAllEvents()
    {
        $result = $this->conn->query("SELECT * FROM eventos ORDER BY fecha, hora");
        $events = [];

        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($events);
    }

    public function getEventById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM eventos WHERE id_evento = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            echo json_encode($result->fetch_assoc());
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Evento no encontrado"]);
        }
        $stmt->close();
    }

    public function updateEvent()
    {
        $id = (int)$_POST['id_evento'];
        $nombre = $this->sanitize($_POST['nombre_evento']);
        $fecha = $this->sanitize($_POST['fecha']);
        $hora = $this->sanitize($_POST['hora']);
        $ubicacion = $this->sanitize($_POST['ubicacion']);
        $descripcion = $this->sanitize($_POST['descripcion']);
        $categoria = $this->sanitize($_POST['categoria']);
        $total_tickets = (int)$_POST['total_tickets'];
        $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
        $organizador = isset($_POST['organizador']) ? $this->sanitize($_POST['organizador']) : '';
        $estado = isset($_POST['estado_evento']) ? $this->sanitize($_POST['estado_evento']) : 'En planificación';

        $stmt = $this->conn->prepare("
            UPDATE eventos SET 
            nombre_evento = ?, 
            fecha = ?, 
            hora = ?, 
            ubicacion = ?, 
            descripcion = ?, 
            categoria = ?, 
            total_tickets = ?, 
            precio = ?, 
            organizador = ?, 
            estado_evento = ? 
            WHERE id_evento = ?
        ");
        $stmt->bind_param("ssssssidssi", $nombre, $fecha, $hora, $ubicacion, $descripcion, $categoria, $total_tickets, $precio, $organizador, $estado, $id);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Evento actualizado exitosamente!";
        } else {
            $_SESSION['error'] = "Error al actualizar evento: " . $stmt->error;
        }
        $stmt->close();

        header("Location: editar_evento.php?id=$id");
        exit();
    }

    public function deleteEvent()
    {
        $id = (int)$_POST['id_evento'];

        // Primero eliminar la imagen asociada si existe
        $stmt = $this->conn->prepare("SELECT imagen FROM eventos WHERE id_evento = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!empty($row['imagen']) && file_exists($row['imagen'])) {
                unlink($row['imagen']);
            }
        }
        $stmt->close();

        // Luego eliminar el evento
        $stmt = $this->conn->prepare("DELETE FROM eventos WHERE id_evento = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Evento eliminado exitosamente!";
        } else {
            $_SESSION['error'] = "Error al eliminar evento: " . $stmt->error;
        }
        $stmt->close();

        header("Location: Calendario.html");
        exit();
    }

    private function sanitize($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}

// Manejo de la solicitud
$controller = new EventController();

try {
    $controller->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
