<?php
require('../fpdf/fpdf.php');

// Función para validar y limpiar campos vacíos
function limpiarCampo($valor) {
    return !empty(trim($valor)) ? $valor : 'No especificado';
}

// Verificar si se enviaron datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $nit = limpiarCampo($_POST['nit'] ?? '');
    $representante = limpiarCampo($_POST['representante'] ?? '');
    $razonSocial = limpiarCampo($_POST['razon_social'] ?? '');
    $telefono = limpiarCampo($_POST['telefono'] ?? '');
    $correo = limpiarCampo($_POST['correo'] ?? '');
    $ubicacion = limpiarCampo($_POST['ubicacion'] ?? '');
    $tipoEmpresa = limpiarCampo($_POST['tipo_empresa'] ?? '');
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
    addRow($pdf, 'Número NIT', $nit);
    addRow($pdf, 'Representante legal', $representante);
    addRow($pdf, 'Razón social', $razonSocial);
    addRow($pdf, 'Número telefónico', $telefono);
    addRow($pdf, 'Correo electrónico', $correo);
    addRow($pdf, 'Ubicación', $ubicacion);
    addRow($pdf, 'Tipo de empresa', $tipoEmpresa);
    addRow($pdf, 'Estado', $estado);

    // Descargar el PDF
    $pdf->Output('D', 'reporte_empresa.pdf');
} else {
    echo "Acceso no autorizado.";
}
