<div class="card shadow-sm border-0 border-top border-primary border-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3 py-3">Nombre</th>
                        <th class="py-3">Apellido</th>
                        <th class="py-3">Programa</th>
                        <th class="py-3">Día</th>
                        <th class="py-3">Hora</th>
                        <th class="py-3">Profesor Asignado</th>
                        <th class="pe-3 py-3 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $horarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $horario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-3 fw-semibold"><?php echo e($horario->estudiante?->persona?->Nombre ?? '—'); ?></td>
                            <td><?php echo e($horario->estudiante?->persona?->Apellido ?? '—'); ?></td>
                            <td><?php echo e($horario->programa?->Nombre ?? '—'); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo e($horario->Dia ?? '—'); ?></span></td>
                            <td><?php echo e($horario->Hora ?? '—'); ?></td>
                            <td>
                                <?php $pp = $horario->profesor?->persona; ?>
                                <?php echo e(($pp?->Nombre && $pp?->Apellido) ? ($pp->Nombre . ' ' . $pp->Apellido) : 'Sin profesor'); ?>

                            </td>
                            <td class="pe-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary shadow-sm"
                                        title="Editar horario" data-bs-toggle="modal" data-bs-target="#modalEditar"
                                        data-id="<?php echo e($horario->Id_horarios); ?>"
                                        data-estudiante="<?php echo e($horario->Id_estudiantes); ?>"
                                        data-profesor="<?php echo e($horario->Id_profesores); ?>" data-dia="<?php echo e($horario->Dia); ?>"
                                        data-hora="<?php echo e($horario->Hora); ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="<?php echo e(route('horarios.destroy', $horario->Id_horarios)); ?>" method="POST"
                                        onsubmit="return confirm('¿Eliminar este horario?');" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger shadow-sm" title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No se encontraron horarios.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4 mb-4">
    <?php echo e($horarios->links('pagination::bootstrap-5')); ?>

</div><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/partials/horarios_table.blade.php ENDPATH**/ ?>