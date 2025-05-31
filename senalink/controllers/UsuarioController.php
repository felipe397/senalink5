<?php
require_once '../models/UsuarioModel.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $correo         = $_POST['correo'] ?? '';
    $contrasena     = $_POST['contrasena'] ?? '';
    $rol            = $_POST['rol'] ?? '';
    $nombres        = $_POST['nombres'] ?? '';
    $apellidos      = $_POST['apellidos'] ?? '';
    $estado         = 'Activo';
    $fecha_creacion = date('Y-m-d H:i:s');


    // Solo para rol Empresas
    $nit                 = $_POST['nit'] ?? '';
    $representante_legal = $_POST['representante_legal'] ?? '';
    $tipo_empresa        = $_POST['tipo_empresa'] ?? '';
    $direccion           = $_POST['direccion'] ?? '';
    $razon_social        = $_POST['razon_social'] ?? '';
    $telefono            = $_POST['telefono'] ?? '';

    // Validación básica
    $errores = [];

    if (!$correo || !$contrasena) {
        $errores[] = "Correo y contraseña son obligatorios.";
    }

    if ($rol === 'super_admin' || $rol === 'AdminSENA') {
        if (!$nombres || !$apellidos) {
            $errores[] = "Nombres y apellidos son obligatorios para este rol.";
        }
    } elseif ($rol === 'empresa') {
        if (!$nit || !$razon_social || !$telefono || !$direccion || !$tipo_empresa) {
            $errores[] = "Todos los campos de empresa son obligatorios.";
        }
    }

    if (!empty($errores)) {
        echo implode("<br>", $errores);
        exit;
    }

    // Validaciones únicas
    if (UsuarioModel::existeCorreo($correo)) {
        echo "Este correo ya está registrado.";
        exit;
    }

    if ($rol === 'empresa' && $nit && UsuarioModel::existeNIT($nit)) {
        echo "Este NIT ya está registrado.";
        exit;
    }
    // Hashear contraseña
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    // Crear el array de datos (sin nickname)
    if ($rol === 'empresa') {
    $datos = [
        'correo'              => $correo,
        'contrasena'          => $hashedPassword,
        'rol'                 => $rol,
        'estado'              => $estado,
        'fecha_creacion'      => $fecha_creacion,
        'nit'                 => $nit,
        'representante_legal' => $representante_legal,
        'tipo_empresa'        => $tipo_empresa,
        'direccion'           => $direccion,
        'razon_social'        => $razon_social,
        'telefono'            => $telefono
    ];
} else {
    $datos = [
        'correo'         => $correo,
        'contrasena'     => $hashedPassword,
        'rol'            => $rol,
        'estado'         => $estado,
        'fecha_creacion' => $fecha_creacion,
        'nombres'        => $nombres,
        'apellidos'      => $apellidos,
        'direccion'      => $direccion,
        'telefono'       => $telefono
    ];
}


    // Crear el usuario
    try {
        $resultado = UsuarioModel::crear($datos);

        if ($resultado) {
            switch ($rol) {
                case 'super_admin':
                    header('Location: ../html/index.html');
                    break;
                case 'AdminSENA':
                    header('Location: ../html/index.html');
                    break;
                case 'empresa':
                    header('Location: ../html/index.html');
                    break;
            }
            exit;
        } else {
            echo "Error al crear el usuario.";
            exit;
        }
    } catch (Exception $e) {
        echo "Error al procesar la solicitud: " . $e->getMessage();
        exit;
    }


}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'listarEmpresas') {
    $empresas = UsuarioModel::listarEmpresas();
    header('Content-Type: application/json');
    echo json_encode($empresas);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'detalleEmpresa' && isset($_GET['id'])) {
    $empresa = UsuarioModel::obtenerEmpresaPorId($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($empresa);
    exit;
}

?>
