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
    header('Content-Type: application/json');  // Siempre JSON para compatibilidad con AJAX

    // Log de datos recibidos para depuración
    error_log("=== INICIO CREACIÓN PROGRAMA ===");
    error_log("POST['ficha'] recibido: '" . ($_POST['ficha'] ?? 'NULL') . "'");

    // NUEVO: Validación estricta inicial para detectar corrupción (ej. HTML enviado como datos)
    $fichaRaw = $_POST['ficha'] ?? '';
    if (empty($fichaRaw) || !preg_match('/^\d{3,7}$/', $fichaRaw) || strlen($fichaRaw) > 10) {
        error_log("Corrupción detectada en ficha: '" . substr($fichaRaw, 0, 100) . "'");
        echo json_encode(['success' => false, 'error' => 'Datos del formulario inválidos o corruptos. Verifica y recarga la página.']);
        exit;
    }

    // Asignación de variables con trim para limpiar espacios
    $codigo              = trim($_POST['codigo'] ?? '');
    $ficha               = trim($_POST['ficha'] ?? '');
    $nivel_formacion     = $_POST['nivel_formacion'] ?? '';
    $sector_programa     = $_POST['sector_programa'] ?? '';
    // Force etapa_ficha to LECTIVA on creation
    $etapa_ficha         = 'LECTIVA';
    $sector_economico    = $_POST['sector_economico'] ?? '';
    $duracion_programa   = $_POST['duracion_programa'] ?? '';
    $nombre_ocupacion    = trim($_POST['nombre_ocupacion'] ?? '');
    $nombre_programa     = trim($_POST['nombre_programa'] ?? '');
    $estado              = $_POST['estado'] ?? 'En ejecucion';
    $fecha_finalizacion  = date('Y-m-d', strtotime($_POST['fecha_finalizacion'] ?? ''));

    // Validaciones mejoradas
    $errores = [];

    if (!preg_match('/^\d{3,}$/', $codigo) || intval($codigo) <= 0) {
        $errores[] = "El código debe ser numérico, positivo y contener al menos 3 dígitos.";
    }
    if (!preg_match('/^\d{3,7}$/', $ficha) || intval($ficha) <= 0) {
        $errores[] = "La ficha debe ser numérica, positiva y contener entre 3 y 7 dígitos.";
    }
    if (!is_numeric($duracion_programa) || intval($duracion_programa) <= 0) {
        $errores[] = "La duración debe ser un número positivo.";
    }
    if (!preg_match('/^[\p{L}\s.]+$/u', $nombre_programa)) {
        $errores[] = "El nombre del programa contiene caracteres inválidos.";
    }
    if (!preg_match('/^[\p{L}\s.]+$/u', $nombre_ocupacion)) {
        $errores[] = "El nombre de la ocupación contiene caracteres inválidos.";
    }

    // Validación de fecha
    $fechaMinima = '1957-06-21';
    if (!$fecha_finalizacion || strtotime($fecha_finalizacion) < strtotime($fechaMinima)) {
        $errores[] = "La fecha de finalización no puede ser anterior al 21 de junio de 1957.";
    }

    // Validación de campos requeridos (simplificada, ya que validamos arriba con regex)
    $campos_requeridos = [
        'nivel_formacion',
        'sector_programa',
        'sector_economico',
        'estado'
    ];
    foreach ($campos_requeridos as $campo) {
        if (empty($_POST[$campo])) {
            $errores[] = "El campo $campo es obligatorio.";
        }
    }

    if (!empty($errores)) {
        error_log("Errores de validación: " . implode(', ', $errores));
        echo json_encode(['success' => false, 'errors' => $errores]);
        exit;
    }

    // Prevent creation if etapa_ficha is PRACTICA (solo una vez, removí duplicado)
    if (isset($_POST['etapa_ficha']) && strtoupper($_POST['etapa_ficha']) === 'PRACTICA') {
        echo json_encode(['success' => false, 'error' => 'No está permitido crear programas en etapa PRACTICA.']);
        exit;
    }

    // Check if ficha already exists
    $fichaInt = intval($ficha);
    error_log("Checking ficha existence for: " . $fichaInt);
    $existeFicha = $programa->existeFicha($fichaInt);
    error_log("existeFicha result: " . ($existeFicha ? "true" : "false"));
    if ($existeFicha) {
        echo json_encode(['success' => false, 'error' => 'No está permitido crear programas de formación si el número de ficha ya existe en otro programa.']);
        exit;
    }

    // Crear programa
    try {
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
        error_log("Resultado de creación: " . ($resultado ? "true" : "false"));
    } catch (PDOException $e) {
        error_log("PDOException en creación: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
        exit;
    }

    if ($resultado) {
        // Devuelve JSON de éxito (el frontend redirigirá)
        $rol = $_SESSION['rol'] ?? '';
        $redirectUrl = ($rol === 'AdminSENA') 
            ? '../html/AdminSENA/Programa_Formacion/Gestion_Programa.html'
            : '../html/Super_Admin/Programa_Formacion/Gestion_Programa.html';  // Ajusta si es necesario
        echo json_encode(['success' => true, 'redirect' => $redirectUrl, 'message' => 'Programa creado correctamente']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al crear el programa. Verifica los datos.']);
    }
    error_log("=== FIN CREACIÓN PROGRAMA ===");
    exit;
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
    $estado              = $_POST['estado'] ?? 'En Ejecucion';
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
?>
