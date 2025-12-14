document.addEventListener('DOMContentLoaded', () => {
    const buscarInput = document.getElementById('buscarEstudiante');
    const listaEstudiantesDiv = document.getElementById('lista-estudiantes');
    const noResultadosDiv = document.getElementById('no-resultados');
    const contadorResultados = document.getElementById('contador-resultados');

    // Función para filtrar tarjetas (solo búsqueda)
    function filtrarTarjetas() {
        const searchTerm = buscarInput.value.toLowerCase().trim();
        const tarjetas = document.querySelectorAll('.estudiante-card');
        let visibles = 0;

        tarjetas.forEach(tarjeta => {
            const tutor = tarjeta.dataset.tutor || '';
            const estudiante = tarjeta.dataset.estudiante || '';

            // Verificar búsqueda
            const coincideBusqueda = searchTerm === '' ||
                tutor.includes(searchTerm) ||
                estudiante.includes(searchTerm);

            // Mostrar/ocultar tarjeta
            if (coincideBusqueda) {
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
            // document.getElementById('modal-max-monto').textContent = restante.toFixed(2); // Ya no se usa

            // Configurar input de monto
            const montoInput = document.getElementById('modal-monto-input');
            montoInput.removeAttribute('max'); // Remover límite máximo
            montoInput.value = '';

            // Validación en tiempo real (solo negativos)
            montoInput.addEventListener('input', function () {
                // Ya no validamos máximo, solo que sea positivo
            });
        });
    }

    // Validación del formulario antes de enviar
    const formAgregarPago = document.getElementById('formAgregarPago');
    if (formAgregarPago) {
        formAgregarPago.addEventListener('submit', function (e) {
            const montoInput = document.getElementById('modal-monto-input');
            const monto = parseFloat(montoInput.value) || 0;

            if (monto <= 0) {
                e.preventDefault();
                alert('El monto debe ser mayor a 0');
                return false;
            }

            // Ya no validamos el restante
            return true;
        });
    }
});