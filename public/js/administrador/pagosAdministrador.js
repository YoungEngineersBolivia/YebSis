document.addEventListener('DOMContentLoaded', () => {
    const buscarInput = document.getElementById('buscarEstudiante');
    const listaEstudiantesDiv = document.getElementById('lista-estudiantes');
    const noResultadosDiv = document.getElementById('no-resultados');
    const contadorResultados = document.getElementById('contador-resultados');

    // Filtros
    const filterTodos = document.getElementById('filter-todos');
    const filterPendientes = document.getElementById('filter-pendientes');
    const filterCompletados = document.getElementById('filter-completados');

    let filtroActual = 'todos';

    // Función para filtrar tarjetas
    function filtrarTarjetas() {
        const searchTerm = buscarInput.value.toLowerCase().trim();
        const tarjetas = document.querySelectorAll('.estudiante-card');
        let visibles = 0;

        tarjetas.forEach(tarjeta => {
            const tutor = tarjeta.dataset.tutor || '';
            const estudiante = tarjeta.dataset.estudiante || '';
            const estado = tarjeta.dataset.estado || '';

            // Verificar búsqueda
            const coincideBusqueda = searchTerm === '' ||
                tutor.includes(searchTerm) ||
                estudiante.includes(searchTerm);

            // Verificar filtro de estado
            let coincideFiltro = true;
            if (filtroActual === 'pendientes') {
                coincideFiltro = estado === 'pendiente';
            } else if (filtroActual === 'completados') {
                coincideFiltro = estado === 'completado';
            }

            // Mostrar/ocultar tarjeta
            if (coincideBusqueda && coincideFiltro) {
                tarjeta.style.display = '';
                visibles++;
            } else {
                tarjeta.style.display = 'none';
            }
        });

        // Actualizar contador y mensaje de no resultados
        if (visibles === 0) {
            noResultadosDiv.style.display = 'block';
            contadorResultados.textContent = 'No se encontraron resultados';
        } else {
            noResultadosDiv.style.display = 'none';
            const plural = visibles === 1 ? 'estudiante' : 'estudiantes';
            contadorResultados.textContent = `${visibles} ${plural}`;
        }
    }

    // Event listener para búsqueda
    buscarInput.addEventListener('input', filtrarTarjetas);

    // Event listeners para filtros
    filterTodos.addEventListener('click', function () {
        filtroActual = 'todos';
        actualizarBotonesFiltro(this);
        filtrarTarjetas();
    });

    filterPendientes.addEventListener('click', function () {
        filtroActual = 'pendientes';
        actualizarBotonesFiltro(this);
        filtrarTarjetas();
    });

    filterCompletados.addEventListener('click', function () {
        filtroActual = 'completados';
        actualizarBotonesFiltro(this);
        filtrarTarjetas();
    });

    // Función para actualizar estado visual de botones de filtro
    function actualizarBotonesFiltro(botonActivo) {
        [filterTodos, filterPendientes, filterCompletados].forEach(btn => {
            btn.classList.remove('active');
        });
        botonActivo.classList.add('active');
    }

    // Modal: pasar el id de la cuota al input oculto
    const modalRegistrarPago = document.getElementById('modalRegistrarPago');
    if (modalRegistrarPago) {
        modalRegistrarPago.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

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
    }

    // Función para pagar plan completo (todas las cuotas pendientes)
    window.pagarPlanCompleto = function (planId, totalPendiente) {
        if (confirm(`¿Está seguro de PAGAR TODAS LAS CUOTAS PENDIENTES del plan #${planId}?\n\nMonto total: Bs. ${totalPendiente.toFixed(2)}`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = pagarPlanCompletoUrl;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const planIdInput = document.createElement('input');
            planIdInput.type = 'hidden';
            planIdInput.name = 'plan_id';
            planIdInput.value = planId;
            form.appendChild(planIdInput);

            document.body.appendChild(form);
            form.submit();
        }
    };
});