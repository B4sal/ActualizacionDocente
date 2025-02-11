<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $curso = $_POST['curso'];
    $status = $_POST['status'];

    $stmt = $enlace->prepare("UPDATE cursos SET curso = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $curso, $status, $id);

    if ($stmt->execute()) {
        echo "✅ Curso editado con éxito.";
    } else {
        echo "❌ Error al editar curso: " . $stmt->error;
    }

    $stmt->close();
    $enlace->close();
}
?>
