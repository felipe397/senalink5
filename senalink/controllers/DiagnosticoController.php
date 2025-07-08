<?php
require_once '../config/conexion.php';

class DiagnosticoController {
    private $db;
    
    public function __construct() {
        $this->db = new Conexion();
    }

     public function obtenerDiagnosticoCompleto() {
        try {
            $conn = Conexion::conectar();
            
            // Obtener preguntas
            $query = "SELECT * FROM preguntas ORDER BY id";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtener opciones para cada pregunta
            foreach ($preguntas as &$pregunta) {
                $query = "SELECT * FROM opciones WHERE id_pregunta = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$pregunta['id']]);
                $pregunta['opciones'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return [
                'success' => true,
                'preguntas' => $preguntas
            ];
        } catch (PDOException $e) {
            error_log("Error en obtenerDiagnosticoCompleto: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al obtener preguntas'
            ];
        }
    }

    // Procesar respuestas y generar recomendaciones
    public function procesarRespuestas($empresaId, $respuestas) {
        try {
            $conn = Conexion::conectar();
            
            // Convertir respuestas a JSON para almacenamiento
            $resultadoJson = json_encode($respuestas);
            
            // Guardar diagnóstico en la base de datos
            $query = "INSERT INTO diagnosticos_empresariales (empresa_id, resultado) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$empresaId, $resultadoJson]);
            
            // Generar recomendaciones basadas en las respuestas
            $recomendaciones = $this->generarRecomendaciones($respuestas);
            
            return [
                'success' => true,
                'recomendaciones' => $recomendaciones
            ];
        } catch (PDOException $e) {
            error_log("Error en procesarRespuestas: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al procesar respuestas'
            ];
        }
    }

    // Generar recomendaciones basadas en las respuestas
   private function generarRecomendaciones($respuestas) {
    try {
        $conn = Conexion::conectar();
        
        // Base de la consulta
        $query = "SELECT * FROM programas_formacion WHERE estado = 'En ejecucion'";
        $params = [];
        
        // Filtro por sector económico
        if (!empty($respuestas['pregunta1'])) {
            $sectores = $this->mapearSectorEconomico($respuestas['pregunta1']);
            if ($sectores) {
                $placeholders = implode(',', array_fill(0, count($sectores), '?'));
                $query .= " AND sector_economico IN ($placeholders)";
                $params = array_merge($params, $sectores);
            }
        }
        
        // Filtro por nivel de formación
        if (!empty($respuestas['pregunta3'])) {
            $query .= " AND nivel_formacion = ?";
            $params[] = $respuestas['pregunta3'];
        }
        
        // Filtro por ocupación (pregunta 2)
        if (!empty($respuestas['pregunta2'])) {
            $query .= " AND nombre_ocupacion LIKE ?";
            $params[] = '%'.$respuestas['pregunta2'].'%';
        }
        
        // Filtro por área temática (pregunta 5)
        if (!empty($respuestas['pregunta5'])) {
            $query .= " AND (descripcion LIKE ? OR habilidades_requeridas LIKE ?)";
            $params[] = '%'.$respuestas['pregunta5'].'%';
            $params[] = '%'.$respuestas['pregunta5'].'%';
        }
        
        // Ordenar por relevancia
        $query .= " ORDER BY 
            CASE 
                WHEN nombre_ocupacion LIKE ? THEN 0 
                WHEN descripcion LIKE ? THEN 1
                WHEN habilidades_requeridas LIKE ? THEN 2
                ELSE 3
            END,
            fecha_finalizacion DESC
            LIMIT 10";
            
        // Agregar parámetros de ordenación
        $params[] = '%'.$respuestas['pregunta2'].'%';
        $params[] = '%'.$respuestas['pregunta5'].'%';
        $params[] = '%'.$respuestas['pregunta5'].'%';
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en generarRecomendaciones: " . $e->getMessage());
        return [];
    }
}

    // Mapear sector productivo a sector económico de la base de datos
    private function mapearSectorEconomico($sector) {
    $mapeo = [
        'Construcción' => ['Construccion', 'Industrial'],
        'Educación' => ['Servicios'],
        'Electricidad' => ['Electricidad', 'Industrial'],
        'Industrial' => ['Industria', 'Electricidad', 'Construccion'],
        'Salud' => ['Servicios'],
        'Servicios' => ['Servicios', 'Industria'],
        'Textiles' => ['Textiles', 'Industrial'],
        'Transporte' => ['Servicios', 'Industrial']
    ];
    
    return $mapeo[$sector] ?? null;
}
private function calcularPuntuacion($programa, $respuestas) {
    $puntuacion = 0;
    
    // Coincidencia exacta en ocupación
    if (stripos($programa['nombre_ocupacion'], $respuestas['pregunta2']) !== false) {
        $puntuacion += 30;
    }
    
    // Coincidencia en área temática
    if (stripos($programa['descripcion'], $respuestas['pregunta5']) !== false || 
        stripos($programa['habilidades_requeridas'], $respuestas['pregunta5']) !== false) {
        $puntuacion += 20;
    }
    
    // Coincidencia en sector económico
    $sectores = $this->mapearSectorEconomico($respuestas['pregunta1']);
    if (in_array($programa['sector_economico'], $sectores)) {
        $puntuacion += 15;
    }
    
    // Coincidencia en nivel de formación
    if ($programa['nivel_formacion'] == $respuestas['pregunta3']) {
        $puntuacion += 10;
    }
    
    // Programas que finalizan pronto tienen prioridad
    $hoy = new DateTime();
    $fin = new DateTime($programa['fecha_finalizacion']);
    $diasRestantes = $hoy->diff($fin)->days;
    
    if ($diasRestantes < 180) { // Menos de 6 meses
        $puntuacion += 5;
    }
    
    return $puntuacion;
}
    // Obtener recomendaciones guardadas para una empresa
    public function obtenerRecomendaciones($empresaId) {
        try {
            $conn = Conexion::conectar();
            
            $query = "SELECT resultado FROM diagnosticos_empresariales WHERE empresa_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$empresaId]);
            $diagnostico = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($diagnostico) {
                $respuestas = json_decode($diagnostico['resultado'], true);
                return $this->generarRecomendaciones($respuestas);
            }
            
            return [];
        } catch (PDOException $e) {
            return [];
        }
    }
}

// Manejo de solicitudes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $controller = new DiagnosticoController();
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['accion'])) {
        switch ($data['accion']) {
            case 'obtenerDiagnosticoCompleto':
                echo json_encode($controller->obtenerDiagnosticoCompleto());
                break;
                
            case 'procesarRespuestas':
                if (isset($data['empresaId']) && isset($data['respuestas'])) {
                    echo json_encode($controller->procesarRespuestas($data['empresaId'], $data['respuestas']));
                } else {
                    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                }
                break;
                
                
            case 'obtenerRecomendaciones':
                if (isset($data['empresaId'])) {
                    echo json_encode($controller->obtenerRecomendaciones($data['empresaId']));
                }
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
    }
}
?>