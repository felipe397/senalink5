<<?php
require_once '../models/Programa.php'; // Ajusta si tu ruta es diferente

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = $_POST['codigo'] ?? null;
    $ficha = $_POST['ficha'] ?? null;
    $nivel = $_POST['nivel_formacion'] ?? null;
    $nombre = $_POST['nombre_programa'] ?? null;
    $duracion = $_POST['duracion_meses'] ?? null;
    $estado = $_POST['estado'] ?? 'Disponible';
    $descripcion = $_POST['descripcion'] ?? null;
    $habilidades = $_POST['habilidades_requeridas'] ?? null;
    $fecha_finalizacion = $_POST['fecha_finalizacion'] ?? null;

    if (
        $codigo && $ficha && $nivel && $nombre  &&
        $duracion && $estado && $descripcion && $habilidades && $fecha_finalizacion
    ) {
        $programa = new ProgramaFormacion();
        $resultado = $programa->crear([
            'codigo' => $codigo,
            'ficha' => $ficha,
            'nivel' => $nivel_formacion,
            'nombre' => $nombre_programa,
            'duracion' => $duracion_meses,
            'estado' => $estado,
            'descripcion' => $descripcion,
            'habilidades' => $habilidades_requeridas,
            'fecha_finalizacion' => $fecha_finalizacion
        ]);

        if ($resultado) {
            header("Location: ../html/Super_Admin/Programa_Formacion/Gestion_Programa.html"); // Redirecciona a una página de éxito
            exit();
        } else {
            echo "Error al guardar el programa.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Método no permitido.";
}
