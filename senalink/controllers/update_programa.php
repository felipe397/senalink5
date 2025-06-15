<?php
require_once '../models/Programa.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;
    $codigo = $_POST['codigo'] ?? null;
    $ficha = $_POST['ficha'] ?? null;
    $nivel_formacion = $_POST['nivel_formacion'] ?? null;
    $nombre_programa = $_POST['nombre_programa'] ?? null;
    $duracion_meses = $_POST['duracion_meses'] ?? null;
    $estado = $_POST['estado'] ?? 'Disponible';
    $descripcion = $_POST['descripcion'] ?? null;
    $habilidades_requeridas = $_POST['habilidades_requeridas'] ?? null;
    $fecha_finalizacion = $_POST['fecha_finalizacion'] ?? null;

    if ($id && $codigo && $ficha && $nivel_formacion && $nombre_programa && $duracion_meses && $estado && $descripcion && $habilidades_requeridas && $fecha_finalizacion) {
        $programa = new ProgramaFormacion();
        $resultado = $programa->update($id, [
            'codigo' => $codigo,
            'ficha' => $ficha,
            'nivel_formacion' => $nivel_formacion,
            'nombre_programa' => $nombre_programa,
            'duracion_meses' => $duracion_meses,
            'estado' => $estado,
            'descripcion' => $descripcion,
            'habilidades_requeridas' => $habilidades_requeridas,
            'fecha_finalizacion' => $fecha_finalizacion
        ]);

        if ($resultado) {
            header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
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
