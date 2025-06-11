<?php
require_once '../models/Programa.php'; // Asegúrate que esta ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger datos del formulario
    $codigo              = $_POST['codigo'] ?? null;
    $ficha               = $_POST['ficha'] ?? null;
    $nivel_formacion     = $_POST['nivel_formacion'] ?? null;
    $nombre_programa     = $_POST['nombre_programa'] ?? null;
    $duracion_meses      = $_POST['duracion_meses'] ?? null;
    $estado              = $_POST['estado'] ?? 'Disponible'; // Valor por defecto
    $descripcion         = $_POST['descripcion'] ?? null;
    $habilidades_requeridas = $_POST['habilidades_requeridas'] ?? null;
    $fecha_finalizacion  = $_POST['fecha_finalizacion'] ?? null;

    // Validar que no falte ningún dato
    if (
        $codigo && $ficha && $nivel_formacion && $nombre_programa &&
        $duracion_meses && $estado && $descripcion && $habilidades_requeridas && $fecha_finalizacion
    ) {
        require_once '../config/Conexion.php'; // Asegúrate que existe esta clase y conecta

        $programa = new ProgramaFormacion(); // Clase en Programa.php

        $resultado = $programa->crear([
            'codigo'                 => $codigo,
            'ficha'                  => $ficha,
            'nivel_formacion'        => $nivel_formacion,
            'nombre_programa'        => $nombre_programa,
            'duracion_meses'         => $duracion_meses,
            'estado'                 => $estado,
            'descripcion'            => $descripcion,
            'habilidades_requeridas' => $habilidades_requeridas,
            'fecha_finalizacion'     => $fecha_finalizacion
        ]);

        if ($resultado) {
            header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html");
            exit();
        } else {
            echo "❌ Error al guardar el programa.";
        }
    } else {
        echo "⚠️ Todos los campos son obligatorios.";
    }
} else {
    echo "⛔ Método no permitido.";
}

