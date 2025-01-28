
<?php
include 'conn.php';

function getEncabezadoData($enlace) {
    $query = "SELECT * FROM encabezado";
    $result = mysqli_query($enlace, $query);
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

$data = getEncabezadoData($enlace);
echo json_encode($data);
?>