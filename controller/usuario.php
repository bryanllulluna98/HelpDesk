<?php
declare(strict_types=1);

require_once(__DIR__ . "/../config/conexion.php");
require_once(__DIR__ . "/../model/Usuarios.php");

$usuarioModel = new Usuarios();
$opcion = $_GET["op"] ?? "";

switch ($opcion) {
    case "login":
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $correo = trim($_POST["correo"] ?? "");
            $password = trim($_POST["password"] ?? "");

            // Validación: campos vacíos
            if ($correo === "" || $password === "") {
                header("Location: " . Conectar::ruta() . "index.php?m=2");
                exit();
            }

            // Intento de autenticación segura en el modelo
            $datosUsuario = $usuarioModel->autenticar($correo, $password);

            if ($datosUsuario !== null) {
                // Prevenir Session Fixation regenerando el ID de sesión
                session_regenerate_id(true);

                // Guardar datos en la sesión
                $_SESSION["id_usuario"] = $datosUsuario["id_usuario"];
                $_SESSION["nombre"]     = $datosUsuario["nombre"];
                $_SESSION["apellido"]   = $datosUsuario["apellido"];
                $_SESSION["correo"]     = $datosUsuario["correo"];
                $_SESSION["rol"]        = $datosUsuario["rol"];

                header("Location: " . Conectar::ruta() . "view/home/home.php");
                exit();
            }

            // Credenciales incorrectas o usuario inactivo
            header("Location: " . Conectar::ruta() . "index.php?m=1");
            exit();
        }
        break;

    case "logout":
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header("Location: " . Conectar::ruta() . "index.php");
        exit();

    default:
        header("Location: " . Conectar::ruta() . "index.php");
        exit();
}

