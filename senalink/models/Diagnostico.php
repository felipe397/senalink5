<?php
// models/Diagnostico.php
require_once __DIR__ . '/../config/conexion.php';

class Diagnostico {
    public $id;
    public $empresa_id;
    public $respuestas;
    public $estado;
    private $db;

    public function __construct($data = []) {
        $this->db = Conexion::conectar(); // Establece conexión a la BD

        $this->id = $data['id'] ?? null;
        $this->empresa_id = $data['empresa_id'] ?? null;
        $this->respuestas = isset($data['respuestas']) ? json_decode($data['respuestas'], true) : [];
        $this->estado = $data['estado'] ?? null;
    }

    // ✅ Obtener preguntas con sus opciones
    public function obtenerPreguntasConOpciones() {
        $stmt = $this->db->query("SELECT * FROM preguntas");
        $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($preguntas as &$pregunta) {
            $stmt_opciones = $this->db->prepare("SELECT * FROM opciones WHERE id_pregunta = ?");
            $stmt_opciones->execute([$pregunta['id']]);
            $pregunta['opciones'] = $stmt_opciones->fetchAll(PDO::FETCH_ASSOC);
        }

        return $preguntas;
    }

    // ✅ Actualizar enunciado de una pregunta existente
    public function actualizarPregunta($id, $enunciado) {
        $stmt = $this->db->prepare("UPDATE preguntas SET enunciado = ? WHERE id = ?");
        return $stmt->execute([$enunciado, $id]);
    }

    // ✅ Insertar nueva opción a una pregunta existente
    public function insertarOpcion($idPregunta, $texto) {
        $stmt = $this->db->prepare("INSERT INTO opciones (id_pregunta, texto) VALUES (?, ?)");
        return $stmt->execute([$idPregunta, $texto]);
    }

    // ✅ Eliminar una opción existente
    public function eliminarOpcion($idOpcion) {
        $stmt = $this->db->prepare("DELETE FROM opciones WHERE id = ?");
        return $stmt->execute([$idOpcion]);
    }
    public function insertarPregunta($enunciado) {
        $conn = Conexion::conectar();
        $stmt = $conn->prepare("INSERT INTO preguntas (enunciado) VALUES (?)");
        $stmt->execute([$enunciado]);
        return $conn->lastInsertId(); // Devuelve el ID de la nueva pregunta
    }
   public function eliminarPregunta($idPregunta) {
    $conn = Conexion::conectar();
    // Elimina primero las opciones asociadas
    $stmt = $conn->prepare("DELETE FROM opciones WHERE pregunta_id = ?");
    $stmt->execute([$idPregunta]);
    // Luego elimina la pregunta
    $stmt2 = $conn->prepare("DELETE FROM preguntas WHERE id = ?");
    return $stmt2->execute([$idPregunta]);
}
}
?>
