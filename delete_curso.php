<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $stmt = $enlace->prepare("DELETE FROM cursos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "✅ Curso eliminado con éxito.";
    } else {
        echo "❌ Error al eliminar curso: " . $stmt->error;
    }

    $stmt->close();
    $enlace->close();
}
?>
