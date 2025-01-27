<?php
header('Content-Type: application/json');
include 'conn.php';

$query = "SELECT periodo FROM encabezado";
$result = mysqli_query($enlace, $query);

$periodos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $periodos[] = $row['periodo'];
}

echo json_encode($periodos);

mysqli_close($enlace);
?>
