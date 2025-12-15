<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/profesor/detallesEstudiante.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="student-detail-container">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="<?php echo e(route('profesor.listado-alumnos', ['tipo' => request('source', 'asignados')])); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <input 
        type="text" 
        class="search-box-detail" 
        placeholder="Codigo o nombre del Estudiante"
    >

    <div class="student-card">
        <div class="student-left">
            <img src="<?php echo e(auto_asset('img/' . (($estudiante->genero ?? 'M') == 'M' ? 'boy.png' : 'girl.png'))); ?>" 
     alt="Estudiante" class="student-detail-avatar">

            <div class="student-code">
                Código del<br>estudiante <br> <b><?php echo e($estudiante->Cod_estudiante ?? 'Sin código'); ?><b>
            </div>

        </div>
        
        <div class="student-right">
            <div class="student-detail-info">
                <div class="info-label">Nombre del Estudiante</div>
                <div class="info-value">
                    <?php echo e($estudiante->full_name); ?>

                </div>


            </div>
            
            <div class="student-detail-info">
                <div class="info-label">Programa</div>
                <div class="info-value"><?php echo e($estudiante->programa->Nombre ?? 'Programa'); ?></div>
            </div>
            
            
            <div class="student-detail-info">
                <div class="info-label">Horario</div>
                <?php
                    $horario = $estudiante->horarios->first(); // toma el primer horario
                ?>

                <?php if($horario): ?>
                    <div class="info-value">
                        <?php echo e($horario->Dia); ?>: <?php echo e($horario->Hora); ?>

                    </div>
                <?php else: ?>
                    <div class="info-value">Sin horario asignado</div>
                <?php endif; ?>
            </div>

            <div class="model-select-wrapper">
                <span class="model-label">Modelo</span>
                <select name="modelo" class="model-select">
                    <option value="">Seleccione...</option>
                    <?php if($estudiante->programa && $estudiante->programa->modelos): ?>
                        <?php $__currentLoopData = $estudiante->programa->modelos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($modelo->Id_modelos); ?>" <?php echo e(($estudiante->Id_modelo ?? '') == $modelo->Id_modelos ? 'selected' : ''); ?>>
                                <?php echo e($modelo->Nombre_modelo); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="action-buttons">
                <?php if(isset($esRecuperatoria) && $esRecuperatoria): ?>
                    <button class="btn-evaluate" onclick="window.location.href='<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>'">
                        <i class="bi bi-clipboard-check me-1"></i>Evaluar y Finalizar
                    </button>
                <?php elseif(isset($yaEvaluado) && $yaEvaluado): ?>
                    <button class="btn-evaluate" style="background-color: #f39c12;" onclick="window.location.href='<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>'">
                        <i class="bi bi-pencil-square me-1"></i>Editar Evaluación
                    </button>
                <?php else: ?>
                    <button class="btn-evaluate" onclick="window.location.href='<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>'">
                        <i class="bi bi-clipboard-plus me-1"></i>Evaluar Estudiante
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/detalleEstudiante.blade.php ENDPATH**/ ?>