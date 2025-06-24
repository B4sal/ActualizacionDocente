// Variables globales
let horariosTemporales = [];
let cursoEditando = null;

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const mainContent = document.querySelector('.main-content .row');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Función para cargar cursos
function cargarCursos() {
    fetch('api/get_cursos_admin.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarCursos(data.data);
            } else {
                mostrarAlerta('Error al cargar los cursos', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión al cargar cursos', 'danger');
        });
}

// Función para mostrar cursos en la tabla
function mostrarCursos(cursos) {
    const tbody = document.getElementById('tablaCursos');
    tbody.innerHTML = '';
    
    if (cursos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay cursos registrados</td></tr>';
        return;
    }
    
    cursos.forEach(curso => {
        const horariosHtml = curso.horarios.map(h => 
            `<span class="badge bg-secondary me-1 mb-1">${h.horario}</span>`
        ).join('');
        
        const statusClass = curso.status === 'A' ? 'status-activo' : 'status-inactivo';
        const statusText = curso.status === 'A' ? 'Activo' : 'Inactivo';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${curso.id}</td>
            <td>${curso.curso}</td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
            <td>
                <div class="horarios-container">
                    ${horariosHtml || '<span class="text-muted">Sin horarios</span>'}
                </div>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarCurso(${curso.id})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmarEliminar(${curso.id}, '${curso.curso}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Función para agregar horario temporal
function agregarHorario() {
    const horarioInput = document.getElementById('nuevoHorario');
    const horario = horarioInput.value.trim();
    
    if (!horario) {
        mostrarAlerta('Por favor ingrese un horario', 'warning');
        return;
    }
    
    // Verificar que no exista ya
    if (horariosTemporales.some(h => h.horario === horario)) {
        mostrarAlerta('Este horario ya existe', 'warning');
        return;
    }
    
    // Agregar a la lista temporal
    const nuevoHorario = {
        num: horariosTemporales.length + 1,
        horario: horario
    };
    
    horariosTemporales.push(nuevoHorario);
    horarioInput.value = '';
    
    mostrarHorariosTemporales();
}

// Función para mostrar horarios temporales
function mostrarHorariosTemporales() {
    const container = document.getElementById('listaHorarios');
    container.innerHTML = '';
    
    if (horariosTemporales.length === 0) {
        container.innerHTML = '<p class="text-muted mb-0">No hay horarios agregados</p>';
        return;
    }
    
    horariosTemporales.forEach((horario, index) => {
        const div = document.createElement('div');
        div.className = 'horario-item';
        div.innerHTML = `
            <span><strong>Horario ${horario.num}:</strong> ${horario.horario}</span>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarHorarioTemporal(${index})">
                <i class="bi bi-x"></i>
            </button>
        `;
        container.appendChild(div);
    });
}

// Función para eliminar horario temporal
function eliminarHorarioTemporal(index) {
    horariosTemporales.splice(index, 1);
    // Reindexar
    horariosTemporales.forEach((h, i) => h.num = i + 1);
    mostrarHorariosTemporales();
}

// Función para limpiar modal
function limpiarModal() {
    document.getElementById('formCurso').reset();
    document.getElementById('cursoId').value = '';
    document.getElementById('modalCursoTitle').innerHTML = '<i class="bi bi-book"></i> Nuevo Curso';
    horariosTemporales = [];
    cursoEditando = null;
    mostrarHorariosTemporales();
}

// Función para editar curso
function editarCurso(id) {
    fetch(`api/get_curso_detalle.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const curso = data.data;
                
                // Llenar el formulario
                document.getElementById('cursoId').value = curso.id;
                document.getElementById('nombreCurso').value = curso.curso;
                document.getElementById('statusCurso').value = curso.status;
                document.getElementById('modalCursoTitle').innerHTML = '<i class="bi bi-pencil"></i> Editar Curso';
                
                // Cargar horarios
                horariosTemporales = curso.horarios || [];
                mostrarHorariosTemporales();
                
                cursoEditando = id;
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalCurso'));
                modal.show();
            } else {
                mostrarAlerta('Error al cargar los datos del curso', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        });
}

// Función para confirmar eliminación
function confirmarEliminar(id, nombre) {
    document.getElementById('cursoEliminar').textContent = nombre;
    document.getElementById('btnConfirmarEliminar').onclick = () => eliminarCurso(id);
    
    const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}

// Función para eliminar curso
function eliminarCurso(id) {
    fetch('api/delete_curso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            cargarCursos();
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminar'));
            modal.hide();
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('Error al eliminar el curso', 'danger');
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Cargar cursos al iniciar
    cargarCursos();
    
    // Botón agregar horario
    document.getElementById('btnAgregarHorario').addEventListener('click', agregarHorario);
    
    // Enter en el input de horario
    document.getElementById('nuevoHorario').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            agregarHorario();
        }
    });
    
    // Limpiar modal al cerrarse
    document.getElementById('modalCurso').addEventListener('hidden.bs.modal', limpiarModal);
    
    // Manejar envío del formulario
    document.getElementById('formCurso').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const cursoId = document.getElementById('cursoId').value;
        
        if (cursoId) {
            formData.append('id', cursoId);
        }
        
        formData.append('nombre', document.getElementById('nombreCurso').value);
        formData.append('status', document.getElementById('statusCurso').value);
        formData.append('horarios', JSON.stringify(horariosTemporales));
        
        const url = cursoId ? 'api/update_curso.php' : 'api/create_curso.php';
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                cargarCursos();
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalCurso'));
                modal.hide();
            } else {
                mostrarAlerta(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al procesar la solicitud', 'danger');
        });
    });
});
