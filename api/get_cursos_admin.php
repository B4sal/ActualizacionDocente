<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    // Obtener todos los cursos
    $query = "SELECT id, curso, status FROM cursos ORDER BY curso";
    $result = mysqli_query($enlace, $query);
    
    $cursos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Obtener horarios para cada curso
        $horarios_query = "SELECT num, horario FROM horario_cursos WHERE id_curso = ? ORDER BY num";
        $stmt = mysqli_prepare($enlace, $horarios_query);
        mysqli_stmt_bind_param($stmt, "i", $row['id']);
        mysqli_stmt_execute($stmt);
        $horarios_result = mysqli_stmt_get_result($stmt);
        
        $horarios = [];
        while ($horario = mysqli_fetch_assoc($horarios_result)) {
            $horarios[] = $horario;
        }
        
        $row['horarios'] = $horarios;
        $cursos[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $cursos]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener cursos: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
