<?php $__env->startSection('title', 'Pagos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h2>Pagos de Estudiantes</h2>

    <form method="GET" action="<?php echo e(route('pagos.index')); ?>">
        <div class="mb-3 position-relative">
            <input type="text" id="buscarEstudiante" name="nombre" placeholder="Buscar estudiante"
                value="<?php echo e(request('nombre')); ?>" autocomplete="off" class="form-control">
            <div id="sugerencias" class="list-group position-absolute w-100" style="z-index:1000;"></div>
        </div>
    </form>

    <hr>

    <div id="estudiante-info" style="display:none;"></div>

    <div id="lista-estudiantes">
        <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mb-3">
                <div class="card-body">

                    <h5>
                        <?php echo e($estudiante->persona->Nombre ?? ''); ?>

                        <?php echo e($estudiante->persona->Apellido ?? ''); ?>

                    </h5>

                    <p>
                        Tutor:
                        <?php echo e($estudiante->tutor && $estudiante->tutor->persona
                            ? $estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido
                            : 'Sin padre asignado'); ?>

                    </p>

                    <h6>Planes y Cuotas:</h6>

                    <?php
                        $planes = $estudiante->planesPago;
                    ?>

                    <?php if($planes && $planes->count() > 0): ?>

                        <?php $__currentLoopData = $planes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            

                            <?php if($plan->cuotas && $plan->cuotas->count() > 0): ?>

                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nro de Cuota</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Monto Cuota</th>
                                            <th>Monto Pagado</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $__currentLoopData = $plan->cuotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($cuota->Nro_de_cuota); ?></td>
                                                <td><?php echo e($cuota->Fecha_vencimiento); ?></td>
                                                <td><?php echo e($cuota->Monto_cuota); ?></td>
                                                <td><?php echo e($cuota->Monto_pagado ?? '0'); ?></td>
                                                <td><?php echo e($cuota->Estado_cuota); ?></td>

                                                <td>
                                                    <?php if($cuota->Estado_cuota !== 'Pagado'): ?>
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalRegistrarPago"
                                                            data-cuota-id="<?php echo e($cuota->Id_cuotas); ?>"
                                                            data-monto="<?php echo e($cuota->Monto_cuota); ?>"
                                                            data-plan-id="<?php echo e($plan->Id_planes_pagos); ?>">
                                                            <i class="fas fa-money-bill"></i> Registrar
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Pagado</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>

                                </table>

                            <?php else: ?>
                                <p class="text-muted">Este plan no tiene cuotas registradas.</p>
                            <?php endif; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php else: ?>
                        <p class="text-muted">El estudiante no tiene plan de pago asignado.</p>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Modal para registrar pago -->
<div class="modal fade" id="modalRegistrarPago" tabindex="-1" aria-labelledby="modalRegistrarPagoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="<?php echo e(route('pagos.registrar')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="cuota_id" id="modal-cuota-id">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarPagoLabel">Registrar Pago de Cuota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <label class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" id="modal-descripcion" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Comprobante</label>
                    <input type="text" class="form-control" name="comprobante" id="modal-comprobante" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Monto Pago</label>
                    <input type="number" step="0.01" class="form-control" name="monto_pago" id="modal-monto-pago" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Fecha Pago</label>
                    <input type="date" class="form-control" name="fecha_pago" id="modal-fecha-pago" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">ID Planes Pagos</label>
                    <input type="number" class="form-control" name="id_planes_pagos" id="modal-id-planes-pagos" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Pago</button>
            </div>

        </div>
    </form>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<script id="estudiantes-data" type="application/json">
    <?php echo json_encode($estudiantes); ?>

</script>

<script>
    const estudiantes = JSON.parse(document.getElementById('estudiantes-data').textContent);
    const registrarPagoUrl = "<?php echo e(route('pagos.registrar')); ?>";
</script>

<script src="<?php echo e(auto_asset('js/administrador/pagosAdministrador.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/pagosAdministrador.blade.php ENDPATH**/ ?>