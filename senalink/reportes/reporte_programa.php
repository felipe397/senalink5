<?php
require('../fpdf/fpdf.php');

// Función para validar y limpiar campos vacíos
function limpiarCampo($valor) {
    return !empty(trim($valor)) ? $valor : 'No especificado';
}

// Verificar si se enviaron datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $codigo = limpiarCampo($_POST['codigo'] ?? '');
    $ficha = limpiarCampo($_POST['ficha'] ?? '');
    $nivel_formacion = limpiarCampo($_POST['nivel_formacion'] ?? '');
    $nombre_programa = limpiarCampo($_POST['nombre_programa'] ?? '');
    $duracion_programa = limpiarCampo($_POST['duracion_programa'] ?? '');
    $nombre_ocupacion = limpiarCampo($_POST['nombre_ocupacion'] ?? '');
    $sector_programa = limpiarCampo($_POST['sector_programa'] ?? '');
    $etapa_ficha = limpiarCampo($_POST['etapa_ficha'] ?? '');
    $sector_economico = limpiarCampo($_POST['sector_economico'] ?? '');
    $fecha_finalizacion = limpiarCampo($_POST['fecha_finalizacion'] ?? '');
    $estado = limpiarCampo($_POST['estado'] ?? '');

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Logo (opcional)
    $pdf->Image('../img/logo-sena-green0.png', 10, 6, 20);

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0);
    $pdf->Cell(190, 10, utf8_decode('Reporte de Programa'), 0, 1, 'C');

    // Fecha del reporte
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(190, 10, 'Fecha: ' . date('d/m/Y'), 0, 1, 'R');

    // Línea divisoria
    $pdf->SetDrawColor(100, 100, 100);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(10);

    // Estilo para celdas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(30, 77, 96); // azul oscuro
    $pdf->SetTextColor(255); // texto blanco

    // Función para crear filas
    function addRow($pdf, $label, $value) {
        $pdf->Cell(65, 10, utf8_decode($label), 1, 0, 'L', true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0);
        $pdf->Cell(125, 10, utf8_decode($value), 1, 1, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(255);
    }

    // Agregar datos
    addRow($pdf, 'Código', $codigo);
    addRow($pdf, 'Número de ficha', $ficha);
    addRow($pdf, 'Nivel de formación', $nivel_formacion);
    addRow($pdf, 'Nombre del programa', $nombre_programa);
    addRow($pdf, 'Duración del programa', $duracion_programa);
    addRow($pdf, 'Nombre de la ocupación', $nombre_ocupacion);
    addRow($pdf, 'Sector del programa', $sector_programa);
    addRow($pdf, 'Etapa de ficha', $etapa_ficha);
    addRow($pdf, 'Sector económico', $sector_economico);
    addRow($pdf, 'Fecha de finalización', $fecha_finalizacion);
    addRow($pdf, 'Estado', $estado);

    // Descargar el PDF
    $pdf->Output('D', 'reporte_programa.pdf');
} else {
    echo "Acceso no autorizado.";
}
