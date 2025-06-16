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
                        primer_apellido,segundo_apellido,numero_documento,tipo_documento
                    ) VALUES (
                        :correo, :contrasena, :rol, :estado, :fecha_creacion,
                        :nombres, :apellidos, :direccion, :telefono,:primer_nombre,:segundo_nombre,
                        :primer_apellido,:segundo_apellido,:numero_documento,:tipo_documento
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
            $stmt->bindValue(':numero_documento', $datos['numero_documento']);
            $stmt->bindValue(':tipo_documento', $datos['tipo_documento']);
        }

        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Error en la base de datos: " . $e->getMessage());
    }
}

public function updateEmpresa($id, $data) {
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
    return $stmt->execute();
}

public static function listarEmpresas() {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT id, razon_social,nit FROM usuarios WHERE rol = 'empresa'");
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
public static function listarPrograma() {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT id, nombre_programa,ficha FROM programas_formacion");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function obtenerProgramaporid($id) {
    $db = Conexion::conectar();
    $stmt = $db->prepare("SELECT * FROM programas_formacion WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updatePasswordByEmail($email, $hashedPassword) {
    $sql = "UPDATE usuarios SET contrasena = :contrasena WHERE correo = :correo";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':contrasena', $hashedPassword);
    $stmt->bindValue(':correo', $email);
    return $stmt->execute();
}
}
