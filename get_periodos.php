<?php
include 'conn.php';

$query = "SELECT DISTINCT id_periodo, nombre_periodo FROM periodo ORDER BY nombre_periodo";
$result = mysqli_query($enlace, $query);

if (!$result) {
    echo json_encode(['error' => 'Error en la consulta a la base de datos']);
    exit;
}

$periodos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $periodos[] = $row;
}

echo json_encode($periodos);
mysqli_close($enlace);
?>
