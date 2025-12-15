<?php $__env->startSection('title', 'Entrada de Componentes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">Entrada de Componentes</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Información -->
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> 
        <strong>Motores en Reparación:</strong> <?php echo e($asignacionesActivas->count()); ?>

    </div>

    <!-- Motores Pendientes de Entrada -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-sign-in-alt"></i> Motores Pendientes de Devolución</h5>
        </div>
        <div class="card-body">
            <?php $__empty_1 = true; $__currentLoopData = $asignacionesActivas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asignacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card mb-3 border-start border-warning border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Información del Motor -->
                        <div class="col-md-3">
                            <h5 class="mb-1">
                                <i class="fas fa-cog"></i> 
                                <strong><?php echo e($asignacion->motor->Id_motor); ?></strong>
                            </h5>
                            <p class="text-muted mb-1">
                                <small>Sucursal: <?php echo e($asignacion->motor->sucursal->Nombre ?? 'N/A'); ?></small>
                            </p>
                            <span class="badge bg-warning text-dark">
                                <?php echo e($asignacion->Estado_motor_salida); ?>

                            </span>
                        </div>

                        <!-- Información del Técnico -->
                        <div class="col-md-3">
                            <p class="mb-1">
                                <i class="fas fa-user-cog"></i> 
                                <strong>Técnico:</strong><br>
                                <?php echo e($asignacion->profesor->persona->Nombre); ?> 
                                <?php echo e($asignacion->profesor->persona->Apellido); ?>

                            </p>
                            <p class="text-muted mb-0">
                                <small>
                                    <i class="fas fa-calendar"></i> 
                                    Salida: <?php echo e($asignacion->Fecha_salida->format('d/m/Y H:i')); ?>

                                </small>
                            </p>
                        </div>

                        <!-- Motivo y Último Reporte -->
                        <div class="col-md-4">
                            <p class="mb-1">
                                <strong>Motivo:</strong> <?php echo e(Str::limit($asignacion->Motivo_salida, 60)); ?>

                            </p>
                            <?php if($asignacion->reportesProgreso->count() > 0): ?>
                                <?php $ultimoReporte = $asignacion->reportesProgreso->first(); ?>
                                <div class="alert alert-light mb-0 p-2">
                                    <small>
                                        <strong>Último Reporte:</strong><br>
                                        <span class="badge bg-info"><?php echo e($ultimoReporte->Estado_actual); ?></span>
                                        <?php echo e(Str::limit($ultimoReporte->Descripcion_trabajo, 50)); ?>

                                        <br>
                                        <em class="text-muted">
                                            <?php echo e($ultimoReporte->Fecha_reporte->format('d/m/Y H:i')); ?>

                                        </em>
                                    </small>
                                </div>
                            <?php else: ?>
                                <small class="text-muted">Sin reportes de progreso</small>
                            <?php endif; ?>
                        </div>

                        <!-- Acciones -->
                        <div class="col-md-2 text-end">
                            <?php if($asignacion->reportesProgreso->count() > 0): ?>
                                <button type="button" class="btn btn-sm btn-info mb-2 w-100" 
                                        onclick="verReportes(<?php echo e($asignacion->Id_asignacion); ?>)">
                                    <i class="fas fa-eye"></i> Ver Reportes
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-success w-100" 
                                    onclick="registrarEntrada(<?php echo e($asignacion->Id_asignacion); ?>)">
                                <i class="fas fa-check-circle"></i> Registrar Entrada
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-check-circle fa-3x mb-3"></i>
                <h5>No hay motores pendientes de entrada</h5>
                <p>Todos los motores están en inventario</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Registrar Entrada -->
<div class="modal fade" id="modalRegistrarEntrada" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.componentes.registrar-entrada')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="Id_asignacion" id="entrada_asignacion_id">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-in-alt"></i> Registrar Entrada de Motor
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Información del Motor -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Motor:</strong> <span id="entrada_motor_id"></span><br>
                                <strong>Técnico:</strong> <span id="entrada_tecnico"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Estado Salida:</strong> <span id="entrada_estado_salida"></span><br>
                                <strong>Motivo:</strong> <span id="entrada_motivo"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Entrada -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado de Entrada *</label>
                                <select class="form-select" name="Estado_entrada" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Disponible">Disponible (Reparado y Listo)</option>
                                    <option value="Funcionando">Funcionando (Sin problemas)</option>
                                    <option value="Descompuesto">Descompuesto (Irreparable)</option>
                                </select>
                                <small class="text-muted">Estado en el que regresa el motor</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Entrada</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo e(now()->format('d/m/Y H:i')); ?>" disabled>
                                <small class="text-muted">Se registrará automáticamente</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trabajo Realizado *</label>
                        <textarea class="form-control" name="Trabajo_realizado" rows="4" required
                                  placeholder="Describa detalladamente el trabajo realizado en el motor...&#10;&#10;Ejemplo:&#10;- Se reemplazó el circuito principal&#10;- Se limpiaron los contactos&#10;- Se realizaron pruebas de funcionamiento"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea class="form-control" name="Observaciones" rows="2"
                                  placeholder="Observaciones adicionales o recomendaciones..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Registrar Entrada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Reportes -->
<div class="modal fade" id="modalVerReportes" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list"></i> Reportes de Progreso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reportesContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    const asignaciones = <?php echo json_encode($asignacionesActivas, 15, 512) ?>;

    function registrarEntrada(asignacionId) {
        const asignacion = asignaciones.find(a => a.Id_asignacion == asignacionId);
        if (!asignacion) return;

        $('#entrada_asignacion_id').val(asignacionId);
        $('#entrada_motor_id').text(asignacion.motor.Id_motor);
        $('#entrada_tecnico').text(
            asignacion.profesor.persona.Nombre + ' ' + asignacion.profesor.persona.Apellido
        );
        $('#entrada_estado_salida').html(
            `<span class="badge bg-warning">${asignacion.Estado_motor_salida}</span>`
        );
        $('#entrada_motivo').text(asignacion.Motivo_salida);

        $('#modalRegistrarEntrada').modal('show');
    }

    function verReportes(asignacionId) {
        const asignacion = asignaciones.find(a => a.Id_asignacion == asignacionId);
        if (!asignacion || !asignacion.reportes_progreso) return;

        let html = '<div class="timeline">';
        
        asignacion.reportes_progreso.forEach((reporte, index) => {
            const badgeClass = {
                'En Diagnostico': 'bg-secondary',
                'En Reparacion': 'bg-warning',
                'Reparado': 'bg-success',
                'Irreparable': 'bg-danger'
            }[reporte.Estado_actual] || 'bg-info';

            html += `
                <div class="card mb-3 ${index === 0 ? 'border-primary' : ''}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge ${badgeClass}">${reporte.Estado_actual}</span>
                                <small class="text-muted ms-2">
                                    ${new Date(reporte.Fecha_reporte).toLocaleString('es-ES')}
                                </small>
                            </div>
                            ${index === 0 ? '<span class="badge bg-primary">Más Reciente</span>' : ''}
                        </div>
                        <p class="mt-2 mb-1"><strong>Descripción:</strong></p>
                        <p class="mb-1">${reporte.Descripcion_trabajo}</p>
                        ${reporte.Observaciones ? `
                            <p class="mb-0"><strong>Observaciones:</strong></p>
                            <p class="text-muted">${reporte.Observaciones}</p>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        html += '</div>';

        $('#reportesContainer').html(html);
        $('#modalVerReportes').modal('show');
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/componentes/entradaComponentes.blade.php ENDPATH**/ ?>