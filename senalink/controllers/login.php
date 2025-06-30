<?php
session_start();
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


// Validar campos según rol
if ($rol === 'empresa') {
    if (!ctype_digit($nit) || intval($nit) <= 0) {
        $errores[] = "El NIT debe ser un número positivo.";
    }
} else {
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico no válido.";
    }
}

if (!empty($errores)) {
    echo "⚠️ Errores de validación:<br>" . implode("<br>", $errores);
    exit;
}

// Consulta a la base de datos según rol
if ($rol === 'empresa') {
    $query = "SELECT * FROM usuarios WHERE nit = :identificador AND rol = 'empresa' AND estado = 'activo'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':identificador', $nit);
} else {
    $query = "SELECT * FROM usuarios WHERE correo = :identificador AND rol = :rol AND estado = 'activo'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':identificador', $correo);
    $stmt->bindParam(':rol', $rol);
}

$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar existencia y contraseña
if ($usuario) {
    if (password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redireccionar según rol
        switch ($rol) {
            case 'empresa':
                header("Location: ../html/Empresa/Home.html");
                break;
            case 'AdminSENA':
                header("Location: ../html/AdminSENA/Home.html");
                break;
            case 'super_admin':
                header("Location: ../html/Super_Admin/Home.html");
                break;
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
