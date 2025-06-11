<?php
require_once __DIR__ . '/../Config/conexion.php';

class ProgramaFormacion {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function crear($data) {
        $sql = "INSERT INTO programas_formacion 
                (codigo, ficha, nivel_formacion, nombre_programa, 
                 duracion_meses, estado, descripcion, habilidades_requeridas, fecha_finalizacion)
                VALUES (:codigo, :ficha, :nivel_formacion, :nombre_programa,
                 :duracion_meses, :estado, :descripcion, :habilidades_requeridas, :fecha_finalizacion)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':codigo' => $data['codigo'],
            ':ficha' => $data['ficha'],
            ':nivel_formacion' => $data['nivel_formacion'],
            ':nombre_programa' => $data['nombre_programa'],
            ':duracion_meses' => $data['duracion_meses'],
            ':estado' => $data['estado'],
            ':descripcion' => $data['descripcion'],
            ':habilidades_requeridas' => $data['habilidades_requeridas'],
            ':fecha_finalizacion' => $data['fecha_finalizacion']
        ]);
    }
}
