<?php
require_once 'conn.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch($action) {
    case 'getPeriodos':
        $query = "SELECT DISTINCT periodo FROM Encabezado";
        $result = mysqli_query($enlace, $query);
        $periodos = [];
        while($row = mysqli_fetch_assoc($result)) {
            $periodos[] = $row['periodo'];
        }
        echo json_encode($periodos);
        break;

    case 'getCursos':
        $query = "SELECT id_curso, curso FROM Encabezado";
        $result = mysqli_query($enlace, $query);
        $cursos = [];
        while($row = mysqli_fetch_assoc($result)) {
            $cursos[] = $row;
        }
        echo json_encode($cursos);
        break;

    case 'getHorarios':
        $query = "SELECT DISTINCT horario FROM Encabezado";
        $result = mysqli_query($enlace, $query);
        $horarios = [];
        while($row = mysqli_fetch_assoc($result)) {
            $horarios[] = $row['horario'];
        }
        echo json_encode($horarios);
        break;
}
?>
