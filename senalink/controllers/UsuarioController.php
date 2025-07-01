<?php
// Al principio del archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
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
    file_put_contents('debug.log', json_encode($_POST));

    // Obtener datos comunes
    $correo            = $_POST['correo'] ?? '';
    $contrasena        = $_POST['contrasena'] ?? '';
    $rol               = $_POST['rol'] ?? '';
    $estado            = 'Activo';
    $fecha_creacion    = date('Y-m-d H:i:s');

    // Validar que el correo no esté vacío
    if (empty($correo)) {
        echo "El campo correo es obligatorio.";
        exit;
    }

    // Hashear contraseña
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    $datos = [
        'correo'         => $correo,
        'contrasena'     => $hashedPassword,
        'rol'            => $rol,
        'estado'         => $estado,
        'fecha_creacion' => $fecha_creacion
    ];

    if ($rol === 'empresa') {
        // Campos específicos de empresa
        $datos['nit']                = $_POST['nit'] ?? '';
        $datos['direccion']         = $_POST['direccion'] ?? '';
        $datos['razon_social']      = $_POST['razon_social'] ?? '';
        $datos['telefono']          = $_POST['telefono'] ?? '';
        $datos['representante_legal'] = $_POST['representante_legal'] ?? '';
        $datos['tipo_empresa']      = $_POST['tipo_empresa'] ?? '';
    } elseif ($rol === 'AdminSENA') {
        // Campos específicos de AdminSENA
        $datos['telefono']          = $_POST['telefono'] ?? '';
        $datos['primer_nombre']     = $_POST['primer_nombre'] ?? '';
        $datos['segundo_nombre']    = $_POST['segundo_nombre'] ?? '';
        $datos['primer_apellido']   = $_POST['primer_apellido'] ?? '';
        $datos['segundo_apellido']  = $_POST['segundo_apellido'] ?? '';
        $datos['numero_documento']  = $_POST['numero_documento'] ?? '';
        $datos['tipo_documento']    = $_POST['tipo_documento'] ?? '';
    } else {
        // Otros roles
        $datos['direccion']         = $_POST['direccion'] ?? '';
        $datos['telefono']          = $_POST['telefono'] ?? '';
        $datos['primer_nombre']     = $_POST['primer_nombre'] ?? '';
        $datos['segundo_nombre']    = $_POST['segundo_nombre'] ?? '';
        $datos['primer_apellido']   = $_POST['primer_apellido'] ?? '';
        $datos['segundo_apellido']  = $_POST['segundo_apellido'] ?? '';
        $datos['numero_documento']  = $_POST['numero_documento'] ?? '';
        $datos['tipo_documento']    = $_POST['tipo_documento'] ?? '';
    }

    try {
        $resultado = UsuarioModel::crear($datos);
        if ($resultado) {
            header('Location: ../html/index.html');
            exit;
        } else {
            echo "Error al crear el usuario.";
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}






