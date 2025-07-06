<?php
// Iniciar sesión al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Incluir modelo
require_once '../models/UsuarioModel.php';

// 🟢 POST: Crear usuario (empresa o AdminSENA)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crearUsuario') {
    $rol = isset($_POST['rol']) ? $_POST['rol'] : '';
    $errores = [];
    $datos = [];
    $datos['rol'] = $rol;
    $datos['estado'] = 'activo';
    $datos['fecha_creacion'] = date('Y-m-d H:i:s');

    // Validación y asignación de campos según el rol
    if ($rol === 'empresa') {
        $datos['nit'] = trim($_POST['nit'] ?? '');
        $datos['representante_legal'] = trim($_POST['representante_legal'] ?? '');
        $datos['razon_social'] = trim($_POST['razon_social'] ?? '');
        $datos['telefono'] = trim($_POST['telefono'] ?? '');
        $datos['correo'] = trim($_POST['correo'] ?? '');
        $datos['direccion'] = trim($_POST['direccion'] ?? '');
        $datos['tipo_empresa'] = trim($_POST['tipo_empresa'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        // Validaciones básicas
        if (!ctype_digit($datos['nit']) || intval($datos['nit']) <= 0) $errores[] = 'NIT inválido.';
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo inválido.';
        if (UsuarioModel::existeNIT($datos['nit'])) $errores[] = 'El NIT ya está registrado.';
        if (UsuarioModel::existeCorreo($datos['correo'])) $errores[] = 'El correo ya está registrado.';
        if (strlen($contrasena) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        $datos['contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
    } elseif ($rol === 'AdminSENA') {
        $datos['primer_nombre'] = trim($_POST['primer_nombre'] ?? '');
        $datos['segundo_nombre'] = trim($_POST['segundo_nombre'] ?? '');
        $datos['primer_apellido'] = trim($_POST['primer_apellido'] ?? '');
        $datos['segundo_apellido'] = trim($_POST['segundo_apellido'] ?? '');
        $datos['correo'] = trim($_POST['correo'] ?? '');
        $datos['telefono'] = trim($_POST['telefono'] ?? '');
        $datos['numero_documento'] = trim($_POST['numero_documento'] ?? '');
        $datos['tipo_documento'] = trim($_POST['tipo_documento'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo inválido.';
        if (UsuarioModel::existeCorreo($datos['correo'])) $errores[] = 'El correo ya está registrado.';
        if (!ctype_digit($datos['numero_documento']) || intval($datos['numero_documento']) <= 0) $errores[] = 'Número de documento inválido.';
        if (strlen($contrasena) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        $datos['contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
    } else {
        $errores[] = 'Rol no válido.';
    }

    if (!empty($errores)) {
        echo '<script>alert("' . implode('\\n', $errores) . '"); window.history.back();</script>';
        exit;
    }

    try {
        $creado = UsuarioModel::crear($datos);
        if ($creado) {
            header('Location: http://localhost/senalink5/senalink5/senalink/html/index.html');
            exit;
        } else {
            echo '<script>alert("Error al crear el usuario."); window.history.back();</script>';
            exit;
        }
    } catch (Exception $e) {
        echo '<script>alert("' . addslashes($e->getMessage()) . '"); window.history.back();</script>';
        exit;
    }
}
// 🟢 POST: Actualizar estado del usuario
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['accion']) && $_POST['accion'] === 'actualizarEstado' &&
    isset($_POST['id']) && isset($_POST['estado'])
) {
    $id = $_POST['id'];
    $nuevoEstado = ucfirst(strtolower(trim($_POST['estado'])));
    if (!in_array($nuevoEstado, ['Activo', 'Desactivado'])) {
        echo json_encode(['success' => false, 'error' => 'Estado no válido.']);
        exit;
    }
    $resultado = UsuarioModel::actualizarEstado($id, $nuevoEstado);
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? "Estado actualizado correctamente." : "Error al actualizar estado."
    ]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'listarAdminSENA') {
    header('Content-Type: application/json');
    $usuarioModel = new UsuarioModel();
    $usuarios = $usuarioModel->obtenerUsuariosPorRol('AdminSENA');
    echo json_encode(['success' => true, 'data' => $usuarios]);
    exit;
}

// 🟢 GET: Detalle de usuario autenticado
if (isset($_GET['action']) && $_GET['action'] === 'detalleUsuario') {
    header('Content-Type: application/json');

    // Permitir obtener por id GET o por sesión
    $usuarioId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null);
    if (!$usuarioId) {
        error_log("❌ Error: id de usuario no proporcionado ni en sesión");
        echo json_encode(['success' => false, 'error' => 'Usuario no autenticado ni id proporcionado']);
        exit;
    }

    $usuarioModel = new UsuarioModel();
    $usuario = $usuarioModel->obtenerUsuarioPorId($usuarioId);

    if (!$usuario) {
        error_log("❌ Usuario no encontrado para ID: $usuarioId");
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $usuario]);
    exit;
}

// 🟡 Si no coincide ninguna acción
// 🟢 POST: Filtrar empresas por estado
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['accion']) && $_POST['accion'] === 'filtrarPorEstado' &&
    isset($_POST['estado'])
) {
    $estado = $_POST['estado'];
    require_once '../models/UsuarioModel.php';
    $empresas = UsuarioModel::getEmpresasPorEstado($estado);
    // Puedes devolver HTML o JSON según lo que espera tu JS
    // Aquí devuelvo una tabla simple como ejemplo:
    if (empty($empresas)) {
        echo '<p>No se encontraron empresas.</p>';
    } else {
        echo '<table class="tabla-empresas">';
        echo '<tr><th>ID</th><th>Razón Social</th><th>NIT</th></tr>';
        foreach ($empresas as $empresa) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($empresa['id']) . '</td>';
            echo '<td>' . htmlspecialchars($empresa['razon_social']) . '</td>';
            echo '<td>' . htmlspecialchars($empresa['nit']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    exit;
}

if (php_sapi_name() !== 'cli') {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET['action'])) {
        echo '<script>window.location.href = "/senalink5/senalink/html/index.html";</script>';
        exit;
    }
    // Si es una petición AJAX o con action inválida, responder JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    exit;
}
