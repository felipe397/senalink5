<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/conexion.php';

class DiagnosticoController {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // ✅ Obtener preguntas dinámicas desde la BD
    public function obtenerDiagnosticoCompleto() {
        try {
            $preguntas = [];

            // Sector del programa
            $stmt = $this->db->query("SELECT DISTINCT sector_programa FROM programas_formacion WHERE sector_programa IS NOT NULL");
            $sectores = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $preguntas[] = ["enunciado" => "¿En qué sector productivo está interesada tu empresa?", "id" => "pregunta1", "opciones" => $sectores];

            // Ocupación
            $stmt = $this->db->query("SELECT DISTINCT nombre_ocupacion FROM programas_formacion WHERE nombre_ocupacion IS NOT NULL");
            $ocupaciones = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $preguntas[] = ["enunciado" => "¿Qué ocupación deseas fortalecer?", "id" => "pregunta2", "opciones" => $ocupaciones];

            // Nivel de formación
            $stmt = $this->db->query("SELECT DISTINCT nivel_formacion FROM programas_formacion WHERE nivel_formacion IS NOT NULL");
            $niveles = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $preguntas[] = ["enunciado" => "¿Qué nivel de formación prefieres?", "id" => "pregunta3", "opciones" => $niveles];

            // Sector económico
            $stmt = $this->db->query("SELECT DISTINCT sector_economico FROM programas_formacion WHERE sector_economico IS NOT NULL");
            $sectoresEco = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $preguntas[] = ["enunciado" => "¿En qué sector económico se encuentra tu empresa?", "id" => "pregunta4", "opciones" => $sectoresEco];

            // Etapa de la ficha
            $stmt = $this->db->query("SELECT DISTINCT etapa_ficha FROM programas_formacion WHERE etapa_ficha IS NOT NULL");
            $etapas = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $preguntas[] = ["enunciado" => "¿Prefieres programas en qué etapa?", "id" => "pregunta5", "opciones" => $etapas];

            // Duración (se define como campo libre)
            $preguntas[] = ["enunciado" => "¿Duración máxima en meses?", "id" => "pregunta6", "opciones" => []];

            return ["success" => true, "preguntas" => $preguntas];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // ✅ Procesar respuestas y recomendar con puntaje
    public function procesarRespuestas($respuestas) {
        $sql = "SELECT * FROM programas_formacion 
                WHERE estado = 'En ejecucion' 
                AND fecha_finalizacion > CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $programas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $recomendados = [];

        foreach ($programas as $prog) {
            $score = 0;

            if (!empty($respuestas['pregunta1']) && stripos($prog['sector_programa'], $respuestas['pregunta1']) !== false) $score += 3;
            if (!empty($respuestas['pregunta2']) && stripos($prog['nombre_ocupacion'], $respuestas['pregunta2']) !== false) $score += 4;
            if (!empty($respuestas['pregunta3']) && stripos($prog['nivel_formacion'], $respuestas['pregunta3']) !== false) $score += 2;
            if (!empty($respuestas['pregunta4']) && stripos($prog['sector_economico'], $respuestas['pregunta4']) !== false) $score += 2;
            if (!empty($respuestas['pregunta5']) && stripos($prog['etapa_ficha'], $respuestas['pregunta5']) !== false) $score += 1;
            if (!empty($respuestas['pregunta6']) && intval($prog['duracion_programa']) <= intval($respuestas['pregunta6']) * 48) $score += 1;

            if ($score > 0) {
                $prog['score'] = $score;
                $recomendados[] = $prog;
            }
        }

        usort($recomendados, fn($a, $b) => $b['score'] <=> $a['score']);
        return ["success" => true, "recomendaciones" => $recomendados];
    }
}

// ✅ Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DiagnosticoController();
    $data = json_decode(file_get_contents("php://input"), true);

    switch ($data['accion'] ?? '') {
        case 'obtenerDiagnosticoCompleto':
            echo json_encode($controller->obtenerDiagnosticoCompleto());
            break;

        case 'procesarRespuestas':
            echo json_encode($controller->procesarRespuestas($data['respuestas'] ?? []));
            break;

        default:
            echo json_encode(["success" => false, "message" => "Acción no válida"]);
            break;
    }
}
?>
