<?php
include 'conn.php';

$sql = "SELECT id_encabezado, id_curso, id_periodo, curso, horario, no_profesores FROM encabezado";
$result = $enlace->query($sql);

$encabezados = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $encabezados[] = $row;
    }
}

echo json_encode($encabezados);

$enlace->close();
?>
