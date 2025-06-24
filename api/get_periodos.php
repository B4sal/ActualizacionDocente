<?php
require_once '../config/conn.php';

header('Content-Type: application/json');

try {
    $query = "SELECT * FROM periodo ORDER BY nombre_periodo";
    $result = mysqli_query($enlace, $query);
    
    $periodos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $periodos[] = $row;
    }
    
    echo json_encode(['success' => true, 'data' => $periodos]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener períodos']);
}

mysqli_close($enlace);
?>
