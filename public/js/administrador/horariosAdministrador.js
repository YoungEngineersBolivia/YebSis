document.getElementById('modalAsignar')?.addEventListener('show.bs.modal', function (event) {
  const button = event.relatedTarget;
  if (!button) return;

  const get = (attr) => button.getAttribute(attr);

  const estudianteId = get('data-estudiante');
  const horarioId    = get('data-horario');
  const programaId   = get('data-programa');
  const dia1         = get('data-dia1');
  const hora1        = get('data-hora1');
  const dia2         = get('data-dia2');
  const hora2        = get('data-hora2');
  const profesorId   = get('data-profesor');

  const setVal = (id, val) => { const el = document.getElementById(id); if (el && val) el.value = val; };

  setVal('Id_estudiantes', estudianteId);
  setVal('horario_id', horarioId);
  setVal('Id_programas', programaId);
  setVal('Dia_clase_uno', dia1);
  setVal('Horario_clase_uno', hora1);
  setVal('Dia_clase_dos', dia2);
  setVal('Horario_clase_dos', hora2);
  setVal('Id_profesores', profesorId);
});

document.getElementById('Id_estudiantes_crear')?.addEventListener('change', async function () {
    const idEstudiante = this.value;
    if (!idEstudiante) return;

    try {
        const response = await fetch(`/horarios/buscar-profesor/${idEstudiante}`);
        const data = await response.json();

        const profesorSelect = document.getElementById('Id_profesores_crear');

        if (data?.Id_profesores) {
            profesorSelect.value = data.Id_profesores;
        } else {
            profesorSelect.value = "";
        }

    } catch (err) {
        console.error("Error buscando profesor:", err);
    }
});