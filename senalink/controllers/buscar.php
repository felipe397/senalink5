<?php
require_once '../Config/conexion.php'; // Llama tu clase de conexión

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$busqueda = $_GET['q'] ?? '';
$sql = "SELECT * FROM empresas WHERE nombre LIKE ? OR nit LIKE ?";
$stmt = $conexion->prepare($sql);
$like = "%$busqueda%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$resultado = $stmt->get_result();

$empresas = [];
while ($fila = $resultado->fetch_assoc()) {
    $empresas[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($empresas);
?>
