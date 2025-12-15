<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/listadoAlumnos.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="students-container">
    
    <div class="header-section">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="<?php echo e(route('profesor.menu-alumnos')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
        <h2 class="page-title">
            <?php if($tipo === 'evaluar'): ?>
                <i class="bi bi-clipboard-check"></i> Evaluar Estudiantes
            <?php elseif($tipo === 'asignados'): ?>
                <i class="bi bi-people-fill"></i> Alumnos Asignados
            <?php else: ?>
                <i class="bi bi-calendar-event"></i> Clase Recuperatoria
            <?php endif; ?>
        </h2>
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input 
                type="text" 
                class="search-box" 
                placeholder="Buscar por nombre..."
                id="searchInput"
            >
        </div>
        <div class="students-count">
            <span class="count-badge"><?php echo e($estudiantes->count()); ?></span> estudiantes
        </div>
    </div>

    
    <div class="students-grid" id="studentsList">
        <?php if(isset($estudiantes) && $estudiantes->count() > 0): ?>
            <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div 
                class="student-card" 
                onclick="window.location.href='<?php echo e(route('profesor.detalle-estudiante', $estudiante->Id_estudiantes)); ?>'"
                data-name="<?php echo e(strtolower($estudiante->persona?->Nombre ?? '')); ?> <?php echo e(strtolower($estudiante->persona?->Apellido ?? '')); ?>"
            >
                
                <div class="card-avatar">
                    <img 
                        src="<?php echo e(auto_asset('img/' . ($estudiante->persona?->Genero === 'M' ? 'boy.png' : 'girl.png'))); ?>" 
                        alt="<?php echo e($estudiante->persona?->Nombre ?? 'Estudiante'); ?>"
                    >
                </div>

                
                <div class="card-info">
                    <h3 class="student-name">
                        <?php echo e($estudiante->persona?->Nombre); ?> <?php echo e($estudiante->persona?->Apellido); ?>

                    </h3>
                    <p class="student-program">
                        <i class="bi bi-mortarboard-fill"></i>
                        <?php echo e($estudiante->programa?->Nombre ?? 'Sin programa'); ?>

                    </p>
                    <?php if($estudiante->horarios && $estudiante->horarios->first()): ?>
                        <?php
                            $horario = $estudiante->horarios->first();
                        ?>
                        <p class="student-schedule">
                            <i class="bi bi-clock-fill"></i>
                            <?php echo e($horario->Dia); ?>: <?php echo e($horario->Hora); ?>

                        </p>
                    <?php endif; ?>
                </div>

                
                <div class="card-arrow">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="no-students">
                <i class="bi bi-inbox"></i>
                <p>No hay estudiantes disponibles</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo e(auto_asset('js/profesor/listadoAlumnos.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/listadoAlumnos.blade.php ENDPATH**/ ?>