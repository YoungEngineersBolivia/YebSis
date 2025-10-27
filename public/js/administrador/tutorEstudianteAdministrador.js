document.addEventListener('DOMContentLoaded', function() {
    const programaSelect = document.querySelector('select[name="programa"]');
    const precioInput = document.getElementById('Precio_programa');
    const matriculaInput = document.getElementById('Monto_matricula');
    const cuotasInput = document.getElementById('nro_cuotas');
    const montoTotalInput = document.getElementById('Monto_total');
    const descuentoInput = document.getElementById('Descuento_aplicado');
    const descuentoPorcentajeInput = document.getElementById('tutor_descuento');
    const totalConDescuentoInput = document.getElementById('Total_con_descuento');
    const tablaCuotas = document.getElementById('tabla-cuotas-auto').querySelector('tbody');
    const cuotasAutoHiddenInputs = document.getElementById('cuotas-auto-hidden-inputs');

    function getDescuentoPorcentaje() {
        let val = descuentoPorcentajeInput ? parseFloat(descuentoPorcentajeInput.value) : 0;
        return isNaN(val) ? 0 : val;
    }

    function actualizarPrecio() {
        const selectedId = programaSelect.value;
        const programa = programas.find(p => String(p.Id_programas) === String(selectedId));
        if (programa) {
            precioInput.value = programa.Costo;
        } else {
            precioInput.value = '';
        }
        actualizarMontoTotal();
    }

    function actualizarMontoTotal() {
        const matricula = parseFloat(matriculaInput.value) || 0;
        const precioPrograma = parseFloat(precioInput.value) || 0;
        const cuotas = parseInt(cuotasInput.value) || 0;
        // Monto total es matrícula + (precio del programa * cuotas) SIN descuento
        const total = Math.round(matricula + (precioPrograma * cuotas));

        montoTotalInput.value = total > 0 ? total : '';

        // Descuento: solo al precio del programa (por cuota * cuotas)
        const descuentoPorcentaje = getDescuentoPorcentaje();
        // Redondeo a la decena más próxima (por ejemplo, 82.32 -> 82.40)
        function roundToNearestTenCents(num) {
            return (Math.ceil(num * 100 / 10) * 10 / 100).toFixed(2);
        }
        const descuentoRaw = (precioPrograma * (descuentoPorcentaje / 100)) * cuotas;
        const descuento = cuotas > 0 && !isNaN(descuentoPorcentaje) ? roundToNearestTenCents(descuentoRaw) : '0.00';
        // Total con descuento aplicado
        const descuentoTotalRaw = total - descuentoRaw;
        const descuentoTotal = total > 0 && !isNaN(descuentoPorcentaje) ? roundToNearestTenCents(descuentoTotalRaw) : '0.00';

        descuentoInput.value = descuento;
        totalConDescuentoInput.value = descuentoTotal;
    }

    function addMonths(date, months) {
        // Suma meses y mantiene el día si es posible
        const d = new Date(date);
        const day = d.getDate();
        d.setMonth(d.getMonth() + months);
        // Si el mes siguiente no tiene ese día, ajusta al último día del mes
        if (d.getDate() < day) {
            d.setDate(0);
        }
        return d;
    }

    function pad(num) {
        return num < 10 ? '0' + num : num;
    }

    function formatDate(date) {
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
    }

    function generarCuotas() {
        const cuotas = parseInt(cuotasInput.value) || 0;
        const totalConDescuento = parseFloat(totalConDescuentoInput.value) || 0;
        const matricula = parseFloat(matriculaInput.value) || 0;
        const partesMatricula = parseInt(document.querySelector('select[name="Partes_matricula"]').value) || 1;
        const fechaBase = document.querySelector('input[name="fecha_plan_pagos"]').value || (new Date()).toISOString().slice(0,10);

        tablaCuotas.innerHTML = '';
        cuotasAutoHiddenInputs.innerHTML = '';
        if (cuotas > 0 && totalConDescuento > 0) {
            // Calcular cuánto de la matrícula va en cada cuota (solo en las primeras "partesMatricula" cuotas)
            let matriculaPorCuota = partesMatricula > 0 ? Math.floor((matricula / partesMatricula) * 100) / 100 : 0;
            let matriculaRestante = matricula - (matriculaPorCuota * partesMatricula);

            // El resto del total (sin matrícula) se distribuye entre todas las cuotas
            let restoTotal = totalConDescuento - matricula;
            let restoPorCuota = cuotas > 0 ? Math.floor((restoTotal / cuotas) * 100) / 100 : 0;
            let restoRestante = restoTotal - (restoPorCuota * cuotas);

            for (let i = 0; i < cuotas; i++) {
                // Sumar matrícula solo a las primeras "partesMatricula" cuotas
                let montoCuota = restoPorCuota;
                if (i < partesMatricula) {
                    montoCuota += matriculaPorCuota;
                    // Ajustar la última cuota de matrícula por redondeo
                    if (i === partesMatricula - 1) {
                        montoCuota += matriculaRestante;
                    }
                }
                // Ajustar la última cuota general por redondeo
                if (i === cuotas - 1) {
                    montoCuota += restoRestante;
                }
                const fechaVenc = addMonths(new Date(fechaBase), i);
                const fechaStr = formatDate(fechaVenc);
                const row = `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${fechaStr}</td>
                        <td>${montoCuota.toFixed(2)}</td>
                        <td>Pendiente</td>
                    </tr>
                `;
                tablaCuotas.insertAdjacentHTML('beforeend', row);

                // Agrega los inputs ocultos fuera de la tabla para que se envíen correctamente
                cuotasAutoHiddenInputs.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="cuotas_auto[${i}][Nro_de_cuota]" value="${i + 1}">
                    <input type="hidden" name="cuotas_auto[${i}][Fecha_vencimiento]" value="${fechaStr}">
                    <input type="hidden" name="cuotas_auto[${i}][Monto_cuota]" value="${montoCuota.toFixed(2)}">
                    <input type="hidden" name="cuotas_auto[${i}][Estado_cuota]" value="Pendiente">
                `);
            }
        }
    }

    function initEventListeners() {
        if (programaSelect) {
            programaSelect.addEventListener('change', actualizarPrecio);
        }
        if (matriculaInput) {
            matriculaInput.addEventListener('input', actualizarMontoTotal);
        }
        if (cuotasInput) {
            cuotasInput.addEventListener('input', function() {
                actualizarMontoTotal();
                generarCuotas();
            });
        }
        if (descuentoPorcentajeInput) {
            descuentoPorcentajeInput.addEventListener('input', actualizarMontoTotal);
        }
        if (totalConDescuentoInput) {
            totalConDescuentoInput.addEventListener('input', generarCuotas);
        }
        document.querySelector('input[name="fecha_plan_pagos"]').addEventListener('change', generarCuotas);
        document.querySelector('select[name="Partes_matricula"]').addEventListener('change', function() {
            actualizarMontoTotal();
            generarCuotas();
        });

        // También actualiza cuotas cuando cambian los valores que afectan el total
        [matriculaInput, precioInput, descuentoPorcentajeInput].forEach(function(input) {
            if (input) input.addEventListener('input', function() {
                actualizarMontoTotal();
                generarCuotas();
            });
        });
    }

    initEventListeners();

    // Inicializa valores al cargar
    actualizarPrecio();
    generarCuotas();
});