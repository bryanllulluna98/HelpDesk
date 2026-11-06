<?php
declare(strict_types=1);

require_once(__DIR__ . "/../config/conexion.php");
require_once(__DIR__ . "/../model/Tickets.php");
require_once(__DIR__ . "/../model/Categorias.php");

// Verificación obligatoria de sesión para todas las acciones del controlador
if (!isset($_SESSION["id_usuario"])) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Sesión expirada. Por favor inicie sesión nuevamente."]);
        exit();
    }
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}

$ticketModel = new Tickets();
$categoriaModel = new Categorias();
$opcion = $_GET["op"] ?? "";

switch ($opcion) {
    /**
     * Retorna el listado de categorías en formato <option> para poblar el <select>
     */
    case "combo_categorias":
        $categorias = $categoriaModel->listarActivas();
        $html = '<option value="">-- Seleccione una categoría --</option>';
        foreach ($categorias as $cat) {
            $id = (int) $cat["id_categoria"];
            $nombre = htmlspecialchars($cat["nombre"], ENT_QUOTES, "UTF-8");
            $html .= "<option value=\"{$id}\">{$nombre}</option>";
        }
        echo $html;
        break;

    /**
     * Registra un nuevo ticket de soporte
     */
    case "insertar":
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $idUsuario = (int) $_SESSION["id_usuario"];
            $idCategoria = (int) ($_POST["id_categoria"] ?? 0);
            $titulo = trim($_POST["titulo"] ?? "");
            $descripcion = trim($_POST["descripcion"] ?? "");
            $prioridad = trim($_POST["prioridad"] ?? "media");

            $esAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            // Validación de campos requeridos
            if ($idCategoria <= 0 || $titulo === "" || $descripcion === "") {
                if ($esAjax) {
                    http_response_code(400);
                    echo json_encode([
                        "status" => "error", 
                        "message" => "Por favor complete todos los campos obligatorios."
                    ]);
                    exit();
                }
                header("Location: " . Conectar::ruta() . "view/NuevoTicket/N_ticket.php?m=2");
                exit();
            }

            // Inserción mediante el modelo
            $nuevoId = $ticketModel->insertar($idUsuario, $idCategoria, $titulo, $descripcion, $prioridad);

            if ($esAjax) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Ticket registrado exitosamente.",
                    "id_ticket" => $nuevoId
                ]);
                exit();
            }

            header("Location: " . Conectar::ruta() . "view/NuevoTicket/N_ticket.php?m=ok&id=" . $nuevoId);
            exit();
        }
        break;

    default:
        header("Location: " . Conectar::ruta() . "view/home/home.php");
        exit();
}

