<?php $__env->startSection('title', 'Detalles del Tutor'); ?>

<?php $__env->startSection('styles'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="<?php echo e(auto_asset ('css/style.css')); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            min-width: 150px;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .student-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .plan-card {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #007bff;
        }
        .payment-status-badge {
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        .status-pagado {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-vencido {
            background-color: #f8d7da;
            color: #721c24;
        }
        .progress-summary {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .stat-box {
            text-align: center;
            padding: 10px;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="bi bi-person-badge"></i> Detalles del Tutor</h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('tutores.index')); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Información del Tutor -->
    <div class="info-card">
        <h3 class="mb-3"><i class="bi bi-person-circle text-primary"></i> Información Personal</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Nombre Completo:</span>
                    <span class="info-value"><?php echo e($tutor->persona->Nombre); ?> <?php echo e($tutor->persona->Apellido); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Celular:</span>
                    <span class="info-value">
                        <i class="bi bi-phone"></i> <?php echo e($tutor->persona->Celular ?? 'No especificado'); ?>

                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value">
                        <i class="bi bi-geo-alt"></i> <?php echo e($tutor->persona->Direccion_domicilio ?? 'No especificado'); ?>

                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Género:</span>
                    <span class="info-value"><?php echo e($tutor->persona->Genero ?? 'No especificado'); ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Correo:</span>
                    <span class="info-value">
                        <i class="bi bi-envelope"></i> <?php echo e($tutor->usuario->Correo); ?>

                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Parentesco:</span>
                    <span class="info-value">
                        <i class="bi bi-people"></i> <?php echo e($tutor->Parentesco); ?>

                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Descuento:</span>
                    <span class="info-value">
                        <span class="badge bg-success"><?php echo e($tutor->Descuento ?? 0); ?>%</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIT:</span>
                    <span class="info-value"><?php echo e($tutor->Nit ?? 'No registrado'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Estudiantes y Planes de Pago -->
    <div class="mt-4">
        <h3 class="mb-3">
            <i class="bi bi-people-fill text-primary"></i> Estudiantes Asociados 
            <span class="badge bg-primary"><?php echo e($tutor->estudiantes->count()); ?></span>
        </h3>
        
        <?php if($tutor->estudiantes->isEmpty()): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Este tutor no tiene estudiantes asociados.
            </div>
        <?php else: ?>
            <?php $__currentLoopData = $tutor->estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="student-card">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-mortarboard-fill text-primary"></i>
                                <?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?>

                            </h4>
                            <div class="d-flex gap-3 flex-wrap">
                                <small class="text-muted">
                                    <i class="bi bi-hash"></i> <strong>Código:</strong> <?php echo e($estudiante->Cod_estudiante); ?>

                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> <strong>Estado:</strong> 
                                    <span class="badge bg-<?php echo e($estudiante->Estado == 'Activo' ? 'success' : 'secondary'); ?>">
                                        <?php echo e($estudiante->Estado ?? 'No especificado'); ?>

                                    </span>
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-book"></i> <strong>Programa:</strong> <?php echo e($estudiante->programa->Nombre ?? 'N/A'); ?>

                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-building"></i> <strong>Sucursal:</strong> <?php echo e($estudiante->sucursal->Nombre ?? 'N/A'); ?>

                                </small>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="stat-box">
                                <div class="stat-number text-primary"><?php echo e($estudiante->planesPagos ? $estudiante->planesPagos->count() : 0); ?></div>
                                <div class="stat-label">Planes de Pago</div>
                            </div>
                        </div>
                    </div>

                    <?php if($estudiante->planesPago->isEmpty()): ?>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i> No hay planes de pago registrados para este estudiante.
                        </div>
                    <?php else: ?>
                        <?php $__currentLoopData = $estudiante->planesPago; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="plan-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="bi bi-credit-card"></i> Plan de Pago #<?php echo e($index + 1); ?>

                                        </h5>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-check"></i> 
                                            Fecha: <?php echo e(\Carbon\Carbon::parse($plan->fecha_plan_pagos)->format('d/m/Y')); ?>

                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 mb-0 text-primary">
                                            Bs. <?php echo e(number_format($plan->Monto_total, 2)); ?>

                                        </div>
                                        <small class="text-muted">Monto Total</small>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <strong><i class="bi bi-book"></i> Programa:</strong> 
                                    <?php echo e($plan->programa->Nombre ?? 'No especificado'); ?>

                                </div>
                                <div class="mb-3">
                                    <strong><i class="bi bi-info-circle"></i> Estado del Plan:</strong> 
                                    <span class="badge bg-<?php echo e($plan->Estado_plan == 'Activo' ? 'success' : 'secondary'); ?>">
                                        <?php echo e($plan->Estado_plan ?? 'No especificado'); ?>

                                    </span>
                                </div>

                                <?php if($plan->cuotas->isNotEmpty()): ?>
                                    <?php
                                        $totalCuotas = $plan->cuotas->count();
                                        $cuotasPagadas = $plan->cuotas->where('Estado_cuota', 'Pagado')->count();
                                        $cuotasPendientes = $plan->cuotas->where('Estado_cuota', 'Pendiente')->count();
                                        $cuotasVencidas = $plan->cuotas->filter(function($cuota) {
                                            return $cuota->Estado_cuota === 'Pendiente' && 
                                                   \Carbon\Carbon::parse($cuota->Fecha_vencimiento)->isPast();
                                        })->count();
                                        $porcentajePagado = $totalCuotas > 0 ? round(($cuotasPagadas / $totalCuotas) * 100, 1) : 0;
                                        $montoPagado = $plan->cuotas->where('Estado_cuota', 'Pagado')->sum('Monto_pagado');
                                        $montoPendiente = $plan->Monto_total - $montoPagado;
                                    ?>

                                    <div class="progress-summary">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number text-success"><?php echo e($cuotasPagadas); ?></div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-check-circle-fill"></i> Pagadas
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number text-warning"><?php echo e($cuotasPendientes); ?></div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-clock-fill"></i> Pendientes
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number text-danger"><?php echo e($cuotasVencidas); ?></div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-exclamation-circle-fill"></i> Vencidas
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number"><?php echo e($totalCuotas); ?></div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-list-ol"></i> Total
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span><strong>Progreso de Pagos</strong></span>
                                                <span><strong><?php echo e($porcentajePagado); ?>%</strong></span>
                                            </div>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: <?php echo e($porcentajePagado); ?>%" 
                                                     aria-valuenow="<?php echo e($porcentajePagado); ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?php echo e($cuotasPagadas); ?>/<?php echo e($totalCuotas); ?>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-2">
                                            <span>
                                                <strong>Pagado:</strong> 
                                                <span class="text-success">Bs. <?php echo e(number_format($montoPagado, 2)); ?></span>
                                            </span>
                                            <span>
                                                <strong>Pendiente:</strong> 
                                                <span class="text-danger">Bs. <?php echo e(number_format($montoPendiente, 2)); ?></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><i class="bi bi-hash"></i> N° Cuota</th>
                                                    <th><i class="bi bi-cash"></i> Monto Cuota</th>
                                                    <th><i class="bi bi-cash-coin"></i> Monto Pagado</th>
                                                    <th><i class="bi bi-calendar-event"></i> Fecha Vencimiento</th>
                                                    <th><i class="bi bi-info-circle"></i> Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $plan->cuotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $estaVencida = $cuota->Estado_cuota === 'Pendiente' && 
                                                                       \Carbon\Carbon::parse($cuota->Fecha_vencimiento)->isPast();
                                                        $estadoClass = $cuota->Estado_cuota === 'Pagado' ? 'status-pagado' : 
                                                                      ($estaVencida ? 'status-vencido' : 'status-pendiente');
                                                        $estadoTexto = $cuota->Estado_cuota === 'Pagado' ? 'Pagado' : 
                                                                      ($estaVencida ? 'Vencido' : 'Pendiente');
                                                        $fechaVencimiento = \Carbon\Carbon::parse($cuota->Fecha_vencimiento);
                                                        // Convertir a entero los días
                                                        $diasRestantes = (int) $fechaVencimiento->diffInDays(\Carbon\Carbon::now(), false);
                                                    ?>
                                                    <tr class="<?php echo e($estaVencida ? 'table-danger' : ''); ?>">
                                                        <td><strong><?php echo e($cuota->Nro_de_cuota); ?></strong></td>
                                                        <td>Bs. <?php echo e(number_format($cuota->Monto_cuota, 2)); ?></td>
                                                        <td>
                                                            <?php if($cuota->Monto_pagado): ?>
                                                                <span class="text-success">
                                                                    Bs. <?php echo e(number_format($cuota->Monto_pagado, 2)); ?>

                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-muted">Bs. 0.00</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo e($fechaVencimiento->format('d/m/Y')); ?>

                                                            <?php if($cuota->Estado_cuota !== 'Pagado'): ?>
                                                                <?php if($estaVencida): ?>
                                                                    <br><small class="text-danger">
                                                                        <i class="bi bi-exclamation-triangle"></i> 
                                                                        Vencido hace <?php echo e(abs($diasRestantes)); ?> días
                                                                    </small>
                                                                <?php else: ?>
                                                                    <br><small class="text-muted">
                                                                        <i class="bi bi-clock"></i> 
                                                                        Faltan <?php echo e(abs($diasRestantes)); ?> días
                                                                    </small>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="payment-status-badge <?php echo e($estadoClass); ?>">
                                                                <?php if($cuota->Estado_cuota === 'Pagado'): ?>
                                                                    <i class="bi bi-check-circle"></i>
                                                                <?php elseif($estaVencida): ?>
                                                                    <i class="bi bi-exclamation-circle"></i>
                                                                <?php else: ?>
                                                                    <i class="bi bi-clock"></i>
                                                                <?php endif; ?>
                                                                <?php echo e($estadoTexto); ?>

                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle"></i> No hay cuotas registradas para este plan de pagos.
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
(function () {
    // Autocierre de alertas después de 5 segundos
    document.querySelectorAll('.alert').forEach(alertEl => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
            bsAlert.close();
        }, 5000);
    });

    console.log('Detalles del tutor cargados correctamente');
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('/administrador/baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/detallesTutor.blade.php ENDPATH**/ ?>