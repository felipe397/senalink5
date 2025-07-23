<?php
require_once '../Config/conexion.php';

$pdo = Conexion::conectar();

// --- DATOS DE ENTRADA (normalmente vendrían de un formulario POST) ---
$primer_nombre      = 'Edwin';
$segundo_nombre     = '';
$primer_apellido    = 'Banol';
$segundo_apellido   = 'Cardona';
$correo             = 'edwin@gmail.com';
$contrasena_input   = 'Edwinbanolcar2244#'; // Contraseña sin encriptar para validar
$rol                = 'super_admin';
$estado             = 'activo';
$direccion          = 'Calle 2488 #49-17';
$telefono           = '3024345636';
$numero_documento   = '3333333333';
$tipo_documento     = 'Cedula de ciudadania';



// --- VALIDACIONES ---
$errores = [];

// Validar contraseña
if (
    strlen($contrasena_input) < 14 ||
    !preg_match('/[a-z]/', $contrasena_input) ||
    !preg_match('/[A-Z]/', $contrasena_input) ||
    !preg_match('/[0-9]/', $contrasena_input) ||
    !preg_match('/[\W_]/', $contrasena_input) // Caracteres especiales
) {
    $errores[] = "La contraseña debe tener al menos 12 caracteres, incluyendo mayúsculas, minúsculas, números y un carácter especial.";
}

// Validar dirección
if (!preg_match('/^[\p{L}0-9\s.#-]+$/u', $direccion)) {
    $errores[] = "La dirección solo puede contener letras, números, espacios, y los símbolos '.', '#', '-'.";
}

// Validar número de teléfono
if (!ctype_digit($telefono) || intval($telefono) <= 0) {
    $errores[] = "El número de teléfono debe ser un número positivo.";
}

// Validar número de documento
if (!ctype_digit($numero_documento) || intval($numero_documento) <= 0) {
    $errores[] = "El número de documento debe ser un número positivo.";
}

// Mostrar errores si existen
if (!empty($errores)) {
    echo "⚠️ Se encontraron errores de validación:<br>" . implode("<br>", $errores);
    exit;
}

// Si pasa las validaciones, hashear la contraseña
$contrasena = password_hash($contrasena_input, PASSWORD_DEFAULT);

// --- INSERCIÓN EN LA BASE DE DATOS ---
$sql = "INSERT INTO usuarios (
    correo, contrasena, rol, estado, fecha_creacion, direccion, telefono,
    primer_nombre, primer_apellido, segundo_apellido, numero_documento, tipo_documento
) VALUES (
    :correo, :contrasena, :rol, :estado, NOW(), :direccion, :telefono,
    :primer_nombre, :primer_apellido, :segundo_apellido, :numero_documento, :tipo_documento
)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'correo'             => $correo,
    'contrasena'         => $contrasena,
    'rol'                => $rol,
    'estado'             => $estado,
    'direccion'          => $direccion,
    'telefono'           => $telefono,
    'primer_nombre'      => $primer_nombre,
    'primer_apellido'    => $primer_apellido,
    'segundo_apellido'   => $segundo_apellido,
    'numero_documento'   => $numero_documento,
    'tipo_documento'     => $tipo_documento
]);

echo "✅ Super admin insertado correctamente.";
?>