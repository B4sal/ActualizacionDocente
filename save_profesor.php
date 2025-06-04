<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_periodo = $_POST['periodo'];
    $id_curso = $_POST['curso'];
    $id_horario_curso = $_POST['horario'];
    $nombre = $_POST['profe'];
    $correo = $_POST['email'];
    $expediente = $_POST['exp'];

    mysqli_begin_transaction($enlace);

    try {
        // Get the periodo name from id_periodo
        $stmt = $enlace->prepare("SELECT nombre_periodo FROM periodo WHERE id_periodo = ?");
        $stmt->bind_param("i", $id_periodo);
        $stmt->execute();
        $stmt->bind_result($nombre_periodo);
        $stmt->fetch();
        $stmt->close();

        if (!$nombre_periodo) {
            throw new Exception("Periodo no encontrado");
        }

        // Check if Encabezado entry exists, if not create it
        $stmt = $enlace->prepare("SELECT COUNT(*) FROM Encabezado WHERE id_curso = ? AND periodo = ?");
        $stmt->bind_param("is", $id_curso, $nombre_periodo);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            // Get curso name
            $stmt = $enlace->prepare("SELECT curso FROM cursos WHERE id = ?");
            $stmt->bind_param("i", $id_curso);
            $stmt->execute();
            $stmt->bind_result($curso_nombre);
            $stmt->fetch();
            $stmt->close();

            // Get horario
            $stmt = $enlace->prepare("SELECT horario FROM horario_cursos WHERE id_curso = ? AND num = ?");
            $stmt->bind_param("ii", $id_curso, $id_horario_curso);
            $stmt->execute();
            $stmt->bind_result($horario);
            $stmt->fetch();
            $stmt->close();

            // Insert into Encabezado
            $stmt = $enlace->prepare("INSERT INTO Encabezado (id_curso, periodo, curso, horario, no_profesores) VALUES (?, ?, ?, ?, 1)");
            $stmt->bind_param("isss", $id_curso, $nombre_periodo, $curso_nombre, $horario);
            if (!$stmt->execute()) {
                throw new Exception("Error al crear encabezado: " . $stmt->error);
            }
            $stmt->close();
        }

        // Generate next id_profesor for this periodo and curso
        $stmt = $enlace->prepare("SELECT COALESCE(MAX(id_profesor), 0) + 1 FROM registro WHERE id_periodo = ? AND id_curso = ?");
        $stmt->bind_param("si", $nombre_periodo, $id_curso);
        $stmt->execute();
        $stmt->bind_result($next_id_profesor);
        $stmt->fetch();
        $stmt->close();

        // Insert into registro
        $stmt = $enlace->prepare("INSERT INTO registro (id_periodo, id_curso, id_profesor, expediente, nombre, correo, id_horario_curso) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisssi", $nombre_periodo, $id_curso, $next_id_profesor, $expediente, $nombre, $correo, $id_horario_curso);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar registro: " . $stmt->error);
        }
        $stmt->close();

        mysqli_commit($enlace);
        echo "✅ Profesor registrado con éxito.";

    } catch (Exception $e) {
        mysqli_rollback($enlace);
        echo "❌ Error: " . $e->getMessage();
    }

    $enlace->close();
}
?>
