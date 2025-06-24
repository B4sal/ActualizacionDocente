<?php
// require_once 'config/conn.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Sistema de Actualización Docente</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/img/itm-logo.svg">
    <link rel="shortcut icon" href="assets/img/itm-logo.svg">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>
      <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar - Hidden on mobile, shown on larger screens -->
            <div class="col-lg-2 col-md-3 d-none d-md-block sidebar">
                <?php include 'includes/sidebar.php'; ?>
            </div>
            
            <!-- Main Content Area -->
            <div class="col-12 col-md-9 col-lg-10 main-content">
                <div class="row">                    <!-- Header Section -->
                    <div class="col-12 mb-3 mb-md-4">
                        <div class="text-center py-3 py-md-4">
                            <img src="assets/img/itm-logo.svg" 
                                 alt="Instituto Tecnológico de Mérida" 
                                 height="60" 
                                 class="mb-2 mb-md-3 d-none d-sm-block">
                            <img src="assets/img/itm-logo.svg" 
                                 alt="Instituto Tecnológico de Mérida" 
                                 height="40" 
                                 class="mb-2 d-block d-sm-none">
                            <h1 class="mb-2 h3 h1-md">Sistema de Actualización Docente</h1>
                            <p class="text-muted lead d-none d-md-block">Instituto Tecnológico de Mérida</p>
                            <p class="text-muted d-block d-md-none">Instituto Tecnológico de Mérida</p>
                            <hr class="w-75 w-md-50 mx-auto">
                        </div>
                    </div>
                      <!-- Welcome Section -->
                    <div class="col-12 mb-3 mb-md-4">
                        <div class="alert alert-info border-0" role="alert">
                            <div class="d-flex align-items-start align-items-md-center">
                                <i class="bi bi-info-circle-fill me-2 me-md-3 mt-1 mt-md-0" style="font-size: 1.2rem; font-size-md: 1.5rem;"></i>
                                <div>
                                    <h5 class="alert-heading mb-1 h6 h5-md">Bienvenido al Sistema</h5>
                                    <p class="mb-0 small">Utiliza el menú de navegación para acceder a las diferentes funciones del sistema de gestión docente.</p>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Main Form Section -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0 h6 h5-md">
                                    <i class="bi bi-file-earmark-plus text-primary me-2"></i>
                                    Crear evento academico
                                </h5>
                                <small class="text-muted">Complete el formulario para crear un nuevo encabezado de curso</small>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <form id="formEncabezado">
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="periodo" class="form-label">Período Académico</label>
                                            <select class="form-select" id="periodo" name="periodo" required>
                                                <option value="">Seleccionar período...</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="curso" class="form-label">Curso</label>
                                            <select class="form-select" id="curso" name="curso" required>
                                                <option value="">Seleccionar curso...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="horario" class="form-label">Horario General</label>
                                            <input type="text" class="form-control" id="horario" name="horario" 
                                                   placeholder="Ej: 08:00 - 10:00" required>
                                        </div>
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="no_profesores" class="form-label">Número de Profesores</label>
                                            <input type="number" class="form-control" id="no_profesores" name="no_profesores" 
                                                   min="1" max="10" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="d-grid d-md-block">
                                                <button type="submit" class="btn btn-primary me-0 me-md-2 mb-2 mb-md-0">
                                                    <i class="bi bi-save"></i> Guardar evento
                                                </button>
                                                <button type="reset" class="btn btn-outline-secondary">
                                                    <i class="bi bi-arrow-clockwise"></i> Limpiar Formulario
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <script src="assets/js/main.js"></script>
</body>
</html>
