<?php $__env->startSection('title', 'Motores Asignados'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Motores Asignados</h1>
            <p class="text-muted">Gestión de motores en mantenimiento</p>
        </div>
        <a href="<?php echo e(route('motores.asignar.create')); ?>" class="btn btn-primary">
            <i class="bi bi-tools me-2"></i>Nueva Asignación
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($asignaciones->isEmpty()): ?>
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No hay motores asignados</h5>
                <p class="text-muted">Todos los motores están disponibles en el inventario</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID Motor</th>
                                <th>Técnico Asignado</th>
                                <th>Fecha Asignación</th>
                                <th>Días en Proceso</th>
                                <th>Estado</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $asignaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asignacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $diasEnProceso = (int) \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->diffInDays(now());
                                ?>
                                <tr>
                                    <td>
                                        <strong class="text-primary"><?php echo e($asignacion->motor->Id_motor); ?></strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-fill text-muted me-1"></i>
                                        <?php echo e($asignacion->profesor->persona->Nombre ?? ''); ?> 
                                        <?php echo e($asignacion->profesor->persona->Apellido_paterno ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(\Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y')); ?>

                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($diasEnProceso > 7 ? 'bg-warning text-dark' : 'bg-info'); ?>">
                                            <?php echo e($diasEnProceso); ?> <?php echo e($diasEnProceso == 1 ? 'día' : 'días'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            <?php if($asignacion->motor->Estado == 'Funcionando'): ?> bg-success
                                            <?php elseif($asignacion->motor->Estado == 'Descompuesto'): ?> bg-danger
                                            <?php else: ?> bg-warning text-dark
                                            <?php endif; ?>">
                                            <?php echo e($asignacion->motor->Estado); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#entregaModal<?php echo e($asignacion->Id_motores_asignados); ?>"
                                                title="Registrar Entrada">
                                            <i class="bi bi-box-arrow-in-down me-1"></i>Registrar Entrada
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal: Registrar Entrada -->
                                <div class="modal fade" id="entregaModal<?php echo e($asignacion->Id_motores_asignados); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-box-arrow-in-down me-2"></i>Registrar Entrada de Motor
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?php echo e(route('motores.registrar.entrada', $asignacion->Id_motores_asignados)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <!-- Información de la Asignación -->
                                                    <div class="card bg-light mb-4">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="bi bi-info-circle me-2"></i>Información de la Asignación
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Motor:</strong> 
                                                                    <span class="text-primary"><?php echo e($asignacion->motor->Id_motor); ?></span>
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Técnico:</strong> 
                                                                    <?php echo e($asignacion->profesor->persona->Nombre ?? ''); ?> 
                                                                    <?php echo e($asignacion->profesor->persona->Apellido_paterno ?? ''); ?>

                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Fecha Asignación:</strong> 
                                                                    <?php echo e(\Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y')); ?>

                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Días en Proceso:</strong> 
                                                                    <span class="badge <?php echo e($diasEnProceso > 7 ? 'bg-warning text-dark' : 'bg-info'); ?>">
                                                                        <?php echo e($diasEnProceso); ?> <?php echo e($diasEnProceso == 1 ? 'día' : 'días'); ?>

                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <?php if($asignacion->Observacion_inicial): ?>
                                                                <div class="mt-3">
                                                                    <strong>Observación Inicial:</strong>
                                                                    <p class="mb-0 mt-1"><?php echo e($asignacion->Observacion_inicial); ?></p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <!-- Formulario de Entrada -->
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="fecha_entrega<?php echo e($asignacion->Id_motores_asignados); ?>" class="form-label">
                                                                <i class="bi bi-calendar-event me-1"></i>Fecha de Entrega 
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="date" 
                                                                   class="form-control" 
                                                                   id="fecha_entrega<?php echo e($asignacion->Id_motores_asignados); ?>" 
                                                                   name="fecha_entrega" 
                                                                   value="<?php echo e(date('Y-m-d')); ?>" 
                                                                   min="<?php echo e($asignacion->Fecha_asignacion); ?>"
                                                                   max="<?php echo e(date('Y-m-d')); ?>"
                                                                   required>
                                                            <small class="text-muted">Debe ser posterior a la fecha de asignación</small>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="estado_final<?php echo e($asignacion->Id_motores_asignados); ?>" class="form-label">
                                                                <i class="bi bi-gear-fill me-1"></i>Estado Final del Motor 
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <select class="form-select" 
                                                                    id="estado_final<?php echo e($asignacion->Id_motores_asignados); ?>" 
                                                                    name="estado_final" required>
                                                                <option value="">Seleccione el estado</option>
                                                                <option value="Funcionando">Funcionando</option>
                                                                <option value="Descompuesto">Descompuesto</option>
                                                                <option value="En Proceso">En Proceso</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label for="Id_sucursales<?php echo e($asignacion->Id_motores_asignados); ?>" class="form-label">
                                                                <i class="bi bi-building me-1"></i>Sucursal de Entrada 
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <select class="form-select" 
                                                                    id="Id_sucursales<?php echo e($asignacion->Id_motores_asignados); ?>" 
                                                                    name="Id_sucursales" required>
                                                                <option value="">Seleccione la sucursal</option>
                                                                <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($sucursal->Id_Sucursales); ?>"
                                                                        <?php echo e($asignacion->motor->Id_sucursales == $sucursal->Id_Sucursales ? 'selected' : ''); ?>>
                                                                        <?php echo e($sucursal->Nombre); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label for="observaciones_entrega<?php echo e($asignacion->Id_motores_asignados); ?>" class="form-label">
                                                                <i class="bi bi-chat-left-text me-1"></i>Observaciones
                                                            </label>
                                                            <textarea class="form-control" 
                                                                      id="observaciones_entrega<?php echo e($asignacion->Id_motores_asignados); ?>" 
                                                                      name="observaciones" 
                                                                      rows="4" 
                                                                      placeholder="Descripción de reparaciones realizadas, estado final, problemas encontrados, piezas reemplazadas, etc."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-circle me-2"></i>Confirmar Entrada
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/componentes/listaAsignaciones.blade.php ENDPATH**/ ?>