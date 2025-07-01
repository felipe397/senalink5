<?php
require_once __DIR__ . '/../config/pdo.php';

class PreguntaOpcion {
    private $pdo;
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    // Obtener todas las preguntas con sus opciones
    public function obtenerPreguntasConOpciones() {
        $preguntas = [];
        try {
            $stmt = $this->pdo->query('SELECT * FROM preguntas');
            while ($preg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $preg['opciones'] = [];
                $stmt2 = $this->pdo->prepare('SELECT * FROM opciones WHERE id_pregunta = ?');
                $stmt2->execute([$preg['id']]);
                while ($opc = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    $preg['opciones'][] = $opc;
                }
                $preguntas[] = $preg;
            }
        } catch (Exception $e) {
            // Mostrar error en la respuesta AJAX
            die(json_encode(['success'=>false,'message'=>'Error SQL: '.$e->getMessage()]));
        }
        return $preguntas;
    }
}
