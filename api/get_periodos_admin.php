<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    $query = "SELECT id_periodo, nombre_periodo FROM periodo ORDER BY id_periodo DESC";
    $result = mysqli_query($enlace, $query);
    
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Error al consultar periodos']);
        exit;
    }
    
    $periodos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $periodos[] = [
            'id' => $row['id_periodo'],
            'nombre' => $row['nombre_periodo']
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $periodos]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

mysqli_close($enlace);
?>
