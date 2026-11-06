<?php
declare(strict_types=1);

require_once(__DIR__ . "/../config/conexion.php");

class Tickets extends Conectar {
    private PDO $conexion;

    public function __construct() {
        $this->conexion = parent::conexion();
    }

    /**
     * Registra un nuevo ticket en la base de datos.
     */
    public function insertar(
        int $idUsuario,
        int $idCategoria,
        string $titulo,
        string $descripcion,
        string $prioridad = 'media'
    ): int {
        $prioridadesPermitidas = ['baja', 'media', 'alta', 'urgente'];
        if (!in_array(strtolower($prioridad), $prioridadesPermitidas, true)) {
            $prioridad = 'media';
        }

        $sql = "INSERT INTO tickets (id_usuario, id_categoria, titulo, descripcion, prioridad, estado, fecha_creacion)
                VALUES (:id_usuario, :id_categoria, :titulo, :descripcion, :prioridad, 'abierto', NOW())";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            'id_usuario'   => $idUsuario,
            'id_categoria' => $idCategoria,
            'titulo'       => trim($titulo),
            'descripcion'  => trim($descripcion),
            'prioridad'    => strtolower($prioridad)
        ]);

        return (int) $this->conexion->lastInsertId();
    }

    /**
     * Obtiene el detalle completo de un ticket con sus relaciones.
     */
    public function obtenerPorId(int $idTicket): ?array {
        $sql = "SELECT 
                    t.id_ticket,
                    t.id_usuario,
                    CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
                    u.correo AS correo_solicitante,
                    t.id_categoria,
                    c.nombre AS categoria,
                    t.id_tecnico,
                    CONCAT(tec.nombre, ' ', tec.apellido) AS tecnico,
                    t.titulo,
                    t.descripcion,
                    t.prioridad,
                    t.estado,
                    t.fecha_creacion,
                    t.fecha_cierre
                FROM tickets t
                INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
                INNER JOIN categorias c ON t.id_categoria = c.id_categoria
                LEFT JOIN usuarios tec ON t.id_tecnico = tec.id_usuario
                WHERE t.id_ticket = :id
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id' => $idTicket]);
        $ticket = $stmt->fetch();

        return $ticket ?: null;
    }

    /**
     * Lista los tickets registrados por un usuario específico.
     */
    public function listarPorUsuario(int $idUsuario): array {
        $sql = "SELECT 
                    t.id_ticket,
                    t.titulo,
                    c.nombre AS categoria,
                    t.prioridad,
                    t.estado,
                    t.fecha_creacion
                FROM tickets t
                INNER JOIN categorias c ON t.id_categoria = c.id_categoria
                WHERE t.id_usuario = :id_usuario
                ORDER BY t.id_ticket DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);

        return $stmt->fetchAll();
    }

    /**
     * Lista todos los tickets del sistema (para administradores y técnicos).
     */
    public function listarTodos(): array {
        $sql = "SELECT 
                    t.id_ticket,
                    CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
                    c.nombre AS categoria,
                    t.titulo,
                    t.prioridad,
                    t.estado,
                    t.fecha_creacion
                FROM tickets t
                INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
                INNER JOIN categorias c ON t.id_categoria = c.id_categoria
                ORDER BY t.id_ticket DESC";

        $stmt = $this->conexion->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Cuenta los tickets por estado (útil para métricas del dashboard).
     */
    public function contarPorEstado(string $estado, ?int $idUsuario = null): int {
        if ($idUsuario !== null) {
            $sql = "SELECT COUNT(*) AS total FROM tickets WHERE estado = :estado AND id_usuario = :id_usuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute(['estado' => $estado, 'id_usuario' => $idUsuario]);
        } else {
            $sql = "SELECT COUNT(*) AS total FROM tickets WHERE estado = :estado";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute(['estado' => $estado]);
        }

        $res = $stmt->fetch();
        return (int) ($res['total'] ?? 0);
    }
}

