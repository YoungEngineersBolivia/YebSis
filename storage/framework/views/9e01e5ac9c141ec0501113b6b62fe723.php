<?php $__env->startSection('title', 'Pagos'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/administrador/pagosAdministrador.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">

    <!-- Header Principal -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1><i class="fas fa-money-bill-wave me-2"></i>Gestión de Pagos</h1>
            <p>Administra los pagos de los estudiantes</p>
        </div>
        <div class="d-flex align-items-center gap-3 flex-grow-1 justify-content-end" style="max-width: 600px;">
            <div class="input-group search-box flex-grow-1">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="buscarEstudiante" class="form-control" 
                       placeholder="Buscar por tutor o estudiante..." autocomplete="off">
            </div>
            <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm" id="contador-resultados">
                <?php echo e($estudiantes->count()); ?>

            </span>
        </div>
    </div>

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

    <!-- Lista de estudiantes -->
    <div id="lista-estudiantes" class="row g-4">
        <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $planes = $estudiante->planesPago;
                $tieneSaldo = false;
                $todosCompletados = true;
                
                if($planes && $planes->count() > 0) {
                    foreach($planes as $plan) {
                        $totalPagado = $plan->pagos->sum('Monto_pago');
                        if($totalPagado < $plan->Monto_total) {
                            $tieneSaldo = true;
                            $todosCompletados = false;
                        }
                    }
                } else {
                    $todosCompletados = false;
                }
                
                $estadoClase = $todosCompletados ? 'completado' : ($tieneSaldo ? 'pendiente' : 'pendiente');
            ?>
            
            <div class="col-lg-6 col-md-12 estudiante-card" data-estado="<?php echo e($estadoClase); ?>" 
                 data-tutor="<?php echo e($estudiante->tutor && $estudiante->tutor->persona ? strtolower($estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido) : ''); ?>"
                 data-estudiante="<?php echo e(strtolower(($estudiante->persona->Nombre ?? '') . ' ' . ($estudiante->persona->Apellido ?? ''))); ?>">
                <div class="card h-100 shadow-sm border-0 hover-card" style="transition: all 0.3s ease;">
                    <div class="card-header bg-gradient border-bottom pt-4 pb-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="mb-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 me-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-user-tie me-1"></i>Tutor
                                    </span>
                                    <span class="fw-bold text-dark" style="font-size: 1.05rem;">
                                        <?php echo e($estudiante->tutor && $estudiante->tutor->persona
                                            ? $estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido
                                            : 'Sin tutor asignado'); ?>

                                    </span>
                                </div>
                                <div>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 me-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-graduation-cap me-1"></i>Estudiante
                                    </span>
                                    <span class="text-dark" style="font-size: 1rem;">
                                        <?php echo e($estudiante->persona->Nombre ?? ''); ?>

                                        <?php echo e($estudiante->persona->Apellido ?? ''); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4" style="max-height: 400px; overflow-y: auto;">
                        <?php if($planes && $planes->count() > 0): ?>
                            <?php $__currentLoopData = $planes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $totalPagado = $plan->pagos->sum('Monto_pago');
                                    $restante = $plan->Monto_total - $totalPagado;
                                    $porcentaje = $plan->Monto_total > 0 ? ($totalPagado / $plan->Monto_total) * 100 : 0;
                                    $ultimoPago = $plan->pagos->sortByDesc('Fecha_pago')->first();
                                ?>
                                
                                <div class="plan-card mb-3 p-3 bg-white rounded-3 border shadow-sm" style="transition: all 0.2s ease;">
                                    <!-- Header del plan -->
                                    <div class="mb-2">
                                        <h6 class="mb-1 fw-bold" style="font-size: 1.05rem;">
                                            <i class="fas fa-file-invoice me-2 text-primary"></i>
                                            <?php echo e($plan->programa->Nombre ?? 'Programa'); ?>

                                        </h6>
                                       
                                    </div>

                                    <!-- Resumen financiero -->
                                    <div class="row g-2 mb-2">
                                        <div class="col-12">
                                            <div class="bg-gradient p-2 rounded shadow-sm border-0" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                                                <div class="text-center">
                                                    <?php if($ultimoPago): ?>
                                                        <small class="text-success d-block fw-semibold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Último Pago Realizado</small>
                                                        <h5 class="text-success mb-1 fw-bold">Bs. <?php echo e(number_format($ultimoPago->Monto_pago, 2)); ?></h5>
                                                        <small class="text-success d-block" style="font-size: 0.75rem;">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            <?php echo e(\Carbon\Carbon::parse($ultimoPago->Fecha_pago)->format('d/m/Y')); ?>

                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-muted d-block fw-semibold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Monto Pagado</small>
                                                        <h5 class="text-success mb-1 fw-bold">Bs. <?php echo e(number_format($totalPagado, 2)); ?></h5>
                                                    <?php endif; ?>
                                                    <?php if($ultimoPago): ?>
                                                    <?php else: ?>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Sin pagos registrados
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botón para ver desglose de pagos -->
                                    <button class="btn btn-outline-secondary btn-sm w-100 mb-2 hover-button" type="button" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDesglose-<?php echo e($plan->Id_planes_pagos); ?>"
                                            style="transition: all 0.3s ease; padding: 0.4rem;">
                                        <i class="fas fa-list-ul me-1" style="font-size: 0.85rem;"></i>
                                        <small>Ver Desglose</small>
                                    </button>



                                    <!-- Botón agregar pago -->
                                    <button type="button" class="btn btn-primary w-100 btn-sm shadow-sm hover-button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAgregarPago"
                                            data-plan-id="<?php echo e($plan->Id_planes_pagos); ?>"
                                            data-monto-total="<?php echo e($plan->Monto_total); ?>"
                                            data-monto-pagado="<?php echo e($totalPagado); ?>"
                                            data-restante="<?php echo e($restante); ?>"
                                            data-programa="<?php echo e($plan->programa->Nombre ?? 'Programa'); ?>"
                                            style="transition: all 0.3s ease; padding: 0.6rem;">
                                        <i class="fas fa-plus-circle me-2"></i>Agregar Pago
                                    </button>


                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="alert alert-warning py-2 mb-0">
                                <small><i class="fas fa-exclamation-triangle me-1"></i> Sin plan asignado</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Mensaje cuando no hay resultados -->
    <div id="no-resultados" class="text-center py-5" style="display: none;">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No se encontraron resultados</h5>
    </div>
</div>

<!-- Modal para agregar pago -->
<div class="modal fade" id="modalAgregarPago" tabindex="-1" aria-labelledby="modalAgregarPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="<?php echo e(route('pagos.registrar')); ?>" id="formAgregarPago">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="plan_id" id="modal-plan-id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="modalAgregarPagoLabel">Registrar Pago</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Info del plan -->
                <div class="alert alert-primary mb-4">
                    <h6 class="mb-1 fw-bold" id="modal-programa-nombre">Programa</h6>
                    
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" 
                           placeholder="Ej: Mensualidad Enero" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Monto (Bs.)</label>
                    <input type="number" step="0.01" class="form-control" 
                           name="monto_pago" id="modal-monto-input" 
                           placeholder="0.00" required min="0.01">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control" 
                               name="fecha_pago" required value="<?php echo e(date('Y-m-d')); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" 
                               name="comprobante" placeholder="Opcional">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Pago</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Modales de desglose para cada plan -->
<?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $planes = $estudiante->planesPago;
    ?>
    <?php if($planes && $planes->count() > 0): ?>
        <?php $__currentLoopData = $planes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $totalPagado = $plan->pagos->sum('Monto_pago');
            ?>
            <div class="modal fade" id="modalDesglose-<?php echo e($plan->Id_planes_pagos); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-white">
                                <i class="fas fa-list-ul me-2"></i>
                                Desglose de Pagos
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    <?php echo e($plan->programa->Nombre ?? 'Programa'); ?>

                                </h6>
                               
                            </div>

                            <hr>

                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-history me-2 text-secondary"></i>
                                Historial de Pagos
                            </h6>

                            <?php if($plan->pagos && $plan->pagos->count() > 0): ?>
                                <div class="list-group">
                                    <?php $__currentLoopData = $plan->pagos->sortByDesc('Fecha_pago'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="list-group-item border-start border-success border-3 mb-2">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div>
                                                    <h6 class="mb-1 text-success fw-bold">Bs. <?php echo e(number_format($pago->Monto_pago, 2)); ?></h6>
                                                    <?php if($pago->Descripcion): ?>
                                                        <small class="text-muted"><?php echo e($pago->Descripcion); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?php echo e(\Carbon\Carbon::parse($pago->Fecha_pago)->format('d/m/Y')); ?>

                                                </small>
                                            </div>
                                            <?php if($pago->comprobante): ?>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-receipt me-1"></i>
                                                    Ref: <?php echo e($pago->comprobante); ?>

                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Total Pagado:</strong>
                                        <h5 class="mb-0 text-success fw-bold">Bs. <?php echo e(number_format($totalPagado, 2)); ?></h5>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay pagos registrados para este plan
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script id="estudiantes-data" type="application/json">
    <?php echo json_encode($estudiantes); ?>

</script>

<script>
    const estudiantes = JSON.parse(document.getElementById('estudiantes-data').textContent);
    const registrarPagoUrl = "<?php echo e(route('pagos.registrar')); ?>";
    const csrfToken = "<?php echo e(csrf_token()); ?>";
</script>

<script src="<?php echo e(auto_asset('js/administrador/pagosAdministrador.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/pagosAdministrador.blade.php ENDPATH**/ ?>