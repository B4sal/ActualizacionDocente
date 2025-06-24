// Variables globales
let horariosDisponibles = [];
let horarioSeleccionado = null;

// Función para cargar períodos
function cargarPeriodos() {
    fetch('api/get_periodos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const selects = ['periodo_reg', 'filtro_periodo'];
                selects.forEach(selectId => {
                    const select = document.getElementById(selectId);
                    const defaultOption = select.querySelector('option[value=""]').textContent;
                    select.innerHTML = `<option value="">${defaultOption}</option>`;
                    
                    data.data.forEach(periodo => {
                        const option = document.createElement('option');
                        option.value = periodo.nombre_periodo;
                        option.textContent = periodo.nombre_periodo;
                        select.appendChild(option);
                    });
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Función para cargar cursos
function cargarCursos(periodo = null) {
    let url = 'api/get_cursos.php';
    if (periodo) {
        url += `?periodo=${encodeURIComponent(periodo)}`;
    }
      fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const selects = ['curso_reg', 'filtro_curso'];
                selects.forEach(selectId => {
                    const select = document.getElementById(selectId);
                    const defaultOption = select.querySelector('option[value=""]').textContent;
                    select.innerHTML = `<option value="">${defaultOption}</option>`;
                    
                    if (data.data.length === 0 && periodo) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No hay cursos disponibles para este período';
                        option.disabled = true;
                        select.appendChild(option);
                    } else {
                        data.data.forEach(curso => {
                            const option = document.createElement('option');
                            option.value = curso.id;
                            option.textContent = curso.curso;
                            option.dataset.nombre = curso.curso;
                            select.appendChild(option);
                        });
                    }
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Función para cargar horarios de un curso
function cargarHorarios(cursoId) {
    if (!cursoId) {
        horariosDisponibles = [];
        return;
    }
    
    fetch(`api/get_horarios.php?curso_id=${cursoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                horariosDisponibles = data.data;
                mostrarHorarios();
            } else {
                horariosDisponibles = [];
                mostrarHorarios();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            horariosDisponibles = [];
            mostrarHorarios();
        });
}

// Función para mostrar horarios en el panel
function mostrarHorarios() {
    const horariosList = document.getElementById('horariosList');
    
    if (horariosDisponibles.length === 0) {
        horariosList.innerHTML = '<p class="text-muted mb-0">No hay horarios disponibles para este curso</p>';
        return;
    }
    
    horariosList.innerHTML = '';
    horariosDisponibles.forEach(horario => {
        const horarioDiv = document.createElement('div');
        horarioDiv.className = 'horario-option';
        horarioDiv.innerHTML = `
            <div><strong>Horario ${horario.num}</strong></div>
            <div class="text-muted small">${horario.horario}</div>
        `;
        horarioDiv.addEventListener('click', () => seleccionarHorario(horario));
        horariosList.appendChild(horarioDiv);
    });
}

// Función para seleccionar un horario
function seleccionarHorario(horario) {
    horarioSeleccionado = horario;
    document.getElementById('horario_seleccionado').value = horario.horario;
    document.getElementById('horario_id').value = horario.num;
    
    // Actualizar visualmente la selección
    document.querySelectorAll('.horario-option').forEach(option => {
        option.classList.remove('selected');
    });
    event.target.closest('.horario-option').classList.add('selected');
    
    // Ocultar panel
    document.getElementById('horariosPanel').style.display = 'none';
    
    // Cargar horarios en filtro
    cargarHorariosFiltro();
}

// Función para cargar horarios en el filtro
function cargarHorariosFiltro() {
    const cursoId = document.getElementById('filtro_curso').value;
    if (!cursoId) return;
    
    fetch(`api/get_horarios.php?curso_id=${cursoId}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('filtro_horario');
            select.innerHTML = '<option value="">Todos los horarios</option>';
            
            if (data.success) {
                data.data.forEach(horario => {
                    const option = document.createElement('option');
                    option.value = horario.num;
                    option.textContent = horario.horario;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Función para cargar profesores registrados
function cargarProfesores() {
    const periodo = document.getElementById('filtro_periodo').value;
    const curso = document.getElementById('filtro_curso').value;
    const horario = document.getElementById('filtro_horario').value;
    
    const params = new URLSearchParams();
    if (periodo) params.append('periodo', periodo);
    if (curso) params.append('curso', curso);
    if (horario) params.append('horario', horario);
    
    fetch(`api/get_profesores.php?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('tablaProfesores');
            
            if (data.success && data.data.length > 0) {
                tbody.innerHTML = '';
                data.data.forEach(profesor => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${profesor.nombre}</td>
                        <td>${profesor.expediente}</td>
                        <td>${profesor.correo}</td>
                        <td>${profesor.curso}</td>
                        <td>${profesor.horario}</td>
                        <td>${profesor.periodo}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarProfesor(${profesor.id_profesor}, '${profesor.periodo}', ${profesor.id_curso})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="bi bi-info-circle"></i> No se encontraron profesores registrados
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('tablaProfesores').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Error al cargar los datos
                    </td>
                </tr>
            `;
        });
}

// Función para eliminar profesor
function eliminarProfesor(idProfesor, periodo, idCurso) {
    if (!confirm('¿Está seguro de que desea eliminar este profesor?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('id_profesor', idProfesor);
    formData.append('periodo', periodo);
    formData.append('id_curso', idCurso);
    
    fetch('api/delete_profesor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            cargarProfesores();
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('Error al eliminar el profesor', 'danger');
    });
}

// Función para limpiar formulario
function limpiarFormulario() {
    document.getElementById('formRegistroProfesor').reset();
    document.getElementById('horario_seleccionado').value = '';
    document.getElementById('horario_id').value = '';
    horarioSeleccionado = null;
    document.getElementById('horariosPanel').style.display = 'none';
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const form = document.getElementById('formRegistroProfesor');
    form.parentNode.insertBefore(alertDiv, form);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Eventos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarPeriodos();
    cargarCursos();
    
    // Evento para mostrar panel de horarios al hacer hover en curso
    const cursoSelect = document.getElementById('curso_reg');
    const horariosPanel = document.getElementById('horariosPanel');
    
    cursoSelect.addEventListener('mouseenter', function() {
        if (this.value) {
            cargarHorarios(this.value);
            horariosPanel.style.display = 'block';
        }
    });
    
    cursoSelect.addEventListener('mouseleave', function() {
        setTimeout(() => {
            if (!horariosPanel.matches(':hover')) {
                horariosPanel.style.display = 'none';
            }
        }, 200);
    });
    
    horariosPanel.addEventListener('mouseleave', function() {
        this.style.display = 'none';
    });
    
    // Evento para cambio en curso del formulario
    cursoSelect.addEventListener('change', function() {
        document.getElementById('horario_seleccionado').value = '';
        document.getElementById('horario_id').value = '';
        horarioSeleccionado = null;
        if (this.value) {
            cargarHorarios(this.value);
        }
    });
    
    // Evento para cambio en período del formulario
    document.getElementById('periodo_reg').addEventListener('change', function() {
        const periodo = this.value;
        document.getElementById('curso_reg').value = '';
        document.getElementById('horario_seleccionado').value = '';
        document.getElementById('horario_id').value = '';
        horarioSeleccionado = null;
        
        if (periodo) {
            cargarCursos(periodo);
        } else {
            cargarCursos();
        }
    });

    // Eventos para filtros
    document.getElementById('filtro_periodo').addEventListener('change', cargarProfesores);
    document.getElementById('filtro_curso').addEventListener('change', function() {
        cargarHorariosFiltro();
        cargarProfesores();
    });
    document.getElementById('filtro_horario').addEventListener('change', cargarProfesores);
    
    // Envío del formulario
    document.getElementById('formRegistroProfesor').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!horarioSeleccionado) {
            mostrarAlerta('Debe seleccionar un horario', 'warning');
            return;
        }
        
        const formData = new FormData();
        formData.append('periodo', document.getElementById('periodo_reg').value);
        formData.append('id_curso', document.getElementById('curso_reg').value);
        formData.append('nombre', document.getElementById('nombre_profesor').value);
        formData.append('expediente', document.getElementById('expediente').value);
        formData.append('correo', document.getElementById('correo').value);
        formData.append('id_horario_curso', document.getElementById('horario_id').value);
        
        fetch('api/save_profesor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                limpiarFormulario();
                cargarProfesores();
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
