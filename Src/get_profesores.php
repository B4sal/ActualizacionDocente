<?php
require_once 'conn.php';

$periodo = $_GET['periodo'] ?? '';
$curso = $_GET['curso'] ?? '';
$horario = $_GET['horario'] ?? '';

$query = "SELECT no_profesores FROM Encabezado 
          WHERE periodo = ? AND id_curso = ? AND horario = ?";

$stmt = mysqli_prepare($enlace, $query);
mysqli_stmt_bind_param($stmt, "sis", $periodo, $curso, $horario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode(['no_profesores' => $row['no_profesores'] ?? 'No disponible']);
?>
