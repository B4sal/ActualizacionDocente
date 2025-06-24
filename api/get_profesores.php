<?php
header('Content-Type: application/json');
require_once '../config/conn.php';

try {
    // Construir la consulta base
    $query = "SELECT r.id_profesor, r.id_periodo as periodo, r.nombre, r.expediente, r.correo, 
                     c.curso, hc.horario, r.id_curso, r.id_horario_curso
              FROM registro r
              JOIN cursos c ON r.id_curso = c.id
              JOIN horario_cursos hc ON r.id_curso = hc.id_curso AND r.id_horario_curso = hc.num
              WHERE 1=1";
    
    $params = [];
    $types = "";
    
    // Agregar filtros según los parámetros GET
    if (isset($_GET['periodo']) && !empty($_GET['periodo'])) {
        $query .= " AND r.id_periodo = ?";
        $params[] = $_GET['periodo'];
        $types .= "s";
    }
    
    if (isset($_GET['curso']) && !empty($_GET['curso'])) {
        $query .= " AND r.id_curso = ?";
        $params[] = intval($_GET['curso']);
        $types .= "i";
    }
    
    if (isset($_GET['horario']) && !empty($_GET['horario'])) {
        $query .= " AND r.id_horario_curso = ?";
        $params[] = intval($_GET['horario']);
        $types .= "i";
    }
    
    $query .= " ORDER BY r.nombre ASC";
    
    $stmt = mysqli_prepare($enlace, $query);
    
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . mysqli_error($enlace));
    }
    
    // Bind parameters if any
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $profesores = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $profesores[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $profesores
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

mysqli_close($enlace);
?>
