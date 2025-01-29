document.addEventListener('DOMContentLoaded', function() {
    fetch('fetch_data.php')
        .then(response => response.json())
        .then(data => {
            const periodoSelect = document.getElementById('periodo');
            const cursoSelect = document.getElementById('curso');
            const horarioSelect = document.getElementById('horario');

            // Set default blank option
            periodoSelect.innerHTML = '<option value="">Seleccione un periodo</option>';
            cursoSelect.innerHTML = '<option value="">Seleccione un curso</option>';
            horarioSelect.innerHTML = '<option value="">Seleccione un horario</option>';

            const periodos = new Set();
            const cursos = new Set();
            const horarios = new Set();

            data.forEach(item => {
                if (!periodos.has(item.periodo)) {
                    periodos.add(item.periodo);
                    const periodoOption = document.createElement('option');
                    periodoOption.value = item.periodo;
                    periodoOption.textContent = item.periodo;
                    periodoSelect.appendChild(periodoOption);
                }

                if (!cursos.has(item.curso)) {
                    cursos.add(item.curso);
                    const cursoOption = document.createElement('option');
                    cursoOption.value = item.curso;
                    cursoOption.textContent = item.curso;
                    cursoSelect.appendChild(cursoOption);
                }

                if (!horarios.has(item.horario)) {
                    horarios.add(item.horario);
                    const horarioOption = document.createElement('option');
                    horarioOption.value = item.horario;
                    horarioOption.textContent = item.horario;
                    horarioSelect.appendChild(horarioOption);
                }
            });
        });

    document.getElementById('fetchButton').addEventListener('click', function() {
        const periodo = document.getElementById('periodo').value;
        const curso = document.getElementById('curso').value;
        const horario = document.getElementById('horario').value;

        fetch('fetch_data.php')
            .then(response => response.json())
            .then(data => {
                const profesoresInput = document.getElementById('profesores');
                const selectedData = data.find(item => item.periodo === periodo && item.curso === curso && item.horario === horario);
                if (selectedData) {
                    profesoresInput.value = selectedData.no_profesores;
                } else {
                    profesoresInput.value = 0;
                }
            });
    });
});