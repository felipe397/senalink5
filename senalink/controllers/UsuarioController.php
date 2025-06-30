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

    // Solo para rol Empresas
    $nit                 = $_POST['nit'] ?? '';
    $representante_legal = $_POST['representante_legal'] ?? '';
    $tipo_empresa        = $_POST['tipo_empresa'] ?? '';
    $direccion           = $_POST['direccion'] ?? '';
    $razon_social        = $_POST['razon_social'] ?? '';
    $telefono            = $_POST['telefono'] ?? '';

    // Validación básica
    $errores = [];

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
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listarEmpresasActivas') {
    $empresas = UsuarioModel::listarEmpresasActivas();
    header('Content-Type: application/json');
    echo json_encode($empresas);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listarEmpresasInhabilitadas') {
    $empresas = UsuarioModel::listarEmpresasInhabilitadas();
    header('Content-Type: application/json');
    echo json_encode($empresas);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['accion'] === 'filtrarPorEstado') {
        $estado = $_POST['estado'];
        $empresas = UsuarioModel::getEmpresasPorEstado($estado);

        if (empty($empresas)) {
            echo "<p>No se encontraron empresas con estado $estado.</p>";
        } else {
            foreach ($empresas as $empresa) {
                echo "
                <article class='cardh'>
                    <a href='Empresa.html?id={$empresa['id']}'>
                        <div class='card-text'>
                            <h2 class='card-title'>{$empresa['razon_social']}</h2>
                            <p class='card-subtitle'>{$empresa['nit']}</p>
                        </div>
                    </a>
                </article>
                ";
            }
        }
        exit;
    }
}
?>