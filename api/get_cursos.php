<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    // Si se proporciona un período, filtrar cursos que tienen encabezado en ese período
    if (isset($_GET['periodo']) && !empty($_GET['periodo'])) {
        $periodo = mysqli_real_escape_string($enlace, $_GET['periodo']);
        $query = "SELECT c.id, c.curso 
                  FROM cursos c 
                  INNER JOIN encabezado e ON c.id = e.id_curso 
                  WHERE c.status = 'A' AND e.periodo = '$periodo' 
                  ORDER BY c.curso";
    } else {
        // Si no se especifica período, devolver todos los cursos activos
        $query = "SELECT id, curso FROM cursos WHERE status = 'A' ORDER BY curso";
    }
    
    $result = mysqli_query($enlace, $query);
    
    $cursos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cursos[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $cursos]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener cursos']);
}

mysqli_close($enlace);
?>
