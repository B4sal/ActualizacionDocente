<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curso = $_POST['curso'];
    $status = $_POST['status'];
    $horario = $_POST['horario'];

    mysqli_begin_transaction($enlace);

    try {
        // Insertar curso
        $stmt = $enlace->prepare("INSERT INTO cursos (curso, status) VALUES (?, ?)");
        $stmt->bind_param("ss", $curso, $status);
        if (!$stmt->execute()) {
            throw new Exception("Error al agregar curso: " . $stmt->error);
        }
        $curso_id = $enlace->insert_id;
        $stmt->close();

        // Obtener el siguiente num para el curso
        $stmt = $enlace->prepare("SELECT COALESCE(MAX(num), 0) + 1 as next_num FROM horario_cursos WHERE id_curso = ?");
        $stmt->bind_param("i", $curso_id);
        $stmt->execute();
        $stmt->bind_result($next_num);
        $stmt->fetch();
        $stmt->close();

        // Insertar horario
        $stmt = $enlace->prepare("INSERT INTO horario_cursos (id_curso, num, horario) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $curso_id, $next_num, $horario);
        if (!$stmt->execute()) {
            throw new Exception("Error al agregar horario: " . $stmt->error);
        }
        $stmt->close();

        mysqli_commit($enlace);
        echo "✅ Curso y horario agregados con éxito.";
    } catch (Exception $e) {
        mysqli_rollback($enlace);
        echo "❌ Error: " . $e->getMessage();
    }

    $enlace->close();
}
?>
