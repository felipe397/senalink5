<?php
require_once '../models/UsuarioModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $nit = isset($_POST['nit']) ? trim($_POST['nit']) : null;
    $representante_legal = isset($_POST['representante_legal']) ? trim($_POST['representante_legal']) : null;
    $razon_social = isset($_POST['razon_social']) ? trim($_POST['razon_social']) : null;
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $ubicacion = isset($_POST['ubicacion']) ? trim($_POST['ubicacion']) : null;
    $tipo_empresa = isset($_POST['tipo_empresa']) ? trim($_POST['tipo_empresa']) : null;

    header('Content-Type: application/json');
    $errores = [];

    if ($id === null || $nit === '' || $representante_legal === '' || $razon_social === '' || $telefono === '' || $correo === '' || $ubicacion === '' || $tipo_empresa === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }

    // Validar tipo_empresa
    $opciones_validas = ['INDUSTRIAL', 'SERVICIOS'];
    if (!in_array($tipo_empresa, $opciones_validas)) {
        $errores[] = 'El tipo de empresa no es válido.';
    }

    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Correo inválido.';
    }

    $usuarioModel = new UsuarioModel();

    // Validar NIT: debe ser numérico y positivo
    if (!ctype_digit($nit) || intval($nit) <= 0) {
        $errores[] = 'NIT inválido.';
    } else {
        // Verificar si el NIT ya existe en otra empresa (excluyendo la actual)
        if ($usuarioModel->existeNIT($nit, $id)) {
            $errores[] = 'El NIT ya está registrado en otra empresa.';
        }
    }

    // Validar que la razón social sea única (excluyendo la actual)
    if ($usuarioModel->existeRazonSocial($razon_social, $id)) {
        $errores[] = 'La razón social ya está registrada en otra empresa.';
    }

    // Verificar si el correo ya existe en otra empresa (excluyendo la actual)
    if ($usuarioModel->existeCorreo($correo, $id)) {
        $errores[] = 'El correo ya está registrado en otra empresa.';
    }

    if (!empty($errores)) {
        echo json_encode(['success' => false, 'errors' => $errores]);
        exit;
    }

    try {
        $resultado = $usuarioModel->updateEmpresa($id, [
            'nit' => $nit,
            'representante_legal' => $representante_legal,
            'razon_social' => $razon_social,
            'telefono' => $telefono,
            'correo' => $correo,
            'direccion' => $ubicacion,
            'tipo_empresa' => $tipo_empresa
        ]);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar la empresa.']);
        }
    } catch (Exception $e) {
        // Devolver mensaje completo para diagnóstico
        echo json_encode(['success' => false, 'error' => 'Excepción al actualizar la empresa: ' . $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
