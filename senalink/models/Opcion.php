<?php
require_once __DIR__ . '/../config/db.php';

class Opcion {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function insertarOpcion($idPregunta, $texto) {
        $stmt = $this->pdo->prepare("INSERT INTO opciones (id_pregunta, texto) VALUES (?, ?)");
        return $stmt->execute([$idPregunta, $texto]);
    }

    public function borrarOpcion($idOpcion) {
        $stmt = $this->pdo->prepare("DELETE FROM opciones WHERE id = ?");
        return $stmt->execute([$idOpcion]);
    }
}
