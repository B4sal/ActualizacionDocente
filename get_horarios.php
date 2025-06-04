<?php
include 'conn.php';

if (isset($_GET['id_curso'])) {
    $id_curso = $_GET['id_curso'];
    
    $query = "SELECT id_curso, num, horario FROM horario_cursos WHERE id_curso = ?";
    $stmt = $enlace->prepare($query);
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $horarios = [];
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row;
    }
    
    echo json_encode($horarios);
    $stmt->close();
}
$enlace->close();
?>
