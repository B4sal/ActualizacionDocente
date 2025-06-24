<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }
    
    $nombre = trim($_POST['nombre'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $horarios = json_decode($_POST['horarios'] ?? '[]', true);
    
    // Validaciones
    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del curso es requerido']);
        exit;
    }
    
    if (empty($status) || !in_array($status, ['A', 'I'])) {
        echo json_encode(['success' => false, 'message' => 'Estado del curso inválido']);
        exit;
    }
    
    // Verificar que no exista un curso con el mismo nombre
    $check_query = "SELECT id FROM cursos WHERE curso = ?";
    $check_stmt = mysqli_prepare($enlace, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $nombre);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_fetch_assoc($check_result)) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un curso con este nombre']);
        exit;
    }
    
    // Iniciar transacción
    mysqli_begin_transaction($enlace);
    
    try {
        // Insertar curso
        $insert_query = "INSERT INTO cursos (curso, status) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($enlace, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "ss", $nombre, $status);
        mysqli_stmt_execute($insert_stmt);
        
        $curso_id = mysqli_insert_id($enlace);
        
        // Insertar horarios si existen
        if (!empty($horarios) && is_array($horarios)) {
            $horario_query = "INSERT INTO horario_cursos (id_curso, num, horario) VALUES (?, ?, ?)";
            $horario_stmt = mysqli_prepare($enlace, $horario_query);
            
            foreach ($horarios as $horario) {
                if (!empty($horario['horario'])) {
                    mysqli_stmt_bind_param($horario_stmt, "iis", $curso_id, $horario['num'], $horario['horario']);
                    mysqli_stmt_execute($horario_stmt);
                }
            }
        }
        
        // Confirmar transacción
        mysqli_commit($enlace);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Curso creado exitosamente',
            'id' => $curso_id
        ]);
        
    } catch (Exception $e) {
        // Revertir transacción
        mysqli_rollback($enlace);
        throw $e;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al crear el curso: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
