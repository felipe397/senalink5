<?php
// Deshabilitar la salida de errores HTML para evitar romper JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../Config/conexion.php';

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
public function procesarRespuestas($respuestas, $empresaId) {
    // 1. Buscar programas activos
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

        if ($score > 0) {
            $prog['score'] = $score;
            $recomendados[] = $prog;
        }
    }

    // 2. Ordenar por puntaje
    usort($recomendados, fn($a, $b) => $b['score'] <=> $a['score']);

    // 3. Filtrar solo entre 5 y 10
    $filtrados = array_filter($recomendados, fn($p) => $p['score'] >= 5 && $p['score'] <= 10);

    // 4. Guardar en BD
    if ($empresaId && !empty($filtrados)) {
        // Borrar recomendaciones anteriores de la empresa
        $del = $this->db->prepare("DELETE FROM recomendaciones WHERE id_usuario = ?");
        $del->execute([$empresaId]);

        // Insertar nuevas recomendaciones
        $insert = $this->db->prepare("INSERT INTO recomendaciones (id_usuario, id_programa, puntaje) VALUES (?, ?, ?)");
        foreach ($filtrados as $rec) {
            $insert->execute([$empresaId, $rec['id_programa'], $rec['score']]);
        }

    }

    return ["success" => true, "recomendaciones" => array_values($filtrados)];
}
}

// ✅ Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $controller = new DiagnosticoController();
        $data = json_decode(file_get_contents("php://input"), true);

        switch ($data['accion'] ?? '') {
            case 'obtenerDiagnosticoCompleto':
                echo json_encode($controller->obtenerDiagnosticoCompleto());
                break;

            case 'procesarRespuestas':
                echo json_encode($controller->procesarRespuestas($data['respuestas'] ?? [], $data['empresaId'] ?? null));
                break;

            default:
                echo json_encode(["success" => false, "message" => "Acción no válida"]);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error interno del servidor: " . $e->getMessage()]);
    }
}
?>
