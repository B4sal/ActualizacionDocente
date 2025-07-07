<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }
    
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    
    // Validaciones
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de periodo inválido']);
        exit;
    }
    
    if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del periodo es requerido']);
        exit;
    }
    
    if (strlen($nombre) > 15) {
        echo json_encode(['success' => false, 'message' => 'El nombre del periodo no puede exceder 15 caracteres']);
        exit;
    }
    
    // Verificar que el periodo existe
    $check_query = "SELECT id_periodo FROM periodo WHERE id_periodo = ?";
    $check_stmt = mysqli_prepare($enlace, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (!mysqli_fetch_assoc($check_result)) {
        echo json_encode(['success' => false, 'message' => 'El periodo no existe']);
        exit;
    }
    
    // Verificar que no exista otro periodo con el mismo nombre
    $check_name_query = "SELECT id_periodo FROM periodo WHERE nombre_periodo = ? AND id_periodo != ?";
    $check_name_stmt = mysqli_prepare($enlace, $check_name_query);
    mysqli_stmt_bind_param($check_name_stmt, "si", $nombre, $id);
    mysqli_stmt_execute($check_name_stmt);
    $check_name_result = mysqli_stmt_get_result($check_name_stmt);
    
    if (mysqli_fetch_assoc($check_name_result)) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otro periodo con este nombre']);
        exit;
    }
    
    // Actualizar periodo
    $update_query = "UPDATE periodo SET nombre_periodo = ? WHERE id_periodo = ?";
    $update_stmt = mysqli_prepare($enlace, $update_query);
    mysqli_stmt_bind_param($update_stmt, "si", $nombre, $id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Periodo actualizado exitosamente',
            'data' => [
                'id' => $id,
                'nombre' => $nombre
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el periodo']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
