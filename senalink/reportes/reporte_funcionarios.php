<?php
require('../fpdf/fpdf.php');

// Función para validar y limpiar campos vacíos
function limpiarCampo($valor) {
    return !empty(trim($valor)) ? $valor : 'No especificado';
}

// Verificar si se enviaron datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $id = limpiarCampo($_POST['id'] ?? '');
    $primer_nombre = limpiarCampo($_POST['primer_nombre'] ?? '');
    $segundo_nombre = limpiarCampo($_POST['segundo_nombre'] ?? '');
    $primer_apellido= limpiarCampo($_POST['primer_apellido'] ?? '');
    $segundo_apellido = limpiarCampo($_POST['segundo_apellido'] ?? '');
    $telefono = limpiarCampo($_POST['telefono'] ?? '');
    $correo = limpiarCampo($_POST['correo'] ?? '');
    $numero_documento = limpiarCampo($_POST['numero_documento'] ?? '');
    $tipo_documento = limpiarCampo($_POST['tipo_documento'] ?? '');
    $estado = limpiarCampo($_POST['estado'] ?? '');

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // (Opcional) Logo en el encabezado
    $pdf->Image('../img/logo-sena-green0.png', 10, 6, 20);
    // $pdf->Ln(20);

    // Título
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0);
    $pdf->Cell(190, 10, utf8_decode('Reporte de Empresa'), 0, 1, 'C');

    // Fecha del reporte (esquina superior derecha)
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
    addRow($pdf, 'Primer nombre', $primer_nombre);
    addRow($pdf, 'Segundo Nombre', $segundo_nombre);
    addRow($pdf, 'Primer apellido', $primer_apellido);
    addRow($pdf, 'Segundo apellido', $segundo_apellido);
    addRow($pdf, 'Número telefónico', $telefono);
    addRow($pdf, 'Correo electrónico', $correo);
    addRow($pdf, 'Numero de documento', $numero_documento);
    addRow($pdf, 'Tipo de documento', $tipo_documento);
    addRow($pdf, 'Estado', $estado);

    // Descargar el PDF
    $pdf->Output('D', 'reporte_funcionario.pdf');
} else {
    echo "Acceso no autorizado.";
}
