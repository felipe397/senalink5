<?php
// Iniciar sesión al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Incluir modelo
require_once '../models/UsuarioModel.php';

// 🟢 POST: Crear usuario (empresa o AdminSENA)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crearUsuario') {
    $rol = isset($_POST['rol']) ? $_POST['rol'] : '';
    $errores = [];
    $datos = [];
    $datos['rol'] = $rol;
    $datos['estado'] = 'activo';
    $datos['fecha_creacion'] = date('Y-m-d H:i:s');

    // Validación y asignación de campos según el rol
    if ($rol === 'empresa') {
        $datos['nit'] = trim($_POST['nit'] ?? '');
        $datos['representante_legal'] = trim($_POST['representante_legal'] ?? '');
        $datos['razon_social'] = trim($_POST['razon_social'] ?? '');
        $datos['telefono'] = trim($_POST['telefono'] ?? '');
        $datos['correo'] = trim($_POST['correo'] ?? '');
        $datos['direccion'] = trim($_POST['direccion'] ?? '');
        $datos['tipo_empresa'] = trim($_POST['tipo_empresa'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';

        // Validar teléfono: debe tener exactamente 10 dígitos numéricos
        if (!preg_match('/^\d{10}$/', $datos['telefono'])) {
            $errores[] = 'El número telefónico debe tener exactamente 10 dígitos numéricos.';
        }

        // Validación NIT: exactamente 9 dígitos numéricos
        if (!preg_match('/^\d{9}$/', $datos['nit'])) $errores[] = 'El NIT debe tener exactamente 9 dígitos numéricos.';
        // Validación razón social: debe incluir tipo societario
        if (!preg_match('/\b(S\.A\.?|S\.A\.S\.?|LTDA)\b/i', $datos['razon_social'])) $errores[] = 'La razón social debe incluir el tipo societario: S.A, S.A.S o LTDA.';
        // Validación representante legal: mínimo 2 caracteres
        if (strlen($datos['representante_legal']) < 2) $errores[] = 'El representante legal debe tener mínimo 2 caracteres.';
        // Validación dirección urbana colombiana
        $regex_direccion_colombia = '/^(Calle|Carrera|Transversal|Diagonal|Autopista|Vía|Mz|Manzana)?\s?\d{1,3}[A-Za-z]?(?:\s?Bis)?(?:\s?(Sur|Norte|Este|Occidente))?\s?(No\.?|#)\s?\d{1,3}[A-Za-z]?(?:-\d{1,3})?$/i';
        if (!preg_match($regex_direccion_colombia, $datos['direccion'])) {
            $errores[] = 'La dirección no cumple con el formato urbano colombiano.';
        }
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo inválido.';
        if (UsuarioModel::existeNIT($datos['nit'])) $errores[] = 'El NIT ya está registrado.';
        if (UsuarioModel::existeCorreo($datos['correo'])) $errores[] = 'El correo ya está registrado.';
        if (strlen($contrasena) < 12) $errores[] = 'La contraseña debe tener al menos 12 caracteres.';
        $datos['contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
    } elseif ($rol === 'AdminSENA') {
        $datos['primer_nombre'] = trim($_POST['primer_nombre'] ?? '');
        $datos['segundo_nombre'] = trim($_POST['segundo_nombre'] ?? '');
        $datos['primer_apellido'] = trim($_POST['primer_apellido'] ?? '');
        $datos['segundo_apellido'] = trim($_POST['segundo_apellido'] ?? '');
        $datos['correo'] = trim($_POST['correo'] ?? '');
        $datos['telefono'] = trim($_POST['telefono'] ?? '');
        $datos['numero_documento'] = trim($_POST['numero_documento'] ?? '');
        $datos['tipo_documento'] = trim($_POST['tipo_documento'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $regex_nombre = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,50}$/u';
        $regex_nombre_opcional = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{0,50}$/u';

        // Validar tipo de documento solo cedula de ciudadania o cedula de extranjeria para AdminSENA
        $tipos_permitidos = ['Cédula de ciudadanía', 'Cédula de extranjería'];
        if (!in_array($datos['tipo_documento'], $tipos_permitidos)) {
            $errores[] = 'Tipo de documento no permitido para AdminSENA. Solo se permiten Cédula de ciudadanía y Cédula de extranjería.';
        }

        // Validar longitudes de nombres y apellidos
        if (strlen($datos['primer_nombre']) < 2 || strlen($datos['primer_nombre']) > 50) {
            $errores[] = 'El primer nombre debe tener entre 2 y 50 caracteres.';
        }
        if ($datos['segundo_nombre'] && (strlen($datos['segundo_nombre']) < 2 || strlen($datos['segundo_nombre']) > 50)) {
            $errores[] = 'El segundo nombre debe tener entre 2 y 50 caracteres si está presente.';
        }
        if (strlen($datos['primer_apellido']) < 2 || strlen($datos['primer_apellido']) > 50) {
            $errores[] = 'El primer apellido debe tener entre 2 y 50 caracteres.';
        }
        if ($datos['segundo_apellido'] && (strlen($datos['segundo_apellido']) < 2 || strlen($datos['segundo_apellido']) > 50)) {
            $errores[] = 'El segundo apellido debe tener entre 2 y 50 caracteres si está presente.';
        }

        if (!preg_match($regex_nombre, $datos['primer_nombre'])) $errores[] = 'El primer nombre debe tener solo letras, tildes o ñ, entre 2 y 50 caracteres.';
        if ($datos['segundo_nombre'] && !preg_match($regex_nombre_opcional, $datos['segundo_nombre'])) $errores[] = 'El segundo nombre debe tener solo letras, tildes o ñ, máximo 50 caracteres.';
        if (!preg_match($regex_nombre, $datos['primer_apellido'])) $errores[] = 'El primer apellido debe tener solo letras, tildes o ñ, entre 2 y 50 caracteres.';
        if ($datos['segundo_apellido'] && !preg_match($regex_nombre_opcional, $datos['segundo_apellido'])) $errores[] = 'El segundo apellido debe tener solo letras, tildes o ñ, máximo 50 caracteres.';
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo inválido.';
        if (UsuarioModel::existeCorreo($datos['correo'])) $errores[] = 'El correo ya está registrado.';
        if (!ctype_digit($datos['numero_documento']) || intval($datos['numero_documento']) <= 0) $errores[] = 'Número de documento inválido.';
        // Validar longitud número de documento para AdminSENA: mínimo 8 y máximo 10 dígitos
        if ($datos['rol'] === 'AdminSENA' && (strlen($datos['numero_documento']) < 8 || strlen($datos['numero_documento']) > 10)) {
            $errores[] = 'El número de documento debe tener entre 8 y 10 dígitos.';
        }
        // Validar duplicados de número de documento para creación
        // Si es edición, pasar el id del usuario para excluirlo de la validación
        if (isset($datos['id']) && $datos['id'] !== '') {
            $GLOBALS['usuario_id_edicion'] = $datos['id'];
        } else {
            $GLOBALS['usuario_id_edicion'] = null;
        }
        if (UsuarioModel::existeNumeroDocumento($datos['numero_documento'])) $errores[] = 'El número de documento ya está registrado.';
        if (UsuarioModel::existeTelefono($datos['telefono'])) $errores[] = 'El número telefónico ya está registrado.';
        if (strlen($contrasena) < 12) $errores[] = 'La contraseña debe tener al menos 12 caracteres.';
        $datos['contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
    } else {
        $errores[] = 'Rol no válido.';
    }

    if (!empty($errores)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errores]);
        exit;
    }

    try {
        $creado = UsuarioModel::crear($datos);
        if ($creado) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'redirect' => 'http://localhost/senalink5/senalink5/senalink/html/index.html']);
            exit;
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// 🟢 POST: Actualizar estado del usuario
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['accion']) && $_POST['accion'] === 'actualizarEstado' &&
    isset($_POST['id']) && isset($_POST['estado'])
) {
    $id = $_POST['id'];
    $nuevoEstado = ucfirst(strtolower(trim($_POST['estado'])));
    if (!in_array($nuevoEstado, ['Activo', 'Desactivado'])) {
        echo json_encode(['success' => false, 'error' => 'Estado no válido.']);
        exit;
    }
    $resultado = UsuarioModel::actualizarEstado($id, $nuevoEstado);
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? "Estado actualizado correctamente." : "Error al actualizar estado."
    ]);
    exit;
}

