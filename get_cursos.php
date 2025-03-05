<?php
include 'conn.php';

$query = "SELECT id, curso, status FROM cursos";
$result = mysqli_query($enlace, $query);

$cursos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cursos[] = $row;
}

echo json_encode($cursos);
?>
