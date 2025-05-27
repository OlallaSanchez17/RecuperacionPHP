<?php
class EventController
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "spmotors";

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Crear un nuevo evento
    public function createEvent($nombre_evento, $fecha, $hora, $ubicacion, $descripcion, $categoria, $total_tickets, $precio, $organizador, $imagen)
    {
        $sql = "INSERT INTO events (nombre_evento, fecha, hora, ubicacion, descripcion, categoria, total_tickets, precio, organizador, imagen)
                VALUES (:nombre_evento, :fecha, :hora, :ubicacion, :descripcion, :categoria, :total_tickets, :precio, :organizador, :imagen)";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombre_evento', $nombre_evento);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':ubicacion', $ubicacion);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':total_tickets', $total_tickets);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':organizador', $organizador);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
        
        return $stmt->execute();
    }

    // Obtener lista de eventos
    public function getEvents()
    {
        $sql = "SELECT * FROM events";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un evento por ID
    public function getEventById($id_evento)
    {
        $sql = "SELECT * FROM events WHERE id_evento = :id_evento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_evento', $id_evento);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar un evento existente
    public function updateEvent($id_evento, $nombre_evento, $fecha, $hora, $ubicacion, $descripcion, $categoria, $total_tickets, $precio, $organizador, $estado_evento, $imagen)
    {
        $sql = "UPDATE events SET nombre_evento = :nombre_evento, fecha = :fecha, hora = :hora, ubicacion = :ubicacion, descripcion = :descripcion, 
                categoria = :categoria, total_tickets = :total_tickets, precio = :precio, organizador = :organizador, estado_evento = :estado_evento, imagen = :imagen
                WHERE id_evento = :id_evento";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_evento', $id_evento);
        $stmt->bindParam(':nombre_evento', $nombre_evento);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':ubicacion', $ubicacion);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':total_tickets', $total_tickets);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':organizador', $organizador);
        $stmt->bindParam(':estado_evento', $estado_evento);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);

        return $stmt->execute();
    }

    // Eliminar un evento por ID
    public function deleteEvent($id_evento)
    {
        $sql = "DELETE FROM events WHERE id_evento = :id_evento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_evento', $id_evento);
        return $stmt->execute();
    }
}

// Manejo de la solicitud GET y POST del formulario
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $controller = new EventController();

    if (isset($_GET["action"])) {
        if ($_GET["action"] == "list") {
            echo json_encode($controller->getEvents());
        } elseif ($_GET["action"] == "view" && isset($_GET["id_evento"])) {
            echo json_encode($controller->getEventById($_GET["id_evento"]));
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new EventController();

    if (isset($_POST["action"])) {
        if ($_POST["action"] == "add") {
            $nombre_evento = $_POST["nombre_evento"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];
            $ubicacion = $_POST["ubicacion"];
            $descripcion = $_POST["descripcion"];
            $categoria = $_POST["categoria"];
            $total_tickets = $_POST["total_tickets"];
            $precio = $_POST["precio"];
            $organizador = $_POST["organizador"];
            $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);

            if ($controller->createEvent($nombre_evento, $fecha, $hora, $ubicacion, $descripcion, $categoria, $total_tickets, $precio, $organizador, $imagen)) {
                echo "Evento creado exitosamente.";
            } else {
                echo "Error al crear el evento.";
            }
        } elseif ($_POST["action"] == "update") {
            $id_evento = $_POST["id_evento"];
            $nombre_evento = $_POST["nombre_evento"];
            $fecha = $_POST["fecha"];
            $hora = $_POST["hora"];
            $ubicacion = $_POST["ubicacion"];
            $descripcion = $_POST["descripcion"];
            $categoria = $_POST["categoria"];
            $total_tickets = $_POST["total_tickets"];
            $precio = $_POST["precio"];
            $organizador = $_POST["organizador"];
            $estado_evento = $_POST["estado_evento"];
            $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);

            if ($controller->updateEvent($id_evento, $nombre_evento, $fecha, $hora, $ubicacion, $descripcion, $categoria, $total_tickets, $precio, $organizador, $estado_evento, $imagen)) {
                echo "Evento actualizado exitosamente.";
            } else {
                echo "Error al actualizar el evento.";
            }
        } elseif ($_POST["action"] == "delete") {
            $id_evento = $_POST["id_evento"];
            if ($controller->deleteEvent($id_evento)) {
                echo "Evento eliminado exitosamente.";
            } else {
                echo "Error al eliminar el evento.";
            }
        }
    }
}
?>
