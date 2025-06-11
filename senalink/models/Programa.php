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

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['codigo'],
            $data['ficha'],
            $data['nivel_formacion'],
            $data['nombre_programa'],
            $data['duracion_meses'],
            $data['estado'],
            $data['descripcion'],
            $data['habilidades_requeridas'],
            $data['fecha_finalizacion']
        ]);
    }
}


