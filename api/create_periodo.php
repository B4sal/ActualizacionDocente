<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }
    
    $nombre = trim($_POST['nombre'] ?? '');
    
    // Validaciones
    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del periodo es requerido']);
        exit;
    }
    
    if (strlen($nombre) > 15) {
        echo json_encode(['success' => false, 'message' => 'El nombre del periodo no puede exceder 15 caracteres']);
        exit;
    }
    
    // Verificar que no exista un periodo con el mismo nombre
    $check_query = "SELECT id_periodo FROM periodo WHERE nombre_periodo = ?";
    $check_stmt = mysqli_prepare($enlace, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $nombre);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_fetch_assoc($check_result)) {
        echo json_encode(['success' => false, 'message' => 'Ya existe un periodo con este nombre']);
        exit;
    }
    
    // Insertar nuevo periodo
    $insert_query = "INSERT INTO periodo (nombre_periodo) VALUES (?)";
    $insert_stmt = mysqli_prepare($enlace, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "s", $nombre);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        $nuevo_id = mysqli_insert_id($enlace);
        echo json_encode([
            'success' => true, 
            'message' => 'Periodo creado exitosamente',
            'data' => [
                'id' => $nuevo_id,
                'nombre' => $nombre
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el periodo']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
