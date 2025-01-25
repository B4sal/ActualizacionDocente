<?php
require_once 'conn.php';

// Obtener periodos únicos
$query_periodos = "SELECT DISTINCT periodo FROM Encabezado";
$result_periodos = mysqli_query($enlace, $query_periodos);

// Obtener cursos
$query_cursos = "SELECT id_curso, curso FROM Encabezado";
$result_cursos = mysqli_query($enlace, $query_cursos);

// Obtener horarios únicos
$query_horarios = "SELECT DISTINCT horario FROM Encabezado";
$result_horarios = mysqli_query($enlace, $query_horarios);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización Docente</title>
</head>
<body>
    <h1>Actualización Docente</h1>
    <form>
        <label for="periodo">Periodo del curso:</label>
        <select id="periodo" name="periodo" required>
            <option value="" disabled selected>Selecciona un periodo</option>
            <?php while($row = mysqli_fetch_assoc($result_periodos)): ?>
                <option value="<?php echo $row['periodo']; ?>"><?php echo $row['periodo']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="curso">Curso:</label>
        <select id="curso" name="curso" required>
            <option value="" disabled selected>Selecciona un curso</option>
            <?php while($row = mysqli_fetch_assoc($result_cursos)): ?>
                <option value="<?php echo $row['id_curso']; ?>"><?php echo $row['curso']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="horario">Horario:</label>
        <select id="horario" name="horario" required>
            <option value="" disabled selected>Selecciona un horario</option>
            <?php while($row = mysqli_fetch_assoc($result_horarios)): ?>
                <option value="<?php echo $row['horario']; ?>"><?php echo $row['horario']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="button" id="btnCapturar">No de profe: <span id="noProfesores">Capturar Detalles</span></button>
    </form>

    <script>
    document.getElementById('btnCapturar').addEventListener('click', function() {
        const periodo = document.getElementById('periodo').value;
        const curso = document.getElementById('curso').value;
        const horario = document.getElementById('horario').value;
        
        fetch(`get_profesores.php?periodo=${periodo}&curso=${curso}&horario=${horario}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('noProfesores').textContent = data.no_profesores;
            });
    });
    </script>
</body>
</html>
