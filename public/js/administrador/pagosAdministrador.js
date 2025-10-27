document.addEventListener('DOMContentLoaded', () => {
// Usa values() para asegurar que el array sea plano y serializable correctamente
const estudiantes = JSON.parse(document.getElementById('estudiantes-data').textContent);
const csrfToken = '{{ csrf_token() }}';
const buscarInput = document.getElementById('buscarEstudiante');
const sugerenciasDiv = document.getElementById('sugerencias');
const estudianteInfoDiv = document.getElementById('estudiante-info');
const listaEstudiantesDiv = document.getElementById('lista-estudiantes');

buscarInput.addEventListener('input', function() {
    const val = this.value.trim().toLowerCase();
    sugerenciasDiv.innerHTML = '';
    estudianteInfoDiv.style.display = 'none';
    listaEstudiantesDiv.style.display = '';

    if (!val) return;

    const filtrados = estudiantes.filter(est => {
        const nombreCompleto = `${est.persona.Nombre} ${est.persona.Apellido}`.toLowerCase();
        return nombreCompleto.includes(val);
    });

    if (filtrados.length > 0) {
        filtrados.forEach(est => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action';
            btn.textContent = `${est.persona.Nombre} ${est.persona.Apellido}`;
            btn.onclick = () => {
                mostrarEstudiante(est);
                buscarInput.value = btn.textContent;
                sugerenciasDiv.innerHTML = '';
            };
            sugerenciasDiv.appendChild(btn);
        });
    } else {
        const noData = document.createElement('div');
        noData.className = 'list-group-item text-muted';
        noData.textContent = 'Sin coincidencias';
        sugerenciasDiv.appendChild(noData);
    }
});

function mostrarEstudiante(est) {
    console.log(est); // Depuración
    let cuotas = [];
    if (est.plan_pago && est.plan_pago.cuotas) {
        cuotas = est.plan_pago.cuotas;
    }
    let html = `
        <div class="card mb-3">
            <div class="card-body">
                <h5>${est.persona.Nombre ?? ''} ${est.persona.Apellido ?? ''}</h5>
                <p>
                    Padre: ${est.tutor && est.tutor.persona ? est.tutor.persona.Nombre + ' ' + est.tutor.persona.Apellido : 'Sin padre asignado'}
                </p>
                <h6>Cuotas:</h6>
    `;
    if (est.plan_pago) {
        if (cuotas.length > 0) {
            html += `
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Nro_de_cuota</th>
                            <th>Fecha_vencimiento</th>
                            <th>Monto_cuota</th>
                            <th>Monto_pagado</th>
                            <th>Estado_cuota</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            cuotas.forEach(cuota => {
                html += `
                    <tr>
                        <td>${cuota.Nro_de_cuota}</td>
                        <td>${cuota.Fecha_vencimiento}</td>
                        <td>${cuota.Monto_cuota}</td>
                        <td>${cuota.Monto_pagado ?? 'NULL'}</td>
                        <td>${cuota.Estado_cuota}</td>
                        <td>
                            ${!cuota.pagado ? `
                                <button type="button" 
                                    class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalRegistrarPago"
                                    data-cuota-id="${cuota.Id_cuotas}"
                                    data-monto="${cuota.Monto_cuota}"
                                    data-plan-id="${cuota.Id_planes_pagos}">
                                    Registrar Pago
                                </button>
                            ` : 'Pagado'}
                        </td>
                    </tr>
                `;
            });
            html += `
                    </tbody>
                </table>
            `;
        } else {
            html += '<ul><li>El estudiante tiene un plan de pago (ID: ' + est.plan_pago.id + '), pero no tiene cuotas registradas.</li></ul>';
        }
    } else {
        html += '<ul><li>El estudiante no tiene plan de pago asignado.</li></ul>';
    }
    html += `
            </div>
        </div>
    `;
    estudianteInfoDiv.innerHTML = html;
    estudianteInfoDiv.style.display = '';
    listaEstudiantesDiv.style.display = 'none';
}

buscarInput.addEventListener('blur', () => {
    setTimeout(() => sugerenciasDiv.innerHTML = '', 150);
});

// Modal: pasar el id de la cuota al input oculto
const modalRegistrarPago = document.getElementById('modalRegistrarPago');
modalRegistrarPago.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    
    // Debug: Mostrar el botón completo
    console.log('Botón:', button);
    console.log('Atributos:', {
        cuotaId: button.getAttribute('data-cuota-id'),
        monto: button.getAttribute('data-monto'),
        planId: button.getAttribute('data-plan-id')
    });
    
    // Obtener datos del botón
    const cuotaId = button.getAttribute('data-cuota-id');
    const monto = button.getAttribute('data-monto');
    const planId = button.getAttribute('data-plan-id');
    
    // Verificar que tenemos los datos
    if (!cuotaId || !monto || !planId) {
        console.error('Faltan datos de la cuota:', { cuotaId, monto, planId });
        return;
    }

    // Llenar el formulario
    document.getElementById('modal-cuota-id').value = cuotaId;
    document.getElementById('modal-monto-pago').value = monto;
    document.getElementById('modal-id-planes-pagos').value = planId;
    document.getElementById('modal-fecha-pago').value = new Date().toISOString().split('T')[0];
    document.getElementById('modal-descripcion').value = `Pago de cuota #${cuotaId}`;
    document.getElementById('modal-comprobante').value = `COMP-${new Date().getTime()}-${cuotaId}`;
});
});