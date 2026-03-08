<?php $__env->startSection('title', 'Inscripción de Estudiante'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-person-fill-add me-2"></i>Inscripción de Estudiante</h1>
                <p class="mb-0 text-muted">Inscribir a estudiante existente a un nuevo programa o taller</p>
            </div>
        </div>

        
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success shadow-sm border-0 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">
                <form action="<?php echo e(route('inscripcionEstudiante.registrar')); ?>" method="POST" id="formInscripcion">
                    <?php echo csrf_field(); ?>

                    <!-- SECCIÓN 1: BUSCADOR -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-search me-2"></i>Buscar Estudiante</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex gap-3 mb-4">
                                <button type="button" id="btn_modo_buscar" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i> Buscar Estudiante
                                </button>
                                <button type="button" id="btn_modo_nuevo" class="btn btn-outline-success">
                                    <i class="bi bi-person-plus me-1"></i> Alumno Nuevo (Express)
                                </button>
                            </div>

                            
                            <div id="wrapper_buscar">
                                <div class="row align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nombre del Estudiante</label>
                                        <div class="input-group">
                                            <input type="text" id="nombre_buscar" class="form-control"
                                                placeholder="Ingrese nombre del estudiante">
                                            <button type="button" id="btn_buscar_nombre" class="btn btn-primary">
                                                <i class="bi bi-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div id="wrapper_nuevo" style="display: none;">
                                <input type="hidden" name="es_nuevo_express" id="es_nuevo_express" value="0">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Nombre(s) *</label>
                                        <input type="text" name="nombre_express" id="nombre_express" class="form-control"
                                            placeholder="Nombre del niño">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Apellido(s) *</label>
                                        <input type="text" name="apellido_express" id="apellido_express"
                                            class="form-control" placeholder="Apellido del niño">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Teléfono / Celular *</label>
                                        <input type="text" name="celular_express" id="celular_express" class="form-control"
                                            placeholder="Ej: 77889900">
                                    </div>
                                </div>
                            </div>

                            
                            <div id="resultados_busqueda" class="mt-4" style="display: none;">
                                <h6 class="fw-bold text-secondary mb-3">Resultados de búsqueda:</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Programa Actual</th>
                                                <th>Estado</th>
                                                <th class="text-end">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabla_resultados">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: DATOS DEL ESTUDIANTE -->
                    <div id="datos_estudiante" class="card shadow-sm mb-4 border-0" style="display: none;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-person-vcard me-2"></i>Datos del
                                Estudiante Seleccionado</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nombre Completo</label>
                                    <input type="text" id="estudiante_nombre_completo" class="form-control bg-light"
                                        readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Código</label>
                                    <input type="text" id="estudiante_codigo" class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Programa Actual</label>
                                    <input type="text" id="estudiante_programa_actual" class="form-control bg-light"
                                        readonly>
                                </div>
                            </div>
                            <input type="hidden" id="Id_estudiantes" name="Id_estudiantes">
                        </div>
                    </div>

                    <!-- SECCIÓN 3: SELECCIÓN DE PROGRAMA -->
                    <div id="seleccion_programa" class="card shadow-sm mb-4 border-0" style="display: none;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-journal-text me-2"></i>Seleccionar Nuevo
                                Programa o Taller</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6" id="wrapper_tipo_seleccion">
                                    <label class="form-label fw-semibold">Tipo</label>
                                    <select id="tipo_seleccion" name="tipo_seleccion" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="programa">Nuevo Programa Regular</option>
                                        <option value="taller">Taller de Temporada</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Programa/Taller</label>
                                    <select id="programa_taller" name="programa_taller" class="form-select" required>
                                        <option value="">Primero seleccione el tipo...</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-none">
                                    <label class="form-label fw-semibold">Costo Original</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Bs.</span>
                                        <input type="number" step="0.01" id="costo_programa" class="form-control bg-light"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Sucursal</label>
                                    <select name="sucursal" id="sucursal" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($s->Id_Sucursales); ?>"><?php echo e($s->Nombre); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Profesor (Opcional)</label>
                                    <select name="profesor" id="profesor" class="form-select">
                                        <option value="">Seleccione...</option>
                                        <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($p->Id_profesores); ?>">
                                                <?php echo e($p->persona->Nombre); ?> <?php echo e($p->persona->Apellido ?? ''); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 4A: PLAN DE PAGOS (PROGRAMA) -->
                    <div id="plan_pagos_programa" class="card shadow-sm mb-4 border-0" style="display: none;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-calculator me-2"></i>Plan de Pagos -
                                Programa</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Matrícula (Bs)</label>
                                    <input type="number" step="0.01" name="Monto_matricula" id="Monto_matricula"
                                        class="form-control" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nro de cuotas</label>
                                    <input type="number" name="Nro_cuotas" id="nro_cuotas" class="form-control" min="1"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Monto total</label>
                                    <input type="number" step="0.01" name="Monto_total" id="Monto_total"
                                        class="form-control bg-light" required readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Descuento (%) - Estudiante Activo</label>
                                    <input type="number" step="0.01" id="descuento_estudiante" class="form-control"
                                        value="15" min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Total con descuento (Bs)</label>
                                    <input type="number" step="0.01" id="Total_con_descuento" name="Total_con_descuento"
                                        class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fecha inicio pagos</label>
                                    <input type="date" name="fecha_plan_pagos" id="fecha_plan_pagos" class="form-control"
                                        value="<?php echo e(now()->format('Y-m-d')); ?>">
                                </div>
                            </div>

                            <h6 class="fw-bold text-secondary mb-3">Cuotas Generadas Automáticamente</h6>
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-striped mb-0" id="tabla-cuotas-programa">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nro de cuota</th>
                                            <th>Fecha vencimiento</th>
                                            <th>Monto cuota</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div id="cuotas-programa-hidden-inputs"></div>
                        </div>
                    </div>

                    <!-- SECCIÓN 4B: PAGO DIRECTO (TALLER) - OCULTO PARA FLUJO EXPRESS PARECIDO A REGISTRO COMBINADO -->
                    <div id="pago_taller" class="card shadow-sm mb-4 border-0 d-none">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="bi bi-credit-card me-2"></i>Información de Pago
                                (Opcional)</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Descripción del Pago</label>
                                    <input type="text" name="descripcion_taller" id="descripcion_taller"
                                        class="form-control" value="Inscripción inicial">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Monto Original (Bs)</label>
                                    <input type="number" step="0.01" name="monto_taller" id="monto_taller"
                                        class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Descuento (%)</label>
                                    <input type="number" step="0.01" id="descuento_taller" class="form-control" value="0"
                                        min="0" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Monto Final (Bs)</label>
                                    <input type="number" step="0.01" id="monto_taller_descuento"
                                        name="monto_taller_descuento" class="form-control fw-bold text-primary" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fecha de Pago</label>
                                    <input type="date" name="fecha_pago_taller" class="form-control"
                                        value="<?php echo e(now()->format('Y-m-d')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Método de Pago</label>
                                    <select name="metodo_pago_taller" class="form-select">
                                        <option value="No especificado" selected>No especificado</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="transferencia">Transferencia</option>
                                        <option value="tarjeta">Tarjeta</option>
                                        <option value="qr">QR</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Estado del Pago</label>
                                    <select name="estado_pago_taller" class="form-select">
                                        <option value="pendiente" selected>Pendiente</option>
                                        <option value="pagado">Pagado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pb-5">
                        <button type="submit" id="btn_inscribir" class="btn btn-primary btn-lg px-5 shadow"
                            style="display: none;">
                            <i class="bi bi-check-circle-fill me-2"></i>Inscribir Estudiante
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    window.programas = <?php echo json_encode($programas ?? [], 15, 512) ?>;
    window.talleres = <?php echo json_encode($talleres ?? [], 15, 512) ?>;
    $(document).ready(function () {
        // DATOS DE PROGRAMAS Y TALLERES DESDE EL SERVIDOR
        const programas = window.programas || [];
        const talleres = window.talleres || [];

        // DEBUG: Mostrar en consola lo que llega del servidor
        console.log('=== DEBUG DATOS ===');
        console.log('Programas cargados:', programas);
        console.log('Talleres cargados:', talleres);
        console.log('Total programas:', programas.length);
        console.log('Total talleres:', talleres.length);

        // CONFIGURACIÓN CSRF PARA AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
            }
        });

        // ===== CAMBIO DE MODO (BUSCAR/NUEVO) =====
        $('#btn_modo_buscar').click(function () {
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
            $('#btn_modo_nuevo').removeClass('btn-success').addClass('btn-outline-success');
            $('#wrapper_buscar').show();
            $('#wrapper_nuevo').hide();
            $('#es_nuevo_express').val('0');
            $('#datos_estudiante').hide();
            $('#seleccion_programa').hide();
            resetearFormulario();
        });

        $('#btn_modo_nuevo').click(function () {
            $(this).removeClass('btn-outline-success').addClass('btn-success');
            $('#btn_modo_buscar').removeClass('btn-primary').addClass('btn-outline-primary');
            $('#wrapper_nuevo').show();
            $('#wrapper_buscar').hide();
            $('#resultados_busqueda').hide();
            $('#es_nuevo_express').val('1');

            // Mostrar secciones de programa directamente
            $('#datos_estudiante').hide();
            $('#seleccion_programa').show();

            // Forzar solo Talleres en modo Express
            $('#wrapper_tipo_seleccion').hide();
            $('#tipo_seleccion').val('taller').trigger('change');

            // Limpiar campos express
            $('#nombre_express, #apellido_express, #celular_express').val('');
        });

        // Al volver a modo buscar, rehabilitar selector de tipo
        $('#btn_modo_buscar').click(function () {
            $('#wrapper_tipo_seleccion').show();
        });

        // ===== BÚSQUEDA POR CÓDIGO =====
        $('#btn_buscar').click(function () {
            const codigo = $('#codigo_estudiante_buscar').val().trim();
            if (!codigo) {
                alert('Ingrese un código de estudiante');
                return;
            }

            $.ajax({
                url: '<?php echo e(route("inscripcionEstudiante.buscarCodigo")); ?>',
                method: 'POST',
                data: { codigo: codigo },
                success: function (response) {
                    console.log('Respuesta búsqueda por código:', response);
                    if (response.success) {
                        mostrarEstudianteSeleccionado(response.estudiante);
                        $('#resultados_busqueda').hide();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error búsqueda por código:', error);
                    alert('Error al buscar estudiante: ' + error);
                }
            });
        });

        // ===== BÚSQUEDA POR NOMBRE =====
        $('#btn_buscar_nombre').click(function () {
            const nombre = $('#nombre_buscar').val().trim();
            if (!nombre) {
                alert('Ingrese un nombre');
                return;
            }

            $.ajax({
                url: '<?php echo e(route("inscripcionEstudiante.buscarNombre")); ?>',
                method: 'POST',
                data: { nombre: nombre },
                success: function (response) {
                    console.log('Respuesta búsqueda por nombre:', response);
                    if (response.success) {
                        mostrarResultadosBusqueda(response.estudiantes);
                    } else {
                        alert(response.message);
                        $('#resultados_busqueda').hide();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error búsqueda por nombre:', error);
                    alert('Error al buscar estudiantes: ' + error);
                }
            });
        });

        // ===== MOSTRAR RESULTADOS DE BÚSQUEDA MÚLTIPLES =====
        function mostrarResultadosBusqueda(estudiantes) {
            let html = '';
            estudiantes.forEach(function (est) {
                html += `
                <tr>
                    <td>${est.codigo}</td>
                    <td>${est.nombre_completo}</td>
                    <td>${est.programa_actual}</td>
                    <td><span class="badge bg-secondary">${est.estado}</span></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary seleccionar-estudiante" 
                                data-id="${est.Id_estudiantes}"
                                data-codigo="${est.codigo}"
                                data-nombre="${est.nombre_completo}"
                                data-programa="${est.programa_actual}">
                            <i class="bi bi-check2"></i> Seleccionar
                        </button>
                    </td>
                </tr>
            `;
            });

            $('#tabla_resultados').html(html);
            $('#resultados_busqueda').show();
            $('#datos_estudiante').hide();
            $('#seleccion_programa').hide();
        }

        // ===== SELECCIONAR ESTUDIANTE DE LA TABLA =====
        $(document).on('click', '.seleccionar-estudiante', function () {
            const estudiante = {
                Id_estudiantes: $(this).data('id'),
                codigo: $(this).data('codigo'),
                nombre_completo: $(this).data('nombre'),
                programa_actual: $(this).data('programa')
            };

            mostrarEstudianteSeleccionado(estudiante);
            $('#resultados_busqueda').hide();
        });

        // ===== MOSTRAR DATOS DEL ESTUDIANTE SELECCIONADO =====
        function mostrarEstudianteSeleccionado(estudiante) {
            $('#Id_estudiantes').val(estudiante.Id_estudiantes);
            $('#estudiante_codigo').val(estudiante.codigo);
            $('#estudiante_nombre_completo').val(estudiante.nombre_completo);
            $('#estudiante_programa_actual').val(estudiante.programa_actual);

            $('#datos_estudiante').show();
            $('#seleccion_programa').show();
            resetearFormulario();
        }

        // ===== CAMBIO DE TIPO (PROGRAMA/TALLER) - CORREGIDO =====
        $('#tipo_seleccion').change(function () {
            const tipo = $(this).val();
            console.log('Tipo seleccionado:', tipo);

            // Limpiar select de programa/taller
            $('#programa_taller').html('<option value="">Seleccione...</option>');
            $('#costo_programa').val('');

            // IMPORTANTE: Limpiar atributos required según el tipo
            if (tipo === 'programa') {
                // Para programas: requeridos los campos del plan de pagos
                $('#nro_cuotas').attr('required', true);
                $('#Monto_total').attr('required', true);
                $('#Total_con_descuento').attr('required', true);
                $('#fecha_plan_pagos').attr('required', true);

                // Para programas: NO requeridos los campos de taller
                $('#monto_taller_descuento').removeAttr('required');
                $('#fecha_pago_taller').removeAttr('required');
                $('#metodo_pago_taller').removeAttr('required');
                $('#estado_pago_taller').removeAttr('required');

                console.log('Cargando programas...');
                if (programas && programas.length > 0) {
                    let options = '<option value="">Seleccione un programa...</option>';
                    programas.forEach(function (programa) {
                        options += `<option value="${programa.Id_programas}" data-costo="${programa.Costo}">${programa.Nombre}</option>`;
                    });
                    $('#programa_taller').html(options);
                } else {
                    $('#programa_taller').html('<option value="">No hay programas disponibles</option>');
                }

            } else if (tipo === 'taller') {
                // Para talleres: requeridos los campos de pago directo
                $('#monto_taller_descuento').attr('required', true);
                $('#fecha_pago_taller').attr('required', true);
                $('#metodo_pago_taller').attr('required', true);
                $('#estado_pago_taller').attr('required', true);

                // Para talleres: NO requeridos los campos de programa
                $('#nro_cuotas').removeAttr('required');
                $('#Monto_total').removeAttr('required');
                $('#Total_con_descuento').removeAttr('required');
                $('#fecha_plan_pagos').removeAttr('required');

                console.log('Cargando talleres...');
                if (talleres && talleres.length > 0) {
                    let options = '<option value="">Seleccione un taller...</option>';
                    talleres.forEach(function (taller) {
                        options += `<option value="${taller.Id_programas}" data-costo="${taller.Costo}">${taller.Nombre}</option>`;
                    });
                    $('#programa_taller').html(options);
                } else {
                    $('#programa_taller').html('<option value="">No hay talleres disponibles</option>');
                }
            }

            ocultarSeccionesFormulario();
        });

        // ===== SELECCIÓN DE PROGRAMA/TALLER ESPECÍFICO =====
        $('#programa_taller').change(function () {
            const costo = $(this).find(':selected').data('costo') || 0;
            const tipo = $('#tipo_seleccion').val();

            console.log('Programa/Taller seleccionado, costo:', costo, 'tipo:', tipo);

            $('#costo_programa').val(costo);

            if (tipo === 'programa') {
                $('#Monto_total').val(costo);
                calcularDescuentoPrograma();
                $('#plan_pagos_programa').show();
                $('#pago_taller').addClass('d-none');
            } else if (tipo === 'taller') {
                $('#monto_taller').val(costo);
                calcularDescuentoTaller();

                // Pre-llenar campos de pago aunque estén ocultos (como Registro Combinado)
                $('#monto_taller_descuento').val(costo);
                $('#pago_taller').addClass('d-none');
                $('#plan_pagos_programa').hide();
            }

            if (costo > 0) {
                $('#btn_inscribir').show();
            }
        });

        // ===== EVENTOS PARA RECALCULAR DESCUENTOS CUANDO CAMBIEN =====
        $('#descuento_estudiante').on('input change', function () {
            const tipo = $('#tipo_seleccion').val();
            if (tipo === 'programa') {
                calcularDescuentoPrograma();
            }
        });

        $('#descuento_taller').on('input change', function () {
            const tipo = $('#tipo_seleccion').val();
            if (tipo === 'taller') {
                calcularDescuentoTaller();
            }
        });

        // ===== CALCULAR CUOTAS CUANDO CAMBIE EL NÚMERO O LA FECHA =====
        $('#nro_cuotas, #fecha_plan_pagos').on('input change', function () {
            generarCuotas();
        });

        // ===== CALCULAR DESCUENTO PARA PROGRAMA =====
        function calcularDescuentoPrograma() {
            const montoTotal = parseFloat($('#Monto_total').val()) || 0;
            const descuento = parseFloat($('#descuento_estudiante').val()) || 0;

            // Validar que el descuento esté en rango válido
            if (descuento < 0) {
                $('#descuento_estudiante').val(0);
                return;
            }
            if (descuento > 100) {
                $('#descuento_estudiante').val(100);
                return;
            }

            const totalConDescuento = montoTotal - (montoTotal * descuento / 100);

            $('#Total_con_descuento').val(totalConDescuento.toFixed(2));
            generarCuotas();
        }

        // ===== CALCULAR DESCUENTO PARA TALLER =====
        function calcularDescuentoTaller() {
            const montoTaller = parseFloat($('#monto_taller').val()) || 0;
            const descuento = parseFloat($('#descuento_taller').val()) || 0;

            // Validar que el descuento esté en rango válido
            if (descuento < 0) {
                $('#descuento_taller').val(0);
                return;
            }
            if (descuento > 100) {
                $('#descuento_taller').val(100);
                return;
            }

            const montoConDescuento = montoTaller - (montoTaller * descuento / 100);

            $('#monto_taller_descuento').val(montoConDescuento.toFixed(2));

            // Actualizar descripción automáticamente
            const nombrePrograma = $('#programa_taller option:selected').text();
            if (nombrePrograma && nombrePrograma !== 'Seleccione...') {
                $('#descripcion_taller').val(`Pago ${nombrePrograma.split(' - ')[0]}`);
            }
        }

        // ===== GENERAR CUOTAS AUTOMÁTICAMENTE =====
        function generarCuotas() {
            const nroCuotas = parseInt($('#nro_cuotas').val()) || 0;
            const totalConDescuento = parseFloat($('#Total_con_descuento').val()) || 0;
            const fechaPlan = $('#fecha_plan_pagos').val();

            if (nroCuotas > 0 && totalConDescuento > 0 && fechaPlan) {
                const montoCuota = (totalConDescuento / nroCuotas).toFixed(2);
                let html = '';
                let htmlInputs = '';

                for (let i = 1; i <= nroCuotas; i++) {
                    const fechaVencimiento = new Date(fechaPlan);
                    fechaVencimiento.setMonth(fechaVencimiento.getMonth() + i);
                    const fechaFormateada = fechaVencimiento.toISOString().split('T')[0];

                    html += `
                    <tr>
                        <td>${i}</td>
                        <td>${fechaFormateada}</td>
                        <td>${montoCuota}</td>
                        <td>Pendiente</td>
                    </tr>
                `;

                    htmlInputs += `
                    <input type="hidden" name="cuotas_programa[${i - 1}][Nro_de_cuota]" value="${i}">
                    <input type="hidden" name="cuotas_programa[${i - 1}][Fecha_vencimiento]" value="${fechaFormateada}">
                    <input type="hidden" name="cuotas_programa[${i - 1}][Monto_cuota]" value="${montoCuota}">
                `;
                }

                $('#tabla-cuotas-programa tbody').html(html);
                $('#cuotas-programa-hidden-inputs').html(htmlInputs);
            }
        }

        // ===== FUNCIONES AUXILIARES =====
        function resetearFormulario() {
            $('#tipo_seleccion').val('');
            $('#programa_taller').html('<option value="">Primero seleccione el tipo...</option>');
            $('#costo_programa').val('');
            ocultarSeccionesFormulario();
            $('#btn_inscribir').hide();
        }

        function ocultarSeccionesFormulario() {
            $('#plan_pagos_programa').hide();
            $('#pago_taller').hide();
            $('#btn_inscribir').hide();

            // Limpiar valores cuando se ocultan las secciones
            $('#plan_pagos_programa input').val('');
            $('#pago_taller input').val('');
            $('#tabla-cuotas-programa tbody').html('');
            $('#cuotas-programa-hidden-inputs').html('');
        }

        // ===== VALIDACIÓN DEL FORMULARIO ANTES DEL ENVÍO =====
        $('#formInscripcion').submit(function (e) {
            const tipo = $('#tipo_seleccion').val();
            const esNuevo = $('#es_nuevo_express').val() === '1';

            if (esNuevo) {
                const nombre = $('#nombre_express').val().trim();
                const apellido = $('#apellido_express').val().trim();
                const celular = $('#celular_express').val().trim();

                if (!nombre || !apellido || !celular) {
                    e.preventDefault();
                    alert('Debe completar todos los datos del alumno (Nombre, Apellido y Celular)');
                    return false;
                }
            } else {
                if (!$('#Id_estudiantes').val()) {
                    e.preventDefault();
                    alert('Debe seleccionar un estudiante primero');
                    return false;
                }
            }

            // Validación específica según tipo
            if (tipo === 'programa') {
                const nroCuotas = $('#nro_cuotas').val();
                const totalConDescuento = $('#Total_con_descuento').val();

                if (!nroCuotas || nroCuotas < 1) {
                    e.preventDefault();
                    alert('El número de cuotas debe ser mayor a 0');
                    $('#nro_cuotas').focus();
                    return false;
                }

                if (!totalConDescuento || totalConDescuento <= 0) {
                    e.preventDefault();
                    alert('El total con descuento debe ser mayor a 0');
                    return false;
                }

            } else if (tipo === 'taller') {
                // Para talleres simplificados (parecido a Registro Combinado), 
                // no obligamos a llenar datos de pago, se usan los defaults del controlador.
            }

            return true;
        });

        // ===== EVENTO ENTER EN CAMPOS DE BÚSQUEDA =====
        $('#codigo_estudiante_buscar').keypress(function (e) {
            if (e.which === 13) { // Enter
                e.preventDefault();
                $('#btn_buscar').click();
            }
        });

        $('#nombre_buscar').keypress(function (e) {
            if (e.which === 13) { // Enter
                e.preventDefault();
                $('#btn_buscar_nombre').click();
            }
        });
    });

</script>
<?php echo $__env->make('administrador/baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/inscripcionEstudiante.blade.php ENDPATH**/ ?>