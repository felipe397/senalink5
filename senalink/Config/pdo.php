<?php
require_once __DIR__ . '/db.php';
try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}
