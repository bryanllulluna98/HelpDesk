<?php
declare(strict_types=1);

require_once(__DIR__ . "/../config/conexion.php");

class Usuarios extends Conectar {
    private PDO $conexion;

    public function __construct() {
        $this->conexion = parent::conexion();
    }

    /**
     * Autentica un usuario mediante correo y contraseña.
     * Soporta password_verify y migración automática transparente si la contraseña estaba en texto plano.
     */
    public function autenticar(string $correo, string $password): ?array {
        $usuario = $this->obtenerPorCorreo($correo);

        // Si no existe o está inactivo (estado != 1)
        if ($usuario === null || (int)$usuario['estado'] !== 1) {
            return null;
        }

        // Verificación con hash seguro (bcrypt / ARGON2)
        if (password_verify($password, $usuario['password'])) {
            return $usuario;
        }

        // Compatibilidad de migración: Si en BD la clave estaba en texto plano (no tiene formato de hash)
        $esHash = str_starts_with($usuario['password'], '$2y$') 
               || str_starts_with($usuario['password'], '$2a$') 
               || str_starts_with($usuario['password'], '$argon2');

        if (!$esHash && $password === $usuario['password']) {
            $this->actualizar((int)$usuario['id_usuario'], ['password' => $password]);
            return $usuario;
        }

        return null;
    }

    /**
     * Lista todos los usuarios ordenados por ID descendente.
     */
    public function listar(): array {
        $consulta = $this->conexion->query(
            'SELECT id_usuario, nombre, apellido, correo, rol, estado, fecha_creacion
             FROM usuarios ORDER BY id_usuario DESC'
        );

        return $consulta->fetchAll();
    }

    /**
     * Obtiene los datos de un usuario por su ID.
     */
    public function obtenerPorId(int $id): ?array {
        $consulta = $this->conexion->prepare(
            'SELECT id_usuario, nombre, apellido, correo, rol, estado, fecha_creacion
             FROM usuarios WHERE id_usuario = :id LIMIT 1'
        );
        $consulta->execute(['id' => $id]);
        $usuario = $consulta->fetch();

        return $usuario ?: null;
    }

    /**
     * Obtiene los datos de un usuario por su correo electrónico.
     */
    public function obtenerPorCorreo(string $correo): ?array {
        $consulta = $this->conexion->prepare(
            'SELECT * FROM usuarios WHERE correo = :correo LIMIT 1'
        );
        $consulta->execute(['correo' => strtolower(trim($correo))]);
        $usuario = $consulta->fetch();

        return $usuario ?: null;
    }

    /**
     * Crea un nuevo usuario en la base de datos con contraseña hasheada.
     */
    public function crear(
        string $nombre,
        string $apellido,
        string $correo,
        string $password,
        string $rol = 'usuario',
        int $estado = 1
    ): int {
        $consulta = $this->conexion->prepare(
            'INSERT INTO usuarios (nombre, apellido, correo, password, rol, estado)
             VALUES (:nombre, :apellido, :correo, :password, :rol, :estado)'
        );
        $consulta->execute([
            'nombre'   => trim($nombre),
            'apellido' => trim($apellido),
            'correo'   => strtolower(trim($correo)),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol'      => $rol,
            'estado'   => $estado,
        ]);

        return (int) $this->conexion->lastInsertId();
    }

    /**
     * Actualiza los datos permitidos de un usuario.
     */
    public function actualizar(int $id, array $datos): bool {
        $permitidos = ['nombre', 'apellido', 'correo', 'rol', 'estado'];
        $campos = array_intersect_key($datos, array_flip($permitidos));

        if (isset($campos['correo'])) {
            $campos['correo'] = strtolower(trim((string) $campos['correo']));
        }

        if (isset($datos['password']) && $datos['password'] !== '') {
            $campos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }

        if ($campos === []) {
            return false;
        }

        $asignaciones = array_map(
            static fn(string $campo): string => "$campo = :$campo",
            array_keys($campos)
        );
        $campos['id'] = $id;

        $consulta = $this->conexion->prepare(
            'UPDATE usuarios SET ' . implode(', ', $asignaciones) .
            ' WHERE id_usuario = :id'
        );

        return $consulta->execute($campos);
    }

    /**
     * Elimina físicamente un usuario por su ID.
     */
    public function eliminar(int $id): bool {
        $consulta = $this->conexion->prepare(
            'DELETE FROM usuarios WHERE id_usuario = :id'
        );

        return $consulta->execute(['id' => $id]);
    }
}
