<?php
error_log("DiagnosticoController.php llamado");
require_once __DIR__ . '/../config/db.php';

require_once __DIR__ . '/../models/Pregunta.php';
require_once __DIR__ . '/../models/Opcion.php';


require_once __DIR__ . '/../models/PreguntaOpcion.php';

class DiagnosticoController {
    private $pdo;
    private $preguntaModel;
    private $opcionModel;
    private $preguntaOpcionModel;

    // Método público para exponer preguntas con opciones
    public function getPreguntasConOpciones() {
        return $this->preguntaOpcionModel->obtenerPreguntasConOpciones();
    }

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->preguntaModel = new Pregunta();
        $this->opcionModel = new Opcion();
        $this->preguntaOpcionModel = new PreguntaOpcion();
    }

    public function actualizarDiagnosticoCompleto($datos) {
        try {
            $this->pdo->beginTransaction();

            // Actualizar enunciados de preguntas
            foreach ($datos['preguntas'] as $idPregunta => $enunciado) {
                $this->preguntaModel->actualizarEnunciado($idPregunta, $enunciado);
            }

            // Insertar nuevas opciones
            if (isset($datos['nuevas_opciones'])) {
                foreach ($datos['nuevas_opciones'] as $idPregunta => $opciones) {
                    foreach ($opciones as $textoOpcion) {
                        $this->opcionModel->insertarOpcion($idPregunta, $textoOpcion);
                    }
                }
            }

            // Borrar opciones eliminadas (tanto nuevas como existentes)
            if (isset($datos['opciones_a_borrar'])) {
                foreach ($datos['opciones_a_borrar'] as $idOpcion) {
                    $this->opcionModel->borrarOpcion($idOpcion);
                }
            }

            $this->pdo->commit();
            return ['success' => true, 'message' => 'Diagnóstico actualizado correctamente.'];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => 'Error al actualizar diagnóstico: ' . $e->getMessage()];
        }

    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['accion'])) {
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        exit;
    }

    $controller = new DiagnosticoController();

    if ($input['accion'] === 'actualizarDiagnosticoCompleto') {
        $resultado = $controller->actualizarDiagnosticoCompleto($input);
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }

if ($input['accion'] === 'obtenerDiagnosticoCompleto') {
        // Acceso correcto a la propiedad pública
        $preguntas = $controller->getPreguntasConOpciones();
        // Log temporal para depuración
        error_log("Preguntas obtenidas: " . print_r($preguntas, true));
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'preguntas' => $preguntas]);
        exit;
    }
    // (El método ya está definido dentro de la clase, no debe repetirse aquí)
}

