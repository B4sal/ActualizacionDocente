<?php
include 'conn.php';

$query = "SELECT c.curso, h.horario 
          FROM registro r 
          JOIN cursos c ON r.id_curso = c.id 
          JOIN horario_cursos h ON r.id_curso = h.id_curso AND r.id_horario_curso = h.num";
$result = mysqli_query($enlace, $query);

$registros = [];
while ($row = mysqli_fetch_assoc($result)) {
    $registros[] = $row;
}

echo json_encode($registros);
?>
