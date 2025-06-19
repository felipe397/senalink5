<?php
require_once '../models/Programa.php'; // Asegúrate que esta ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger datos del formulario
    $codigo              = $_POST['codigo'] ?? null;
    $ficha               = $_POST['ficha'] ?? null;
    $nivel_formacion     = $_POST['nivel_formacion'] ?? null;
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $duracion_meses      = $_POST['duracion_meses'] ?? null;
    $estado              = $_POST['estado'] ?? 'Disponible'; // Valor por defecto
    $descripcion         = $_POST['descripcion'] ?? null;
    $habilidades_requeridas = $_POST['habilidades_requeridas'] ?? null;
    $fecha_finalizacion  = $_POST['fecha_finalizacion'] ?? null;

    // Inicializar array de errores
    $errores = [];

    // Validaciones numéricas positivas
    if (!is_numeric($codigo) || intval($codigo) <= 0) {
        $errores[] = "El código del programa debe ser un número positivo.";
    }

    if (!is_numeric($ficha) || intval($ficha) <= 0) {
        $errores[] = "La ficha debe ser un número positivo.";
    }

    if (!is_numeric($duracion_meses) || intval($duracion_meses) <= 0) {
        $errores[] = "La duración debe ser un número positivo en meses.";
    }

    // Validar nombre del programa (texto, espacios, puntos)
    if (!preg_match('/^[\p{L}\s.]+$/u', $nombre_programa)) {
        $errores[] = "El nombre del programa solo puede contener letras, espacios y puntos.";
    }

    // Validar descripción (texto, espacios, comas, puntos)
    if (!preg_match('/^[\p{L}\p{N}\s.,]+$/u', $descripcion)) {
        $errores[] = "La descripción solo puede contener letras, números, espacios, comas y puntos.";
    }

    // Validar habilidades requeridas (texto, espacios, comas, puntos)
    if (!preg_match('/^[\p{L}\p{N}\s.,]+$/u', $habilidades_requeridas)) {
        $errores[] = "Las habilidades requeridas solo pueden contener letras, números, espacios, comas y puntos.";
    }

    // Validar fecha mínima
    $fechaMinima = '1957-06-21';
    if (!$fecha_finalizacion || strtotime($fecha_finalizacion) < strtotime($fechaMinima)) {
        $errores[] = "La fecha de finalización no puede ser anterior al 21 de junio de 1957.";
    }

    // Validar campos obligatorios básicos
    if (
        !$codigo || !$ficha || !$nivel_formacion || !$nombre_programa ||
        !$duracion_meses || !$estado || !$descripcion || !$habilidades_requeridas || !$fecha_finalizacion
    ) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    // Mostrar errores si existen
    if (!empty($errores)) {
        echo "⚠️ Se encontraron los siguientes errores:<br>" . implode("<br>", $errores);
        exit;
    }

    // Si pasa todas las validaciones:
    require_once '../config/Conexion.php';
    $programa = new ProgramaFormacion();

    $resultado = $programa->crear([
        'codigo'                 => $codigo,
        'ficha'                  => $ficha,
        'nivel_formacion'        => $nivel_formacion,
        'nombre_programa'        => $nombre_programa,
        'duracion_meses'         => $duracion_meses,
        'estado'                 => $estado,
        'descripcion'            => $descripcion,
        'habilidades_requeridas' => $habilidades_requeridas,
        'fecha_finalizacion'     => $fecha_finalizacion
    ]);

    if ($resultado) {
        header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
        exit();
    } else {
        echo "❌ Error al guardar el programa.";
    }
}

// Obtener detalle de programa
elseif ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === 'detallePrograma' && isset($_GET['id'])) {
    require_once '../config/Conexion.php';
    $programa = new ProgramaFormacion();
    $id = $_GET['id'];
    $data = $programa->getById($id);
    if ($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Programa no encontrado']);
    }
} else {
    echo "⛔ Método no permitido.";
}