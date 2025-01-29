<?php
include 'conn.php';

$action = $_GET['action'];

if ($action == 'read') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT * FROM Encabezado WHERE id_curso = $id";
        $result = mysqli_query($enlace, $query);
        echo json_encode(mysqli_fetch_assoc($result));
    } else {
        $query = "SELECT * FROM Encabezado";
        $result = mysqli_query($enlace, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
} elseif ($action == 'create') {
    $periodo = $_POST['periodo'];
    $curso = $_POST['curso'];
    $horario = $_POST['horario'];
    $no_profesores = $_POST['no_profesores'];
    
    // Generate a continuous ID
    $query = "SELECT MAX(id_curso) AS max_id FROM Encabezado";
    $result = mysqli_query($enlace, $query);
    $row = mysqli_fetch_assoc($result);
    $new_id = $row['max_id'] + 1;

    $query = "INSERT INTO Encabezado (id_curso, periodo, curso, horario, no_profesores) VALUES ($new_id, '$periodo', '$curso', '$horario', $no_profesores)";
    mysqli_query($enlace, $query);
    echo json_encode(['success' => true]);
} elseif ($action == 'update') {
    $id_curso = $_POST['id_curso'];
    $periodo = $_POST['periodo'];
    $curso = $_POST['curso'];
    $horario = $_POST['horario'];
    $no_profesores = $_POST['no_profesores'];
    $query = "UPDATE Encabezado SET periodo = '$periodo', curso = '$curso', horario = '$horario', no_profesores = $no_profesores WHERE id_curso = $id_curso";
    mysqli_query($enlace, $query);
    echo json_encode(['success' => true]);
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    $query = "DELETE FROM Encabezado WHERE id_curso = $id";
    mysqli_query($enlace, $query);
    echo json_encode(['success' => true]);
}
?>
