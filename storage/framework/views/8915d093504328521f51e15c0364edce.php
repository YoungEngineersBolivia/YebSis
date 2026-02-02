<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="text-uppercase fw-bold text-dark">
            <i class="bi bi-calendar-range me-2"></i> Reporte de Asistencia
        </h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo e(route('asistencia.admin.pdf', request()->all())); ?>" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </a>
        <a href="<?php echo e(route('asistencia.admin.excel', request()->all())); ?>" class="btn btn-success ms-2">
            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-muted"><i class="bi bi-funnel me-1"></i> Filtros de Búsqueda</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('asistencia.admin.index')); ?>" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo e(request('fecha_inicio')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?php echo e(request('fecha_fin')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Profesor</label>
                    <select name="profesor_id" class="form-select">
                        <option value="">Todos</option>
                        <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($profe->Id_profesores); ?>" <?php echo e(request('profesor_id') == $profe->Id_profesores ? 'selected' : ''); ?>>
                                <?php echo e($profe->persona->Nombre); ?> <?php echo e($profe->persona->Apellido); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Programa</label>
                    <select name="programa_id" class="form-select">
                        <option value="">Todos</option>
                        <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($prog->Id_programas); ?>" <?php echo e(request('programa_id') == $prog->Id_programas ? 'selected' : ''); ?>>
                                <?php echo e($prog->Nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label small fw-bold">Buscar Estudiante</label>
                    <input type="text" name="estudiante_nombre" class="form-control" placeholder="Nombre o Apellido..." value="<?php echo e(request('estudiante_nombre')); ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                    <a href="<?php echo e(route('asistencia.admin.index')); ?>" class="btn btn-outline-secondary ms-2" title="Limpiar">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Profesor</th>
                        <th>Programa</th>
                        <th>Estado</th>
                        <th>Observación</th>
                        <th>Reprogramado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e(\Carbon\Carbon::parse($asistencia->Fecha)->format('d/m/Y')); ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo e($asistencia->estudiante->persona->Nombre); ?> <?php echo e($asistencia->estudiante->persona->Apellido); ?></div>
                                <small class="text-muted"><?php echo e($asistencia->estudiante->Cod_estudiante); ?></small>
                            </td>
                            <td><?php echo e($asistencia->profesor->persona->Nombre); ?> <?php echo e($asistencia->profesor->persona->Apellido); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo e($asistencia->programa->Nombre); ?></span></td>
                            <td>
                                <?php if($asistencia->Estado == 'Asistio'): ?>
                                    <span class="badge bg-success">Asistió</span>
                                <?php elseif($asistencia->Estado == 'Falta'): ?>
                                    <span class="badge bg-danger">Falta</span>
                                <?php elseif($asistencia->Estado == 'Licencia'): ?>
                                    <span class="badge bg-warning text-dark">Licencia</span>
                                <?php elseif($asistencia->Estado == 'Reprogramado'): ?>
                                    <span class="badge bg-info">Reprogramado</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($asistencia->Observacion ?? '-'); ?></td>
                            <td>
                                <?php if($asistencia->Fecha_reprogramada): ?>
                                    <span class="text-info fw-bold">
                                        <i class="bi bi-arrow-right-circle me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($asistencia->Fecha_reprogramada)->format('d/m/Y')); ?>

                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-clipboard-x display-4"></i>
                                    <p class="mt-2">No se encontraron registros de asistencia con los filtros seleccionados.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0">
        <?php echo e($asistencias->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/asistenciaAdministrador.blade.php ENDPATH**/ ?>