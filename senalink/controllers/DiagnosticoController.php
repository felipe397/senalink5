<?php
require_once __DIR__ . '/../config/db.php';

require_once __DIR__ . '/../models/Pregunta.php';
require_once __DIR__ . '/../models/Opcion.php';

class DiagnosticoController {
    private $pdo;
    private $preguntaModel;
    private $opcionModel;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->preguntaModel = new Pregunta();
        $this->opcionModel = new Opcion();
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizarDiagnosticoCompleto') {
    // Log para debug
    error_log("Llega petición actualizarDiagnosticoCompleto");
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        error_log("Error al decodificar JSON: " . file_get_contents('php://input'));
    } else {
        error_log("Datos recibidos: " . print_r($input, true));
    }
    $controller = new DiagnosticoController();
    $resultado = $controller->actualizarDiagnosticoCompleto($input);
    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}
