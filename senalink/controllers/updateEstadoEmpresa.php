<?php
require_once '../Config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';

    if ($id > 0 && ($estado === 'activo' || $estado === 'Desactivado')) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($db->connect_error) {
            error_log("Error de conexión a la base de datos: " . $db->connect_error);
            echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
            exit;
        }

        $stmt = $db->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $db->error);
            echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
            exit;
        }
        $stmt->bind_param("si", $estado, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } else {
            error_log("Error al ejecutar la consulta: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
        }

        $stmt->close();
        $db->close();
    } else {
        error_log("Parámetros inválidos: id=$id, estado=$estado");
        echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
    }
} else {
    error_log("Método no permitido: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
