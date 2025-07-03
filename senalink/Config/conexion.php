<?php
class Conexion {
    private static $conn = null;

    public static function conectar() {
        if (self::$conn === null) {
            $host = 'localhost:3307'; // Cambia esto si tu servidor MySQL está en otro host
            // $host = 'localhost:3307'; 
            // $host = 'localhost:8111'; 
            $db = 'senalink';
            $user = 'root';
            $pass = ''; // o tu contraseña si la tienes
            $charset = 'utf8mb4';

            try {
                self::$conn = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
