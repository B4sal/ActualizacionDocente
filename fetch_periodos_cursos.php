<?php
include 'conn.php';

$data = fetchPeriodsAndCourses();
echo json_encode($data);



function fetchPeriodsAndCourses() {
    global $enlace;
    $query = "SELECT periodo, curso FROM Encabezado";
    $result = mysqli_query($enlace, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

?>
