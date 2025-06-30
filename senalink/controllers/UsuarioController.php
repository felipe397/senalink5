<?php
// Al principio del archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once '../models/UsuarioModel.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['accion']) && $_POST['accion'] === 'actualizarEstado' &&
    isset($_POST['id']) && isset($_POST['estado'])) {

    $id = $_POST['id'];

    // Normalizar estado a primera letra mayúscula
    $nuevoEstado = ucfirst(strtolower(trim($_POST['estado'])));

    // Validar estados permitidos
    if (!in_array($nuevoEstado, ['Activo', 'Desactivado'])) {
        echo "Estado no válido.";
        exit;
    }

    $resultado = UsuarioModel::actualizarEstado($id, $nuevoEstado);

    echo $resultado ? "Estado actualizado correctamente." : "Error al actualizar estado.";
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crearUsuario') {
    // Obtener datos del formulario
    $correo         = $_POST['correo'] ?? '';
    $contrasena     = $_POST['contrasena'] ?? '';
    $rol            = $_POST['rol'] ?? '';
    $primer_nombre        = $_POST['primer_nombre'] ?? '';
    $segundo_nombre      = $_POST['segundo_nombre'] ?? '';
    $primer_apellido        = $_POST['primer_apellido'] ?? '';
    $segundo_apellido      = $_POST['segundo_apellido'] ?? '';
    $numero_documento         = $_POST['numero_documento'] ?? '';
    $tipo_documento     = $_POST['tipo_documento'] ?? '';
    $estado         = 'Activo';
    $fecha_creacion = date('Y-m-d H:i:s');

    // Validar correo no vacío
    if (empty($correo)) {
        echo "El campo correo es obligatorio.";
        exit;
    }

    // Validación básica
    $errores = [];

    // Hashear contraseña
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    // Crear el array de datos
    if ($rol === 'AdminSENA') {
    $datos = [
        'correo'              => $correo,
        'contrasena'          => $hashedPassword,
        'rol'                 => $rol,
        'estado'              => $estado,
        'fecha_creacion'      => $fecha_creacion,
        'telefono'      => $telefono,
        'primer_nombre'        => $primer_nombre,
        'segundo_nombre'      => $segundo_nombre,
        'primer_apellido'        => $primer_apellido,
        'segundo_apellido'      => $segundo_apellido,
        'numero_documento'      => $numero_documento,
        'tipo_documento'       => $tipo_documento
    ];
} else {
    $datos = [
        'correo'         => $correo,
        'contrasena'     => $hashedPassword,
        'rol'            => $rol,
        'estado'         => $estado,
        'fecha_creacion' => $fecha_creacion,
        'direccion'      => $direccion,
        'telefono'       => $telefono,
        'primer_nombre'        => $primer_nombre,
        'segundo_nombre'      => $segundo_nombre,
        'primer_apellido'        => $primer_apellido,
        'segundo_apellido'      => $segundo_apellido,
        'numero_documento'      => $numero_documento,
        'tipo_documento'       => $tipo_documento
    ];
}


    // Crear el usuario
if ($resultado) {
    header('Location: ../html/index.html');
    exit;
} else {
    echo "Error al crear el usuario.";
    exit;
}


    }


