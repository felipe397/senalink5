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
        $this->db = Conexion::conectar(); // Conexión única en la instancia

        $this->id = $data['id'] ?? null;
        $this->empresa_id = $data['empresa_id'] ?? null;
        $this->respuestas = isset($data['respuestas']) ? json_decode($data['respuestas'], true) : [];
        $this->estado = $data['estado'] ?? null;
    }

    // ✅ Obtener todas las preguntas con sus opciones asociadas
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

    // ✅ Actualizar el enunciado de una pregunta existente
    public function actualizarPregunta($id, $enunciado) {
        $stmt = $this->db->prepare("UPDATE preguntas SET enunciado = ? WHERE id = ?");
        return $stmt->execute([$enunciado, $id]);
    }

    // ✅ Insertar una nueva opción a una pregunta
    public function insertarOpcion($idPregunta, $texto) {
        $stmt = $this->db->prepare("INSERT INTO opciones (id_pregunta, texto) VALUES (?, ?)");
        return $stmt->execute([$idPregunta, $texto]);
    }

    // ✅ Eliminar una opción específica
    public function eliminarOpcion($idOpcion) {
        $stmt = $this->db->prepare("DELETE FROM opciones WHERE id = ?");
        return $stmt->execute([$idOpcion]);
    }

    // ✅ Insertar una nueva pregunta y devolver su ID
    public function insertarPregunta($enunciado) {
        $stmt = $this->db->prepare("INSERT INTO preguntas (enunciado) VALUES (?)");
        $stmt->execute([$enunciado]);
        return $this->db->lastInsertId(); // Devuelve el ID generado
    }

    // ✅ Eliminar una pregunta y sus opciones asociadas
    public function eliminarPregunta($idPregunta) {
        // Primero elimina opciones asociadas
        $stmt = $this->db->prepare("DELETE FROM opciones WHERE id_pregunta = ?");
        $stmt->execute([$idPregunta]);

        // Luego elimina la pregunta
        $stmt2 = $this->db->prepare("DELETE FROM preguntas WHERE id = ?");
        return $stmt2->execute([$idPregunta]);
    }
}
?>
