<!-- Navbar responsive -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1B396A;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/img/itm-logo.svg" 
                 alt="ITM Logo" 
                 height="25" 
                 class="me-2 d-none d-sm-block">
            <span class="d-none d-md-inline">Sistema de Actualización Docente</span>
            <span class="d-inline d-md-none">SADocente</span>
        </a>
        
        <!-- Mobile menu button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-house"></i> <span class="d-lg-inline d-none">Inicio</span>
                </a>
                <a class="nav-link" href="registro_profesores.php">
                    <i class="bi bi-person-plus"></i> <span class="d-lg-inline">Registro</span>
                </a>
                <a class="nav-link" href="administrar_cursos.php">
                    <i class="bi bi-book"></i> <span class="d-lg-inline">Cursos</span>
                </a>
                <a class="nav-link" href="administrar_periodos.php">
                    <i class="bi bi-calendar3"></i> <span class="d-lg-inline">Periodos</span>
                </a>
            </div>
        </div>
    </div>
</nav>
