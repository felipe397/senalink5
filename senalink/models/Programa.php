<?php
require_once __DIR__ . '/../Config/conexion.php';

class ProgramaFormacion
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }

    public function crear($data)
    {
        $sql = "INSERT INTO programas_formacion
        (codigo, ficha, nivel_formacion, nombre_programa, duracion_meses, estado, descripcion, habilidades_requeridas, fecha_finalizacion,sector_programa)
        VALUES (:codigo, :ficha, :nivel_formacion, :nombre_programa, :duracion_meses, :estado, :descripcion, :habilidades_requeridas, :fecha_finalizacion,:sector_programa)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':ficha', $data['ficha']);
        $stmt->bindParam(':nivel_formacion', $data['nivel_formacion']);
        $stmt->bindParam(':nombre_programa', $data['nombre_programa']);
        $stmt->bindParam(':duracion_meses', $data['duracion_meses']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':habilidades_requeridas', $data['habilidades_requeridas']);
        $stmt->bindParam(':fecha_finalizacion', $data['fecha_finalizacion']);
        $stmt->bindParam(':sector_programa', $data['sector_programa']);
        $stmt->bindParam(':estado', $data['estado']);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM programas_formacion WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE programas_formacion SET 
            codigo = :codigo,
            ficha = :ficha,
            nivel_formacion = :nivel_formacion,
            nombre_programa = :nombre_programa,
            duracion_meses = :duracion_meses,
            estado = :estado,
            descripcion = :descripcion,
            habilidades_requeridas = :habilidades_requeridas,
            fecha_finalizacion = :fecha_finalizacion,
            sector_programa = :sector_programa
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':ficha', $data['ficha']);
        $stmt->bindParam(':nivel_formacion', $data['nivel_formacion']);
        $stmt->bindParam(':sector_programa', $data['sector_programa']);
        $stmt->bindParam(':nombre_programa', $data['nombre_programa']);
        $stmt->bindParam(':duracion_meses', $data['duracion_meses']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':habilidades_requeridas', $data['habilidades_requeridas']);
        $stmt->bindParam(':fecha_finalizacion', $data['fecha_finalizacion']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function listarProgramasDisponibles() {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT id,nombre_programa,ficha FROM programas_formacion WHERE estado = 'Disponible'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProgramasEnCurso() {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT * FROM programas_formacion WHERE estado = 'en curso'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProgramasFinalizados() {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT * FROM programas_formacion WHERE estado = 'finalizado'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerDetallePrograma($id)
    {
        $db = Conexion::conectar();
        $stmt = $db->prepare("SELECT * FROM programas_formacion WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
