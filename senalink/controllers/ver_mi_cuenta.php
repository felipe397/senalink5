<?php
require '../config/conexion.php';
session_start();

// Obtener ID y rol desde la sesión
$id = $_SESSION['id'] ?? null;
$rol = $_SESSION['rol'] ?? null;

// Validar sesión
if (!$id || !$rol) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

try {
    $db = Conexion::conectar();

    // Preparar consulta según el rol
    if ($rol === 'empresa') {
        $stmt = $db->prepare("
            SELECT nit, representante_legal, razon_social, telefono, correo, direccion, tipo_empresa, estado 
            FROM usuarios 
            WHERE id = :id
        ");
    } elseif ($rol === 'super_admin') {
        $stmt = $db->prepare("
            SELECT 
                primer_nombre, 
                segundo_nombre, 
                primer_apellido, 
                segundo_apellido, 
                direccion, 
                telefono, 
                numero_documento, 
                tipo_documento, 
                correo,
                genero,
                fecha_nacimiento
            FROM usuarios 
            WHERE id = :id
        ");
    } else {
        echo json_encode(['error' => 'Rol no válido']);
        exit;
    }

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si no hay datos
    if (!$data) {
        echo json_encode(['error' => 'Usuario no encontrado']);
        exit;
    }

    // Adjuntar el rol al JSON de respuesta
    $data['rol'] = $rol;

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de servidor: ' . $e->getMessage()]);
    exit;
}
