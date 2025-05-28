<?php
class eventcontroller
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "spmotors";

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
            exit;
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
        $stmt->bindParam(':total_tickets', $total_tickets, PDO::PARAM_INT);
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
    $stmt->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
    $stmt->execute();

    $evento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evento) {
        http_response_code(404);
        return null;
    }

    return $evento; 
}


    // Actualizar un evento existente
public function updateEvent($id_evento, $nombre_evento, $fecha, $hora, $ubicacion, $descripcion, $categoria, $total_tickets, $precio, $organizador, $estado_evento, $imagen = null)
{
    if ($imagen === null) {
        $sql = "UPDATE events SET 
                    nombre_evento = :nombre_evento, 
                    fecha = :fecha, 
                    hora = :hora, 
                    ubicacion = :ubicacion, 
                    descripcion = :descripcion, 
                    categoria = :categoria, 
                    total_tickets = :total_tickets, 
                    precio = :precio, 
                    organizador = :organizador, 
                    estado_evento = :estado_evento
                WHERE id_evento = :id_evento";
        $stmt = $this->conn->prepare($sql);
    } else {
        $sql = "UPDATE events SET 
                    nombre_evento = :nombre_evento, 
                    fecha = :fecha, 
                    hora = :hora, 
                    ubicacion = :ubicacion, 
                    descripcion = :descripcion, 
                    categoria = :categoria, 
                    total_tickets = :total_tickets, 
                    precio = :precio, 
                    organizador = :organizador, 
                    estado_evento = :estado_evento, 
                    imagen = :imagen
                WHERE id_evento = :id_evento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':imagen', $imagen, PDO::PARAM_LOB);
    }

    $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
    $stmt->bindValue(':nombre_evento', $nombre_evento, PDO::PARAM_STR);
    $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->bindValue(':hora', $hora, PDO::PARAM_STR);
    $stmt->bindValue(':ubicacion', $ubicacion, PDO::PARAM_STR);
    $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindValue(':categoria', $categoria, PDO::PARAM_STR);
    $stmt->bindValue(':total_tickets', $total_tickets, PDO::PARAM_INT);

    if ($precio === null) {
        $stmt->bindValue(':precio', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':precio', $precio, PDO::PARAM_STR);
    }

    $stmt->bindValue(':organizador', $organizador, PDO::PARAM_STR);
    $stmt->bindValue(':estado_evento', $estado_evento, PDO::PARAM_STR);

    $resultado = $stmt->execute();

    return $resultado && $stmt->rowCount() > 0;
}


    

    // Eliminar un evento por ID
    public function deleteEvent($id_evento)
    {
        $sql = "DELETE FROM events WHERE id_evento = :id_evento";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_evento', $id_evento, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

// Establecer encabezado JSON
header('Content-Type: application/json');

$controller = new EventController();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET["action"] ?? "";

    if ($action === "list") {
        echo json_encode($controller->getEvents());
        exit;
    } elseif ($action === "view" && isset($_GET["id_evento"])) {
        $evento = $controller->getEventById((int)$_GET["id_evento"]);
        if ($evento === null) {
            http_response_code(404);
            echo json_encode(["error" => "Evento no encontrado"]);
            exit;
        }
        echo json_encode($evento);
        exit;
    }

    http_response_code(400);
    echo json_encode(["error" => "Acción GET inválida o parámetros faltantes"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    // Validación mínima para datos comunes
    function validarDatosEvento(array $data, $isUpdate = false)
    {
        $camposRequeridos = ["nombre_evento", "fecha", "hora", "ubicacion", "descripcion", "categoria", "total_tickets", "precio", "organizador"];
        if ($isUpdate) {
            $camposRequeridos[] = "id_evento";
            $camposRequeridos[] = "estado_evento";
        }
        foreach ($camposRequeridos as $campo) {
            if (empty($data[$campo])) {
                return false;
            }
        }
        return true;
    }

    if ($action === "add") {
        if (!validarDatosEvento($_POST)) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos para crear el evento"]);
            exit;
        }

        if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(["error" => "Error al subir la imagen"]);
            exit;
        }

        $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);

        $resultado = $controller->createEvent(
            $_POST["nombre_evento"],
            $_POST["fecha"],
            $_POST["hora"],
            $_POST["ubicacion"],
            $_POST["descripcion"],
            $_POST["categoria"],
            (int)$_POST["total_tickets"],
            $_POST["precio"],
            $_POST["organizador"],
            $imagen
        );

        if ($resultado) {
            echo json_encode(["success" => "Evento creado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el evento."]);
        }
        exit;
    } elseif ($action === "update") {
        if (!validarDatosEvento($_POST, true)) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos para actualizar el evento"]);
            exit;
        }

        $imagen = null;
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
            $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);
        }

        $resultado = $controller->updateEvent(
            (int)$_POST["id_evento"],
            $_POST["nombre_evento"],
            $_POST["fecha"],
            $_POST["hora"],
            $_POST["ubicacion"],
            $_POST["descripcion"],
            $_POST["categoria"],
            (int)$_POST["total_tickets"],
            $_POST["precio"],
            $_POST["organizador"],
            $_POST["estado_evento"],
            $imagen
        );

        if ($resultado) {
            echo json_encode(["success" => "Evento actualizado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el evento."]);
        }
        exit;
    } elseif ($action === "delete") {
        if (empty($_POST["id_evento"])) {
            http_response_code(400);
            echo json_encode(["error" => "ID de evento requerido para eliminar"]);
            exit;
        }

        $resultado = $controller->deleteEvent((int)$_POST["id_evento"]);
        if ($resultado) {
            echo json_encode(["success" => "Evento eliminado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el evento."]);
        }
        exit;
    }

    http_response_code(400);
    echo json_encode(["error" => "Acción POST inválida o parámetros faltantes"]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Método no permitido"]);
exit;
