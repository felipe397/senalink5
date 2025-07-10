<?php
/**
 * Configuración de la base de datos
 * Este archivo contiene las constantes de conexión
 */

// Constantes de configuración de la base de datos
define('DB_HOST', 'localhost'); // Cambia el puerto si es necesario
// define('DB_HOST', 'localhost:8111'); // Otra opción de puerto si es
define('DB_NAME', 'senalink');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');

// Opciones para PDO
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
]);

// String de conexión DSN
define('DB_DSN', "mysql:host=" . DB_HOST . 
                ";dbname=" . DB_NAME . 
                ";charset=" . DB_CHARSET);
?>
