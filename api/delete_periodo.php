<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }
    
    $id = intval($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID del periodo inválido']);
        exit;
    }
    
    // Verificar que el periodo existe
    $check_query = "SELECT nombre_periodo FROM periodo WHERE id_periodo = ?";
    $check_stmt = mysqli_prepare($enlace, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $periodo = mysqli_fetch_assoc($check_result);
    
    if (!$periodo) {
        echo json_encode(['success' => false, 'message' => 'Periodo no encontrado']);
        exit;
    }
    
    // Verificar si el periodo tiene registros asociados
    $usage_queries = [
        "SELECT COUNT(*) as count FROM encabezado WHERE periodo = ?",
        "SELECT COUNT(*) as count FROM registro WHERE id_periodo = ?"
    ];
    
    $has_references = false;
    foreach ($usage_queries as $query) {
        $stmt = mysqli_prepare($enlace, $query);
        mysqli_stmt_bind_param($stmt, "s", $periodo['nombre_periodo']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = mysqli_fetch_assoc($result)['count'];
        
        if ($count > 0) {
            $has_references = true;
            break;
        }
    }
    
    if ($has_references) {
        echo json_encode([
            'success' => false, 
            'message' => 'No se puede eliminar este periodo porque tiene registros asociados'
        ]);
        exit;
    }
    
    // Eliminar periodo
    $delete_query = "DELETE FROM periodo WHERE id_periodo = ?";
    $delete_stmt = mysqli_prepare($enlace, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Periodo eliminado exitosamente'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el periodo']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
