<?php
require_once '../Config/conexion.php'; // Asegúrate que aquí se crea un objeto PDO llamado $conexion

$busqueda = $_GET['q'] ?? '';
$sql = "SELECT * FROM empresas WHERE nombre LIKE ? OR nit LIKE ?";
$stmt = $conexion->prepare($sql);
$like = "%$busqueda%";
$stmt->bindValue(1, $like);
$stmt->bindValue(2, $like);
$stmt->execute();

$empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($empresas);
?>
