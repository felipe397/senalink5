<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 0);
ini_set('log_errors', 1);
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
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $duracion_meses      = $_POST['duracion_meses'] ?? null;
    $estado              = $_POST['estado'] ?? 'Disponible';
    $descripcion         = $_POST['descripcion'] ?? null;
    $habilidades         = $_POST['habilidades_requeridas'] ?? null;
    $fecha_finalizacion  = $_POST['fecha_finalizacion'] ?? null;

    // Validaciones
    $errores = [];

    if (!is_numeric($codigo) || intval($codigo) <= 0) $errores[] = "El código debe ser numérico y positivo.";
    if (!is_numeric($ficha) || intval($ficha) <= 0) $errores[] = "La ficha debe ser numérica y positiva.";
    if (!is_numeric($duracion_meses) || intval($duracion_meses) <= 0) $errores[] = "La duración debe ser un número positivo.";

    if (!preg_match('/^[\p{L}\s.]+$/u', $nombre_programa)) $errores[] = "Nombre inválido.";
    if (!preg_match('/^[\p{L}\p{N}\s.,]+$/u', $descripcion)) $errores[] = "Descripción inválida.";
    if (!preg_match('/^[\p{L}\p{N}\s.,]+$/u', $habilidades)) $errores[] = "Habilidades inválidas.";

    $fechaMinima = '1957-06-21';
    if (!$fecha_finalizacion || strtotime($fecha_finalizacion) < strtotime($fechaMinima)) {
        $errores[] = "Fecha de finalización inválida.";
    }

    if (!$codigo || !$ficha || !$nivel_formacion || !$sector_programa || !$nombre_programa ||
        !$duracion_meses || !$estado || !$descripcion || !$habilidades || !$fecha_finalizacion) {
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
        'sector_programa' => $sector_programa,
        'nombre_programa' => $nombre_programa,
        'duracion_meses' => $duracion_meses,
        'estado' => $estado,
        'descripcion' => $descripcion,
        'habilidades_requeridas' => $habilidades,
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
    $sector_programa     = $_POST['sector_programa'] ?? null;
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $duracion_meses      = $_POST['duracion_meses'] ?? null;
    $estado              = $_POST['estado'] ?? 'Disponible';
    $descripcion         = $_POST['descripcion'] ?? null;
    $habilidades         = $_POST['habilidades_requeridas'] ?? null;
    $fecha_finalizacion  = $_POST['fecha_finalizacion'] ?? null;

    // Validaciones básicas
    if (!$id || !$codigo || !$ficha || !$nivel_formacion || !$sector_programa || !$nombre_programa ||
        !$duracion_meses || !$estado || !$descripcion || !$habilidades || !$fecha_finalizacion) {
        echo "⚠️ Todos los campos son obligatorios.";
        exit;
    }

    // Intentar actualizar
    $resultado = $programa->update($id, [
        'codigo' => $codigo,
        'ficha' => $ficha,
        'nivel_formacion' => $nivel_formacion,
        'sector_programa' => $sector_programa,
        'nombre_programa' => $nombre_programa,
        'duracion_meses' => $duracion_meses,
        'estado' => $estado,
        'descripcion' => $descripcion,
        'habilidades_requeridas' => $habilidades,
        'fecha_finalizacion' => $fecha_finalizacion
    ]);

    if ($resultado) {
        header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
    } else {
        echo "❌ Error al actualizar el programa.";
    }
    exit;
}

// 📃 LISTAR PROGRAMAS
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listarProgramasActivos') {
    try {
        $programas = $programa->listarProgramasActivos();
        header('Content-Type: application/json');
        echo json_encode($programas);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
    }
    exit;
}
// GET action listarEmpresasInhabilitadas
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listarProgramasInhabilitados') {
    try {
        $programas = $programa->listarProgramasInhabilitados();
        header('Content-Type: application/json');
        echo json_encode($programas);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
    }
    exit;
}
// 🔍 DETALLE DE UN PROGRAMA
else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'DetallePrograma' && isset($_GET['id'])) {
    error_log("DEBUG: Recibiendo DetallePrograma con ID: " . $_GET['id']);
    $detalle = ProgramaFormacion::obtenerProgramaporid($_GET['id']);
    error_log("DEBUG: Resultado de obtenerProgramaporid: " . print_r($detalle, true));
    header('Content-Type: application/json');
    if (!$detalle || empty($detalle)) {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontró el programa de formación con el ID proporcionado.']);
    } else {
        echo json_encode($detalle);
    }
    exit;
}
