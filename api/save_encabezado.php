<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    $id_curso = $_POST['id_curso'] ?? null;
    $periodo = $_POST['periodo'] ?? null;
    $curso_nombre = $_POST['curso_nombre'] ?? null;
    $horario = $_POST['horario'] ?? null;
    $no_profesores = $_POST['no_profesores'] ?? null;
    
    if (!$id_curso || !$periodo || !$curso_nombre || !$horario || !$no_profesores) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit;
    }
    
    // Verificar si ya existe el encabezado
    $check_query = "SELECT * FROM encabezado WHERE id_curso = ? AND periodo = ?";
    $check_stmt = mysqli_prepare($enlace, $check_query);
    mysqli_stmt_bind_param($check_stmt, "is", $id_curso, $periodo);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un encabezado para este curso y período']);
        exit;
    }
    
    // Insertar encabezado
    $query = "INSERT INTO encabezado (id_curso, periodo, curso, horario, no_profesores) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($enlace, $query);
    mysqli_stmt_bind_param($stmt, "isssi", $id_curso, $periodo, $curso_nombre, $horario, $no_profesores);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Encabezado guardado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar encabezado']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
