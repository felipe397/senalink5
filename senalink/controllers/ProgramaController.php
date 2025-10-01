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
    $codigo              = $_POST['codigo'] ?? null;
    $ficha               = $_POST['ficha'] ?? null;
    $nivel_formacion     = $_POST['nivel_formacion'] ?? null;
    $sector_programa     = $_POST['sector_programa'] ?? null;
    $etapa_ficha         = $_POST['etapa_ficha'] ?? null;
    $sector_economico    = $_POST['sector_economico'] ?? null;
    $duracion_programa   = $_POST['duracion_programa'] ?? null;
    $nombre_ocupacion    = $_POST['nombre_ocupacion'] ?? null;
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $estado              = $_POST['estado'] ?? 'En ejecucion';
    $fecha_finalizacion  = date('Y-m-d', strtotime($_POST['fecha_finalizacion'] ?? ''));

    // Validaciones
    $errores = [];

    if (!is_numeric($codigo) || intval($codigo) <= 0) $errores[] = "El código debe ser numérico y positivo.";
    if (!is_numeric($ficha) || intval($ficha) <= 0) $errores[] = "La ficha debe ser numérica y positiva.";
    if (!is_numeric($duracion_programa) || intval($duracion_programa) <= 0) $errores[] = "La duración debe ser un número positivo.";

    if (!preg_match('/^[\p{L}\s.]+$/u', $nombre_programa)) $errores[] = "El nombre del programa contiene caracteres inválidos.";
    if (!preg_match('/^[\p{L}\s.]+$/u', $nombre_ocupacion)) $errores[] = "El nombre de la ocupación contiene caracteres inválidos.";

    $fechaMinima = '1957-06-21';
    if (!$fecha_finalizacion || strtotime($fecha_finalizacion) < strtotime($fechaMinima)) {
        $errores[] = "La fecha de finalización no puede ser anterior al 21 de junio de 1957.";
    }
    $campos_requeridos = [
        'codigo',
        'ficha',
        'nivel_formacion',
        'sector_programa',
        'etapa_ficha',
        'sector_economico',
        'duracion_programa',
        'estado',
        'fecha_finalizacion',
        'nombre_ocupacion',
        'nombre_programa'
    ];

    foreach ($campos_requeridos as $campo) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
            $errores[] = "El campo $campo es obligatorio.";
        }
    }


    if (!empty($errores)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errores]);
        exit;
    }

    // Check if ficha already exists
    if ($programa->existeFicha($ficha)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'No está permitido crear programas de formación si el número de ficha ya existe en otro programa.']);
        exit;
    }

    // Crear programa
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
        $rol = $_SESSION['rol'] ?? '';
        if ($rol === 'AdminSENA') {
            header("Location: ../html/AdminSENA/Programa_Formacion/Gestion_Programa.html");
        } else {
            header("Location: ../html/Super_Admin/Programa_Formacion/CreatePrograma.php?success=1");
        }
        exit;
    } else {
        header("Location: ../html/Super_Admin/Programa_Formacion/CreatePrograma.php?error=1");
        exit;
    }
}

// ✏️ ACTUALIZAR PROGRAMA
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = $_POST['id'] ?? null;
    $codigo              = $_POST['codigo'] ?? null;
    $ficha               = $_POST['ficha'] ?? null;
    $nivel_formacion     = $_POST['nivel_formacion'] ?? null;
    $etapa_ficha         = $_POST['etapa_ficha'] ?? null;
    $sector_programa     = $_POST['sector_programa'] ?? null;
    $sector_economico    = $_POST['sector_economico'] ?? null;
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $nombre_ocupacion    = $_POST['nombre_ocupacion'] ?? null;
    $duracion_programa   = $_POST['duracion_programa'] ?? null;
    $estado              = $_POST['estado'] ?? 'Disponible';
    $fecha_finalizacion  = $_POST['fecha_finalizacion'] ?? null;

    if (
        !$id || !$codigo || !$ficha || !$nivel_formacion || !$etapa_ficha || !$sector_programa || !$nombre_programa ||
        !$duracion_programa || !$estado || !$fecha_finalizacion
    ) {
        echo "⚠️ Todos los campos son obligatorios.";
        exit;
    }

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
        $rol = $_SESSION['rol'] ?? '';
        switch ($rol) {
            case 'super_admin':
                header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
                break;
            case 'AdminSENA':
                header("Location: ../html/AdminSENA/Programa_Formacion/Gestion_Programa.html");
                break;
            default:
                header("Location: ../html/index.html");
                break;
        }
    } else {
        echo "❌ Error al actualizar el programa.";
    }
    exit;
}

// 📄 CONSULTAS GET
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    try {
        switch ($_GET['action']) {
            case 'listarProgramasDisponibles':
                echo json_encode($programa->listarProgramasDisponibles());
                break;
            case 'listarProgramasEnEjecucion':
                if (!method_exists($programa, 'listarProgramasEnEjecucion')) {
                    http_response_code(500);
                    echo json_encode(['error' => 'No existe el método listarProgramasEnEjecucion']);
                    break;
                }
                $data = $programa->listarProgramasEnEjecucion();
                echo json_encode($data ?: []);
                break;
            case 'listarProgramasFinalizados':
                echo json_encode($programa->listarProgramasFinalizados());
                break;
            case 'DetallePrograma':
                if (!isset($_GET['id'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Falta el parámetro ID']);
                    break;
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

// 🧪 PETICIONES POST con JSON (AJAX)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['accion']) && $data['accion'] === 'detallePrograma' && isset($data['id'])) {
        $detalle = $programa->getById($data['id']);
        echo json_encode([
            'success' => (bool)$detalle,
            'programa' => $detalle ?? null,
            'message' => $detalle ? null : 'Programa no encontrado.'
        ]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Acción inválida o ID faltante.']);
    exit;
}
