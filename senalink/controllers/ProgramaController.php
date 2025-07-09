<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../models/Programa.php';
require_once '../config/Conexion.php';

$programa = new ProgramaFormacion();


// 🚀 CREAR PROGRAMA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    // Recolectar datos
    $codigo              = $_POST['codigo'] ?? null;
    $ficha               = $_POST['ficha'] ?? null;
    $nivel_formacion     = $_POST['nivel_formacion'] ?? null;
    $sector_programa     = $_POST['sector_programa'] ?? null;
    $etapa_ficha        = $_POST['etapa_ficha'] ?? null;
    $sector_economico   = $_POST['sector_economico'] ?? null;
    $duracion_programa      = $_POST['duracion_programa'] ?? null;
    $nombre_ocupacion    = $_POST['nombre_ocupacion'] ?? null;
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $estado              = $_POST['estado'] ?? 'En ejecucion';
    $fecha_finalizacion  = $_POST['fecha_finalizacion'] ?? null;

    // Validaciones
    $errores = [];

    if (!is_numeric($codigo) || intval($codigo) <= 0) $errores[] = "El código debe ser numérico y positivo.";
    if (!is_numeric($ficha) || intval($ficha) <= 0) $errores[] = "La ficha debe ser numérica y positiva.";
    if (!is_numeric($duracion_programa) || intval($duracion_programa) <= 0) $errores[] = "La duración debe ser un número positivo.";

    if (!preg_match('/^[\p{L}\s.]+$/u', $nombre_programa)) $errores[] = "Nombre inválido.";

    $fechaMinima = '1957-06-21';
    if (!$fecha_finalizacion || strtotime($fecha_finalizacion) < strtotime($fechaMinima)) {
        $errores[] = "Fecha de finalización inválida.";
    }

    if (!$codigo || !$ficha || !$nivel_formacion || !$sector_programa || !$nombre_programa || !$etapa_ficha || !$sector_economico ||
        !$duracion_programa || !$estado  || !$fecha_finalizacion) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    // Si hay errores
    if (!empty($errores)) {
        echo "⚠️ Errores encontrados:<br>" . implode("<br>", $errores);
        exit;
    }

    // Intentar crear
    $resultado = $programa->crear([
        'codigo' => $codigo,
        'ficha' => $ficha,
        'nivel_formacion' => $nivel_formacion,
        'etapa_ficha' => $etapa_ficha,
        'sector_economico' => $sector_economico,
        'sector_programa' => $sector_programa,
        'nombre_programa' => $nombre_programa,
        'nombre_ocupacion' => $nombre_ocupacion,
        'duracion_programa' => $duracion_programa,
        'estado' => $estado,
        'fecha_finalizacion' => $fecha_finalizacion
    ]);

    if ($resultado) {
        header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
    } else {
        echo "❌ Error al guardar el programa.";
    }
    exit;
}

// ✏️ ACTUALIZAR PROGRAMA
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = $_POST['id'] ?? null;
    $codigo              = $_POST['codigo'] ?? null;
    $ficha               = $_POST['ficha'] ?? null;
    $nivel_formacion     = $_POST['nivel_formacion'] ?? null;
    $etapa_ficha        = $_POST['etapa_ficha'] ?? null;
    $sector_programa     = $_POST['sector_programa'] ?? null;
    $sector_economico   = $_POST['sector_economico'] ?? null;
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $nombre_ocupacion    = $_POST['nombre_ocupacion'] ?? null;
    $duracion_programa      = $_POST['duracion_programa'] ?? null;
    $estado              = $_POST['estado'] ?? 'Disponible';
    $fecha_finalizacion  = $_POST['fecha_finalizacion'] ?? null;

    // Validaciones básicas
    if (!$id || !$codigo || !$ficha || !$nivel_formacion || !$sector_programa || !$nombre_programa ||
        !$duracion_meses || !$estado || !$fecha_finalizacion) {
        echo "⚠️ Todos los campos son obligatorios.";
        exit;
    }

    // Intentar actualizar
    $resultado = $programa->update($id, [
        'codigo' => $codigo,
        'ficha' => $ficha,
        'nivel_formacion' => $nivel_formacion,
        'sector_programa' => $sector_programa,
        'etapa_ficha' => $etapa_ficha,
        'sector_economico' => $sector_economico,
        'nombre_programa' => $nombre_programa,
        'nombre_ocupacion' => $nombre_ocupacion,
        'duracion_programa' => $duracion_programa,
        'estado' => $estado,
        'fecha_finalizacion' => $fecha_finalizacion
    ]);

    if ($resultado) {
        header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
    } else {
        echo "❌ Error al actualizar el programa.";
    }
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    try {
        switch ($_GET['action']) {
            case 'listarProgramasDisponibles':
                $data = $programa->listarProgramasDisponibles();
                echo json_encode($data);
                break;
            case 'listarProgramasEnEjecucion':
                header('Content-Type: application/json');
                try {
                    if (!method_exists($programa, 'listarProgramasEnEjecucion')) {
                        http_response_code(500);
                        echo json_encode(['error' => 'No existe el método listarProgramasEnEjecucion en ProgramaFormacion']);
                        exit;
                    }
                    $data = $programa->listarProgramasEnEjecucion();
                    if ($data === false) {
                        http_response_code(500);
                        echo json_encode(['error' => 'Error al obtener los programas en ejecución']);
                        exit;
                    }
                    echo json_encode($data);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Error interno: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                }
                break;
            case 'listarProgramasFinalizados':
                header('Content-Type: application/json');
                $data = $programa->listarProgramasFinalizados();
                echo json_encode($data);
                break;
            case 'DetallePrograma':
                if (!isset($_GET['id'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Falta el parámetro ID']);
                    exit;
                }
                $detalle = $programa->obtenerDetallePrograma($_GET['id']);
                echo json_encode($detalle ?: []);
                break;
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Acción no válida']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error interno: ' . $e->getMessage()]);
    }
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['accion']) && $data['accion'] === 'detallePrograma' && isset($data['id'])) {
        $programa = new ProgramaFormacion();
        $detalle = $programa->getById($data['id']);

        if ($detalle) {
            echo json_encode(['success' => true, 'programa' => $detalle]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Programa no encontrado.']);
        }
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Acción inválida o ID faltante.']);
    exit;
}
