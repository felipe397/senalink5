<?php
session_start();
require '../config/conexion.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die('Token no proporcionado');
}

try {
    $pdo = Conexion::conectar();

    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['token_recuperacion'] = $token;

        // Redirigir al formulario
        header("Location: ../html/forgot_new_password.php");
        exit;
    } else {
        echo "Token inválido o expirado.";
    }
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos.";
}
