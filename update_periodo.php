<?php
include 'conn.php';

$id_periodo = $_POST['id_periodo'];
$nombre_periodo = $_POST['nombre_periodo'];

$query = "UPDATE periodo SET nombre_periodo='$nombre_periodo' WHERE id_periodo='$id_periodo'";
$result = mysqli_query($enlace, $query);

if ($result) {
    echo json_encode(['success' => 'Periodo actualizado correctamente']);
} else {
    echo json_encode(['error' => 'Error al actualizar el periodo']);
}
?>
