<?php
require_once 'conn.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch($action) {
    case 'getDetalles':
        $curso = $_GET['curso'] ?? '';

        $query = "SELECT * FROM Detalle WHERE id_curso = ?";
        $stmt = mysqli_prepare($enlace, $query);
        mysqli_stmt_bind_param($stmt, "i", $curso);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $detalles = [];
        while($row = mysqli_fetch_assoc($result)) {
            $detalles[] = $row;
        }
        echo json_encode($detalles);
        break;

    case 'deleteDetalle':
        $id = $_GET['id'] ?? '';
        if ($id) {
            $query = "DELETE FROM Detalle WHERE id_detalle = ?";
            $stmt = mysqli_prepare($enlace, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
        }
        break;

    case 'addDetalle':
        $data = json_decode(file_get_contents('php://input'), true);
        $query = "INSERT INTO Detalle (periodo, id_curso, expediente, nombre) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($enlace, $query);
        mysqli_stmt_bind_param($stmt, "siss", $data['periodo'], $data['id_curso'], $data['expediente'], $data['nombre']);
        mysqli_stmt_execute($stmt);
        echo json_encode(['status' => 'success']);
        break;

    case 'editDetalle':
        $data = json_decode(file_get_contents('php://input'), true);
        $query = "UPDATE Detalle SET periodo = ?, id_curso = ?, expediente = ?, nombre = ? WHERE id_detalle = ?";
        $stmt = mysqli_prepare($enlace, $query);
        mysqli_stmt_bind_param($stmt, "sissi", $data['periodo'], $data['id_curso'], $data['expediente'], $data['nombre'], $data['id']);
        mysqli_stmt_execute($stmt);
        echo json_encode(['status' => 'success']);
        break;
}
?>
