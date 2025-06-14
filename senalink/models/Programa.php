<?php
require_once __DIR__ . '/../Config/conexion.php';

class ProgramaFormacion {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function crear($data) {
        $sql = "INSERT INTO programas_formacion
        (codigo, ficha, nivel_formacion, nombre_programa, duracion_meses, estado, descripcion, habilidades_requeridas, fecha_finalizacion)
        VALUES (:codigo, :ficha, :nivel_formacion, :nombre_programa, :duracion_meses, :estado, :descripcion, :habilidades_requeridas, :fecha_finalizacion)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':ficha', $data['ficha']);
        $stmt->bindParam(':nivel_formacion', $data['nivel_formacion']);
        $stmt->bindParam(':nombre_programa', $data['nombre_programa']);
        $stmt->bindParam(':duracion_meses', $data['duracion_meses']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':habilidades_requeridas', $data['habilidades_requeridas']);
        $stmt->bindParam(':fecha_finalizacion', $data['fecha_finalizacion']);
        return $stmt->execute();
    }

    public function getById($id) {
        $sql = "SELECT * FROM programas_formacion WHERE codigo = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
