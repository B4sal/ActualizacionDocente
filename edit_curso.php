<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $curso = $_POST['curso'];
    $status = $_POST['status'];
    $new_horarios = json_decode($_POST['new_horarios'], true);

    mysqli_begin_transaction($enlace);

    try {
        // Actualizar curso
        $stmt = $enlace->prepare("UPDATE cursos SET curso = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $curso, $status, $id);
        if (!$stmt->execute()) {
            throw new Exception("Error al editar curso: " . $stmt->error);
        }
        $stmt->close();

        // Add new horarios
        if (!empty($new_horarios)) {
            $stmt = $enlace->prepare("INSERT INTO horario_cursos (id_curso, num, horario) VALUES (?, ?, ?)");
            foreach ($new_horarios as $horario) {
                // Calculate next num for the course
                $stmt_num = $enlace->prepare("SELECT COALESCE(MAX(num), 0) + 1 FROM horario_cursos WHERE id_curso = ?");
                $stmt_num->bind_param("i", $id);
                $stmt_num->execute();
                $stmt_num->bind_result($next_num);
                $stmt_num->fetch();
                $stmt_num->close();

                $stmt->bind_param("iis", $id, $next_num, $horario);
                if (!$stmt->execute()) {
                    throw new Exception("Error al agregar nuevo horario: " . $stmt->error);
                }
            }
            $stmt->close();
        }

        mysqli_commit($enlace);
        echo "✅ Curso y horarios editados con éxito.";
    } catch (Exception $e) {
        mysqli_rollback($enlace);
        echo "❌ Error: " . $e->getMessage();
    }

    $enlace->close();
}
?>
