<?php
session_start();
session_unset();
session_destroy();
session_start();
// Luego continúa con la lógica de login

session_regenerate_id(true);
require_once '../config/conexion.php';

$conn = Conexion::conectar();

$rol = $_POST['rol'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$correo = $_POST['correo'] ?? '';
$nit = $_POST['nit'] ?? '';


$errores = [];
/*
// --- Manejo de intentos fallidos para empresas ---
if ($rol === 'empresa') {
    if (!isset($_SESSION['empresa_login_attempts'])) {
        $_SESSION['empresa_login_attempts'] = 0;
    }
    if ($_SESSION['empresa_login_attempts'] >= 3) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Acceso bloqueado: Ha superado el número máximo de intentos fallidos.']);
        exit;
    }
}
*/
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

header('Content-Type: application/json');

// ✅ Verificar existencia y contraseña
if ($usuario) {
    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Guardar datos en sesión
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];
        // Resetear intentos fallidos al éxito
        if ($rol === 'empresa') {
            $_SESSION['empresa_login_attempts'] = 0;
        }
        // Enviar respuesta JSON con URL de redirección
        if ($rol === 'empresa') {
            $redirect = '../html/Empresa/Home2.php';
        } elseif ($rol === 'AdminSENA') {
            $redirect = '../html/AdminSENA/Home2.php';
        } elseif ($rol === 'super_admin') {
            $redirect = '../html/Super_Admin/Home2.php';
        } else {
            $redirect = '../html/index.php';
        }
        echo json_encode(['success' => true, 'redirect' => $redirect]);
        exit;
    } else {
        // Contraseña incorrecta
        if ($rol === 'empresa') {
            $_SESSION['empresa_login_attempts'] += 1;
            if ($_SESSION['empresa_login_attempts'] >= 3) {
                echo json_encode(['success' => false, 'error' => 'Acceso bloqueado: Ha superado el número máximo de intentos fallidos.']);
                exit;
            }
            $msg = 'El NIT o la contraseña son incorrectos.';
        } else {
            $msg = 'El correo o la contraseña son inválidos.';
        }
        echo json_encode(['success' => false, 'error' => $msg]);
        exit;
    }
} else {
    // Usuario no encontrado
    if ($rol === 'empresa') {
        $_SESSION['empresa_login_attempts'] += 1;
        if ($_SESSION['empresa_login_attempts'] >= 3) {
            echo json_encode(['success' => false, 'error' => 'Acceso bloqueado: Ha superado el número máximo de intentos fallidos.']);
            exit;
        }
        $msg = 'El NIT o la contraseña son incorrectos.';
    } else {
        $msg = 'El correo o la contraseña son inválidos.';
    }
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}
