<?php
session_start();
require '../config/conexion.php';

$token = $_POST['token'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';


if (!$token || !$newPassword || !$confirmPassword) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Faltan datos requeridos.'
    ]);
    exit;
}

if ($newPassword !== $confirmPassword) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Las contraseñas no coinciden.'
    ]);
    exit;
}

$pdo = Conexion::conectar();

// Validar token y obtener correo
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $row['email'];
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE usuarios SET contrasena = ? WHERE correo = ?");
    $update->execute([$hashed, $email]);

    // Eliminar token usado
    $pdo->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$token]);

    // Marcar éxito en la sesión
    $_SESSION['password_changed'] = true;
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'redirect' => '../html/index.php'
    ]);
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Token inválido o expirado.'
    ]);
    exit;
}
?>
