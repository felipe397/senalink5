<?php
session_start(); // Asegúrate de iniciar sesión

require_once '../models/Programa.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;
    $codigo = $_POST['codigo'] ?? null;
    $ficha = $_POST['ficha'] ?? null;
    $nivel_formacion = $_POST['nivel_formacion'] ?? null;
    $sector_programa = $_POST['sector_programa'] ?? null;
    $etapa_ficha = $_POST['etapa_ficha'] ?? null;
    $sector_economico = $_POST['sector_economico'] ?? null;
    $nombre_ocupacion = $_POST['nombre_ocupacion'] ?? null;
    $nombre_programa = $_POST['nombre_programa'] ?? null;
    $duracion_programa = $_POST['duracion_programa'] ?? null;
    $estado = $_POST['estado'] ?? 'En ejecucion';
    $fecha_finalizacion = $_POST['fecha_finalizacion'] ?? null;

    if (
        $id !== null && $codigo !== '' && $ficha !== '' && $nivel_formacion !== '' &&
        $sector_programa !== '' && $etapa_ficha !== '' && $sector_economico !== '' &&
        $nombre_ocupacion !== '' && $nombre_programa !== '' && $duracion_programa !== '' &&
        $estado !== '' && $fecha_finalizacion !== ''
    ) {
        $programa = new ProgramaFormacion();
        $resultado = $programa->update($id, [
            'codigo' => $codigo,
            'ficha' => $ficha,
            'nivel_formacion' => $nivel_formacion,
            'sector_programa' => $sector_programa,
            'etapa_ficha' => $etapa_ficha,
            'sector_economico' => $sector_economico,
            'duracion_programa' => $duracion_programa,
            'nombre_ocupacion' => $nombre_ocupacion,
            'nombre_programa' => $nombre_programa,
            'fecha_finalizacion' => $fecha_finalizacion,
            'estado' => $estado
        ]);

        if ($resultado) {
            // ✅ Redirección según el rol de sesión
            $rol = $_SESSION['rol'] ?? '';
            switch ($rol) {
                case 'super_admin':
                    header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
                    break;
                case 'AdminSENA':
                    header("Location: ../html/AdminSENA/Programa_Formacion/Gestion_Programa.html");
                    break;
                default:
                    header("Location: ../html/index.html");
                    break;
            }
            exit;
        } else {
            echo "❌ Error al actualizar el programa.";
        }
    } else {
        echo "⚠️ Todos los campos son obligatorios.";
    }
} else {
    echo "⛔ Método no permitido.";
}
