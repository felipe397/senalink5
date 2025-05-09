<?php
require_once '../models/UsuarioModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $correo         = $_POST['correo'] ?? '';
    $contrasena     = $_POST['contrasena'] ?? '';
    $rol            = $_POST['rol'] ?? '';
    $estado         = 'Activo';
    $fecha_creacion = date('Y-m-d H:i:s');


    // Solo para rol Empresa
    $nit                 = $_POST['nit'] ?? '';
    $actividad_economica = $_POST['actividad_economica'] ?? '';
    $direccion           = $_POST['direccion'] ?? '';
    $nombre_empresa      = $_POST['nombre_empresa'] ?? '';
    $telefono            = $_POST['telefono'] ?? '';

    // Validación básica
    $errores = [];

    if (!$correo || !$contrasena) {
        $errores[] = "Correo y contraseña son obligatorios.";
    }

    if ($rol === 'SuperAdmin' || $rol === 'AdminSENA') {
        if (!$nombres || !$apellidos) {
            $errores[] = "Nombres y apellidos son obligatorios para este rol.";
        }
    } elseif ($rol === 'empresa') {
        if (!$nit || !$nombre_empresa || !$telefono || !$direccion || !$actividad_economica) {
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
    $datos = [
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
?>
