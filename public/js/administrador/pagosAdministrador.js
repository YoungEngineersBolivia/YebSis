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

    // Modal "Agregar Pago" - Nuevo sistema
    const modalAgregarPago = document.getElementById('modalAgregarPago');
    if (modalAgregarPago) {
        modalAgregarPago.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Obtener datos del botón
            const planId = button.getAttribute('data-plan-id');
            const montoTotal = parseFloat(button.getAttribute('data-monto-total'));
            const montoPagado = parseFloat(button.getAttribute('data-monto-pagado'));
            const restante = parseFloat(button.getAttribute('data-restante'));
            const programaNombre = button.getAttribute('data-programa');

            // Verificar que tenemos los datos
            if (!planId || isNaN(montoTotal) || isNaN(restante)) {
                console.error('Faltan datos del plan:', { planId, montoTotal, montoPagado, restante });
                return;
            }

            // Llenar información del modal
            document.getElementById('modal-plan-id').value = planId;
            document.getElementById('modal-programa-nombre').textContent = programaNombre;
            document.getElementById('modal-monto-total-display').textContent = montoTotal.toFixed(2);
            document.getElementById('modal-restante-display').textContent = restante.toFixed(2);
            document.getElementById('modal-max-monto').textContent = restante.toFixed(2);

            // Configurar validación del input de monto
            const montoInput = document.getElementById('modal-monto-input');
            montoInput.max = restante;
            montoInput.value = '';

            // Validación en tiempo real
            montoInput.addEventListener('input', function () {
                const valor = parseFloat(this.value) || 0;
                if (valor > restante) {
                    this.value = restante.toFixed(2);
                    alert(`El monto no puede exceder el saldo restante de Bs. ${restante.toFixed(2)}`);
                }
            });
        });
    }

    // Validación del formulario antes de enviar
    const formAgregarPago = document.getElementById('formAgregarPago');
    if (formAgregarPago) {
        formAgregarPago.addEventListener('submit', function (e) {
            const montoInput = document.getElementById('modal-monto-input');
            const restanteDisplay = document.getElementById('modal-restante-display');
            const restante = parseFloat(restanteDisplay.textContent);
            const monto = parseFloat(montoInput.value) || 0;

            if (monto <= 0) {
                e.preventDefault();
                alert('El monto debe ser mayor a 0');
                return false;
            }

            if (monto > restante) {
                e.preventDefault();
                alert(`El monto no puede exceder el saldo restante de Bs. ${restante.toFixed(2)}`);
                return false;
            }

            return true;
        });
    }
});