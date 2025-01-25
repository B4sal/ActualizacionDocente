document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadPeriodos();
    loadCursos();
    loadHorarios();

    // Add event listener for the capture button
    document.getElementById('btnCapturar').addEventListener('click', function() {
        getProfesores();
        const periodo = document.getElementById('periodo').value;
        const curso = document.getElementById('curso').value;
        const horario = document.getElementById('horario').value;
        window.open(`detalle.html?periodo=${periodo}&curso=${curso}&horario=${horario}`, '_blank');
    });
});

function loadPeriodos() {
    fetch('data.php?action=getPeriodos')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('periodo');
            data.forEach(periodo => {
                const option = document.createElement('option');
                option.value = periodo;
                option.textContent = periodo;
                select.appendChild(option);
            });
        });
}

function loadCursos() {
    fetch('data.php?action=getCursos')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('curso');
            data.forEach(curso => {
                const option = document.createElement('option');
                option.value = curso.id_curso;
                option.textContent = curso.curso;
                select.appendChild(option);
            });
        });
}

function loadHorarios() {
    fetch('data.php?action=getHorarios')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('horario');
            data.forEach(horario => {
                const option = document.createElement('option');
                option.value = horario;
                option.textContent = horario;
                select.appendChild(option);
            });
        });
}

function getProfesores() {
    const periodo = document.getElementById('periodo').value;
    const curso = document.getElementById('curso').value;
    const horario = document.getElementById('horario').value;
    
    fetch(`get_profesores.php?periodo=${periodo}&curso=${curso}&horario=${horario}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('noProfesores').textContent = data.no_profesores;
        });
}
