<?php
session_start();
require_once '../config/conexion.php'; // Asegúrate que esta es la ruta correcta

$conn = Conexion::conectar();

$rol = $_POST['rol'];
$contrasena = $_POST['contrasena'];

if ($rol === 'Empresa') {
    $nit = $_POST['nit'];
    $query = "SELECT * FROM usuarios WHERE nit = :identificador AND rol = 'empresa'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':identificador', $nit);
} else {
    $correo = $_POST['correo'];
    $query = "SELECT * FROM usuarios WHERE correo = :identificador AND rol = :rol";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':identificador', $correo);
    $stmt->bindParam(':rol', $rol);
}

$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    if (password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['id'] = $usuario['nit'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redirección según el rol
        if ($rol === 'Empresa') {
            header("Location: ../html/Empresa/Home.html");
        } elseif ($rol === 'admin') {
            header("Location: admin/inicio.php");
        } elseif ($rol === 'superadmin') {
            header("Location: superadmin/inicio.php");
        }
        exit();
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}
?>
