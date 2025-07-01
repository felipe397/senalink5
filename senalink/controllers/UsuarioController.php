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
    // Obtener datos comunes
    $correo         = $_POST['correo'] ?? '';
    $contrasena     = $_POST['contrasena'] ?? '';
    $rol            = $_POST['rol'] ?? '';
    $estado         = 'Activo';
    $fecha_creacion = date('Y-m-d H:i:s');

    // Validar correo y contraseña
    if (empty($correo) || empty($contrasena) || empty($rol)) {
        header('Location: ../html/index.html?response=error');
        exit;
    }

    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    // Construir datos según el rol
    $datos = [
        'correo'         => $correo,
        'contrasena'     => $hashedPassword,
        'rol'            => $rol,
        'estado'         => $estado,
        'fecha_creacion' => $fecha_creacion
    ];

    // Campos adicionales para AdminSENA
    if ($rol === 'AdminSENA') {
        $datos['telefono']         = $_POST['telefono'] ?? '';
        $datos['primer_nombre']    = $_POST['primer_nombre'] ?? '';
        $datos['segundo_nombre']   = $_POST['segundo_nombre'] ?? '';
        $datos['primer_apellido']  = $_POST['primer_apellido'] ?? '';
        $datos['segundo_apellido'] = $_POST['segundo_apellido'] ?? '';
        $datos['numero_documento'] = $_POST['numero_documento'] ?? '';
        $datos['tipo_documento']   = $_POST['tipo_documento'] ?? '';

        // Validar campos requeridos para AdminSENA
        $camposRequeridos = ['telefono','primer_nombre','primer_apellido','numero_documento','tipo_documento'];
        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo])) {
                header('Location: ../html/index.html?response=error');
                exit;
            }
        }
    }
    // Campos adicionales para empresa
    else if ($rol === 'empresa') {
        $datos['nit']                = $_POST['nit'] ?? '';
        $datos['direccion']          = $_POST['direccion'] ?? '';
        $datos['razon_social']       = $_POST['razon_social'] ?? '';
        $datos['telefono']           = $_POST['telefono'] ?? '';
        $datos['representante_legal']= $_POST['representante_legal'] ?? '';
        $datos['tipo_empresa']       = $_POST['tipo_empresa'] ?? '';

        // Validar campos requeridos para empresa
        $camposRequeridos = ['nit','direccion','razon_social','telefono','representante_legal','tipo_empresa'];
        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo])) {
                header('Location: ../html/index.html?response=error');
                exit;
            }
        }
    } else {
        // Otros roles: agregar campos genéricos si existen
        $datos['direccion']         = $_POST['direccion'] ?? '';
        $datos['telefono']          = $_POST['telefono'] ?? '';
        $datos['primer_nombre']     = $_POST['primer_nombre'] ?? '';
        $datos['segundo_nombre']    = $_POST['segundo_nombre'] ?? '';
        $datos['primer_apellido']   = $_POST['primer_apellido'] ?? '';
        $datos['segundo_apellido']  = $_POST['segundo_apellido'] ?? '';
        $datos['numero_documento']  = $_POST['numero_documento'] ?? '';
        $datos['tipo_documento']    = $_POST['tipo_documento'] ?? '';
    }

    // Intentar crear el usuario y redirigir según resultado
    try {
        $resultado = UsuarioModel::crear($datos);
        if ($resultado) {
            header('Location: ../html/index.html?response=ok');
            exit;
        } else {
            header('Location: ../html/index.html?response=error');
            exit;
        }
    } catch (Exception $e) {
        header('Location: ../html/index.html?response=error');
        exit;
    }
}


