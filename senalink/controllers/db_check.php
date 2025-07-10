<?php
require_once '../config/Conexion.php';
$db = Conexion::conectar();

// Inserta un log de prueba en una tabla temporal
$db->exec("CREATE TABLE IF NOT EXISTS debug_log (mensaje VARCHAR(255))");
$db->exec("INSERT INTO debug_log (mensaje) VALUES ('🚀 INSERT DESDE db_check.php')");

echo "✅ Insert ejecutado. Revisa si se creó la tabla 'debug_log' en tu phpMyAdmin.";
