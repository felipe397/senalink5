<?php
require_once '../models/Programa.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : null;
    $ficha = isset($_POST['ficha']) ? trim($_POST['ficha']) : null;
    $nivel_formacion = isset($_POST['nivel_formacion']) ? trim($_POST['nivel_formacion']) : null;
    $sector_programa = isset($_POST['sector_programa']) ? trim($_POST['sector_programa']) : null;
    $etapa_ficha = isset($_POST['etapa_ficha']) ? trim($_POST['etapa_ficha']) : null;
    $sector_economico = isset($_POST['sector_economico']) ? trim($_POST['sector_economico']) : null;
    $nombre_ocupacion = isset($_POST['nombre_ocupacion']) ? trim($_POST['nombre_ocupacion']) : null;
    $nombre_programa = isset($_POST['nombre_programa']) ? trim($_POST['nombre_programa']) : null;
    $duracion_programa = isset($_POST['duracion_programa']) ? trim($_POST['duracion_programa']) : null;
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : 'En ejecucion';
    $habilidades_requeridas = isset($_POST['habilidades_requeridas']) ? trim($_POST['habilidades_requeridas']) : null;
    $fecha_finalizacion = isset($_POST['fecha_finalizacion']) ? trim($_POST['fecha_finalizacion']) : null;

    if ($id !== null && $codigo !== '' && $ficha !== '' && $nivel_formacion !== '' && $sector_programa !== '' && $etapa_ficha !== '' && $sector_economico !== '' && $nombre_ocupacion !== '' && $nombre_programa !== '' && $duracion_programa !== '' && $estado !== '' && $fecha_finalizacion !== '') {
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
            'habilidades_requeridas' => $habilidades_requeridas,
            'fecha_finalizacion' => $fecha_finalizacion,
            'estado' => $estado
        ]);

        // Redirección dinámica según el origen
        $origen = isset($_POST['origen']) ? $_POST['origen'] : '';
        if ($resultado) {
            if ($origen === 'Super_Admin') {
                header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
            } else {
                header("Location: ../html/AdminSENA/Programa_Formacion/Gestion_Programa.html");
            }
            exit();
        } else {
            echo "❌ Error al actualizar el programa.";
        }
    } else {
        echo "⚠️ Todos los campos son obligatorios.";
    }
} else {
    echo "⛔ Método no permitido.";
}
