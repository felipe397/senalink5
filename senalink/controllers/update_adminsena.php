<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $primer_nombre = $_POST['primer_nombre'] ?? '';
    $segundo_nombre = $_POST['segundo_nombre'] ?? '';
    $primer_apellido = $_POST['primer_apellido'] ?? '';
    $segundo_apellido = $_POST['segundo_apellido'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $numero_documento = $_POST['numero_documento'] ?? '';
    $tipo_documento = $_POST['tipo_documento'] ?? '';

    // Validar tipo de documento solo cedula de ciudadania o cedula de extranjeria para AdminSENA
    $tipos_permitidos = ['Cédula de ciudadanía', 'Cédula de extranjería'];
    if (!in_array($tipo_documento, $tipos_permitidos)) {
        echo json_encode(['error' => 'Tipo de documento no permitido para AdminSENA. Solo se permiten Cédula de ciudadanía y Cédula de extranjería.']);
        exit;
    }

    // Validar longitud número de documento: mínimo 8 y máximo 10 dígitos
    if (strlen($numero_documento) < 8 || strlen($numero_documento) > 10) {
        echo json_encode(['error' => 'El número de documento debe tener entre 8 y 10 dígitos.']);
        exit;
    }

    // Validar duplicados de correo, teléfono y número de documento, excluyendo el usuario actual
    if (UsuarioModel::existeCorreo($correo, $id)) {
        echo json_encode(['error' => 'El correo ya está registrado en otra cuenta.']);
        exit;
    }
    if (UsuarioModel::existeTelefono($telefono, $id)) {
        echo json_encode(['error' => 'El número telefónico ya está registrado en otra cuenta.']);
        exit;
    }
    if (UsuarioModel::existeNumeroDocumento($numero_documento)) {
        // Para número de documento, permitir si es el mismo usuario
        $usuarioActual = UsuarioModel::obtenerUsuarioPorId($id);
        if ($usuarioActual && $usuarioActual['numero_documento'] !== $numero_documento) {
            echo json_encode(['error' => 'El número de documento ya está registrado en otra cuenta.']);
            exit;
        }
    }

    // Validar formato de correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Correo electrónico no tiene un formato válido.']);
        exit;
    }

    // Validar teléfono: debe tener exactamente 10 dígitos numéricos
    if (!preg_match('/^\d{10}$/', $telefono)) {
        echo json_encode(['error' => 'El número telefónico debe tener exactamente 10 dígitos numéricos.']);
        exit;
    }

    // Validar longitudes de nombres y apellidos
    if (strlen($primer_nombre) < 2 || strlen($primer_nombre) > 50) {
        echo json_encode(['error' => 'El primer nombre debe tener entre 2 y 50 caracteres.']);
        exit;
    }
    if ($segundo_nombre && (strlen($segundo_nombre) < 2 || strlen($segundo_nombre) > 50)) {
        echo json_encode(['error' => 'El segundo nombre debe tener entre 2 y 50 caracteres si está presente.']);
        exit;
    }
    if (strlen($primer_apellido) < 2 || strlen($primer_apellido) > 50) {
        echo json_encode(['error' => 'El primer apellido debe tener entre 2 y 50 caracteres.']);
        exit;
    }
    if ($segundo_apellido && (strlen($segundo_apellido) < 2 || strlen($segundo_apellido) > 50)) {
        echo json_encode(['error' => 'El segundo apellido debe tener entre 2 y 50 caracteres si está presente.']);
        exit;
    }

    if (!$id) {
        echo json_encode(['error' => 'ID de usuario requerido']);
        exit;
    }

    $usuarioModel = new UsuarioModel();
    $resultado = $usuarioModel->actualizarAdminSENA([
        'id' => $id,
        'primer_nombre' => $primer_nombre,
        'segundo_nombre' => $segundo_nombre,
        'primer_apellido' => $primer_apellido,
        'segundo_apellido' => $segundo_apellido,
        'correo' => $correo,
        'telefono' => $telefono,
        'numero_documento' => $numero_documento,
        'tipo_documento' => $tipo_documento
    ]);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo actualizar el usuario']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
