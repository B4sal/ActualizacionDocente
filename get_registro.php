<?php
include 'conn.php';

$query = "SELECT r.nombre, r.expediente, r.correo, c.curso, h.horario, r.id_periodo as periodo
          FROM registro r 
          JOIN cursos c ON r.id_curso = c.id 
          JOIN horario_cursos h ON r.id_curso = h.id_curso AND r.id_horario_curso = h.num";

$params = [];
$types = "";

// Add filters if provided
if (isset($_GET['periodo']) && !empty($_GET['periodo']) && isset($_GET['curso']) && !empty($_GET['curso'])) {
    $periodo_id = $_GET['periodo'];
    $curso_id = $_GET['curso'];
    
    $query .= " WHERE r.id_periodo = (SELECT nombre_periodo FROM periodo WHERE id_periodo = ?)
                AND r.id_curso = ?";
    $params[] = $periodo_id;
    $params[] = $curso_id;
    $types = "ii";
}

$query .= " ORDER BY r.id_periodo, c.curso, r.nombre";

$stmt = $enlace->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo json_encode($registros);
$stmt->close();
$enlace->close();
?>
