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
                        <button onclick="editarDetalle(${detalle.id_detalle})">Editar</button>
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

function editarDetalle(id) {
    const periodo = prompt("Ingrese el nuevo periodo:");
    const id_curso = prompt("Ingrese el nuevo ID del curso:");
    const expediente = prompt("Ingrese el nuevo expediente:");
    const nombre = prompt("Ingrese el nuevo nombre:");

    fetch('crud_detalle.php?action=editDetalle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, periodo, id_curso, expediente, nombre })
    })
    .then(response => response.json())
    .then(() => loadDetalles(id_curso));
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
