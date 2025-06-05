<?php
require_once '../Config/conexion.php'; // Llama tu clase de conexión

$pdo = Conexion::conectar(); // Llama al método estático para conectarte

// Datos de ejemplo (pueden venir de un formulario)
$primer_nombre = 'Breiner';
$primer_apellido = 'Chica';
$segundo_apellido = 'alzate';
$correo = 'breiner.chica@admin.com';
$contrasena = password_hash('ClaveSegura123', PASSWORD_DEFAULT);
$rol = 'super_admin';
$estado = 'activo';
$direccion = 'Calle 123';
$telefono = '3001234567';
$numero_documento = '3001234567';
$tipo_documento = 'Cedula de ciudadania';
$sql = "INSERT INTO usuarios (
    correo, contrasena, rol, estado, fecha_creacion, direccion, telefono,primer_nombre,primer_apellido,segundo_apellido,numero_documento,tipo_documento
) VALUES (
    :correo, :contrasena, :rol, :estado, NOW(), :direccion, :telefono,:primer_nombre, :primer_apellido,:segundo_apellido,:numero_documento,:tipo_documento
)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'primer_nombre'    => $primer_nombre,
    'primer_apellido'    => $primer_apellido,
    'segundo_apellido'  => $segundo_apellido,
    'correo'     => $correo,
    'contrasena' => $contrasena,
    'rol'        => $rol,
    'estado'     => $estado,
    'direccion'  => $direccion,
    'telefono'   => $telefono,
    'numero_documento'      => $numero_documento,
    'tipo_documento'       => $tipo_documento
]);

echo "✅ Super admin insertado correctamente.";
?>
