<?php
session_start();
session_regenerate_id(true);
require_once '../config/conexion.php';

$conn = Conexion::conectar();

$rol = $_POST['rol'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$correo = $_POST['correo'] ?? '';
$nit = $_POST['nit'] ?? '';

$errores = [];

// Validar que el rol sea correcto
$roles_permitidos = ['empresa', 'AdminSENA', 'super_admin'];
if (!in_array($rol, $roles_permitidos)) {
    $errores[] = "Rol no válido.";
}


if ($rol === 'empresa') {
    // Buscar el usuario empresa por NIT
    $query = "SELECT * FROM usuarios WHERE nit = :identificador AND rol = 'empresa' AND estado = 'activo'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':identificador', $nit);
} else {
    // Buscar el usuario por correo y rol
    $query = "SELECT * FROM usuarios WHERE correo = :identificador AND rol = :rol AND estado = 'activo'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':identificador', $correo);
    $stmt->bindParam(':rol', $rol);
}

$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Verificar existencia y contraseña
if ($usuario) {
    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Guardar datos en sesión
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];

        // ✅ Redirigir según rol y si hizo el diagnóstico
        if ($rol === 'empresa') {
            if ($usuario['diagnostico_realizado']) {
                header("Location: ../html/Empresa/Home.html");
            } else {
                header("Location: ../html/formulario.php");
            }
        } elseif ($rol === 'AdminSENA') {
            header("Location: ../html/AdminSENA/Home.html");
        } elseif ($rol === 'super_admin') {
            header("Location: ../html/Super_Admin/Home.html");
        }
        exit;
    } else {
        echo "❌ Contraseña incorrecta.";
        exit;
    }
} else {
    echo "❌ Usuario no encontrado.";
    exit;
}
