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

// 🟢 POST: Actualizar estado del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['accion']) && $_POST['accion'] === 'actualizarEstado' &&
    isset($_POST['id']) && isset($_POST['estado'])) {

    $id = $_POST['id'];

    // Normalizar estado
    $nuevoEstado = ucfirst(strtolower(trim($_POST['estado'])));

    // Validar estado
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

// 🟢 GET: Detalle de usuario autenticado
if (isset($_GET['action']) && $_GET['action'] === 'detalleUsuario') {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        error_log("❌ Error: user_id no definido en sesión");
        echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    error_log("🟢 user_id en sesión: $userId");

    $usuarioModel = new UsuarioModel();
    $usuario = $usuarioModel->obtenerUsuarioPorId($userId);

    if (!$usuario) {
        error_log("❌ Usuario no encontrado para ID: $userId");
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $usuario]);
    exit;
}

// 🟡 Si no coincide ninguna acción
header('Content-Type: application/json');
echo json_encode(['success' => false, 'error' => 'Acción no válida']);
exit;
