<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_curso']) && isset($_POST['num'])) {
    $id_curso = $_POST['id_curso'];
    $num = $_POST['num'];

    $stmt = $enlace->prepare("DELETE FROM horario_cursos WHERE id_curso = ? AND num = ?");
    $stmt->bind_param("ii", $id_curso, $num);

    if ($stmt->execute()) {
        echo "✅ Horario eliminado con éxito.";
    } else {
        echo "❌ Error al eliminar horario: " . $stmt->error;
    }

    $stmt->close();
    $enlace->close();
}
?>
