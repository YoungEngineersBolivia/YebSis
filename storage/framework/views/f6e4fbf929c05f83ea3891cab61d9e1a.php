

<?php $__env->startSection('title', 'Evaluaciones del Estudiante'); ?>

<?php $__env->startSection('styles'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .modelo-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .modelo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
            border-color: #0d6efd;
        }
        .modelo-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .modelo-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #000;
        }
        .evaluaciones-count {
            background: #0d6efd;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .modelo-info {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .fecha-texto {
            background: white;
            color: #000;
            border: 1px solid #dee2e6;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        .evaluacion-item {
            border-bottom: 1px solid #e9ecef;
            padding: 15px 0;
        }
        .evaluacion-item:last-child {
            border-bottom: none;
        }
        .pregunta-respuesta-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 10px;
        }
        .pregunta-box, .respuesta-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #0d6efd;
        }
        .respuesta-box {
            border-left-color: #198754;
        }
        .label-texto {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .contenido-texto {
            color: #212529;
            line-height: 1.6;
        }
        .programa-badge {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 4px;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .pregunta-respuesta-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(isset($estudiante) && $estudiante): ?>
    <?php
        $evaluaciones = $estudiante->evaluaciones;
    ?>
<?php endif; ?>

<div class="container-fluid mt-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <?php if(isset($estudiante) && $estudiante): ?>
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-clipboard-check me-2"></i>Evaluaciones de <?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?>

            </h2>
        <?php else: ?>
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-clipboard-check me-2"></i>Evaluaciones
            </h2>
        <?php endif; ?>
        
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('tutor.home')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver al Panel
            </a>
        </div>
    </div>

    
    <?php if(isset($estudiante) && $estudiante): ?>
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="mb-0 fw-bold me-3"><?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?></h5>
                            <span class="badge bg-primary px-3 py-2 rounded-pill"><?php echo e($estudiante->Cod_estudiante); ?></span>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Programa:</strong> <?php echo e($estudiante->programa->Nombre ?? 'Sin programa'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Sucursal:</strong> <?php echo e($estudiante->sucursal->Nombre ?? 'Sin sucursal'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0"><strong>Profesor:</strong> 
                                    <?php echo e($estudiante->profesor->persona->Nombre ?? ''); ?> <?php echo e($estudiante->profesor->persona->Apellido ?? 'Sin profesor'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-inline-block bg-white p-3 rounded-3 shadow-sm">
                            <h6 class="text-muted mb-2">Total de Evaluaciones</h6>
                            <h2 class="mb-0 text-primary fw-bold"><?php echo e($evaluaciones->count()); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
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

    
    <?php if($evaluaciones->isEmpty()): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-muted">No hay evaluaciones registradas</h5>
                <p class="text-muted mb-4">Este estudiante no tiene evaluaciones en el sistema.</p>
            </div>
        </div>
    <?php else: ?>
        <?php
            $evaluacionesPorModelo = $evaluaciones->groupBy(function($evaluacion) {
                return $evaluacion->modelo->Id_modelos ?? 'sin_modelo';
            });
        ?>

        <div class="row g-4">
            <?php $__currentLoopData = $evaluacionesPorModelo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modeloId => $evaluacionesModelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $primerEvaluacion = $evaluacionesModelo->first();
                    $modelo = $primerEvaluacion->modelo ?? null;
                    $nombreModelo = $modelo ? $modelo->Nombre_modelo : 'Sin modelo';
                ?>
                
                <div class="col-md-6 col-lg-4">
                    <div class="modelo-card" data-bs-toggle="modal" data-bs-target="#modalModelo<?php echo e($modeloId); ?>">
                        <div class="modelo-header">
                            <div class="modelo-name">
                                <i class="bi bi-box me-2"></i><?php echo e($nombreModelo); ?>

                            </div>
                            <div class="evaluaciones-count">
                                <?php echo e($evaluacionesModelo->count()); ?>

                            </div>
                        </div>
                        <div class="modelo-info">
                            <i class="bi bi-clipboard-check me-1"></i>
                            <?php echo e($evaluacionesModelo->count()); ?> <?php echo e($evaluacionesModelo->count() == 1 ? 'evaluación' : 'evaluaciones'); ?>

                        </div>
                    </div>
                </div>

                
                <div class="modal fade" id="modalModelo<?php echo e($modeloId); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-box me-2"></i>Evaluaciones - <?php echo e($nombreModelo); ?>

                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php $__currentLoopData = $evaluacionesModelo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $profesor = $evaluacion->profesor ?? null;
                                        $personaProf = $profesor->persona ?? null;
                                        $nombreProfesor = $personaProf ? trim($personaProf->Nombre . ' ' . $personaProf->Apellido) : 'Profesor no asignado';
                                    ?>
                                    
                                    <div class="evaluacion-item">
                                        
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="programa-badge">
                                                    <?php echo e($evaluacion->programa->Nombre ?? 'Sin programa'); ?>

                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <div class="mb-2">
                                                    <span class="fecha-texto">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        <?php if($evaluacion->fecha_evaluacion): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y')); ?>

                                                        <?php else: ?>
                                                            Sin fecha
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-person-circle me-1"></i><?php echo e($nombreProfesor); ?>

                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="pregunta-respuesta-row">
                                            <div class="pregunta-box">
                                                <div class="label-texto">
                                                    <i class="bi bi-question-circle me-1"></i>Pregunta
                                                </div>
                                                <div class="contenido-texto">
                                                    <?php echo e($evaluacion->pregunta->Pregunta ?? 'Pregunta no disponible'); ?>

                                                </div>
                                            </div>
                                            <div class="respuesta-box">
                                                <div class="label-texto">
                                                    <i class="bi bi-check-circle me-1"></i>Respuesta
                                                </div>
                                                <div class="contenido-texto">
                                                    <?php echo e($evaluacion->respuesta->Respuesta ?? 'Sin respuesta'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-cerrar alertas después de 5 segundos
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('tutor.baseTutor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/tutor/evaluacionesTutorEstudiante.blade.php ENDPATH**/ ?>