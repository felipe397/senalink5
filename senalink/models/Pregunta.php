<?php
require_once __DIR__ . '/../config/pdo.php';

class Pregunta {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function actualizarEnunciado($id, $enunciado) {
        $stmt = $this->pdo->prepare("UPDATE preguntas SET enunciado = ? WHERE id = ?");
        return $stmt->execute([$enunciado, $id]);
    }
}
