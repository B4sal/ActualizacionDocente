// Variables globales
let periodoIdEliminar = null;

// Cuando se carga el documento
document.addEventListener('DOMContentLoaded', function() {
    cargarPeriodos();
    
    // Event listeners
    document.getElementById('formPeriodo').addEventListener('submit', guardarPeriodo);
    document.getElementById('btnConfirmarEliminar').addEventListener('click', confirmarEliminarPeriodo);
});

// Cargar lista de periodos
function cargarPeriodos() {
    fetch('api/get_periodos_admin.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarPeriodos(data.data);
            } else {
                mostrarMensaje('Error al cargar periodos: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error de conexión al cargar periodos', 'danger');
        });
}

// Mostrar periodos en la interfaz
function mostrarPeriodos(periodos) {
    const container = document.getElementById('listaPeriodos');
    
    if (periodos.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                    <h5 class="mt-3 text-muted">No hay periodos registrados</h5>
                    <p class="text-muted">Crea tu primer periodo haciendo clic en "Nuevo Periodo"</p>
                </div>
            </div>
        `;
        return;
    }
    
    let html = '';
    periodos.forEach(periodo => {
        html += `
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card periodo-card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="periodo-id me-3">
                                ${periodo.id}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">${periodo.nombre}</h6>
                                <small class="text-muted">ID: ${periodo.id}</small>
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-danger btn-sm flex-fill" 
                                        onclick="eliminarPeriodo(${periodo.id}, '${periodo.nombre}')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Guardar nuevo periodo
function guardarPeriodo(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const nombre = document.getElementById('nombrePeriodo').value.trim();
    
    if (!nombre) {
        mostrarMensaje('El nombre del periodo es requerido', 'danger');
        return;
    }
    
    formData.append('nombre', nombre);
    
    // Deshabilitar botón de envío
    const btnSubmit = e.target.querySelector('button[type="submit"]');
    const btnText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
    
    fetch('api/create_periodo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarMensaje('Periodo creado exitosamente', 'success');
            document.getElementById('formPeriodo').reset();
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPeriodo'));
            modal.hide();
            
            // Recargar lista
            cargarPeriodos();
        } else {
            mostrarMensaje('Error: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('Error de conexión al guardar periodo', 'danger');
    })
    .finally(() => {
        // Rehabilitar botón
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = btnText;
    });
}

// Eliminar periodo
function eliminarPeriodo(id, nombre) {
    periodoIdEliminar = id;
    document.getElementById('periodoEliminar').textContent = nombre;
    
    const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}

// Confirmar eliminación
function confirmarEliminarPeriodo() {
    if (!periodoIdEliminar) return;
    
    const formData = new FormData();
    formData.append('id', periodoIdEliminar);
    
    const btnEliminar = document.getElementById('btnConfirmarEliminar');
    const btnText = btnEliminar.innerHTML;
    btnEliminar.disabled = true;
    btnEliminar.innerHTML = '<i class="bi bi-hourglass-split"></i> Eliminando...';
    
    fetch('api/delete_periodo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarMensaje('Periodo eliminado exitosamente', 'success');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminar'));
            modal.hide();
            
            // Recargar lista
            cargarPeriodos();
        } else {
            mostrarMensaje('Error: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarMensaje('Error de conexión al eliminar periodo', 'danger');
    })
    .finally(() => {
        btnEliminar.disabled = false;
        btnEliminar.innerHTML = btnText;
        periodoIdEliminar = null;
    });
}

// Mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    // Remover alertas existentes
    const alertasExistentes = document.querySelectorAll('.alert-dismissible');
    alertasExistentes.forEach(alerta => alerta.remove());
    
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alerta);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.remove();
        }
    }, 5000);
}

// Limpiar modal al cerrarlo
document.getElementById('modalPeriodo').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formPeriodo').reset();
});
