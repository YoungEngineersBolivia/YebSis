

<?php $__env->startSection('title', 'Historial de Asignaciones'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <?php if($asignaciones->isEmpty()): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No hay historial de asignaciones</h5>
                <p class="text-muted">Los motores completados aparecerán aquí</p>
            </div>
        </div>
    <?php else: ?>
        
        <div class="card shadow-sm border-0">
            
            <div class="card-header bg-white border-0">
                <label class="form-label fw-semibold mb-2">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           class="form-control border-start-0"
                           id="searchInput"
                           placeholder="Filtrar por ID de motor, técnico, sucursal...">
                </div>
            </div>

            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 py-3">ID Motor</th>
                                <th class="border-0 py-3">Técnico</th>
                                <th class="border-0 py-3">Sucursal</th>
                                <th class="border-0 py-3">Fecha Asignación</th>
                                <th class="border-0 py-3">Fecha Entrega</th>
                                <th class="border-0 py-3">Duración</th>
                                <th class="border-0 py-3">Estado Final</th>
                                <th class="border-0 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $asignaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asignacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $duracion = \Carbon\Carbon::parse($asignacion->Fecha_asignacion)
                                        ->diffInDays(\Carbon\Carbon::parse($asignacion->Fecha_entrega));
                                ?>
                                <tr>
                                    <td class="py-3">
                                        <strong class="text-primary"><?php echo e($asignacion->motor->Id_motor); ?></strong>
                                    </td>
                                    <td class="py-3">
                                        <?php echo e($asignacion->profesor->persona->Nombre ?? ''); ?>

                                        <?php echo e($asignacion->profesor->persona->Apellido_paterno ?? ''); ?>

                                    </td>
                                    <td class="py-3">
                                        <?php echo e($asignacion->motor->sucursal->Nombre ?? 'N/A'); ?>

                                    </td>
                                    <td class="py-3">
                                        <?php echo e(\Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y')); ?>

                                    </td>
                                    <td class="py-3">
                                        <?php echo e(\Carbon\Carbon::parse($asignacion->Fecha_entrega)->format('d/m/Y')); ?>

                                    </td>
                                    <td class="py-3">
                                        <span class="badge rounded-pill <?php echo e($duracion > 7 ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                                            <?php echo e($duracion); ?> <?php echo e($duracion == 1 ? 'día' : 'días'); ?>

                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge rounded-pill
                                            <?php if($asignacion->motor->Estado == 'Funcionando'): ?> bg-success
                                            <?php elseif($asignacion->motor->Estado == 'Descompuesto'): ?> bg-danger
                                            <?php else: ?> bg-warning text-dark
                                            <?php endif; ?>">
                                            <?php echo e($asignacion->motor->Estado); ?>

                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detalleModal<?php echo e($asignacion->Id_motores_asignados); ?>"
                                                title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <?php $__currentLoopData = $asignaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asignacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $duracion = \Carbon\Carbon::parse($asignacion->Fecha_asignacion)
                    ->diffInDays(\Carbon\Carbon::parse($asignacion->Fecha_entrega));
            ?>
            
            <div class="modal fade" id="detalleModal<?php echo e($asignacion->Id_motores_asignados); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-info-circle me-2"></i>
                                Detalles de Asignación #<?php echo e($asignacion->Id_motores_asignados); ?>

                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold" style="width: 20%;">Motor</td>
                                        <td style="width: 30%;"><?php echo e($asignacion->motor->Id_motor); ?></td>
                                        <td class="bg-light fw-bold" style="width: 20%;">Sucursal</td>
                                        <td style="width: 30%;"><?php echo e($asignacion->motor->sucursal->Nombre ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Técnico</td>
                                        <td colspan="3">
                                            <?php echo e($asignacion->profesor->persona->Nombre ?? ''); ?>

                                            <?php echo e($asignacion->profesor->persona->Apellido_paterno ?? ''); ?>

                                            <?php echo e($asignacion->profesor->persona->Apellido_materno ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Fecha Asignación</td>
                                        <td><?php echo e(\Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y')); ?></td>
                                        <td class="bg-light fw-bold">Fecha Entrega</td>
                                        <td><?php echo e(\Carbon\Carbon::parse($asignacion->Fecha_entrega)->format('d/m/Y')); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Duración</td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo e($duracion > 7 ? 'bg-warning text-dark' : 'bg-secondary'); ?>">
                                                <?php echo e($duracion); ?> <?php echo e($duracion == 1 ? 'día' : 'días'); ?>

                                            </span>
                                        </td>
                                        <td class="bg-light fw-bold">Estado Final</td>
                                        <td>
                                            <span class="badge rounded-pill
                                                <?php if($asignacion->motor->Estado == 'Funcionando'): ?> bg-success
                                                <?php elseif($asignacion->motor->Estado == 'Descompuesto'): ?> bg-danger
                                                <?php else: ?> bg-warning text-dark
                                                <?php endif; ?>">
                                                <?php echo e($asignacion->motor->Estado); ?>

                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Observación Inicial</td>
                                        <td colspan="3"><?php echo e($asignacion->Observacion_inicial ?? 'Sin observaciones'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Observaciones Finales</td>
                                        <td colspan="3"><?php echo e($asignacion->motor->Observacion ?? 'Sin observaciones finales'); ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            
                            <?php if($asignacion->reportes && $asignacion->reportes->count() > 0): ?>
                            <div class="p-4 bg-light">
                                <h6 class="mb-3">
                                    <i class="bi bi-clipboard-check me-2"></i>
                                    Reportes de Mantenimiento (<?php echo e($asignacion->reportes->count()); ?>)
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0 bg-white">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 25%;">Fecha</th>
                                                <th style="width: 25%;">Estado</th>
                                                <th style="width: 50%;">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $asignacion->reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reporte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(\Carbon\Carbon::parse($reporte->Fecha_reporte)->format('d/m/Y')); ?></td>
                                                <td>
                                                    <span class="badge rounded-pill
                                                        <?php if($reporte->Estado_final == 'Funcionando'): ?> bg-success
                                                        <?php elseif($reporte->Estado_final == 'Descompuesto'): ?> bg-danger
                                                        <?php else: ?> bg-warning text-dark
                                                        <?php endif; ?>">
                                                        <?php echo e($reporte->Estado_final); ?>

                                                    </span>
                                                </td>
                                                <td><?php echo e($reporte->Observaciones); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="p-4 bg-light text-center text-muted">
                                <i class="bi bi-clipboard-x" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">No hay reportes de mantenimiento registrados</p>
                            </div>
                            <?php endif; ?>
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
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/componentes/historialAsignaciones.blade.php ENDPATH**/ ?>