// Validación para crear empresa: razón social única
if (isset($_POST['accion']) && $_POST['accion'] === 'crearEmpresa') {
    header('Content-Type: application/json');
    $razon_social = isset($_POST['razon_social']) ? trim($_POST['razon_social']) : '';
    if (UsuarioModel::existeRazonSocial($razon_social)) {
        echo json_encode([
            'success' => false,
            'error' => 'La razón social ya está registrada. Por favor ingrese una diferente.'
        ]);
        exit;
    }
    if (!preg_match('/\b(S\.A\.?|S\.A\.S\.?|LTDA)\b/i', $razon_social)) {
        echo json_encode([
            'success' => false,
            'error' => 'La razón social debe incluir el tipo societario: S.A, S.A.S o LTDA.'
        ]);
        exit;
    }
    $nit = isset($_POST['nit']) ? trim($_POST['nit']) : '';
    if (!preg_match('/^\d{9}$/', $nit)) {
        echo json_encode([
            'success' => false,
            'error' => 'El NIT debe tener exactamente 9 dígitos numéricos.'
        ]);
        exit;
    }
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    if (!preg_match('/^\d{10}$/', $telefono)) {
        echo json_encode([
            'success' => false,
            'error' => 'El teléfono debe tener exactamente 10 dígitos numéricos.'
        ]);
        exit;
    }
}

