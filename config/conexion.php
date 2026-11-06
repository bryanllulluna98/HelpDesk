<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Conectar {
    protected ?PDO $dbh = null;

    /**
     * Establece y retorna la conexión PDO a la base de datos (reutilizando la instancia).
     */
    protected function conexion(): PDO {
        if ($this->dbh !== null) {
            return $this->dbh;
        }

        try {
            $dsn = "mysql:host=localhost;dbname=helpdesk_tecnostar;charset=utf8mb4";
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->dbh = new PDO($dsn, "root", "", $opciones);
            return $this->dbh;
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error en la conexión con la base de datos. Por favor revise su configuración o contacte al administrador.");
        }
    }

    /**
     * Mantener compatibilidad con llamadas legacy a set_names
     */
    protected function set_names(): void {
        // El charset utf8mb4 ya se configura de forma nativa en el DSN
    }

    /**
     * Retorna la URL base del proyecto
     */
    public static function ruta(): string {
        return "http://localhost/PERSONAL_HelpDesk/";
    }
}
