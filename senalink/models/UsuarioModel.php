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

    // Verificar si el nickname ya existe
    public static function existeNickname($nickname) {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE nickname = :nickname");
        $stmt->bindValue(':nickname', $nickname);
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

            // Si el rol es Empresa, no enviar nickname
            $nickname = ($datos['rol'] === 'SuperAdmin' || $datos['rol'] === 'AdminSENA') ? $datos['nickname'] : null;
            $nombre_empresa = ($datos['rol'] === 'Empresa') ? $datos['nombre_empresa'] : null;

            $sql = "INSERT INTO usuarios (
                        nombres, apellidos, nickname, correo, contrasena, rol, estado, fecha_creacion,
                        nit, actividad_economica, direccion, nombre_empresa, telefono
                    ) VALUES (
                        :nombres, :apellidos, :nickname, :correo, :contrasena, :rol, :estado, :fecha_creacion,
                        :nit, :actividad_economica, :direccion, :nombre_empresa, :telefono
                    )";

            $stmt = $db->prepare($sql);

            // Asignar todos los valores
            $stmt->bindValue(':nombres', $datos['nombres']);
            $stmt->bindValue(':apellidos', $datos['apellidos']);
            $stmt->bindValue(':nickname', $nickname);  // Solo se asigna si el rol lo requiere
            $stmt->bindValue(':correo', $datos['correo']);
            $stmt->bindValue(':contrasena', $datos['contrasena']);
            $stmt->bindValue(':rol', $datos['rol']);
            $stmt->bindValue(':estado', $datos['estado']);
            $stmt->bindValue(':fecha_creacion', $datos['fecha_creacion']);
            $stmt->bindValue(':nit', $datos['nit']);
            $stmt->bindValue(':actividad_economica', $datos['actividad_economica']);
            $stmt->bindValue(':direccion', $datos['direccion']);
            $stmt->bindValue(':nombre_empresa', $nombre_empresa);  // Solo se asigna para rol 'Empresa'
            $stmt->bindValue(':telefono', $datos['telefono']);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Registrar o retornar el error si necesitas más detalles para debug
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }
    }
}
?>
