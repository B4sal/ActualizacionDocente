<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_periodo = $_POST['periodo'];
    $id_curso = $_POST['curso'];
    $horario = $_POST['horario'];
    $no_profesores = $_POST['profesores'];

    mysqli_begin_transaction($enlace);

    try {
        $stmt = $enlace->prepare("INSERT INTO encabezado (id_curso, periodo, curso, horario, no_profesores) 
                                  VALUES (?, (SELECT nombre_periodo FROM periodo WHERE id_periodo = ?), (SELECT curso FROM cursos WHERE id = ?), ?, ?)");
        $stmt->bind_param("iiisi", $id_curso, $id_periodo, $id_curso, $horario, $no_profesores);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar en Encabezado: " . $stmt->error);
        }
        $stmt->close();

        $stmt = $enlace->prepare("SELECT COUNT(*) FROM horario_cursos WHERE id_curso = ?");
        $stmt->bind_param("i", $id_curso);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($num);
        $stmt->fetch();
        $stmt->close();

        $num++;

        $stmt = $enlace->prepare("INSERT INTO horario_cursos (id_curso, num, horario) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_curso, $num, $horario);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar en Horario_Cursos: " . $stmt->error);
        }

        mysqli_commit($enlace);
        
        echo "✅ Registro guardado con éxito.";

    } catch (Exception $e) {
        mysqli_rollback($enlace);
        echo "❌ Error: " . $e->getMessage();
    }

    $stmt->close();
    $enlace->close();
}
?>
