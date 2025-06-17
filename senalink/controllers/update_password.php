<?php
session_start();
require '../config/conexion.php';

$token = $_POST['token'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (!$token || !$newPassword || !$confirmPassword) {
    die("Faltan datos requeridos.");
}

if ($newPassword !== $confirmPassword) {
    die("Las contraseñas no coinciden.");
}

$pdo = Conexion::conectar();

// Validar token y obtener correo
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $row['email']; // <- este es el nombre correcto de la columna
    // Cambiar contraseña (con hash seguro)
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE usuarios SET contrasena = ? WHERE correo = ?");
    $update->execute([$hashed, $email]);

    // Eliminar token usado
    $pdo->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$token]);

    // Redirigir
    header('Location: ../html/index.html?reset=success');
    exit;
} else {
    die("Token inválido o expirado.");
}
?>
