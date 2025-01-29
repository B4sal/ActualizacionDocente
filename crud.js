document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#encabezadoTable tbody');
    const formContainer = document.getElementById('formContainer');
    const formTitle = document.getElementById('formTitle');
    const crudForm = document.getElementById('crudForm');
    const addButton = document.getElementById('addButton');
    const cancelButton = document.getElementById('cancelButton');
    let editing = false;

    function fetchData() {
        fetch('crud_operations.php?action=read')
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id_curso}</td>
                        <td>${item.periodo}</td>
                        <td>${item.curso}</td>
                        <td>${item.horario}</td>
                        <td>${item.no_profesores}</td>
                        <td>
                            <button onclick="editData(${item.id_curso})">Editar</button>
                            <button onclick="deleteData(${item.id_curso})">Eliminar</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });
    }

    window.editData = function(id) {
        fetch(`crud_operations.php?action=read&id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('id_curso').value = data.id_curso;
                document.getElementById('periodo').value = data.periodo;
                document.getElementById('curso').value = data.curso;
                document.getElementById('horario').value = data.horario;
                document.getElementById('no_profesores').value = data.no_profesores;
                formTitle.textContent = 'Editar Curso';
                formContainer.style.display = 'block';
                editing = true;
            });
    }

    window.deleteData = function(id) {
        if (confirm('¿Está seguro de que desea eliminar este curso?')) {
            fetch(`crud_operations.php?action=delete&id=${id}`, { method: 'POST' })
                .then(response => response.json())
                .then(() => fetchData());
        }
    }

    addButton.addEventListener('click', function() {
        formTitle.textContent = 'Agregar Nuevo Curso';
        crudForm.reset();
        formContainer.style.display = 'block';
        editing = false;
    });

    cancelButton.addEventListener('click', function() {
        formContainer.style.display = 'none';
    });

    crudForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(crudForm);
        const action = editing ? 'update' : 'create';
        fetch(`crud_operations.php?action=${action}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(() => {
            formContainer.style.display = 'none';
            fetchData();
        });
    });

    fetchData();
});
