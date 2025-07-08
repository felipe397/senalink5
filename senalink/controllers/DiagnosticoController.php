<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/conexion.php';
require_once '../models/Diagnostico.php';

class DiagnosticoController {
    private $db;

    public function __construct() {
        $this->db = new Conexion();
    }

    // ✅ Obtener preguntas con sus opciones
    public function obtenerDiagnosticoCompleto() {
        try {
            $modelo = new Diagnostico();
            $preguntas = $modelo->obtenerPreguntasConOpciones();
            return ['success' => true, 'preguntas' => $preguntas];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // ✅ Procesar respuestas y guardar diagnóstico
    public function procesarRespuestas($empresaId, $respuestas) {
        try {
            $conn = Conexion::conectar();
            $resultadoJson = json_encode($respuestas);

            $query = "INSERT INTO diagnosticos_empresariales (empresa_id, resultado) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$empresaId, $resultadoJson]);

            $recomendaciones = $this->generarRecomendaciones($respuestas);

            return ['success' => true, 'recomendaciones' => $recomendaciones];
        } catch (PDOException $e) {
            error_log("Error en procesarRespuestas: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al procesar respuestas'];
        }
    }

    // ✅ Generar recomendaciones basadas en las respuestas
    private function generarRecomendaciones($respuestas) {
        try {
            $conn = Conexion::conectar();
            $query = "SELECT * FROM programas_formacion WHERE estado = 'En ejecucion'";
            $params = [];

            if (!empty($respuestas['pregunta1'])) {
                $sectores = $this->mapearSectorEconomico($respuestas['pregunta1']);
                if ($sectores) {
                    $placeholders = implode(',', array_fill(0, count($sectores), '?'));
                    $query .= " AND sector_economico IN ($placeholders)";
                    $params = array_merge($params, $sectores);
                }
            }

            if (!empty($respuestas['pregunta3'])) {
                $query .= " AND nivel_formacion = ?";
                $params[] = $respuestas['pregunta3'];
            }

            if (!empty($respuestas['pregunta2'])) {
                $query .= " AND nombre_ocupacion LIKE ?";
                $params[] = '%' . $respuestas['pregunta2'] . '%';
            }

            if (!empty($respuestas['pregunta5'])) {
                $query .= " AND (descripcion LIKE ? OR habilidades_requeridas LIKE ?)";
                $params[] = '%' . $respuestas['pregunta5'] . '%';
                $params[] = '%' . $respuestas['pregunta5'] . '%';
            }

            $query .= " ORDER BY 
                CASE 
                    WHEN nombre_ocupacion LIKE ? THEN 0 
                    WHEN descripcion LIKE ? THEN 1
                    WHEN habilidades_requeridas LIKE ? THEN 2
                    ELSE 3
                END,
                fecha_finalizacion DESC
                LIMIT 10";

            $params[] = '%' . $respuestas['pregunta2'] . '%';
            $params[] = '%' . $respuestas['pregunta5'] . '%';
            $params[] = '%' . $respuestas['pregunta5'] . '%';

            $stmt = $conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en generarRecomendaciones: " . $e->getMessage());
            return [];
        }
    }

    // ✅ Mapear sector productivo
    private function mapearSectorEconomico($sector) {
        $mapeo = [
            'Construcción' => ['Construccion', 'Industrial'],
            'Educación' => ['Servicios'],
            'Electricidad' => ['Electricidad', 'Industrial'],
            'Industrial' => ['Industria', 'Electricidad', 'Construccion'],
            'Salud' => ['Servicios'],
            'Servicios' => ['Servicios', 'Industria'],
            'Textiles' => ['Textiles', 'Industrial'],
            'Transporte' => ['Servicios', 'Industrial']
        ];
        return $mapeo[$sector] ?? null;
    }

    // ✅ Obtener recomendaciones guardadas
    public function obtenerRecomendaciones($empresaId) {
        try {
            $conn = Conexion::conectar();
            $query = "SELECT resultado FROM diagnosticos_empresariales WHERE empresa_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$empresaId]);

            $diagnostico = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($diagnostico) {
                $respuestas = json_decode($diagnostico['resultado'], true);
                return $this->generarRecomendaciones($respuestas);
            }
            return [];
        } catch (PDOException $e) {
            return [];
        }
    }

    // ✅ Editar preguntas, insertar nuevas opciones, eliminar opciones
public function actualizarDiagnosticoCompleto($datos) {
    try {
        $modelo = new Diagnostico();

        // 1. Actualizar enunciados
        if (isset($datos['preguntas'])) {
            foreach ($datos['preguntas'] as $id => $enunciado) {
                $modelo->actualizarPregunta($id, $enunciado);
            }
        }

        // 2. Insertar nuevas preguntas y sus opciones (alineadas por índice)
        if (isset($datos['preguntas_nuevas']) && isset($datos['nuevas_opciones'])) {
            foreach ($datos['preguntas_nuevas'] as $idx => $enunciado) {
                $nuevaPreguntaId = $modelo->insertarPregunta($enunciado); // Debe devolver el ID insertado
                if ($nuevaPreguntaId && !empty($datos['nuevas_opciones'][$idx])) {
                    foreach ($datos['nuevas_opciones'][$idx] as $texto) {
                        $modelo->insertarOpcion($nuevaPreguntaId, $texto);
                    }
                }
            }
        }

        // 3. Insertar nuevas opciones para preguntas existentes
        if (isset($datos['nuevas_opciones_existentes'])) {
            foreach ($datos['nuevas_opciones_existentes'] as $idPregunta => $opciones) {
                foreach ($opciones as $texto) {
                    $modelo->insertarOpcion($idPregunta, $texto);
                }
            }
        }

        // 4. Eliminar opciones
        if (isset($datos['opciones_a_borrar'])) {
            foreach ($datos['opciones_a_borrar'] as $idOpcion) {
                $modelo->eliminarOpcion($idOpcion);
            }
        }

        return ['success' => true];
    } catch (PDOException $e) {
        error_log("Error en actualizarDiagnosticoCompleto: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error al actualizar el diagnóstico'];
    }
}
}


////// 🛠️ Manejo de solicitudes externas //////
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DiagnosticoController();
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['accion'])) {
        switch ($data['accion']) {
            case 'obtenerDiagnosticoCompleto':
                echo json_encode($controller->obtenerDiagnosticoCompleto());
                break;

            case 'procesarRespuestas':
                if (isset($data['empresaId']) && isset($data['respuestas'])) {
                    echo json_encode($controller->procesarRespuestas($data['empresaId'], $data['respuestas']));
                } else {
                    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                }
                break;

            case 'obtenerRecomendaciones':
                if (isset($data['empresaId'])) {
                    echo json_encode($controller->obtenerRecomendaciones($data['empresaId']));
                }
                break;

            case 'actualizarDiagnosticoCompleto':
                echo json_encode($controller->actualizarDiagnosticoCompleto($data));
                break;
            case 'eliminarPregunta':
            if (isset($data['idPregunta'])) {
                $modelo = new Diagnostico();
                $ok = $modelo->eliminarPregunta($data['idPregunta']);
                echo json_encode(['success' => $ok]);
            } else {
                echo json_encode(['success' => false, 'message' => 'ID no especificado']);
            }
            break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no especificada']);
    }
}
?>
