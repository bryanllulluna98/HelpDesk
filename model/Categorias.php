<?php
declare(strict_types=1);

require_once(__DIR__ . "/../config/conexion.php");

class Categorias extends Conectar {
    private PDO $conexion;

    public function __construct() {
        $this->conexion = parent::conexion();
    }

    /**
     * Lista todas las categorías activas para poblar los formularios.
     */
    public function listarActivas(): array {
        $consulta = $this->conexion->prepare(
            "SELECT id_categoria, nombre, descripcion, estado 
             FROM categorias 
             WHERE estado = 1 
             ORDER BY nombre ASC"
        );
        $consulta->execute();
        return $consulta->fetchAll();
    }

    /**
     * Obtiene una categoría por su ID.
     */
    public function obtenerPorId(int $idCategoria): ?array {
        $consulta = $this->conexion->prepare(
            "SELECT id_categoria, nombre, descripcion, estado 
             FROM categorias 
             WHERE id_categoria = :id LIMIT 1"
        );
        $consulta->execute(['id' => $idCategoria]);
        $categoria = $consulta->fetch();

        return $categoria ?: null;
    }
}

