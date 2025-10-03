<?php
// Deshabilitar la salida de errores HTML para evitar romper JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../Config/conexion.php';
require_once '../models/Diagnostico.php';

class DiagnosticoController {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // ✅ Obtener preguntas dinámicas desde la BD o por defecto
    public function obtenerDiagnosticoCompleto() {
        try {
            // Check if there are custom questions in DB
            $stmt = $this->db->query("SELECT COUNT(*) FROM preguntas");
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                // Use DB questions
                $stmt = $this->db->query("SELECT * FROM preguntas ORDER BY id");
                $preguntasDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $preguntas = [];
                foreach ($preguntasDB as $index => $p) {
                    $stmt_op = $this->db->prepare("SELECT texto FROM opciones WHERE id_pregunta = ?");
                    $stmt_op->execute([$p['id']]);
                    $opciones = $stmt_op->fetchAll(PDO::FETCH_COLUMN);
                    $preguntas[] = [
                        "enunciado" => $p['enunciado'],
                        "id" => "pregunta" . ($index + 1),
                        "opciones" => $opciones
                    ];
                }
                return ["success" => true, "preguntas" => $preguntas];
            } else {
                // Default hardcoded
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
            }
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

    // Obtener preguntas para edición
    public function obtenerPreguntasParaEdicion() {
        $diagnostico = new Diagnostico();
        return ["success" => true, "preguntas" => $diagnostico->obtenerPreguntasConOpciones()];
    }

    // Actualizar enunciado de pregunta
    public function actualizarEnunciado($id, $enunciado) {
        $diagnostico = new Diagnostico();
        return ["success" => $diagnostico->actualizarPregunta($id, $enunciado)];
    }

    // Eliminar pregunta
    public function eliminarPregunta($id) {
        $diagnostico = new Diagnostico();
        return ["success" => $diagnostico->eliminarPregunta($id)];
    }

    // Actualizar diagnóstico completo
    public function actualizarDiagnosticoCompleto($data) {
        $diagnostico = new Diagnostico();
        try {
            // Update existing preguntas
            if (isset($data['preguntas'])) {
                foreach ($data['preguntas'] as $id => $enunciado) {
                    $diagnostico->actualizarPregunta($id, $enunciado);
                }
            }
            // Insert new preguntas
            if (isset($data['preguntas_nuevas'])) {
                foreach ($data['preguntas_nuevas'] as $index => $enunciado) {
                    $id = $diagnostico->insertarPregunta($enunciado);
                    // Insert opciones for this new pregunta
                    if (isset($data['nuevas_opciones'][$index])) {
                        foreach ($data['nuevas_opciones'][$index] as $texto) {
                            $diagnostico->insertarOpcion($id, $texto);
                        }
                    }
                }
            }
            // Insert new opciones for existing preguntas
            if (isset($data['nuevas_opciones_existentes'])) {
                foreach ($data['nuevas_opciones_existentes'] as $idPregunta => $opciones) {
                    foreach ($opciones as $texto) {
                        $diagnostico->insertarOpcion($idPregunta, $texto);
                    }
                }
            }
            // Delete opciones
            if (isset($data['opciones_a_borrar'])) {
                foreach ($data['opciones_a_borrar'] as $idOpcion) {
                    $diagnostico->eliminarOpcion($idOpcion);
                }
            }
            return ["success" => true];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
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

            case 'obtenerPreguntasParaEdicion':
                echo json_encode($controller->obtenerPreguntasParaEdicion());
                break;

            case 'actualizarEnunciado':
                echo json_encode($controller->actualizarEnunciado($data['idPregunta'], $data['enunciado']));
                break;

            case 'eliminarPregunta':
                echo json_encode($controller->eliminarPregunta($data['idPregunta']));
                break;

            case 'actualizarDiagnosticoCompleto':
                echo json_encode($controller->actualizarDiagnosticoCompleto($data));
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
