<?php
require_once '../models/UsuarioModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $nit = isset($_POST['nit']) ? trim($_POST['nit']) : null;
    $representante_legal = isset($_POST['representante_legal']) ? trim($_POST['representante_legal']) : null;
    $razon_social = isset($_POST['razon_social']) ? trim($_POST['razon_social']) : null;
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : null;
    $barrio = isset($_POST['barrio']) ? trim($_POST['barrio']) : null;
    $departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : null;
    $ciudad = isset($_POST['ciudad']) ? trim($_POST['ciudad']) : null;
    $tipo_empresa = isset($_POST['tipo_empresa']) ? trim($_POST['tipo_empresa']) : null;

    header('Content-Type: application/json');
    $errores = [];

    if ($id === null || $nit === '' || $representante_legal === '' || $razon_social === '' || $telefono === '' || $correo === '' || $direccion === '' || $barrio === '' || $departamento === '' || $ciudad === '' || $tipo_empresa === '') {
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

    // Validar NIT: exactamente 9 o 10 dígitos numéricos
    if (!preg_match('/^\d{9,10}$/', $nit)) {
        $errores[] = 'El NIT debe tener 9 o 10 dígitos numéricos.';
    } else {
        // Verificar si el NIT ya existe en otra empresa (excluyendo la actual)
        if ($usuarioModel->existeNIT($nit, $id)) {
            $errores[] = 'El NIT ya está registrado en otra empresa.';
        }
    }

    // Validar tipo societario en razón social
    if (!preg_match('/\b(S\.A\.?|S\.A\.S\.?|LTDA)\b/i', $razon_social)) {
        $errores[] = 'La razón social debe incluir el tipo societario: S.A, S.A.S o LTDA.';
    }
    // Validar que la razón social sea única (excluyendo la actual)
    if ($usuarioModel->existeRazonSocial($razon_social, $id)) {
        $errores[] = 'La razón social ya está registrada en otra empresa.';
    }
    // Validar dirección urbana colombiana con más tipos de vía

    // Relaxed regex to accept common Colombian address formats including "Carrera 9 # 56-34"
    $regex_direccion_colombia = '/^(Calle|Carrera|Avenida|Transversal|Diagonal|Autopista|Troncal|Variante|Via|Vía|Mz|Manzana|Casa|Peatonal)\\s+\\d+[A-Za-z]?\\s*(Bis)?\\s*(Sur|Norte|Este|Occidente)?\\s*(No\\.?|#)?\\s*\\d+([A-Za-z]?)(?:-\\d+)?$/iu';
    if (!preg_match($regex_direccion_colombia, $direccion)) {
        // Si no pasa, intenta con formato alternativo sin # o No.
        $regex_alternativo = '/^(Calle|Carrera|Avenida|Transversal|Diagonal|Autopista|Troncal|Variante|Via|Vía|Mz|Manzana|Casa|Peatonal)\\s+\\d+[A-Za-z]?\\s*(Bis)?\\s*(Sur|Norte|Este|Occidente)?\\s*\\d+([A-Za-z]?)(?:-\\d+)?$/iu';
        if (!preg_match($regex_alternativo, $direccion)) {
            $errores[] = 'La dirección no cumple con el formato urbano colombiano.';
        }
    }



    // Verificar si el correo ya existe en otra empresa (excluyendo la actual)
    if ($usuarioModel->existeCorreo($correo, $id)) {
        $errores[] = 'El correo ya está registrado en otra empresa.';
    }
    // Validar teléfono: debe empezar por 3 y tener al menos 10 dígitos
    if (!preg_match('/^3\d{9,}$/', $telefono)) {
        $errores[] = 'El número telefónico debe empezar por 3 y contener al menos 10 dígitos.';
    }
    // Verificar si el teléfono ya existe en otra empresa (excluyendo la actual)
    if ($usuarioModel->existeTelefono($telefono, $id)) {
        $errores[] = 'El número telefónico ya está registrado en otra empresa.';
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
            'direccion' => $direccion,
            'barrio' => $barrio,
            'departamento' => $departamento,
            'ciudad' => $ciudad,
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
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
