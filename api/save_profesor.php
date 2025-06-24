<?php
header('Content-Type: application/json');
require_once '../config/conn.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    // Validar campos requeridos
    $required_fields = ['periodo', 'id_curso', 'nombre', 'expediente', 'correo', 'id_horario_curso'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("El campo $field es requerido");
        }
    }
    
    $periodo = trim($_POST['periodo']);
    $id_curso = intval($_POST['id_curso']);
    $nombre = trim($_POST['nombre']);
    $expediente = trim($_POST['expediente']);
    $correo = trim($_POST['correo']);
    $id_horario_curso = intval($_POST['id_horario_curso']);
    
    // Validar formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Formato de correo electrónico inválido');
    }
    
    // Generar ID único para el profesor
    $query_max_id = "SELECT COALESCE(MAX(id_profesor), 100) + 1 as next_id FROM registro";
    $result_max_id = mysqli_query($enlace, $query_max_id);
    $row_max_id = mysqli_fetch_assoc($result_max_id);
    $id_profesor = $row_max_id['next_id'];
    
    // Verificar que existe el encabezado para este curso y período
    $query_encabezado = "SELECT COUNT(*) as count FROM encabezado WHERE id_curso = ? AND periodo = ?";
    $stmt_encabezado = mysqli_prepare($enlace, $query_encabezado);
    mysqli_stmt_bind_param($stmt_encabezado, "is", $id_curso, $periodo);
    mysqli_stmt_execute($stmt_encabezado);
    $result_encabezado = mysqli_stmt_get_result($stmt_encabezado);
    $row_encabezado = mysqli_fetch_assoc($result_encabezado);
      if ($row_encabezado['count'] == 0) {
        throw new Exception('No existe un encabezado de curso para la combinación de curso y período seleccionados. Debe crear primero el encabezado en la página principal.');
    }
    
    // Verificar que el horario seleccionado existe para este curso
    $query_horario = "SELECT COUNT(*) as count FROM horario_cursos WHERE id_curso = ? AND num = ?";
    $stmt_horario = mysqli_prepare($enlace, $query_horario);
    mysqli_stmt_bind_param($stmt_horario, "ii", $id_curso, $id_horario_curso);
    mysqli_stmt_execute($stmt_horario);
    $result_horario = mysqli_stmt_get_result($stmt_horario);
    $row_horario = mysqli_fetch_assoc($result_horario);
    
    if ($row_horario['count'] == 0) {
        throw new Exception('El horario seleccionado no existe para este curso.');
    }
    
    // Verificar si ya existe un profesor con el mismo expediente en el mismo curso y período
    $query_check = "SELECT COUNT(*) as count FROM registro 
                    WHERE expediente = ? AND id_curso = ? AND id_periodo = ? AND id_horario_curso = ?";
    $stmt_check = mysqli_prepare($enlace, $query_check);
    mysqli_stmt_bind_param($stmt_check, "sisi", $expediente, $id_curso, $periodo, $id_horario_curso);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    $row_check = mysqli_fetch_assoc($result_check);
    
    if ($row_check['count'] > 0) {
        throw new Exception('Ya existe un profesor con este expediente registrado en este curso, período y horario');
    }
    
    // Insertar el nuevo profesor
    $query = "INSERT INTO registro (id_periodo, id_curso, id_profesor, expediente, nombre, correo, id_horario_curso) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($enlace, $query);
    
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta: ' . mysqli_error($enlace));
    }
    
    mysqli_stmt_bind_param($stmt, "siisssi", $periodo, $id_curso, $id_profesor, $expediente, $nombre, $correo, $id_horario_curso);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt));
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Profesor registrado exitosamente',
        'data' => [
            'id_profesor' => $id_profesor,
            'nombre' => $nombre,
            'expediente' => $expediente
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($enlace);
?>
