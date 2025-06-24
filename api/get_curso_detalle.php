<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID del curso es requerido']);
        exit;
    }
    
    $curso_id = intval($_GET['id']);
    
    // Obtener datos del curso
    $query = "SELECT id, curso, status FROM cursos WHERE id = ?";
    $stmt = mysqli_prepare($enlace, $query);
    mysqli_stmt_bind_param($stmt, "i", $curso_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($curso = mysqli_fetch_assoc($result)) {
        // Obtener horarios del curso
        $horarios_query = "SELECT num, horario FROM horario_cursos WHERE id_curso = ? ORDER BY num";
        $stmt_horarios = mysqli_prepare($enlace, $horarios_query);
        mysqli_stmt_bind_param($stmt_horarios, "i", $curso_id);
        mysqli_stmt_execute($stmt_horarios);
        $horarios_result = mysqli_stmt_get_result($stmt_horarios);
        
        $horarios = [];
        while ($horario = mysqli_fetch_assoc($horarios_result)) {
            $horarios[] = $horario;
        }
        
        $curso['horarios'] = $horarios;
        
        echo json_encode(['success' => true, 'data' => $curso]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Curso no encontrado']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
