<?php
header('Content-Type: application/json');
require_once '../config/conn.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    // Validar campos requeridos
    if (!isset($_POST['id_profesor']) || !isset($_POST['periodo']) || !isset($_POST['id_curso'])) {
        throw new Exception('Faltan parámetros requeridos');
    }
    
    $id_profesor = intval($_POST['id_profesor']);
    $periodo = trim($_POST['periodo']);
    $id_curso = intval($_POST['id_curso']);
    
    // Verificar que el profesor existe
    $query_check = "SELECT nombre FROM registro WHERE id_profesor = ? AND id_periodo = ? AND id_curso = ?";
    $stmt_check = mysqli_prepare($enlace, $query_check);
    mysqli_stmt_bind_param($stmt_check, "isi", $id_profesor, $periodo, $id_curso);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) === 0) {
        throw new Exception('El profesor no existe o ya fue eliminado');
    }
    
    $profesor_data = mysqli_fetch_assoc($result_check);
    
    // Eliminar el profesor
    $query = "DELETE FROM registro WHERE id_profesor = ? AND id_periodo = ? AND id_curso = ?";
    $stmt = mysqli_prepare($enlace, $query);
    
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . mysqli_error($enlace));
    }
    
    mysqli_stmt_bind_param($stmt, "isi", $id_profesor, $periodo, $id_curso);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt));
    }
    
    if (mysqli_stmt_affected_rows($stmt) === 0) {
        throw new Exception('No se pudo eliminar el profesor');
    }
    
    echo json_encode([
        'success' => true,
        'message' => "Profesor {$profesor_data['nombre']} eliminado exitosamente"
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($enlace);
?>