// 🟢 GET: Listar AdminSENA por estado
if (isset($_GET['action']) && $_GET['action'] === 'listarAdminSENA') {
    header('Content-Type: application/json');
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'Activo';
    $usuarioModel = new UsuarioModel();
    $usuarios = $usuarioModel->obtenerUsuariosPorRolYEstado('AdminSENA', $estado);
    echo json_encode(['success' => true, 'data' => $usuarios]);
    exit;
}

// 🟢 GET: Detalle de usuario autenticado
if (isset($_GET['action']) && $_GET['action'] === 'detalleUsuario') {
    header('Content-Type: application/json');
    $usuarioId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null);
    if (!$usuarioId) {
        error_log("❌ Error: id de usuario no proporcionado ni en sesión");
        echo json_encode(['success' => false, 'error' => 'Usuario no autenticado ni id proporcionado']);
        exit;
    }

    $usuarioModel = new UsuarioModel();
    $usuario = $usuarioModel->obtenerUsuarioPorId($usuarioId);

    if (!$usuario) {
        error_log("❌ Usuario no encontrado para ID: $usuarioId");
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $usuario]);
    exit;
}

// 🟢 POST: Filtrar empresas por estado
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['accion']) && $_POST['accion'] === 'filtrarPorEstado' &&
    isset($_POST['estado'])
) {
    $estado = $_POST['estado'];
    $empresas = UsuarioModel::getEmpresasPorEstado($estado);
    if (empty($empresas)) {
        echo '<p>No se encontraron empresas.</p>';
    } else {
        echo '<table class="tabla-empresas">';
        echo '<tr><th>ID</th><th>Razón Social</th><th>NIT</th></tr>';
        foreach ($empresas as $empresa) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($empresa['id']) . '</td>';
            echo '<td>' . htmlspecialchars($empresa['razon_social']) . '</td>';
            echo '<td>' . htmlspecialchars($empresa['nit']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    exit;
}

// 🟡 Si no coincide ninguna acción y es GET sin 'action', redirigir
if (php_sapi_name() !== 'cli') {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET['action'])) {
        echo '<script>window.location.href = "http://localhost/senalink5/senalink5/senalink/html/index.html";</script>';
        exit;
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    exit;
}
