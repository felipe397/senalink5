<?php
require_once '../Config/conexion.php'; // Llama tu clase de conexión

$pdo = Conexion::conectar(); // Llama al método estático para conectarte

// Datos de ejemplo (pueden venir de un formulario)
$nombres = 'Breiner';
$apellidos = 'Chica';
$correo = 'breiner.chica@admin.com';
$contrasena = password_hash('ClaveSegura123', PASSWORD_DEFAULT);
$rol = 'super_admin';
$estado = 'activo';
$direccion = 'Calle 123';
$telefono = '3001234567';

$sql = "INSERT INTO usuarios (
    nombres, apellidos, correo, contrasena, rol, estado, fecha_creacion, direccion, telefono
) VALUES (
    :nombres, :apellidos, :correo, :contrasena, :rol, :estado, NOW(), :direccion, :telefono
)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nombres'    => $nombres,
    'apellidos'  => $apellidos,
    'correo'     => $correo,
    'contrasena' => $contrasena,
    'rol'        => $rol,
    'estado'     => $estado,
    'direccion'  => $direccion,
    'telefono'   => $telefono
]);

echo "✅ Super admin insertado correctamente.";
?>
