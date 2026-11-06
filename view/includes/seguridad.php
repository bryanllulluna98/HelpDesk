<?php
declare(strict_types=1);

require_once(__DIR__ . "/../../config/conexion.php");

// Verificación de autenticación: si no hay sesión activa, redirigir al login
if (!isset($_SESSION["id_usuario"])) {
    header("Location: " . Conectar::ruta() . "index.php");
    exit();
}

