<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    mysqli_begin_transaction($enlace);
    
    try {
        // First delete all associated horario_cursos records to satisfy foreign key constraint
        $stmt = $enlace->prepare("DELETE FROM horario_cursos WHERE id_curso = ?");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar horarios asociados: " . $stmt->error);
        }
        $stmt->close();
        
        // Then delete the curso record
        $stmt = $enlace->prepare("DELETE FROM cursos WHERE id = ?");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar curso: " . $stmt->error);
        }
        $stmt->close();
        
        mysqli_commit($enlace);
        echo "✅ Curso eliminado con éxito.";
    } catch (Exception $e) {
        mysqli_rollback($enlace);
        echo "❌ Error: " . $e->getMessage();
    }
    
    $enlace->close();
}
?>
