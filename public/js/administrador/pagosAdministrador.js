document.addEventListener('DOMContentLoaded', () => {
    const buscarInput = document.getElementById('buscarEstudiante');
    const listaEstudiantesDiv = document.getElementById('lista-estudiantes');
    const noResultadosDiv = document.getElementById('no-resultados');
    const contadorResultados = document.getElementById('contador-resultados');

    let timeout = null;

    // Función para buscar vía AJAX
    function buscarEstudiantes() {
        const searchTerm = buscarInput.value.trim();
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchTerm);
        // Al buscar, usualmente queremos volver a la página 1
        url.searchParams.delete('page');

        // Mostrar un pequeño indicador de carga si lo hubiera
        // (Opcional, pero ayuda a la experiencia)

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.text())
            .then(html => {
                document.getElementById('contenedor-estudiantes').innerHTML = html;

                // Actualizar contador
                const totalHidden = document.getElementById('total-count-hidden');
                if (totalHidden) {
                    const total = totalHidden.value;
                    contadorResultados.textContent = total;
                    if (noResultadosDiv) {
                        if (parseInt(total) === 0) {
                            noResultadosDiv.style.display = 'block';
                        } else {
                            noResultadosDiv.style.display = 'none';
                        }
                    }
                }

                // Re-vincular eventos si es necesario (ej: botones de paginación)
                vincularPaginacion();
            })
            .catch(error => console.error('Error al buscar:', error));
    }

    // Función para manejar clicks en links de paginación (AJAX)
    function vincularPaginacion() {
        const links = document.querySelectorAll('#pagination-links a');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.href;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('contenedor-estudiantes').innerHTML = html;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        vincularPaginacion();
                    })
                    .catch(error => console.error('Error en paginación:', error));
            });
        });
    }

    // Event listener para búsqueda con debounce
    buscarInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(buscarEstudiantes, 500);
    });

    // Inicializar paginación AJAX
    vincularPaginacion();

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