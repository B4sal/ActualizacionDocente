<?php
include 'conn.php';

$id_periodo = $_POST['id_periodo'];

$query = "DELETE FROM periodo WHERE id_periodo='$id_periodo'";
$result = mysqli_query($enlace, $query);

if ($result) {
    echo json_encode(['success' => 'Periodo borrado correctamente']);
} else {
    echo json_encode(['error' => 'Error al borrar el periodo']);
}
?>
