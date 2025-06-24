<?php
header('Content-Type: application/json');
require_once '../config/conn.php';

try {
    if (!isset($_GET['curso_id']) || empty($_GET['curso_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID del curso es requerido'
        ]);
        exit;
    }
    
    $curso_id = intval($_GET['curso_id']);
    
    $query = "SELECT num, horario FROM horario_cursos WHERE id_curso = ? ORDER BY num";
    $stmt = mysqli_prepare($enlace, $query);
    
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . mysqli_error($enlace));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $curso_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $horarios = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $horarios[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $horarios
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

mysqli_close($enlace);
?>
