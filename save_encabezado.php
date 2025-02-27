<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_periodo = $_POST['periodo'];
    $id_curso = $_POST['curso'];
    $horario = $_POST['horario'];
    $no_profesores = $_POST['profesores'];

    // Iniciar una transacción para asegurar la integridad de los datos
    mysqli_begin_transaction($enlace);

    try {
        // Insertar en la tabla Encabezado
        $stmt = $enlace->prepare("INSERT INTO encabezado (id_curso, id_periodo, curso, horario, no_profesores) 
                                  VALUES (?, ?, (SELECT curso FROM cursos WHERE id = ?), ?, ?)");
        $stmt->bind_param("iiisi", $id_curso, $id_periodo, $id_curso, $horario, $no_profesores);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar en Encabezado: " . $stmt->error);
        }
        $stmt->close(); // Cerrar la consulta para liberar recursos

        // Obtener el número de registros existentes en horario_cursos para este curso
        $stmt = $enlace->prepare("SELECT COUNT(*) FROM horario_cursos WHERE id_curso = ?");
        $stmt->bind_param("i", $id_curso);
        $stmt->execute();
        $stmt->store_result(); // Necesario antes de llamar fetch() en algunos entornos
        $stmt->bind_result($num);
        $stmt->fetch();
        $stmt->close();

        // Incrementar el número de horario
        $num++;

        // Insertar en la tabla horario_cursos
        $stmt = $enlace->prepare("INSERT INTO horario_cursos (id_curso, num, horario) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_curso, $num, $horario);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar en Horario_Cursos: " . $stmt->error);
        }

        // Confirmar la transacción
        mysqli_commit($enlace);
        
        echo "✅ Registro guardado con éxito.";

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        mysqli_rollback($enlace);
        echo "❌ Error: " . $e->getMessage();
    }

    // Cerrar la conexión
    $stmt->close();
    $enlace->close();
}
?>
