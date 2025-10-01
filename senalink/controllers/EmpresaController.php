<?php
// Al principio del archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once '../models/UsuarioModel.php';

// GET action detalleEmpresa
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'detalleEmpresa' && isset($_GET['id'])) {
    $empresa = UsuarioModel::obtenerEmpresaPorId($_GET['id']);
    header('Content-Type: application/json');
    echo json_encode($empresa);
    exit;
}

// GET action listarEmpresasActivas
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listarEmpresasActivas') {
    $empresas = UsuarioModel::listarEmpresasActivas();
    header('Content-Type: application/json');
    echo json_encode($empresas);
    exit;
}

// GET action listarEmpresasInhabilitadas
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listarEmpresasInhabilitadas') {
    $empresas = UsuarioModel::listarEmpresasInhabilitadas();
    header('Content-Type: application/json');
    echo json_encode($empresas);
    exit;
}

// POST action filtrarPorEstado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'filtrarPorEstado') {
        $estado = $_POST['estado'];
        $empresas = UsuarioModel::getEmpresasPorEstado($estado);
        if (empty($empresas)) {
            echo "<p>No se encontraron empresas con estado $estado.</p>";
        } else {
            foreach ($empresas as $empresa) {
                echo "
                <article class='cardh'>
                    <a href='Empresa.html?id={$empresa['id']}'>
                        <div class='card-text'>
                            <h2 class='card-title'>{$empresa['razon_social']}</h2>
                            <p class='card-subtitle'>{$empresa['nit']}</p>
                        </div>
                    </a>
                </article>
                ";
            }
        }
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
        // Validación tipo societario en razón social
        if (!preg_match('/\b(S\.A\.?|S\.A\.S\.?|LTDA)\b/i', $razon_social)) {
            echo json_encode([
                'success' => false,
                'error' => 'La razón social debe incluir el tipo societario: S.A, S.A.S o LTDA.'
            ]);
            exit;
        }
        // Validación NIT de 9 dígitos estrictamente numéricos
        $nit = isset($_POST['nit']) ? trim($_POST['nit']) : '';
        if (!preg_match('/^\d{9}$/', $nit)) {
            echo json_encode([
                'success' => false,
                'error' => 'El NIT debe tener exactamente 9 dígitos numéricos.'
            ]);
            exit;
        }
        // Validación teléfono de 10 dígitos
        $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
        if (!preg_match('/^\d{10}$/', $telefono)) {
            echo json_encode([
                'success' => false,
                'error' => 'El teléfono debe tener exactamente 10 dígitos numéricos.'
            ]);
            exit;
        }
        // Aquí iría el código para crear la empresa normalmente...
        // Ejemplo:
        // $resultado = UsuarioModel::crear($_POST);
        // if ($resultado) {
        //     echo json_encode(['success' => true, 'message' => 'Empresa creada correctamente']);
        // } else {
        //     echo json_encode(['success' => false, 'error' => 'Error al crear la empresa']);
        // }
        // exit;
    }

    // POST action actualizarEmpresa
    if (isset($_POST['accion']) && $_POST['accion'] === 'actualizarEmpresa') {
        header('Content-Type: application/json');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de empresa inválido.'
            ]);
            exit;
        }

        $razon_social = isset($_POST['razon_social']) ? trim($_POST['razon_social']) : '';
        if (UsuarioModel::existeRazonSocial($razon_social, $id)) {
            echo json_encode([
                'success' => false,
                'error' => 'La razón social ya está registrada para otra empresa.'
            ]);
            exit;
        }
        // Validación tipo societario en razón social
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
        if (UsuarioModel::existeNIT($nit, $id)) {
            echo json_encode([
                'success' => false,
                'error' => 'El NIT ya está registrado para otra empresa.'
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
        if (UsuarioModel::existeTelefono($telefono, $id)) {
            echo json_encode([
                'success' => false,
                'error' => 'El teléfono ya está registrado para otra empresa.'
            ]);
            exit;
        }

        $data = $_POST;

        // Convertir array de ciudades a string separado por comas si es un array
        if (isset($data['ciudad']) && is_array($data['ciudad'])) {
            $data['ciudad'] = implode(',', $data['ciudad']);
        }

        require_once '../models/UsuarioModel.php';
        $model = new UsuarioModel();
        try {
            $resultado = $model->updateEmpresa($id, $data);
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Empresa actualizada correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Error al actualizar la empresa.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Error en la base de datos: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}


