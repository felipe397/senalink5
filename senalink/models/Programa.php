

<?php
require_once __DIR__ . '/../Config/conexion.php';

class ProgramaFormacion
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }


    // Devuelve todos los programas con estado 'En ejecucion'
    public function listarProgramasEnEjecucion() {
        if (!$this->db) {
            return false;
        }
        try {
            $stmt = $this->db->prepare("SELECT * FROM programas_formacion WHERE estado = 'En ejecucion'");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function crear($data)
    {
        if (!$this->db) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'No hay conexión a la base de datos']);
            exit;
        }
        $sql = "INSERT INTO programas_formacion
        (codigo, ficha, nivel_formacion, nombre_programa, duracion_programa, estado, habilidades_requeridas, fecha_finalizacion,sector_programa,sector_economico,etapa_ficha,nombre_ocupacion)
        VALUES (:codigo, :ficha, :nivel_formacion, :nombre_programa, :duracion_programa, :estado, :habilidades_requeridas, :fecha_finalizacion,:sector_programa, :sector_economico, :etapa_ficha, :nombre_ocupacion)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':ficha', $data['ficha']);
        $stmt->bindParam(':nivel_formacion', $data['nivel_formacion']);
        $stmt->bindParam(':nombre_programa', $data['nombre_programa']);
        $stmt->bindParam(':duracion_programa', $data['duracion_meses']);
        $stmt->bindParam(':habilidades_requeridas', $data['habilidades_requeridas']);
        $stmt->bindParam(':fecha_finalizacion', $data['fecha_finalizacion']);
        $stmt->bindParam(':sector_programa', $data['sector_programa']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':sector_economico', $data['sector_economico']);
        $stmt->bindParam(':etapa_ficha', $data['etapa_ficha']);
        $stmt->bindParam(':nombre_ocupacion', $data['nombre_ocupacion']);
        return $stmt->execute();
    }

    public function getById($id)
    {
        if (!$this->db) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'No hay conexión a la base de datos']);
            exit;
        }
        $sql = "SELECT * FROM programas_formacion WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        if (!$this->db) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'No hay conexión a la base de datos']);
            exit;
        }
        $sql = "UPDATE programas_formacion SET 
            codigo = :codigo,
            ficha = :ficha,
            nivel_formacion = :nivel_formacion,
            etapa_ficha = :etapa_ficha,
            sector_economico = :sector_economico,
            nombre_ocupacion = :nombre_ocupacion,
            nombre_programa = :nombre_programa,
            duracion_programa = :duracion_programa,
            estado = :estado,
            habilidades_requeridas = :habilidades_requeridas,
            fecha_finalizacion = :fecha_finalizacion,
            sector_programa = :sector_programa
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':ficha', $data['ficha']);
        $stmt->bindParam(':nivel_formacion', $data['nivel_formacion']);
        $stmt->bindParam(':etapa_ficha', $data['etapa_ficha']);
        $stmt->bindParam(':sector_economico', $data['sector_economico']);
        $stmt->bindParam(':nombre_ocupacion', $data['nombre_ocupacion']);
        $stmt->bindParam(':duracion_programa', $data['duracion_programa']);
        $stmt->bindParam(':sector_programa', $data['sector_programa']);
        $stmt->bindParam(':nombre_programa', $data['nombre_programa']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':habilidades_requeridas', $data['habilidades_requeridas']);
        $stmt->bindParam(':fecha_finalizacion', $data['fecha_finalizacion']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function listarProgramasDisponibles() {
        if (!$this->db) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'No hay conexión a la base de datos']);
            exit;
        }
        $stmt = $this->db->prepare("SELECT id,nombre_programa,ficha FROM programas_formacion WHERE estado = 'En ejecucion'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProgramasEnCurso() {
        if (!$this->db) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'No hay conexión a la base de datos']);
            exit;
        }
        $stmt = $this->db->prepare("SELECT * FROM programas_formacion WHERE estado = 'En ejecucion'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarProgramasFinalizados() {
        if (!$this->db) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'No hay conexión a la base de datos']);
            exit;
        }
        $stmt = $this->db->prepare("SELECT * FROM programas_formacion WHERE estado = 'Finalizado'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerDetallePrograma($id)
    {
        $db = Conexion::conectar();
        if (!$db) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'No hay conexión a la base de datos']);
            exit;
        }
        $stmt = $db->prepare("SELECT * FROM programas_formacion WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
