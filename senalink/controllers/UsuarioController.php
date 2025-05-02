<?php
require_once '../models/UsuarioModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $correo         = $_POST['correo'] ?? '';
    $contrasena     = $_POST['contrasena'] ?? '';
    $rol            = $_POST['rol'] ?? 'Empresa';
    $estado         = 'Activo';
    $fecha_creacion = date('Y-m-d H:i:s');

    // Para AdminSENA y SuperAdmin (requiere nickname)
    $nickname        = $_POST['nickname'] ?? '';
    $nombres         = $_POST['nombres'] ?? '';
    $apellidos       = $_POST['apellidos'] ?? '';

    // Para Empresa (no requiere nickname)
    $nit             = $_POST['nit'] ?? '';
    $actividad_economica = $_POST['actividad_economica'] ?? '';
    $direccion       = $_POST['direccion'] ?? '';
    $nombre_empresa  = $_POST['nombre_empresa'] ?? '';
    $telefono        = $_POST['telefono'] ?? '';

    // Validación básica de campos obligatorios
    $errores = [];

    // Validación general de correo y contraseña
    if (!$correo || !$contrasena) {
        $errores[] = "Correo y contraseña son obligatorios.";
    }

    // Validaciones específicas según el rol
    if ($rol === 'SuperAdmin' || $rol === 'AdminSENA') {
        if (!$nickname || !$nombres || !$apellidos) {
            $errores[] = "Nickname, nombres y apellidos son obligatorios para este rol.";
        }
    } elseif ($rol === 'Empresa') {
        if (!$nit || !$nombre_empresa || !$telefono || !$direccion || !$actividad_economica) {
            $errores[] = "Todos los campos de empresa son obligatorios.";
        }
    }

    // Si hay errores, detener ejecución
    if (!empty($errores)) {
        echo implode("<br>", $errores);
        exit;
    }

    // Validar duplicados
    if (UsuarioModel::existeCorreo($correo)) {
        echo "Este correo ya está registrado.";
        exit;
    }

    // Solo validar nickname si es SuperAdmin o AdminSENA
    if ($rol === 'SuperAdmin' || $rol === 'AdminSENA') {
        if ($nickname && UsuarioModel::existeNickname($nickname)) {
            echo "Este nickname ya existe.";
            exit;
        }
    }

    if ($nit && UsuarioModel::existeNIT($nit)) {
        echo "Este NIT ya está registrado.";
        exit;
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    // Crear el array de datos completo (sin nulls)
    $datos = [
        'nombres'             => $nombres,
        'apellidos'           => $apellidos,
        'nickname'            => $rol === 'SuperAdmin' || $rol === 'AdminSENA' ? $nickname : '',  // Solo asignar nickname si es AdminSENA o SuperAdmin
        'correo'              => $correo,
        'contrasena'          => $hashedPassword,
        'rol'                 => $rol,
        'estado'              => $estado,
        'fecha_creacion'      => $fecha_creacion,
        'nit'                 => $nit,
        'actividad_economica' => $actividad_economica,
        'direccion'           => $direccion,
        'nombre_empresa'      => $nombre_empresa,
        'telefono'            => $telefono
    ];

    // Crear el usuario
    try {
        $resultado = UsuarioModel::crear($datos);

        if ($resultado) {
            switch ($rol) {
                case 'SuperAdmin':
                    header('Location: ../html/Super_Admin/index.html');
                    break;
                case 'AdminSENA':
                    header('Location: ../html/Administrador/index_funcionario.html');
                    break;
                case 'Empresa':
                    header('Location: ../html/Empresa/index.html');
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
?>