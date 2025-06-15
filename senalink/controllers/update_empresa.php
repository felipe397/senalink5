<?php
require_once '../models/UsuarioModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;
    $nit = $_POST['nit'] ?? null;
    $representante_legal = $_POST['representante_legal'] ?? null;
    $razon_social = $_POST['razon_social'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $correo = $_POST['correo'] ?? null;
    $ubicacion = $_POST['ubicacion'] ?? null;
    $tipo_empresa = $_POST['tipo_empresa'] ?? null;

    if ($id && $nit && $representante_legal && $razon_social && $telefono && $correo && $ubicacion && $tipo_empresa) {
        // Validar que tipo_empresa sea una de las opciones válidas
        $opciones_validas = ['Agrícola', 'Industrial', 'Servicios', 'Conocimiento, Innovacion y Desarrollo'];
        if (!in_array($tipo_empresa, $opciones_validas)) {
            echo "⚠️ El tipo de empresa no es válido.";
            exit;
        }

        $usuarioModel = new UsuarioModel();
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
            header("Location: ../html/Super_Admin/Empresa/Gestion_Empresa.html");
            exit();
        } else {
            echo "❌ Error al actualizar la empresa.";
        }
    } else {
        echo "⚠️ Todos los campos son obligatorios.";
    }
} else {
    echo "⛔ Método no permitido.";
}
