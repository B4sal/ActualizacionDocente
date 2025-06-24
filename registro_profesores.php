<?php
// require_once 'config/conn.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Profesores - Sistema de Actualización Docente</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
        .course-hover-panel {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-width: 300px;
            display: none;
        }
        
        .course-hover-panel .panel-header {
            background-color: #1B396A;
            color: white;
            padding: 10px 15px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
        }
        
        .course-hover-panel .panel-body {
            padding: 15px;
        }
        
        .horario-option {
            padding: 8px 12px;
            margin: 4px 0;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .horario-option:hover {
            background-color: #f8f9fa;
            border-color: #1B396A;
        }
        
        .horario-option.selected {
            background-color: #1B396A;
            color: white;
            border-color: #1B396A;
        }
        
        .profesores-table {
            margin-top: 30px;
        }
        
        .course-select-container {
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <?php include 'includes/sidebar.php'; ?>
            </div>
            
            <!-- Main Content Area -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="row">                    <div class="col-12 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2><i class="bi bi-person-plus"></i> Registro de Profesores</h2>
                                <p class="text-muted">Asignar profesores a cursos y horarios específicos</p>
                            </div>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                        </div>
                        
                        <!-- Mensaje informativo -->
                        <div class="alert alert-info border-0 mt-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-3" style="font-size: 1.2rem;"></i>
                                <div>
                                    <strong>Importante:</strong> Solo podrá seleccionar cursos que tengan un evento creado previamente en el período académico correspondiente. 
                                    Si no ve cursos disponibles, debe crear primero un evento en la <a href="index.php" class="alert-link">página principal</a>.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulario de Registro -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="bi bi-pencil-square"></i> Datos del Profesor</h5>
                            </div>
                            <div class="card-body">
                                <form id="formRegistroProfesor">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="periodo_reg" class="form-label">Período Académico</label>
                                            <select class="form-select" id="periodo_reg" name="periodo" required>
                                                <option value="">Seleccionar período...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3 course-select-container">
                                            <label for="curso_reg" class="form-label">Curso</label>
                                            <select class="form-select" id="curso_reg" name="curso" required>
                                                <option value="">Seleccionar curso...</option>
                                            </select>
                                            <!-- Panel de horarios -->
                                            <div id="horariosPanel" class="course-hover-panel">
                                                <div class="panel-header">
                                                    <i class="bi bi-clock"></i> Horarios Disponibles
                                                </div>
                                                <div class="panel-body" id="horariosList">
                                                    <!-- Horarios se cargarán aquí -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="horario_seleccionado" class="form-label">Horario Seleccionado</label>
                                            <input type="text" class="form-control" id="horario_seleccionado" name="horario_display" readonly 
                                                   placeholder="Pase el mouse sobre un curso para ver horarios">
                                            <input type="hidden" id="horario_id" name="horario_id">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="nombre_profesor" class="form-label">Nombre del Profesor</label>
                                            <input type="text" class="form-control" id="nombre_profesor" name="nombre" 
                                                   placeholder="Nombre completo" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="expediente" class="form-label">Expediente</label>
                                            <input type="text" class="form-control" id="expediente" name="expediente" 
                                                   placeholder="Número de expediente" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="correo" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo" 
                                                   placeholder="correo@ejemplo.com" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save"></i> Registrar Profesor
                                            </button>
                                            <button type="button" class="btn btn-secondary ms-2" onclick="limpiarFormulario()">
                                                <i class="bi bi-arrow-clockwise"></i> Limpiar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tabla de Profesores Registrados -->
                    <div class="col-12 profesores-table">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="bi bi-people"></i> Profesores Registrados</h5>
                                <small class="text-muted">Seleccione un período, curso y horario para ver los profesores asignados</small>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="filtro_periodo" class="form-label">Filtrar por Período</label>
                                        <select class="form-select" id="filtro_periodo">
                                            <option value="">Todos los períodos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="filtro_curso" class="form-label">Filtrar por Curso</label>
                                        <select class="form-select" id="filtro_curso">
                                            <option value="">Todos los cursos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="filtro_horario" class="form-label">Filtrar por Horario</label>
                                        <select class="form-select" id="filtro_horario">
                                            <option value="">Todos los horarios</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Expediente</th>
                                                <th>Correo</th>
                                                <th>Curso</th>
                                                <th>Horario</th>
                                                <th>Período</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaProfesores">
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    <i class="bi bi-info-circle"></i> Seleccione filtros para mostrar profesores
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/registro_profesores.js"></script>
</body>
</html>
