document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const curso = params.get('curso');

    loadDetalles(curso);

    document.getElementById('btnAgregar').addEventListener('click', agregarDetalle);
    document.getElementById('btnRegresar').addEventListener('click', function() {
        window.close();
    });
});

function loadDetalles(curso) {
    fetch(`crud_detalle.php?action=getDetalles&curso=${curso}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('detallesBody');
            tableBody.innerHTML = '';
            data.forEach(detalle => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${detalle.id_detalle}</td>
                    <td>${detalle.periodo}</td>
                    <td>${detalle.id_curso}</td>
                    <td>${detalle.expediente}</td>
                    <td>${detalle.nombre}</td>
                    <td>
                        <button onclick="mostrarFormularioEditar(${detalle.id_detalle}, '${detalle.periodo}', ${detalle.id_curso}, '${detalle.expediente}', '${detalle.nombre}')">Editar</button>
                        <button onclick="borrarDetalle(${detalle.id_detalle})">Borrar</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        });
}

function agregarDetalle() {
    const periodo = prompt("Ingrese el periodo:");
    const id_curso = prompt("Ingrese el ID del curso:");
    const expediente = prompt("Ingrese el expediente:");
    const nombre = prompt("Ingrese el nombre:");

    fetch('crud_detalle.php?action=addDetalle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ periodo, id_curso, expediente, nombre })
    })
    .then(response => response.json())
    .then(() => loadDetalles(id_curso));
}

function mostrarFormularioEditar(id, periodo, id_curso, expediente, nombre) {
    const form = document.getElementById('formEditar');
    form.innerHTML = `
        <input type="hidden" id="editId" value="${id}">
        <label for="editPeriodo">Periodo:</label>
        <input type="text" id="editPeriodo" value="${periodo}">
        <label for="editCurso">ID Curso:</label>
        <input type="text" id="editCurso" value="${id_curso}">
        <label for="editExpediente">Expediente:</label>
        <input type="text" id="editExpediente" value="${expediente}">
        <label for="editNombre">Nombre:</label>
        <input type="text" id="editNombre" value="${nombre}">
        <button type="button" onclick="editarDetalle()">Guardar</button>
    `;
    form.style.display = 'block';
}

function editarDetalle() {
    const id = document.getElementById('editId').value;
    const periodo = document.getElementById('editPeriodo').value;
    const id_curso = document.getElementById('editCurso').value;
    const expediente = document.getElementById('editExpediente').value;
    const nombre = document.getElementById('editNombre').value;

    fetch('crud_detalle.php?action=editDetalle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, periodo, id_curso, expediente, nombre })
    })
    .then(response => response.json())
    .then(() => {
        const params = new URLSearchParams(window.location.search);
        const curso = params.get('curso');
        loadDetalles(curso);
        document.getElementById('formEditar').style.display = 'none';
    });
}

function borrarDetalle(id) {
    fetch(`crud_detalle.php?action=deleteDetalle&id=${id}`, { method: 'DELETE' })
        .then(response => response.json())
        .then(() => {
            const params = new URLSearchParams(window.location.search);
            const curso = params.get('curso');
            loadDetalles(curso);
        });
}
