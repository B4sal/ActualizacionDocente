<?php
include 'conn.php';

$nombre_periodo = $_POST['nombre_periodo'];

$query = "INSERT INTO periodo (nombre_periodo) VALUES ('$nombre_periodo')";
$result = mysqli_query($enlace, $query);

if ($result) {
    echo json_encode(['success' => 'Periodo agregado correctamente']);
} else {
    echo json_encode(['error' => 'Error al agregar el periodo']);
}
?>
