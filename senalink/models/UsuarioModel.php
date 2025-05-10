<?php
require_once __DIR__ . '/../Config/conexion.php';

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Verificar si el correo ya existe
    public static function existeCorreo($correo) {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
        $stmt->bindValue(':correo', $correo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Verificar si el NIT ya existe
    public static function existeNIT($nit) {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE nit = :nit");
        $stmt->bindValue(':nit', $nit);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Crear un nuevo usuario
    public static function crear($datos) {
    try {
        $db = Conexion::conectar();

        $rol = $datos['rol'];

        // Solo usamos nombre_empresa si el rol es Empresa
        $nombre_empresa = ($rol === 'empresa') ? $datos['nombre_empresa'] ?? null : null;

        $sql = "INSERT INTO usuarios (
                    correo, contrasena, rol, estado, fecha_creacion,
                    nit, actividad_economica, direccion, nombre_empresa, telefono
                ) VALUES (
                    :correo, :contrasena, :rol, :estado, :fecha_creacion,
                    :nit, :actividad_economica, :direccion, :nombre_empresa, :telefono
                )";

        $stmt = $db->prepare($sql);

        // Asignar todos los valores
        $stmt->bindValue(':correo', $datos['correo']);
        $stmt->bindValue(':contrasena', $datos['contrasena']); // ya debe venir hasheada
        $stmt->bindValue(':rol', $rol);
        $stmt->bindValue(':estado', $datos['estado']);
        $stmt->bindValue(':fecha_creacion', $datos['fecha_creacion']);
        $stmt->bindValue(':nit', $datos['nit']);
        $stmt->bindValue(':actividad_economica', $datos['actividad_economica']);
        $stmt->bindValue(':direccion', $datos['direccion']);
        $stmt->bindValue(':nombre_empresa', $nombre_empresa);
        $stmt->bindValue(':telefono', $datos['telefono']);

        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error en la base de datos: " . $e->getMessage());
    }
    
}
public static function listarEmpresas() {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT id, nombre_empresa,nit FROM usuarios WHERE rol = 'empresa'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function obtenerEmpresaPorId($id) {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id AND rol = 'empresa'");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}

?>