<?php $__env->startSection('title', 'Historial de Movimientos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clock-history"></i> Historial de Movimientos</h2>
        <a href="<?php echo e(route('admin.componentes.inventario')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Inventario
        </a>
    </div>

    
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.componentes.historial-general')); ?>">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-arrow-left-right"></i> Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="Salida" <?php echo e(request('tipo') == 'Salida' ? 'selected' : ''); ?>>Salida</option>
                            <option value="Entrada" <?php echo e(request('tipo') == 'Entrada' ? 'selected' : ''); ?>>Entrada</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-person-gear"></i> Técnico</label>
                        <select name="tecnico" class="form-select">
                            <option value="">Todos</option>
                            <?php $__currentLoopData = $tecnicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tec->Id_profesores); ?>" <?php echo e(request('tecnico') == $tec->Id_profesores ? 'selected' : ''); ?>>
                                    <?php echo e($tec->persona->Nombre ?? ''); ?> <?php echo e($tec->persona->Apellido ?? $tec->persona->Apellido_paterno ?? ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-building"></i> Sucursal</label>
                        <select name="sucursal" class="form-select">
                            <option value="">Todas</option>
                            <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $suc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($suc->Id_sucursales); ?>" <?php echo e(request('sucursal') == $suc->Id_sucursales ? 'selected' : ''); ?>>
                                    <?php echo e($suc->Nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-tag"></i> ID Motor</label>
                        <input type="text" name="motor" class="form-control" placeholder="Buscar..." value="<?php echo e(request('motor')); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-calendar-range"></i> Rango de fechas</label>
                        <div class="input-group">
                            <input type="date" name="fecha_desde" class="form-control" value="<?php echo e(request('fecha_desde')); ?>" title="Desde">
                            <input type="date" name="fecha_hasta" class="form-control" value="<?php echo e(request('fecha_hasta')); ?>" title="Hasta">
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Aplicar Filtros
                    </button>
                    <a href="<?php echo e(route('admin.componentes.historial-general')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i>
                Mostrando <strong><?php echo e($movimientos->count()); ?></strong> de <strong><?php echo e($movimientos->total()); ?></strong> movimientos
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="bi bi-arrow-left-right"></i> Tipo</th>
                            <th><i class="bi bi-tag"></i> ID Motor</th>
                            <th><i class="bi bi-person-gear"></i> Técnico Asignado</th>
                            <th><i class="bi bi-building"></i> Sucursal</th>
                            <th><i class="bi bi-calendar3"></i> Fecha</th>
                            <th><i class="bi bi-circle-fill"></i> Estado</th>
                            <th><i class="bi bi-chat-left-text"></i> Detalle</th>
                            <th class="text-center"><i class="bi bi-gear"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <?php if($mov->Tipo_movimiento == 'Salida'): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-box-arrow-right"></i> Salida
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-box-arrow-in-left"></i> Entrada
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><strong class="text-primary"><?php echo e($mov->motor->Id_motor ?? 'N/A'); ?></strong></td>
                                <td>
                                    <?php if($mov->profesor && $mov->profesor->persona): ?>
                                        <i class="bi bi-person-check"></i>
                                        <?php echo e($mov->profesor->persona->Nombre); ?>

                                        <?php echo e($mov->profesor->persona->Apellido ?? $mov->profesor->persona->Apellido_paterno ?? ''); ?>

                                    <?php else: ?>
                                        <span class="text-muted"><?php echo e($mov->Nombre_tecnico ?? '---'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($mov->sucursal->Nombre ?? 'N/A'); ?></td>
                                <td>
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo e(\Carbon\Carbon::parse($mov->Fecha_movimiento)->format('d/m/Y H:i')); ?>

                                </td>
                                <td>
                                    <?php
                                        $estado = $mov->Tipo_movimiento == 'Salida' ? $mov->Estado_salida : $mov->Estado_entrada;
                                        $badge = [
                                            'Disponible' => 'bg-success',
                                            'Funcionando' => 'bg-success',
                                            'En Reparacion' => 'bg-warning text-dark',
                                            'Descompuesto' => 'bg-danger',
                                        ][$estado] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?php echo e($badge); ?>"><?php echo e($estado ?? '---'); ?></span>
                                </td>
                                <td>
                                    <?php if($mov->Tipo_movimiento == 'Salida'): ?>
                                        <?php echo e(Str::limit($mov->Motivo_salida, 50) ?? '---'); ?>

                                    <?php else: ?>
                                        <?php echo e(Str::limit($mov->Trabajo_realizado, 50) ?? '---'); ?>

                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detalleMov<?php echo e($mov->Id_movimientos); ?>"
                                            title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-0">No se encontraron movimientos con los filtros seleccionados</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if($movimientos->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($movimientos->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>


<?php $__currentLoopData = $movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="detalleMov<?php echo e($mov->Id_movimientos); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-info-circle me-2"></i>
                        Detalle del Movimiento #<?php echo e($mov->Id_movimientos); ?>

                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td class="bg-light fw-bold" style="width: 25%;">Tipo</td>
                                <td>
                                    <?php if($mov->Tipo_movimiento == 'Salida'): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-box-arrow-right"></i> Salida</span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><i class="bi bi-box-arrow-in-left"></i> Entrada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">ID Motor</td>
                                <td><strong><?php echo e($mov->motor->Id_motor ?? 'N/A'); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Técnico</td>
                                <td>
                                    <?php if($mov->profesor && $mov->profesor->persona): ?>
                                        <?php echo e($mov->profesor->persona->Nombre); ?>

                                        <?php echo e($mov->profesor->persona->Apellido ?? $mov->profesor->persona->Apellido_paterno ?? ''); ?>

                                    <?php else: ?>
                                        <?php echo e($mov->Nombre_tecnico ?? '---'); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Sucursal</td>
                                <td><?php echo e($mov->sucursal->Nombre ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="bg-light fw-bold">Fecha</td>
                                <td><?php echo e(\Carbon\Carbon::parse($mov->Fecha_movimiento)->format('d/m/Y H:i')); ?></td>
                            </tr>
                            <?php if($mov->Tipo_movimiento == 'Salida'): ?>
                                <tr>
                                    <td class="bg-light fw-bold">Estado al Salir</td>
                                    <td><?php echo e($mov->Estado_salida ?? '---'); ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-bold">Motivo de Salida</td>
                                    <td><?php echo e($mov->Motivo_salida ?? '---'); ?></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td class="bg-light fw-bold">Estado al Entrar</td>
                                    <td><?php echo e($mov->Estado_entrada ?? '---'); ?></td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-bold">Trabajo Realizado</td>
                                    <td><?php echo e($mov->Trabajo_realizado ?? '---'); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="bg-light fw-bold">Observaciones</td>
                                <td><?php echo e($mov->Observaciones ?? 'Sin observaciones'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/componentes/historialMovimientos.blade.php ENDPATH**/ ?>