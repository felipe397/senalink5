<?php
require_once '../models/Programa.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : null;
    $ficha = isset($_POST['ficha']) ? trim($_POST['ficha']) : null;
    $nivel_formacion = isset($_POST['nivel_formacion']) ? trim($_POST['nivel_formacion']) : null;
    $sector_programa = isset($_POST['sector_programa']) ? trim($_POST['sector_programa']) : null;
    $nombre_programa = isset($_POST['nombre_programa']) ? trim($_POST['nombre_programa']) : null;
    $duracion_meses = isset($_POST['duracion_meses']) ? trim($_POST['duracion_meses']) : null;
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : 'Disponible';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $habilidades_requeridas = isset($_POST['habilidades_requeridas']) ? trim($_POST['habilidades_requeridas']) : null;
    $fecha_finalizacion = isset($_POST['fecha_finalizacion']) ? trim($_POST['fecha_finalizacion']) : null;

    if ($id !== null && $codigo !== '' && $ficha !== '' && $nivel_formacion !== '' && $nombre_programa !== '' && $duracion_meses !== '' && $estado !== '' && $descripcion !== '' && $habilidades_requeridas !== '' && $fecha_finalizacion !== '') {
        $programa = new ProgramaFormacion();
        $resultado = $programa->update($id, [
            'codigo' => $codigo,
            'ficha' => $ficha,
            'nivel_formacion' => $nivel_formacion,
            'sector_programa' => $sector_programa,
            'nombre_programa' => $nombre_programa,
            'duracion_meses' => $duracion_meses,
            'estado' => $estado,
            'descripcion' => $descripcion,
            'habilidades_requeridas' => $habilidades_requeridas,
            'fecha_finalizacion' => $fecha_finalizacion
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
