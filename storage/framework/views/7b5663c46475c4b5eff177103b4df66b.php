<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/listadoAlumnos.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="students-container">
    
    <div class="container py-4">
        
        <div class="row mb-4 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-3">
                    <a href="<?php echo e(route('profesor.menu-alumnos')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                    <h2 class="h4 mb-0 fw-bold text-dark">
                        <?php if($tipo === 'evaluar'): ?>
                            <i class="bi bi-clipboard-check me-2"></i> Evaluar
                        <?php elseif($tipo === 'asignados'): ?>
                            <i class="bi bi-people-fill me-2"></i> Mis Alumnos
                        <?php else: ?>
                            <i class="bi bi-calendar-event me-2"></i> Recuperatoria
                        <?php endif; ?>
                    </h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        class="form-control border-start-0 ps-0" 
                        placeholder="Buscar estudiante..." 
                        id="searchInput"
                        autocomplete="off"
                    >
                    <span class="input-group-text bg-light fw-bold text-primary">
                        <?php echo e($estudiantes->count()); ?>

                    </span>
                </div>
            </div>
        </div>

    
    
    <div class="row g-3" id="studentsList">
        <?php if(isset($estudiantes) && $estudiantes->count() > 0): ?>
            <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div 
                    class="card h-100 shadow-sm border-0 student-card p-3" 
                    style="cursor: pointer; transition: transform 0.2s;"
                    onclick="window.location.href='<?php echo e(route('profesor.detalle-estudiante', ['id' => $estudiante->Id_estudiantes, 'source' => $tipo])); ?>'"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='none'"
                    data-name="<?php echo e(strtolower($estudiante->persona?->Nombre ?? '')); ?> <?php echo e(strtolower($estudiante->persona?->Apellido ?? '')); ?>"
                >
                    <div class="d-flex align-items-center gap-3">
                        
                        <div class="flex-shrink-0">
                            <img 
                                src="<?php echo e(auto_asset('img/' . ($estudiante->persona?->Genero === 'M' ? 'boy.png' : 'girl.png'))); ?>" 
                                alt="<?php echo e($estudiante->persona?->Nombre ?? 'Estudiante'); ?>"
                                class="rounded-circle bg-light p-1"
                                style="width: 60px; height: 60px; object-fit: cover;"
                            >
                        </div>

                        
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="mb-1 fw-bold text-dark text-wrap" style="word-break: break-word;">
                                <?php echo e($estudiante->persona?->Nombre); ?> <?php echo e($estudiante->persona?->Apellido); ?>

                            </h5>
                            <p class="mb-1 text-muted small text-truncate">
                                <i class="bi bi-mortarboard-fill me-1 text-primary"></i>
                                <?php echo e($estudiante->programa?->Nombre ?? 'Sin programa'); ?>

                            </p>
                            <?php if($estudiante->horarios && $estudiante->horarios->first()): ?>
                                <?php
                                    $horario = $estudiante->horarios->first();
                                ?>
                                <p class="mb-0 text-muted small text-truncate">
                                    <i class="bi bi-clock-fill me-1 text-warning"></i>
                                    <?php echo e($horario->Dia); ?>: <?php echo e($horario->Hora); ?>

                                </p>
                            <?php endif; ?>
                        </div>

                        
                        <div class="flex-shrink-0 text-muted">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-3 mb-3 d-block"></i>
                    <p class="h5">No hay estudiantes disponibles</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo e(auto_asset('js/profesor/listadoAlumnos.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/profesor/listadoAlumnos.blade.php ENDPATH**/ ?>