<?php
// Al principio del archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once '../models/UsuarioModel.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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


    // Solo para rol Empresas
    $nit                 = $_POST['nit'] ?? '';
    $representante_legal = $_POST['representante_legal'] ?? '';
    $tipo_empresa        = $_POST['tipo_empresa'] ?? '';
    $direccion           = $_POST['direccion'] ?? '';
    $razon_social        = $_POST['razon_social'] ?? '';
    $telefono            = $_POST['telefono'] ?? '';

    // Validación básica
    $errores = [];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "El correo no tiene un formato válido.";
    exit;
}

    if (!$correo || !$contrasena) {
        $errores[] = "Correo y contraseña son obligatorios.";
    }

    if ($rol === 'super_admin' || $rol === 'AdminSENA') {
        if (!$primer_nombre || !$primer_apellido) {
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
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "El correo no tiene un formato válido.";
        exit;
    }

    // Validaciones únicas
    if (UsuarioModel::existeCorreo($correo)) {
        echo "Este correo ya está registrado.";
        exit;
    }

    if ($rol === 'empresa' && (!is_numeric($nit) || intval($nit) <= 0)) {
        echo "El NIT debe ser un número positivo.";
        exit;
    }

    if ($rol === 'empresa' && $nit && UsuarioModel::existeNIT($nit)) {
        echo "Este NIT ya está registrado.";
        exit;
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $contrasena)) {
    echo "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un caracter especial.";
    exit;
    }
    
    if ($rol === 'empresa' && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $representante_legal)) {
        echo "El nombre del representante legal solo debe contener letras.";
        exit;
    }

    if ($rol === 'empresa' && !preg_match('/^[\p{L}\p{N}\s,.\']+$/u', $razon_social)) {
        echo "La razón social contiene caracteres no válidos.";
        exit;
    }

    if ($rol === 'empresa' && !preg_match('/^[\p{L}\p{N}\s,.\']+$/u', $tipo_empresa)) {
        echo "El tipo de empresa contiene caracteres no válidos.";
        exit;
    }

    if ($rol === 'empresa' && !preg_match('/^[\p{L}\p{N}\s,.\'#-]+$/u', $direccion)) {
        echo "La dirección contiene caracteres no válidos.";
        exit;
    }

    // Hashear contraseña
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    // Crear el array de datos
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
} else if ($rol === 'AdminSENA') {
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'detalleUsuario') {
    $id = $_SESSION['id_usuario'] ?? null;


    if (!$id) {
        echo json_encode(['error' => 'No autorizado']);
        exit;
    }

    $usuario = UsuarioModel::obtenerUsuarioPorId($id);

    if ($usuario) {
        echo json_encode($usuario);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }

    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'detalleEmpresa' && isset($_GET['id'])) {
    $empresa = UsuarioModel::obtenerEmpresaPorId($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($empresa);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'listarPrograma') {
    $programas = UsuarioModel::listarPrograma();
    header('Content-Type: application/json');
    echo json_encode($programas); 
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'DetallePrograma' && isset($_GET['id'])) {
    $programa = UsuarioModel::obtenerProgramaporid($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($programa);
    exit;
}
?>