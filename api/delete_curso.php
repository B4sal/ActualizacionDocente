<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID del curso inválido']);
        exit;
    }
    
    // Verificar que el curso existe
    $check_query = "SELECT curso FROM cursos WHERE id = ?";
    $check_stmt = mysqli_prepare($enlace, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (!($curso = mysqli_fetch_assoc($check_result))) {
        echo json_encode(['success' => false, 'message' => 'Curso no encontrado']);
        exit;
    }
    
    // Verificar si hay registros de profesores asociados
    $registros_query = "SELECT COUNT(*) as total FROM registro WHERE id_curso = ?";
    $registros_stmt = mysqli_prepare($enlace, $registros_query);
    mysqli_stmt_bind_param($registros_stmt, "i", $id);
    mysqli_stmt_execute($registros_stmt);
    $registros_result = mysqli_stmt_get_result($registros_stmt);
    $registros_count = mysqli_fetch_assoc($registros_result)['total'];
    
    if ($registros_count > 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'No se puede eliminar el curso porque tiene profesores registrados. Elimine primero los registros asociados.'
        ]);
        exit;
    }
    
    // Verificar si hay encabezados asociados
    $encabezados_query = "SELECT COUNT(*) as total FROM encabezado WHERE id_curso = ?";
    $encabezados_stmt = mysqli_prepare($enlace, $encabezados_query);
    mysqli_stmt_bind_param($encabezados_stmt, "i", $id);
    mysqli_stmt_execute($encabezados_stmt);
    $encabezados_result = mysqli_stmt_get_result($encabezados_stmt);
    $encabezados_count = mysqli_fetch_assoc($encabezados_result)['total'];
    
    if ($encabezados_count > 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'No se puede eliminar el curso porque tiene encabezados asociados. Elimine primero los encabezados asociados.'
        ]);
        exit;
    }
    
    // Iniciar transacción
    mysqli_begin_transaction($enlace);
    
    try {
        // Eliminar horarios del curso
        $delete_horarios_query = "DELETE FROM horario_cursos WHERE id_curso = ?";
        $delete_horarios_stmt = mysqli_prepare($enlace, $delete_horarios_query);
        mysqli_stmt_bind_param($delete_horarios_stmt, "i", $id);
        mysqli_stmt_execute($delete_horarios_stmt);
        
        // Eliminar curso
        $delete_curso_query = "DELETE FROM cursos WHERE id = ?";
        $delete_curso_stmt = mysqli_prepare($enlace, $delete_curso_query);
        mysqli_stmt_bind_param($delete_curso_stmt, "i", $id);
        mysqli_stmt_execute($delete_curso_stmt);
        
        // Confirmar transacción
        mysqli_commit($enlace);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Curso "' . $curso['curso'] . '" eliminado exitosamente'
        ]);
        
    } catch (Exception $e) {
        // Revertir transacción
        mysqli_rollback($enlace);
        throw $e;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el curso: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
