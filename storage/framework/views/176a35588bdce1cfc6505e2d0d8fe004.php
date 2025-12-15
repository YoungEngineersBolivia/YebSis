

<?php $__env->startSection('title', 'Planes de Pago - ' . ($estudiante->persona->Nombre ?? 'Estudiante')); ?>

<?php $__env->startSection('styles'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(auto_asset('css/administrador/planesPagoEstudiante.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?php echo e(route('estudiantes.ver', $estudiante->Id_estudiantes)); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a detalles
        </a>
    </div>

    <?php
        $persona = $estudiante->persona ?? null;
        $nombreCompleto = $persona ? trim(($persona->Nombre ?? '').' '.($persona->Apellido ?? '')) : 'Sin nombre';
    ?>

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-cash-coin"></i> Planes de Pago
                </h2>
                <p class="mb-0 opacity-75">
                    <?php echo e($nombreCompleto); ?> - <?php echo e($estudiante->Cod_estudiante); ?>

                </p>
            </div>
            <button class="btn btn-light" onclick="window.print()">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>
    </div>

    <?php if($planesPago->isEmpty()): ?>
        <div class="card">
            <div class="card-body empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No hay planes de pago registrados</h4>
                <p class="text-muted mb-3">Este estudiante aún no tiene planes de pago asignados.</p>
                <a href="<?php echo e(route('planesPago.crear', $estudiante->Id_estudiantes)); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Crear Plan de Pago
                </a>
            </div>
        </div>
    <?php else: ?>
        
        <?php
            $totalPlanes = $planesPago->count();
            $planesActivos = $planesPago->where('Estado_plan', 'Activo')->count();
            $planesCompletados = $planesPago->where('Estado_plan', 'Completado')->count();
            $montoTotal = $planesPago->sum('Monto_total');
            
            $totalCuotas = 0;
            $cuotasPagadas = 0;
            $montoPagado = 0;
            
            foreach($planesPago as $plan) {
                $totalCuotas += $plan->cuotas->count();
                $cuotasPagadas += $plan->cuotas->where('Estado_cuota', 'Pagado')->count();
                $montoPagado += $plan->cuotas->sum('Monto_pagado');
            }
            
            $porcentajePago = $montoTotal > 0 ? ($montoPagado / $montoTotal) * 100 : 0;
        ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-value"><?php echo e($totalPlanes); ?></div>
                    <div class="stats-label">Planes Totales</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-value text-success"><?php echo e($planesActivos); ?></div>
                    <div class="stats-label">Planes Activos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-value text-info">Bs. <?php echo e(number_format($montoPagado, 2)); ?></div>
                    <div class="stats-label">Total Pagado</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-box">
                    <div class="stats-value text-warning"><?php echo e(number_format($porcentajePago, 1)); ?>%</div>
                    <div class="stats-label">Progreso</div>
                </div>
            </div>
        </div>

        
        <?php $__currentLoopData = $planesPago; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $cuotas = $plan->cuotas ?? collect();
                $totalCuotasPlan = $cuotas->count();
                $cuotasPagadasPlan = $cuotas->where('Estado_cuota', 'Pagado')->count();
                $progresoPlan = $totalCuotasPlan > 0 ? ($cuotasPagadasPlan / $totalCuotasPlan) * 100 : 0;
                
                $montoPagadoPlan = $cuotas->sum('Monto_pagado');
                $montoRestante = $plan->Monto_total - $montoPagadoPlan;
                
                $estadoClass = match(Str::lower($plan->Estado_plan ?? '')) {
                    'activo' => 'status-activo',
                    'completado' => 'status-completado',
                    'pendiente' => 'status-pendiente',
                    'vencido' => 'status-vencido',
                    default => 'status-pendiente'
                };
            ?>

            <div class="plan-card">
                <div class="plan-header">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="mb-1">
                                <i class="bi bi-file-text"></i> 
                                Plan de Pago - <?php echo e($plan->programa->Nombre ?? 'Programa'); ?>

                            </h5>
                            <small class="text-muted">
                                Fecha: <?php echo e(\Carbon\Carbon::parse($plan->fecha_plan_pagos)->format('d/m/Y')); ?>

                            </small>
                        </div>
                        <span class="plan-status <?php echo e($estadoClass); ?>">
                            <?php echo e($plan->Estado_plan ?? 'Pendiente'); ?>

                        </span>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <strong>Monto Total:</strong><br>
                            <span class="text-primary fs-5">Bs. <?php echo e(number_format($plan->Monto_total, 2)); ?></span>
                        </div>
                        <div class="col-md-3">
                            <strong>Pagado:</strong><br>
                            <span class="text-success fs-5">Bs. <?php echo e(number_format($montoPagadoPlan, 2)); ?></span>
                        </div>
                        <div class="col-md-3">
                            <strong>Restante:</strong><br>
                            <span class="text-danger fs-5">Bs. <?php echo e(number_format($montoRestante, 2)); ?></span>
                        </div>
                        <div class="col-md-3">
                            <strong>Cuotas:</strong><br>
                            <span class="fs-5"><?php echo e($cuotasPagadasPlan); ?> / <?php echo e($totalCuotasPlan); ?></span>
                        </div>
                    </div>

                    
                    <div class="progress-container">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Progreso del plan</small>
                            <small><strong><?php echo e(number_format($progresoPlan, 1)); ?>%</strong></small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?php echo e($progresoPlan); ?>%;" 
                                 aria-valuenow="<?php echo e($progresoPlan); ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="mb-3">
                        <i class="bi bi-list-check"></i> Detalle de Cuotas
                    </h5>

                    <?php if($cuotas->isEmpty()): ?>
                        <p class="text-muted mb-0">No hay cuotas registradas para este plan.</p>
                    <?php else: ?>
                        <?php $__currentLoopData = $cuotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $fechaVencimiento = \Carbon\Carbon::parse($cuota->Fecha_vencimiento);
                                $estadoCuota = Str::lower($cuota->Estado_cuota ?? '');
                                $estaVencida = $fechaVencimiento->isPast() && $estadoCuota !== 'pagado';
                                
                                $cuotaClass = match($estadoCuota) {
                                    'pagado' => 'cuota-pagada',
                                    default => $estaVencida ? 'cuota-vencida' : 'cuota-pendiente'
                                };
                                
                                $iconoCuota = match($estadoCuota) {
                                    'pagado' => 'bi-check-circle-fill text-success',
                                    default => $estaVencida ? 'bi-exclamation-circle-fill text-danger' : 'bi-clock-fill text-warning'
                                };
                            ?>

                            <div class="cuota-item <?php echo e($cuotaClass); ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <i class="bi <?php echo e($iconoCuota); ?> fs-4"></i>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Cuota #<?php echo e($cuota->Nro_de_cuota); ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Vencimiento:</small><br>
                                        <strong><?php echo e($fechaVencimiento->format('d/m/Y')); ?></strong>
                                        <?php if($estaVencida && $estadoCuota !== 'pagado'): ?>
                                            <br><small class="text-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Vencida
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Monto:</small><br>
                                        <strong>Bs. <?php echo e(number_format($cuota->Monto_cuota, 2)); ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Pagado:</small><br>
                                        <strong class="text-success">Bs. <?php echo e(number_format($cuota->Monto_pagado ?? 0, 2)); ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge <?php echo e($estadoCuota === 'pagado' ? 'bg-success' : ($estaVencida ? 'bg-danger' : 'bg-warning')); ?>">
                                            <?php echo e(ucfirst($cuota->Estado_cuota ?? 'Pendiente')); ?>

                                        </span>
                                    </div>
                                    
                                </div>
                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Auto-cerrar alertas
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/planesPagoEstudiante.blade.php ENDPATH**/ ?>