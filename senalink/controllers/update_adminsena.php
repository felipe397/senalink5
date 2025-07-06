<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $primer_nombre = $_POST['primer_nombre'] ?? '';
    $segundo_nombre = $_POST['segundo_nombre'] ?? '';
    $primer_apellido = $_POST['primer_apellido'] ?? '';
    $segundo_apellido = $_POST['segundo_apellido'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $numero_documento = $_POST['numero_documento'] ?? '';
    $tipo_documento = $_POST['tipo_documento'] ?? '';

    if (!$id) {
        echo json_encode(['error' => 'ID de usuario requerido']);
        exit;
    }

    $usuarioModel = new UsuarioModel();
    $resultado = $usuarioModel->actualizarAdminSENA([
        'id' => $id,
        'primer_nombre' => $primer_nombre,
        'segundo_nombre' => $segundo_nombre,
        'primer_apellido' => $primer_apellido,
        'segundo_apellido' => $segundo_apellido,
        'correo' => $correo,
        'telefono' => $telefono,
        'numero_documento' => $numero_documento,
        'tipo_documento' => $tipo_documento
    ]);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo actualizar el usuario']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
