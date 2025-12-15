<?php $__env->startSection('title', 'Registro Estudiante y Tutor'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <label><b>Buscar Tutor</b></label>
        <div style="position: relative;">
            <input type="text" id="buscarTutor" class="form-control" placeholder="Escriba el nombre del tutor">
            <ul id="listaTutor" class="list-group" style="position:absolute; z-index:1000; width:100%;"></ul>
        </div>
    </div>
</div>


<input type="hidden" id="tutoresJson" value='<?php echo json_encode($tutores, 15, 512) ?>'>

<div class="container mt-4">
    <h2>Registro Combinado</h2>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('registroCombinado.registrar')); ?>" method="POST" id="formRegistroCombinado">
        <?php echo csrf_field(); ?>
        
        <h4>Datos del Tutor</h4>
        <div class="row">
            <div class="col-md-6">
                <label>Nombre</label>
                <input type="text" name="tutor_nombre" class="form-control" value="<?php echo e(old('tutor_nombre')); ?>" placeholder="Ej: Juan" required>
                <input type="hidden" name="tutor_id_existente" id="tutor_id_existente" value="<?php echo e(old('tutor_id_existente')); ?>">
            </div>
            <div class="col-md-6">
                <label>Apellido</label>
                <input type="text" name="tutor_apellido" class="form-control" value="<?php echo e(old('tutor_apellido')); ?>" placeholder="Ej: Pérez García" required>
            </div>
            <div class="col-md-4">
                <label>Género</label>
                <select name="tutor_genero" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <option value="M" <?php echo e(old('tutor_genero')=='M'?'selected':''); ?>>Masculino</option>
                    <option value="F" <?php echo e(old('tutor_genero')=='F'?'selected':''); ?>>Femenino</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="tutor_fecha_nacimiento" class="form-control" value="<?php echo e(old('tutor_fecha_nacimiento')); ?>" required>
            </div>
            <div class="col-md-4">
                <label>Celular</label>
                <input type="text" name="tutor_celular" class="form-control" value="<?php echo e(old('tutor_celular')); ?>" placeholder="Ej: 70123456" required>
            </div>
            <div class="col-md-12">
                <label>Dirección</label>
                <input type="text" name="tutor_direccion" class="form-control" value="<?php echo e(old('tutor_direccion')); ?>" placeholder="Ej: Av. Ballivián #123, Zona Sur" required>
            </div>
            <div class="col-md-4">
                    <label>Parentesco</label>
                    <select name="tutor_parentesco" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="Padre" <?php echo e(old('tutor_parentesco') == 'Padre' ? 'selected' : ''); ?>>Padre</option>
                        <option value="Madre" <?php echo e(old('tutor_parentesco') == 'Madre' ? 'selected' : ''); ?>>Madre</option>
                        <option value="Hermano/a" <?php echo e(old('tutor_parentesco') == 'Hermano/a' ? 'selected' : ''); ?>>Hermano/a</option>
                        <option value="Tío/a" <?php echo e(old('tutor_parentesco') == 'Tío/a' ? 'selected' : ''); ?>>Tío/a</option>
                        <option value="Abuelo/a" <?php echo e(old('tutor_parentesco') == 'Abuelo/a' ? 'selected' : ''); ?>>Abuelo/a</option>
                        <option value="Otro" <?php echo e(old('tutor_parentesco') == 'Otro' ? 'selected' : ''); ?>>Otro</option>
                    </select>
                </div>
         
            <div class="col-md-4">
                <label>NIT</label>
                <input type="text" name="tutor_nit" class="form-control" value="<?php echo e(old('tutor_nit')); ?>" placeholder="Ej: 1234567 (Opcional)">
            </div>
            <div class="col-md-6">
                <label>Nombre Factura</label>
                <input type="text" name="tutor_nombre_factura" class="form-control" value="<?php echo e(old('tutor_nombre_factura')); ?>" placeholder="Ej: Juan Pérez García (Opcional)">
            </div>

        <div class="row">
            <div class="col-md-6">
                <label>Correo</label>
                <input type="email" name="tutor_email" class="form-control" value="<?php echo e(old('tutor_email')); ?>" placeholder="Ej: juan.perez@gmail.com" required>
            </div>
             <div class="col-md-6">
                <label>Descuento Especial (%)</label>
                <input type="number" step="0.01" name="tutor_descuento" id="tutor_descuento" class="form-control" value="<?php echo e(old('tutor_descuento')); ?>" placeholder="0 - 100">
            </div>

        </div>

        </div>

        <hr>
        
        <h4>Datos del Estudiante</h4>
        
        <input type="hidden" name="estudiante_id_existente" id="estudiante_id_existente" value="<?php echo e(old('estudiante_id_existente')); ?>">
        
        <div class="row" id="form-estudiante">
            <div class="col-md-6">
                <label>Nombre</label>
                <input type="text" name="estudiante_nombre" class="form-control" value="<?php echo e(old('estudiante_nombre')); ?>" placeholder="Ej: María" required>
            </div>
            <div class="col-md-6">
                <label>Apellido</label>
                <input type="text" name="estudiante_apellido" class="form-control" value="<?php echo e(old('estudiante_apellido')); ?>" placeholder="Ej: Pérez García" required>
            </div>
            <div class="col-md-4">
                <label>Género</label>
                <select name="estudiante_genero" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <option value="M" <?php echo e(old('estudiante_genero')=='M'?'selected':''); ?>>Masculino</option>
                    <option value="F" <?php echo e(old('estudiante_genero')=='F'?'selected':''); ?>>Femenino</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="estudiante_fecha_nacimiento" class="form-control" value="<?php echo e(old('estudiante_fecha_nacimiento')); ?>" required>
            </div>
            <div class="col-md-4">
                <label>Número de referencia</label>
                <input type="text" name="estudiante_celular" class="form-control" value="<?php echo e(old('estudiante_celular')); ?>" placeholder="Ej: 70987654" required>
            </div>
            <div class="col-md-12">
                <label>Dirección</label>
                <input type="text" name="estudiante_direccion" class="form-control" value="<?php echo e(old('estudiante_direccion')); ?>" placeholder="Ej: Calle Los Pinos #456, Zona Norte" required>
            </div>
            <div class="col-md-6">
                <label>Código de Estudiante</label>
                <input type="text" name="codigo_estudiante" class="form-control" value="<?php echo e(old('codigo_estudiante')); ?>" placeholder="Ej: EST-2024-001" required>
            </div>
             <div class="col-md-6">
                <label>Programa</label>
                <select name="programa" id="programa" class="form-control" required>
                     <option value="" disabled selected>Seleccione un programa</option>
                     <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($prog->Id_programas); ?>" 
                            data-precio="<?php echo e($prog->Costo); ?>"
                            <?php echo e(old('programa') == $prog->Id_programas ? 'selected' : ''); ?>>
                            <?php echo e($prog->Nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-6">
                <label>Sucursal</label>
                <select name="sucursal" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php $__empty_1 = true; $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <option value="<?php echo e($s->Id_sucursales); ?>" <?php echo e(old('sucursal') == $s->Id_sucursales ? 'selected' : ''); ?>>
                            <?php echo e($s->Nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <option value="">No hay sucursales registradas</option>
                    <?php endif; ?>
                </select>
            </div>
            
          <div class="col-md-6">
        <label>Profesor</label>
        <select name="profesor" class="form-control" >
            <option value="">Seleccione...</option>
            <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($prof->Id_profesores); ?>"
                    <?php echo e(old('profesor') == $prof->Id_profesores ? 'selected' : ''); ?>>
                    <?php echo e($prof->persona->Nombre); ?> <?php echo e($prof->persona->Apellido); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    </div>

        <hr>
        
        <h4>Pago</h4>
        <div class="row">
            
            <input type="hidden" name="Nro_cuotas" value="1">
            <input type="hidden" name="Estado_plan" value="Completado">
            
            <input type="hidden" name="Monto_total" id="hidden_monto_total">
            
            <div class="col-md-6">
                <label>Descripción</label>
                <input type="text" name="Descripcion" class="form-control" placeholder="Ej: Pago de Matrícula" value="<?php echo e(old('Descripcion', 'Pago del Curso')); ?>">
            </div>
            <div class="col-md-6">
                <label>Comprobante</label>
                <div class="input-group">
                    <input type="text" name="Comprobante" id="input_comprobante" class="form-control" placeholder="Se llena con Nombre Factura" value="<?php echo e(old('Comprobante')); ?>">
                    <button class="btn btn-outline-secondary" type="button" id="btn-copy-factura">
                        <i class="fas fa-sync"></i>
                    </button>
                </div>
                <small class="text-muted">Se llena automáticamente con el Nombre de Factura</small>
            </div>
            <div class="col-md-6 mt-3">
                <label>Monto (Bs)</label>
                <input type="number" step="0.01" name="Monto_pago" id="input_monto_pago" class="form-control" required placeholder="0.00" value="<?php echo e(old('Monto_pago')); ?>">
            </div>
            <div class="col-md-6 mt-3">
                <label>Fecha</label>
                <input type="date" name="Fecha_pago" class="form-control" value="<?php echo e(old('Fecha_pago', \Carbon\Carbon::now()->format('Y-m-d'))); ?>" required>
            </div>
        </div>
        
        
        <div class="d-none">
            <h4>Plan de Pagos (Oculto)</h4>
             <div class="row">
                <div class="col-md-4">
                    <label>Matrícula (Bs)</label>
                    <input type="number" step="0.01" name="Monto_matricula" id="Monto_matricula" class="form-control" value="<?php echo e(old('Monto_matricula')); ?>">
                </div>
                <div class="col-md-4">
                    <label>Matrícula en cuántas partes</label>
                    <select name="Partes_matricula" class="form-control">
                        <option value="1" <?php echo e(old('Partes_matricula') == 1 ? 'selected' : ''); ?>>1</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Precio del Programa (Bs)</label>
                    <input type="number" step="0.01" id="Precio_programa" class="form-control" >
                </div>
                <div class="col-md-4">
                    <label>Nro de cuotas</label>
                    <input type="number" id="nro_cuotas_dummy" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Monto total</label>
                    <input type="number" step="0.01" id="Monto_total_dummy" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Descuento (%)</label>
                    <input type="number" step="0.01" name="tutor_descuento_hidden" id="tutor_descuento_hidden" class="form-control">
                </div>
                 <div class="col-md-4">
                    <label>Descuento aplicado (Bs)</label>
                    <input type="number" step="0.01" id="Descuento_aplicado" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label>Total con descuento aplicado (Bs)</label>
                    <input type="number" step="0.01" id="Total_con_descuento" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label>Fecha plan de pagos</label>
                    <input type="date" name="fecha_plan_pagos" class="form-control" value="<?php echo e(old('fecha_plan_pagos', \Carbon\Carbon::now()->format('Y-m-d'))); ?>">
                </div>
            </div>
             <div class="table-responsive mt-3">
                <table class="table table-bordered" id="tabla-cuotas-auto">
                    <thead><tr><th>Nro</th><th>Fecha</th><th>Monto</th><th>Estado</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>
            <div id="cuotas-auto-hidden-inputs"></div>
        </div>
        <hr>

        <button type="submit" class="btn btn-primary mt-4" id="btnRegistrar">
            <span id="btnText">Registrar</span>
            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </form>
</div>


<div class="modal fade" id="modalEstudiantesExistentes" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hijos Registrados del Tutor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Seleccione uno de los hijos registrados para inscribirlo en un nuevo programa.</p>
                <div class="table-responsive">
                    <table class="table table-hover" id="tablaHijos">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se llena dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalSeleccionarHijo" tabindex="-1" aria-labelledby="modalSeleccionarHijoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSeleccionarHijoLabel">
                    <i class="fas fa-users me-2"></i>Seleccionar Estudiante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3"><strong>Este tutor tiene los siguientes hijos registrados:</strong></p>
                <div id="lista-hijos-modal" class="row g-3">
                    
                </div>
                <hr>
                <div class="text-center">
                    <button type="button" class="btn btn-success btn-lg" id="btn-nuevo-hijo-modal" data-bs-dismiss="modal">
                        <i class="fas fa-plus me-2"></i>Registrar Nuevo Hijo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<style>
.hover-card {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-color: #0d6efd;
}
</style>

<script>
    // Pasar los datos de PHP a JavaScript e inicializar
    document.addEventListener('DOMContentLoaded', function() {
        // Prevención de doble envío
        const form = document.getElementById('formRegistroCombinado');
        const btn = document.getElementById('btnRegistrar');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        form.addEventListener('submit', function() {
            if (form.checkValidity()) {
                btn.disabled = true;
                btnText.textContent = 'Enviando...';
                btnSpinner.classList.remove('d-none');
            }
        });

        const programas = <?php echo json_encode($programas->map(fn($p) => [
            'Id_programas' => $p->Id_programas, 'Nombre' => $p->Nombre, 'Costo' => $p->Costo
        ])) ?>;
        
        // Inicializar la clase
        new RegistroCombinado(programas);
    });
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
        
        // --- NUEVO: Sincronización para vista simplificada de Pago ---
        const inputMontoPago = document.getElementById('input_monto_pago');
        const hiddenMontoTotal = document.getElementById('hidden_monto_total');
        const inputComprobante = document.getElementById('input_comprobante');
        const btnCopyFactura = document.getElementById('btn-copy-factura');
        const inputNombreFactura = document.querySelector('input[name="tutor_nombre_factura"]');

        if (inputMontoPago) {
            inputMontoPago.addEventListener('input', () => {
                // Sincronizar Monto Total oculto con Monto Pago
                if (hiddenMontoTotal) {
                    hiddenMontoTotal.value = inputMontoPago.value;
                }
            });
        }

        // Función para copiar nombre de factura
        const copiarNombreFactura = () => {
            if (inputNombreFactura && inputComprobante) {
                inputComprobante.value = inputNombreFactura.value;
            }
        };

        // Escuchar cambios en nombre factura
        if (inputNombreFactura) {
             inputNombreFactura.addEventListener('input', copiarNombreFactura);
             inputNombreFactura.addEventListener('change', copiarNombreFactura);
        }

        if (btnCopyFactura) {
            btnCopyFactura.addEventListener('click', copiarNombreFactura);
        }

        // --- MANEJO DE PLAN DE PAGOS (AUNQUE ESTÉ OCULTO) ---
        // Mantener listeners antiguos para evitar errores si existen elementos
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
document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.getElementById('buscarTutor');
    const listaTutor = document.getElementById('listaTutor');
    const tutores = JSON.parse(document.getElementById('tutoresJson').value);

    inputBuscar.addEventListener('input', function() {
        const valor = this.value.toLowerCase();
        listaTutor.innerHTML = '';

        if (valor.length === 0) return; // si no hay texto, no mostrar nada

        const coincidencias = tutores.filter(t => 
            t.Nombre.toLowerCase().includes(valor) || t.Apellido.toLowerCase().includes(valor)
        );

        coincidencias.forEach(t => {
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = t.Nombre + ' ' + t.Apellido + ' - ' + t.Correo;
            li.style.cursor = 'pointer';

            li.addEventListener('click', () => {
                // Rellenar los campos del formulario
                document.querySelector('input[name="tutor_nombre"]').value = t.Nombre || '';
                document.querySelector('input[name="tutor_apellido"]').value = t.Apellido || '';
                document.querySelector('select[name="tutor_genero"]').value = t.Genero || '';
                document.querySelector('input[name="tutor_fecha_nacimiento"]').value = t.Fecha_nacimiento ? t.Fecha_nacimiento.substring(0, 10) : '';
                document.querySelector('input[name="tutor_celular"]').value = t.Celular || '';
                document.querySelector('input[name="tutor_direccion"]').value = t.Direccion_domicilio || '';
                document.querySelector('input[name="tutor_email"]').value = t.Correo || '';
                document.querySelector('select[name="tutor_parentesco"]').value = t.Parentesco || '';
                document.querySelector('input[name="tutor_nit"]').value = t.Nit || '';
                document.querySelector('input[name="tutor_nombre_factura"]').value = t.Nombre_factura || '';
                document.querySelector('input[name="tutor_descuento"]').value = t.Descuento || '';

                // Guardar ID del tutor existente
                document.getElementById('tutor_id_existente').value = t.Id_tutores;

                // Mostrar hijos existentes si los tiene
                mostrarHijosExistentes(t);

                // Ocultar la lista
                listaTutor.innerHTML = '';
                inputBuscar.value = t.Nombre + ' ' + t.Apellido;
            });


            listaTutor.appendChild(li);
        });
    });

    // Función para mostrar hijos existentes en modal
    function mostrarHijosExistentes(tutor) {
        const listaHijosModal = document.getElementById('lista-hijos-modal');
        const formEstudiante = document.getElementById('form-estudiante');
        
        if (tutor.estudiantes && tutor.estudiantes.length > 0) {
            // Limpiar modal
            listaHijosModal.innerHTML = '';
            
            // Crear tarjetas para cada hijo
            tutor.estudiantes.forEach(hijo => {
                const card = `
                    <div class="col-md-6">
                        <div class="card hover-card" style="cursor: pointer;" onclick="seleccionarHijoExistente(${JSON.stringify(hijo).replace(/"/g, '&quot;')})">
                            <div class="card-body">
                                <h6 class="card-title mb-2">
                                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                                    ${hijo.Nombre} ${hijo.Apellido}
                                </h6>
                                <p class="card-text mb-1">
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i>Código: ${hijo.Cod_estudiante}
                                    </small>
                                </p>
                                <p class="card-text mb-0">
                                    <small class="text-muted">
                                        <i class="fas fa-birthday-cake me-1"></i>${hijo.Fecha_nacimiento}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                listaHijosModal.insertAdjacentHTML('beforeend', card);
            });
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('modalSeleccionarHijo'));
            modal.show();
        } else {
            // No tiene hijos, mostrar formulario directamente
            formEstudiante.style.display = '';
            limpiarFormularioEstudiante();
        }
    }
    
    // Función para seleccionar hijo existente (ahora global para onclick)
    window.seleccionarHijoExistente = function(hijo) {
        const formEstudiante = document.getElementById('form-estudiante');
        
        // Guardar ID del estudiante existente
        document.getElementById('estudiante_id_existente').value = hijo.Id_estudiantes;
        
        // Auto-completar datos
        document.querySelector('input[name="estudiante_nombre"]').value = hijo.Nombre || '';
        document.querySelector('input[name="estudiante_apellido"]').value = hijo.Apellido || '';
        document.querySelector('select[name="estudiante_genero"]').value = hijo.Genero || '';
        document.querySelector('input[name="estudiante_fecha_nacimiento"]').value = hijo.Fecha_nacimiento ? hijo.Fecha_nacimiento.substring(0, 10) : '';
        document.querySelector('input[name="estudiante_celular"]').value = hijo.Celular || '';
        document.querySelector('input[name="estudiante_direccion"]').value = hijo.Direccion_domicilio || '';
        document.querySelector('input[name="codigo_estudiante"]').value = hijo.Cod_estudiante || '';
        
        // Deshabilitar campos para que no se puedan editar (género con estilo visual)
        document.querySelector('input[name="estudiante_nombre"]').readOnly = true;
        document.querySelector('input[name="estudiante_apellido"]').readOnly = true;
        const generoSelect = document.querySelector('select[name="estudiante_genero"]');
        generoSelect.style.pointerEvents = 'none';
        generoSelect.style.backgroundColor = '#e9ecef';
        document.querySelector('input[name="estudiante_fecha_nacimiento"]').readOnly = true;
        document.querySelector('input[name="estudiante_celular"]').readOnly = true;
        document.querySelector('input[name="estudiante_direccion"]').readOnly = true;
        document.querySelector('input[name="codigo_estudiante"]').readOnly = true;
        
        // Mostrar formulario
        formEstudiante.style.display = '';
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalSeleccionarHijo'));
        if (modal) {
            modal.hide();
        }
    }
    
    // Función para limpiar formulario de estudiante
    function limpiarFormularioEstudiante() {
        document.getElementById('estudiante_id_existente').value = '';
        document.querySelector('input[name="estudiante_nombre"]').value = '';
        document.querySelector('input[name="estudiante_apellido"]').value = '';
        document.querySelector('select[name="estudiante_genero"]').value = '';
        document.querySelector('input[name="estudiante_fecha_nacimiento"]').value = '';
        document.querySelector('input[name="estudiante_celular"]').value = '';
        document.querySelector('input[name="estudiante_direccion"]').value = '';
        document.querySelector('input[name="codigo_estudiante"]').value = '';
        
        // Habilitar campos
        document.querySelector('input[name="estudiante_nombre"]').readOnly = false;
        document.querySelector('input[name="estudiante_apellido"]').readOnly = false;
        const generoSelect = document.querySelector('select[name="estudiante_genero"]');
        generoSelect.style.pointerEvents = '';
        generoSelect.style.backgroundColor = '';
        document.querySelector('input[name="estudiante_fecha_nacimiento"]').readOnly = false;
        document.querySelector('input[name="estudiante_celular"]').readOnly = false;
        document.querySelector('input[name="estudiante_direccion"]').readOnly = false;
        document.querySelector('input[name="codigo_estudiante"]').readOnly = false;
    }
    
    // Botón para registrar nuevo hijo (desde modal)
    document.getElementById('btn-nuevo-hijo-modal').addEventListener('click', () => {
        const formEstudiante = document.getElementById('form-estudiante');
        limpiarFormularioEstudiante();
        formEstudiante.style.display = '';
    });

    // Ocultar lista si se hace click fuera
    document.addEventListener('click', function(e) {
        if (!listaTutor.contains(e.target) && e.target !== inputBuscar) {
            listaTutor.innerHTML = '';
        }
    });
});


// Exportar para uso global
window.RegistroCombinado = RegistroCombinado;

</script>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/administrador/tutorEstudianteAdministrador.blade.php ENDPATH**/ ?>