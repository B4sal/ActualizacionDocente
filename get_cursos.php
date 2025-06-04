<?php
include 'conn.php';

$query = "SELECT DISTINCT c.id, c.curso, c.status FROM cursos c ORDER BY c.curso";
$result = $enlace->query($query);

$cursos = [];
while ($row = $result->fetch_assoc()) {
    $cursos[] = $row;
}

echo json_encode($cursos);
$enlace->close();
?>
