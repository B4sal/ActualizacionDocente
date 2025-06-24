<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }
    
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $horarios = json_decode($_POST['horarios'] ?? '[]', true);
    
    // Validaciones
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID del curso inválido']);
        exit;
    }
    
    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del curso es requerido']);
        exit;
    }
    
    if (empty($status) || !in_array($status, ['A', 'I'])) {
        echo json_encode(['success' => false, 'message' => 'Estado del curso inválido']);
        exit;
    }
    
    // Verificar que el curso existe
    $check_query = "SELECT id FROM cursos WHERE id = ?";
    $check_stmt = mysqli_prepare($enlace, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (!mysqli_fetch_assoc($check_result)) {
        echo json_encode(['success' => false, 'message' => 'Curso no encontrado']);
        exit;
    }
    
    // Verificar que no exista otro curso con el mismo nombre
    $duplicate_query = "SELECT id FROM cursos WHERE curso = ? AND id != ?";
    $duplicate_stmt = mysqli_prepare($enlace, $duplicate_query);
    mysqli_stmt_bind_param($duplicate_stmt, "si", $nombre, $id);
    mysqli_stmt_execute($duplicate_stmt);
    $duplicate_result = mysqli_stmt_get_result($duplicate_stmt);
    
    if (mysqli_fetch_assoc($duplicate_result)) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otro curso con este nombre']);
        exit;
    }
      // Iniciar transacción
    mysqli_begin_transaction($enlace);
    
    try {
        // Actualizar curso
        $update_query = "UPDATE cursos SET curso = ?, status = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($enlace, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssi", $nombre, $status, $id);
        mysqli_stmt_execute($update_stmt);
        
        // Obtener horarios existentes
        $existing_query = "SELECT num, horario FROM horario_cursos WHERE id_curso = ? ORDER BY num";
        $existing_stmt = mysqli_prepare($enlace, $existing_query);
        mysqli_stmt_bind_param($existing_stmt, "i", $id);
        mysqli_stmt_execute($existing_stmt);
        $existing_result = mysqli_stmt_get_result($existing_stmt);
        
        $existing_horarios = [];
        while ($row = mysqli_fetch_assoc($existing_result)) {
            $existing_horarios[$row['num']] = $row['horario'];
        }
        
        // Manejar horarios de manera inteligente
        if (!empty($horarios) && is_array($horarios)) {
            $new_horarios = [];
            foreach ($horarios as $horario) {
                if (!empty($horario['horario'])) {
                    $new_horarios[$horario['num']] = $horario['horario'];
                }
            }
            
            // Actualizar horarios existentes que han cambiado
            foreach ($new_horarios as $num => $horario_text) {
                if (isset($existing_horarios[$num])) {
                    // Existe, verificar si cambió
                    if ($existing_horarios[$num] !== $horario_text) {
                        $update_horario_query = "UPDATE horario_cursos SET horario = ? WHERE id_curso = ? AND num = ?";
                        $update_horario_stmt = mysqli_prepare($enlace, $update_horario_query);
                        mysqli_stmt_bind_param($update_horario_stmt, "sii", $horario_text, $id, $num);
                        mysqli_stmt_execute($update_horario_stmt);
                    }
                } else {
                    // No existe, insertar nuevo
                    $insert_horario_query = "INSERT INTO horario_cursos (id_curso, num, horario) VALUES (?, ?, ?)";
                    $insert_horario_stmt = mysqli_prepare($enlace, $insert_horario_query);
                    mysqli_stmt_bind_param($insert_horario_stmt, "iis", $id, $num, $horario_text);
                    mysqli_stmt_execute($insert_horario_stmt);
                }
            }
            
            // Eliminar horarios que ya no están en la nueva lista
            // Pero solo si no tienen referencias en la tabla registro
            foreach ($existing_horarios as $num => $horario_text) {
                if (!isset($new_horarios[$num])) {
                    // Verificar si este horario tiene referencias en registro
                    $check_ref_query = "SELECT COUNT(*) as count FROM registro WHERE id_curso = ? AND id_horario_curso = ?";
                    $check_ref_stmt = mysqli_prepare($enlace, $check_ref_query);
                    mysqli_stmt_bind_param($check_ref_stmt, "ii", $id, $num);
                    mysqli_stmt_execute($check_ref_stmt);
                    $check_ref_result = mysqli_stmt_get_result($check_ref_stmt);
                    $ref_count = mysqli_fetch_assoc($check_ref_result)['count'];
                    
                    if ($ref_count == 0) {
                        // No tiene referencias, se puede eliminar
                        $delete_horario_query = "DELETE FROM horario_cursos WHERE id_curso = ? AND num = ?";
                        $delete_horario_stmt = mysqli_prepare($enlace, $delete_horario_query);
                        mysqli_stmt_bind_param($delete_horario_stmt, "ii", $id, $num);
                        mysqli_stmt_execute($delete_horario_stmt);
                    }
                    // Si tiene referencias, no lo eliminamos para mantener la integridad
                }
            }
        }
        
        // Confirmar transacción
        mysqli_commit($enlace);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Curso actualizado exitosamente'
        ]);
        
    } catch (Exception $e) {
        // Revertir transacción
        mysqli_rollback($enlace);
        throw $e;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el curso: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
