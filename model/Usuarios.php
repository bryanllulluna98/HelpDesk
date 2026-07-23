<?php

class Usuarios extends Conectar{
	private PDO $conexion;

    public function login(){
        $conectar = parent::conexion();
        parent::set_names();
        if(isset($_POST["enviar"])){
            $correo = $_POST["correo"];
            $password = $_POST["password"];
            if(empty($correo) and empty($password)){
                header("Location:".Conectar::ruta()."index.php?m=2");
                exit();
            }else{
                $sql = "SELECT * FROM usuarios WHERE correo=? and password=? and estado='1'";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $correo);
                $stmt->bindValue(2, $password);
                $stmt->execute();
                $resultado = $stmt->fetch();
                if(is_array($resultado) and count($resultado)>0){
                    $_SESSION["id_usuario"] = $resultado["id_usuario"];
                    $_SESSION["nombre"] = $resultado["nombre"];
                    $_SESSION["apellido"] = $resultado["apellido"];
                    $_SESSION["rol"] = $resultado["rol"];
                    header("Location:".Conectar::ruta()."view/Home/");
                    exit();
                }else{
                    header("Location:".Conectar::ruta()."index.php?m=1");
                    exit();
                }
            }
        }
    }
	public function __construct(PDO $conexion)
	{
		$this->conexion = $conexion;
		$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}

	public function listar(): array
	{
		$consulta = $this->conexion->query(
			'SELECT id_usuario, nombre, apellido, correo, rol, estado
			 FROM usuarios ORDER BY id_usuario DESC'
		);

		return $consulta->fetchAll();
	}

	public function obtenerPorId(int $id): ?array
	{
		$consulta = $this->conexion->prepare(
			'SELECT id_usuario, nombre, apellido, correo, rol, estado
			 FROM usuarios WHERE id_usuario = :id LIMIT 1'
		);
		$consulta->execute(['id' => $id]);
		$usuario = $consulta->fetch();

		return $usuario ?: null;
	}

	public function obtenerPorCorreo(string $correo): ?array
	{
		$consulta = $this->conexion->prepare(
			'SELECT * FROM usuarios WHERE correo = :correo LIMIT 1'
		);
		$consulta->execute(['correo' => $correo]);
		$usuario = $consulta->fetch();

		return $usuario ?: null;
	}

	public function autenticar(string $correo, string $password): ?array
	{
		$usuario = $this->obtenerPorCorreo($correo);

		if ($usuario === null || (isset($usuario['estado']) && !$usuario['estado'])) {
			return null;
		}

		return password_verify($password, $usuario['password']) ? $usuario : null;
	}

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
			'nombre' => trim($nombre),
			'apellido' => trim($apellido),
			'correo' => strtolower(trim($correo)),
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'rol' => $rol,
			'estado' => $estado,
		]);

		return (int) $this->conexion->lastInsertId();
	}

	public function actualizar(int $id, array $datos): bool
	{
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

	public function eliminar(int $id): bool
	{
		$consulta = $this->conexion->prepare(
			'DELETE FROM usuarios WHERE id_usuario = :id'
		);

		return $consulta->execute(['id' => $id]);
	}
}
