<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_periodo = $_POST['periodo'];
    $id_curso = $_POST['curso'];
    $nombre = $_POST['profe'];
    $correo = $_POST['email'];
    $expediente = $_POST['exp'];

    $stmt = $enlace->prepare("INSERT INTO registro (id_periodo, id_curso, id_profesor, expediente, nombre, correo, id_horario_curso) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisssi", $id_periodo, $id_curso, $id_profesor, $expediente, $nombre, $correo, $id_horario_curso);

    if ($stmt->execute()) {
        echo "✅ Profesor registrado con éxito.";
    } else {
        echo "❌ Error al registrar profesor: " . $stmt->error;
    }

    $stmt->close();
    $enlace->close();
}
?>
