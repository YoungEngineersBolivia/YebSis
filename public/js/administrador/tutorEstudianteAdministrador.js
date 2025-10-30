// public/js/registro-combinado.js

class RegistroCombinado {
    constructor(programas) {
        this.programas = programas;
        this.initElements();
        this.initEventListeners();
        this.init();
    }

    initElements() {
        this.programaSelect = document.querySelector('select[name="programa"]');
        this.precioInput = document.getElementById('Precio_programa');
        this.matriculaInput = document.getElementById('Monto_matricula');
        this.cuotasInput = document.getElementById('nro_cuotas');
        this.montoTotalInput = document.getElementById('Monto_total');
        this.descuentoInput = document.getElementById('Descuento_aplicado');
        this.descuentoPorcentajeInput = document.getElementById('tutor_descuento');
        this.totalConDescuentoInput = document.getElementById('Total_con_descuento');
        this.tablaCuotas = document.getElementById('tabla-cuotas-auto').querySelector('tbody');
        this.cuotasAutoHiddenInputs = document.getElementById('cuotas-auto-hidden-inputs');
        this.partesMatriculaSelect = document.querySelector('select[name="Partes_matricula"]');
        this.fechaPlanInput = document.querySelector('input[name="fecha_plan_pagos"]');
    }

    getDescuentoPorcentaje() {
        let val = this.descuentoPorcentajeInput ? parseFloat(this.descuentoPorcentajeInput.value) : 0;
        return isNaN(val) ? 0 : val;
    }

    actualizarPrecio() {
        const selectedId = this.programaSelect.value;
        const programa = this.programas.find(p => String(p.Id_programas) === String(selectedId));
        
        if (programa) {
            this.precioInput.value = programa.Costo;
        } else {
            this.precioInput.value = '';
        }
        this.actualizarMontoTotal();
    }

    actualizarMontoTotal() {
        const matricula = parseFloat(this.matriculaInput.value) || 0;
        const precioPrograma = parseFloat(this.precioInput.value) || 0;
        const cuotas = parseInt(this.cuotasInput.value) || 0;
        
        // Monto total es matrícula + (precio del programa * cuotas) SIN descuento
        const total = matricula + (precioPrograma * cuotas);
        this.montoTotalInput.value = total > 0 ? total.toFixed(2) : '';

        // Descuento: solo al precio del programa (por cuota * cuotas)
        const descuentoPorcentaje = this.getDescuentoPorcentaje();
        
        const descuentoRaw = (precioPrograma * (descuentoPorcentaje / 100)) * cuotas;
        const descuento = cuotas > 0 && descuentoPorcentaje > 0 ? descuentoRaw.toFixed(2) : '0.00';
        
        // Total con descuento aplicado
        const totalConDescuento = (total - descuentoRaw).toFixed(2);

        this.descuentoInput.value = descuento;
        this.totalConDescuentoInput.value = totalConDescuento;
        
        // Generar cuotas después de actualizar totales
        this.generarCuotas();
    }

    addMonths(date, months) {
        const d = new Date(date);
        const day = d.getDate();
        d.setMonth(d.getMonth() + months);
        if (d.getDate() < day) {
            d.setDate(0);
        }
        return d;
    }

    pad(num) {
        return num < 10 ? '0' + num : num;
    }

    formatDate(date) {
        return date.getFullYear() + '-' + this.pad(date.getMonth() + 1) + '-' + this.pad(date.getDate());
    }

    generarCuotas() {
        const cuotas = parseInt(this.cuotasInput.value) || 0;
        const totalConDescuento = parseFloat(this.totalConDescuentoInput.value) || 0;
        const matricula = parseFloat(this.matriculaInput.value) || 0;
        const partesMatricula = parseInt(this.partesMatriculaSelect.value) || 1;
        const fechaBase = this.fechaPlanInput.value || (new Date()).toISOString().slice(0,10);

        this.tablaCuotas.innerHTML = '';
        this.cuotasAutoHiddenInputs.innerHTML = '';
        
        if (cuotas > 0 && totalConDescuento > 0) {
            // Calcular cuánto de la matrícula va en cada cuota
            let matriculaPorCuota = partesMatricula > 0 ? (matricula / partesMatricula) : 0;
            
            // El resto del total (sin matrícula) se distribuye entre todas las cuotas
            let restoTotal = totalConDescuento - matricula;
            let restoPorCuota = cuotas > 0 ? (restoTotal / cuotas) : 0;

            for (let i = 0; i < cuotas; i++) {
                let montoCuota = restoPorCuota;
                
                // Agregar parte de matrícula solo a las primeras cuotas
                if (i < partesMatricula) {
                    montoCuota += matriculaPorCuota;
                }
                
                // Ajustar última cuota para compensar redondeos
                if (i === cuotas - 1) {
                    let sumaAcumulada = 0;
                    for (let j = 0; j < cuotas - 1; j++) {
                        let tempMonto = restoPorCuota;
                        if (j < partesMatricula) {
                            tempMonto += matriculaPorCuota;
                        }
                        sumaAcumulada += tempMonto;
                    }
                    montoCuota = totalConDescuento - sumaAcumulada;
                }
                
                const fechaVenc = this.addMonths(new Date(fechaBase), i);
                const fechaStr = this.formatDate(fechaVenc);
                
                const row = `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${fechaStr}</td>
                        <td>${montoCuota.toFixed(2)}</td>
                        <td>Pendiente</td>
                    </tr>
                `;
                this.tablaCuotas.insertAdjacentHTML('beforeend', row);

                this.cuotasAutoHiddenInputs.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="cuotas_auto[${i}][Nro_de_cuota]" value="${i + 1}">
                    <input type="hidden" name="cuotas_auto[${i}][Fecha_vencimiento]" value="${fechaStr}">
                    <input type="hidden" name="cuotas_auto[${i}][Monto_cuota]" value="${montoCuota.toFixed(2)}">
                    <input type="hidden" name="cuotas_auto[${i}][Estado_cuota]" value="Pendiente">
                `);
            }
        }
    }

    initEventListeners() {
        if (this.programaSelect) {
            this.programaSelect.addEventListener('change', () => this.actualizarPrecio());
        }
        
        if (this.matriculaInput) {
            this.matriculaInput.addEventListener('input', () => this.actualizarMontoTotal());
        }
        
        if (this.cuotasInput) {
            this.cuotasInput.addEventListener('input', () => this.actualizarMontoTotal());
        }
        
        if (this.descuentoPorcentajeInput) {
            this.descuentoPorcentajeInput.addEventListener('input', () => this.actualizarMontoTotal());
        }
        
        if (this.partesMatriculaSelect) {
            this.partesMatriculaSelect.addEventListener('change', () => this.actualizarMontoTotal());
        }
        
        if (this.fechaPlanInput) {
            this.fechaPlanInput.addEventListener('change', () => this.generarCuotas());
        }
    }

    init() {
        // Inicializa valores al cargar si hay un programa pre-seleccionado
        if (this.programaSelect && this.programaSelect.value) {
            this.actualizarPrecio();
        }
    }
}

// Exportar para uso global
window.RegistroCombinado = RegistroCombinado;