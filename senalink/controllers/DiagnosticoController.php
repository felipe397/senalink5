<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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

    // ✅ Procesar respuestas y guardar diagnóstico si hay empresa
    public function procesarRespuestas($empresaId, $respuestas) {
    try {
        $conn = Conexion::conectar();
        $resultadoJson = json_encode($respuestas);

        // Guardar solo si el ID es válido
        if ($empresaId && is_numeric($empresaId) && $empresaId > 0) {
            $query = "INSERT INTO diagnosticos_empresariales (empresa_id, resultado) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$empresaId, $resultadoJson]);
            // ✅ Marcar que la empresa ya hizo el diagnóstico
            $update = $conn->prepare("UPDATE usuarios SET diagnostico_realizado = 1 WHERE id = ?");
            $update->execute([$empresaId]);

        }

        $recomendaciones = $this->generarRecomendaciones($respuestas);

        return ['success' => true, 'recomendaciones' => $recomendaciones];
    } catch (PDOException $e) {
        error_log("Error en procesarRespuestas: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error al procesar respuestas: ' . $e->getMessage()];
    }
}


    // ✅ Mapear sector económico flexible
    private function mapearSectorEconomico($sector) {
        $mapeo = [
            'servicios'     => ['servicios', 'industrial'],
            'industrial'    => ['industrial', 'electricidad', 'construccion', 'textiles', 'servicios'],
            'construccion'  => ['construccion', 'industrial'],
            'electricidad'  => ['electricidad', 'industrial'],
            'textiles'      => ['textiles', 'industrial']
        ];
        $sector = strtolower(trim($sector));
        return $mapeo[$sector] ?? null;
    }

    // ✅ Generar recomendaciones desde respuestas
    private function generarRecomendaciones($respuestas) {
        $conn = Conexion::conectar();

        $sector_programa = $respuestas['pregunta1'] ?? null;
        $ocupacion = $respuestas['pregunta2'] ?? null;
        $nivel = $respuestas['pregunta3'] ?? null;
        $sector_economico = $respuestas['pregunta4'] ?? null;

        $sql = "SELECT * FROM programas_formacion WHERE 1=1";
        $params = [];

        if ($sector_programa) {
            $sql .= " AND LOWER(sector_programa) LIKE ?";
            $params[] = '%' . strtolower($sector_programa) . '%';
        }

        if ($ocupacion) {
            $sql .= " AND LOWER(nombre_ocupacion) LIKE ?";
            $params[] = '%' . strtolower($ocupacion) . '%';
        }

        if ($nivel) {
            $sql .= " AND nivel_formacion LIKE ?";
            $params[] = "%$nivel%";
        }

        // Filtro por sector económico mapeado
        $sectoresRelacionados = $this->mapearSectorEconomico($sector_economico);
        if ($sectoresRelacionados) {
            $placeholders = implode(',', array_fill(0, count($sectoresRelacionados), '?'));
            $sql .= " AND sector_economico IN ($placeholders)";
            $params = array_merge($params, $sectoresRelacionados);
        } elseif ($sector_economico) {
            $sql .= " AND sector_economico = ?";
            $params[] = $sector_economico;
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Logs útiles
        error_log("SQL: $sql", 3, __DIR__ . "/mi_log_personal.log");
        error_log("Params: " . print_r($params, true), 3, __DIR__ . "/mi_log_personal.log");
        error_log("Respuestas recibidas: " . print_r($respuestas, true), 3, __DIR__ . "/mi_log_personal.log");
        error_log("Recomendaciones encontradas: " . print_r($resultados, true), 3, __DIR__ . "/mi_log_personal.log");

        return $resultados;
    }

    // ✅ Obtener recomendaciones guardadas por empresa
    public function obtenerRecomendaciones($empresaId = null) {
        if (!$empresaId) return [];

        try {
            $conn = Conexion::conectar();
            $query = "SELECT resultado FROM diagnosticos_empresariales WHERE empresa_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$empresaId]);

            $diagnostico = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($diagnostico) {
                $respuestas = json_decode($diagnostico['resultado'], true);
                $recomendaciones = $this->generarRecomendaciones($respuestas);

                error_log("Respuestas desde diagnostico guardado: " . print_r($respuestas, true), 3, __DIR__ . "/mi_log_personal.log");
                error_log("Recomendaciones desde diagnostico guardado: " . print_r($recomendaciones, true), 3, __DIR__ . "/mi_log_personal.log");

                return $recomendaciones;
            }

            return [];
        } catch (PDOException $e) {
            error_log("Error en obtenerRecomendaciones: " . $e->getMessage());
            return [];
        }
    }

    // ✅ Actualizar enunciados, insertar y eliminar preguntas/opciones
    public function actualizarDiagnosticoCompleto($datos) {
        try {
            $modelo = new Diagnostico();

            if (isset($datos['preguntas'])) {
                foreach ($datos['preguntas'] as $id => $enunciado) {
                    $modelo->actualizarPregunta($id, $enunciado);
                }
            }

            if (isset($datos['preguntas_nuevas']) && isset($datos['nuevas_opciones'])) {
                foreach ($datos['preguntas_nuevas'] as $idx => $enunciado) {
                    $nuevaPreguntaId = $modelo->insertarPregunta($enunciado);
                    if ($nuevaPreguntaId && !empty($datos['nuevas_opciones'][$idx])) {
                        foreach ($datos['nuevas_opciones'][$idx] as $texto) {
                            $modelo->insertarOpcion($nuevaPreguntaId, $texto);
                        }
                    }
                }
            }

            if (isset($datos['nuevas_opciones_existentes'])) {
                foreach ($datos['nuevas_opciones_existentes'] as $idPregunta => $opciones) {
                    foreach ($opciones as $texto) {
                        $modelo->insertarOpcion($idPregunta, $texto);
                    }
                }
            }

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

//// 🛠️ Solicitudes HTTP POST
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
                if (isset($data['respuestas'])) {
                    echo json_encode($controller->procesarRespuestas($data['empresaId'] ?? null, $data['respuestas']));
                } else {
                    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                }
                break;

            case 'obtenerRecomendaciones':
                echo json_encode($controller->obtenerRecomendaciones($data['empresaId'] ?? null));
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
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no especificada']);
    }
}
?>
