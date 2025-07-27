<?php
require_once __DIR__ . '/../Config/conexion.php';
class UsuarioModel {
    private $db;

    // Actualizar datos de un usuario AdminSENA
    public function actualizarAdminSENA($data) {
        $sql = "UPDATE usuarios SET primer_nombre = :primer_nombre, segundo_nombre = :segundo_nombre, primer_apellido = :primer_apellido, segundo_apellido = :segundo_apellido, correo = :correo, telefono = :telefono, numero_documento = :numero_documento, tipo_documento = :tipo_documento WHERE id = :id AND rol = 'AdminSENA'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':primer_nombre', $data['primer_nombre']);
        $stmt->bindParam(':segundo_nombre', $data['segundo_nombre']);
        $stmt->bindParam(':primer_apellido', $data['primer_apellido']);
        $stmt->bindParam(':segundo_apellido', $data['segundo_apellido']);
        $stmt->bindParam(':correo', $data['correo']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':numero_documento', $data['numero_documento']);
        $stmt->bindParam(':tipo_documento', $data['tipo_documento']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Obtener usuarios por rol y estado
    public static function obtenerUsuariosPorRolYEstado($rol, $estado) {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT id, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, numero_documento, estado FROM usuarios WHERE rol = :rol AND estado = :estado");
        $stmt->bindValue(':rol', $rol, PDO::PARAM_STR);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar si el correo ya existe, excluyendo un ID opcional
    public static function existeCorreo($correo, $excludeId = null) {
        $db = Conexion::conectar();
        if ($excludeId) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo AND id != :id");
            $stmt->bindValue(':correo', $correo);
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
            $stmt->bindValue(':correo', $correo);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public static function obtenerUsuariosPorRol($rol) {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT id, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, numero_documento FROM usuarios WHERE rol = :rol");
        $stmt->bindValue(':rol', $rol, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar si el NIT ya existe, excluyendo un ID opcional
    public static function existeNIT($nit, $excludeId = null) {
        $db = Conexion::conectar();
        if ($excludeId) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE nit = :nit AND id != :id");
            $stmt->bindValue(':nit', $nit);
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE nit = :nit");
            $stmt->bindValue(':nit', $nit);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Verificar si la razón social ya existe, excluyendo un ID opcional
    public static function existeRazonSocial($razon_social, $excludeId = null) {
        $db = Conexion::conectar();
        if ($excludeId) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE razon_social = :razon_social AND id != :id");
            $stmt->bindValue(':razon_social', $razon_social);
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE razon_social = :razon_social");
            $stmt->bindValue(':razon_social', $razon_social);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Verificar si el teléfono ya existe
    public static function existeTelefono($telefono, $excludeId = null) {
        $db = Conexion::conectar();
        if ($excludeId) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE telefono = :telefono AND id != :id");
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE telefono = :telefono");
            $stmt->bindValue(':telefono', $telefono);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Verificar si el número de documento ya existe
    public static function existeNumeroDocumento($numero_documento) {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE numero_documento = :numero_documento");
        $stmt->bindValue(':numero_documento', $numero_documento);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public static function crear($datos) {
    try {
        $db = Conexion::conectar();
        $rol = $datos['rol'];

        if ($rol === 'empresa') {
            $sql = "INSERT INTO usuarios (
                        correo, contrasena, rol, estado, fecha_creacion,
                        nit, direccion, razon_social, telefono, representante_legal, tipo_empresa
                    ) VALUES (
                        :correo, :contrasena, :rol, :estado, :fecha_creacion,
                        :nit, :direccion, :razon_social, :telefono, :representante_legal, :tipo_empresa
                    )";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':correo', $datos['correo']);
            $stmt->bindValue(':contrasena', $datos['contrasena']);
            $stmt->bindValue(':rol', $rol);
            $stmt->bindValue(':estado', $datos['estado']);
            $stmt->bindValue(':fecha_creacion', $datos['fecha_creacion']);
            $stmt->bindValue(':nit', $datos['nit']);
            $stmt->bindValue(':direccion', $datos['direccion']);
            $stmt->bindValue(':razon_social', $datos['razon_social']);
            $stmt->bindValue(':telefono', $datos['telefono']);
            $stmt->bindValue(':representante_legal', $datos['representante_legal']);
            $stmt->bindValue(':tipo_empresa', $datos['tipo_empresa']);

        } 
        else if ($rol === 'AdminSENA') {
            $sql = "INSERT INTO usuarios (
                        correo, contrasena, rol, estado, fecha_creacion,
                        telefono,primer_nombre,segundo_nombre,
                        primer_apellido,segundo_apellido,numero_documento,tipo_documento
                    ) VALUES (
                        :correo, :contrasena, :rol, :estado, :fecha_creacion,
                        :telefono,:primer_nombre,:segundo_nombre,
                        :primer_apellido,:segundo_apellido,:numero_documento,:tipo_documento
                    )";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':correo', $datos['correo']);
            $stmt->bindValue(':contrasena', $datos['contrasena']);
            $stmt->bindValue(':rol', $rol);
            $stmt->bindValue(':estado', $datos['estado']);
            $stmt->bindValue(':fecha_creacion', $datos['fecha_creacion']);
            $stmt->bindValue(':telefono', $datos['telefono']);
            $stmt->bindValue(':primer_nombre', $datos['primer_nombre']);
            $stmt->bindValue(':segundo_nombre', $datos['segundo_nombre']);
            $stmt->bindValue(':primer_apellido', $datos['primer_apellido']);
            $stmt->bindValue(':segundo_apellido', $datos['segundo_apellido']);
            $stmt->bindValue(':numero_documento', $datos['numero_documento']);
            $stmt->bindValue(':tipo_documento', $datos['tipo_documento']);
        }
        else {
            $sql = "INSERT INTO usuarios (
                        correo, contrasena, rol, estado, fecha_creacion,
                        direccion, telefono,primer_nombre,segundo_nombre,
                        primer_apellido,segundo_apellido
                    ) VALUES (
                        :correo, :contrasena, :rol, :estado, :fecha_creacion,
                        :direccion, :telefono,:primer_nombre,:segundo_nombre,
                        :primer_apellido,:segundo_apellido
                    )";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':correo', $datos['correo']);
            $stmt->bindValue(':contrasena', $datos['contrasena']);
            $stmt->bindValue(':rol', $rol);
            $stmt->bindValue(':estado', $datos['estado']);
            $stmt->bindValue(':fecha_creacion', $datos['fecha_creacion']);
            $stmt->bindValue(':direccion', $datos['direccion']);
            $stmt->bindValue(':telefono', $datos['telefono']);
            $stmt->bindValue(':primer_nombre', $datos['primer_nombre']);
            $stmt->bindValue(':segundo_nombre', $datos['segundo_nombre']);
            $stmt->bindValue(':primer_apellido', $datos['primer_apellido']);
            $stmt->bindValue(':segundo_apellido', $datos['segundo_apellido']);
        }

        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error en la base de datos: " . $e->getMessage());
    }
}

public function updateEmpresa($id, $data) {
    try {
        $sql = "UPDATE usuarios SET 
                nit = :nit,
                representante_legal = :representante_legal,
                razon_social = :razon_social,
                telefono = :telefono,
                correo = :correo,
                direccion = :direccion,
                tipo_empresa = :tipo_empresa
                WHERE id = :id AND rol = 'empresa'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nit', $data['nit']);
        $stmt->bindValue(':representante_legal', $data['representante_legal']);
        $stmt->bindValue(':razon_social', $data['razon_social']);
        $stmt->bindValue(':telefono', $data['telefono']);
        $stmt->bindValue(':correo', $data['correo']);
        $stmt->bindValue(':direccion', $data['direccion']);
        $stmt->bindValue(':tipo_empresa', $data['tipo_empresa']);
        $stmt->bindValue(':id', $id);
        $result = $stmt->execute();
        if (!$result) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Error SQL: " . $errorInfo[2]);
        }
        return $result;
    } catch (Exception $e) {
        error_log("Error en updateEmpresa: " . $e->getMessage());
        throw $e;
    }
}

public static function listarEmpresas() {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT id, razon_social,nit FROM usuarios WHERE rol = 'empresa'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public static function obtenerUsuarioPorId($id) {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
}


public static function obtenerEmpresaPorId($id) {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id AND rol = 'empresa'");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public static function actualizarEstado($id, $estado) {
    try {
        $db = Conexion::conectar();
        $stmt = $db->prepare("UPDATE usuarios SET estado = :estado WHERE id = :id");
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0; // ✅ esto sí verifica si realmente cambió el estado
    } catch (PDOException $e) {
        error_log("Error al actualizar estado: " . $e->getMessage());
        return false;
    }
}



public function updatePasswordByEmail($email, $hashedPassword) {
    $sql = "UPDATE usuarios SET contrasena = :contrasena WHERE correo = :correo";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':contrasena', $hashedPassword);
    $stmt->bindValue(':correo', $email);
    return $stmt->execute();
}

public static function listarEmpresasActivas() {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT id, razon_social, nit FROM usuarios WHERE rol = 'empresa' AND estado = 'activo'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function listarEmpresasInhabilitadas() {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT id, razon_social, nit FROM usuarios WHERE rol = 'empresa' AND estado = 'Desactivado'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public static function getEmpresasPorEstado($estado) {
    $conexion = Conexion::conectar();
    $sql = "SELECT id,razon_social,nit FROM usuarios WHERE estado = :estado";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':estado' => $estado]); // ✅ MATCH
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
