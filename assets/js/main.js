// Función para cargar períodos
function cargarPeriodos() {
    fetch('api/get_periodos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('periodo');
                select.innerHTML = '<option value="">Seleccionar período...</option>';
                
                data.data.forEach(periodo => {
                    const option = document.createElement('option');
                    option.value = periodo.nombre_periodo;
                    option.textContent = periodo.nombre_periodo;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Función para cargar cursos
function cargarCursos() {
    fetch('api/get_cursos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('curso');
                select.innerHTML = '<option value="">Seleccionar curso...</option>';
                
                data.data.forEach(curso => {
                    const option = document.createElement('option');
                    option.value = curso.id;
                    option.textContent = curso.curso;
                    option.dataset.nombre = curso.curso;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const form = document.getElementById('formEncabezado');
    form.parentNode.insertBefore(alertDiv, form);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Cargar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarPeriodos();
    cargarCursos();
    
    // Manejar envío del formulario
    document.getElementById('formEncabezado').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const cursoSelect = document.getElementById('curso');
        const selectedOption = cursoSelect.options[cursoSelect.selectedIndex];
        
        formData.append('id_curso', cursoSelect.value);
        formData.append('periodo', document.getElementById('periodo').value);
        formData.append('curso_nombre', selectedOption.dataset.nombre);
        formData.append('horario', document.getElementById('horario').value);
        formData.append('no_profesores', document.getElementById('no_profesores').value);
        
        fetch('api/save_encabezado.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                document.getElementById('formEncabezado').reset();
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
