<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curso = $_POST['curso'];
    $status = $_POST['status'];

    $stmt = $enlace->prepare("INSERT INTO cursos (curso, status) VALUES (?, ?)");
    $stmt->bind_param("ss", $curso, $status);

    if ($stmt->execute()) {
        echo "✅ Curso agregado con éxito.";
    } else {
        echo "❌ Error al agregar curso: " . $stmt->error;
    }

    $stmt->close();
    $enlace->close();
}
?>
