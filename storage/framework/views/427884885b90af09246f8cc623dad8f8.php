

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-out-alt"></i> Salidas de Motores
                    </h4>
                    <div>
                        <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarSalida">
                            <i class="fas fa-plus"></i> Registrar Nueva Salida
                        </button>
                        <a href="<?php echo e(route('componentes.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver al Inventario
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Técnico</label>
                            <select class="form-select" id="filtroTecnico" onchange="filtrarSalidas()">
                                <option value="">Todos los técnicos</option>
                                <?php $__currentLoopData = $tecnicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tecnico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tecnico->Id_profesores); ?>">
                                        <?php echo e($tecnico->persona->Nombre); ?> <?php echo e($tecnico->persona->Apellido_paterno); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Desde</label>
                            <input type="date" class="form-control" id="fechaDesde" onchange="filtrarSalidas()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hasta</label>
                            <input type="date" class="form-control" id="fechaHasta" onchange="filtrarSalidas()">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-sync"></i> Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de salidas -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha Salida</th>
                                    <th>ID Motor</th>
                                    <th>Técnico</th>
                                    <th>Estado al Salir</th>
                                    <th>Sucursal</th>
                                    <th>Motivo</th>
                                    <th>Registrado Por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $salidas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salida): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(\Carbon\Carbon::parse($salida->Fecha_movimiento)->format('d/m/Y H:i')); ?></td>
                                        <td><strong><?php echo e($salida->motor->Id_motor); ?></strong></td>
                                        <td><?php echo e($salida->Nombre_tecnico); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($salida->Estado_salida == 'Funcionando' ? 'success' : 'warning'); ?>">
                                                <?php echo e($salida->Estado_salida); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($salida->sucursal->Nombre); ?></td>
                                        <td><small><?php echo e(Str::limit($salida->Motivo_salida, 40)); ?></small></td>
                                        <td>
                                            <?php if($salida->usuario): ?>
                                                <?php echo e($salida->usuario->persona->Nombre ?? 'Sistema'); ?>

                                            <?php else: ?>
                                                Sistema
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm" 
                                                    onclick="verDetalleSalida(<?php echo e($salida->Id_movimientos); ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay salidas registradas</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-3">
                        <?php echo e($salidas->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar Salida -->
<div class="modal fade" id="modalRegistrarSalida" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo e(route('motores.salidas.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-out-alt"></i> Registrar Salida de Motor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Motor <span class="text-danger">*</span></label>
                            <select name="Id_motores" class="form-select" required id="selectMotor">
                                <option value="">Seleccione un motor...</option>
                                <?php $__currentLoopData = $motoresDisponibles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($motor->Id_motores); ?>">
                                        <?php echo e($motor->Id_motor); ?> - <?php echo e($motor->Estado); ?> 
                                        (<?php echo e($motor->sucursal->Nombre ?? 'Sin sucursal'); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Solo se muestran motores disponibles en inventario</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Técnico <span class="text-danger">*</span></label>
                            <select name="Id_profesores" class="form-select" required>
                                <option value="">Seleccione un técnico...</option>
                                <?php $__currentLoopData = $tecnicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tecnico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tecnico->Id_profesores); ?>">
                                        <?php echo e($tecnico->persona->Nombre); ?> <?php echo e($tecnico->persona->Apellido_paterno); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de Salida <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="fecha_salida" class="form-control" required
                               value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motivo de la Salida <span class="text-danger">*</span></label>
                        <textarea name="motivo_salida" class="form-control" rows="3" required
                                  placeholder="Ejemplo: Reparación de motor quemado, cambio de bobinas..."></textarea>
                        <small class="text-muted">Mínimo 10 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea name="observaciones" class="form-control" rows="2"
                                  placeholder="Cualquier información adicional relevante..."></textarea>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i>
                        <strong>Importante:</strong> El motor será marcado como "En Reparación" y asignado al técnico seleccionado.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check"></i> Registrar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Detalle de Salida -->
<div class="modal fade" id="modalDetalleSalida" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalle de Salida</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filtrarSalidas() {
    const tecnico = document.getElementById('filtroTecnico').value;
    const desde = document.getElementById('fechaDesde').value;
    const hasta = document.getElementById('fechaHasta').value;
    
    let url = new URL(window.location.href);
    url.searchParams.set('tecnico_id', tecnico);
    url.searchParams.set('fecha_desde', desde);
    url.searchParams.set('fecha_hasta', hasta);
    
    window.location.href = url.toString();
}

function limpiarFiltros() {
    window.location.href = '<?php echo e(route('motores.salidas.index')); ?>';
}

function verDetalleSalida(id) {
    // Implementar según tu estructura
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleSalida'));
    modal.show();
}

<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo e(session('error')); ?>'
    });
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/componentes/historialSalidas.blade.php ENDPATH**/ ?>