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
                    <button class="btn-evaluate" onclick="window.location.href='<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>?modelo_id=' + document.querySelector('.model-select').value">
                        <i class="bi bi-clipboard-check me-1"></i>Evaluar y Finalizar
                    </button>
                <?php else: ?>
                    <a id="btn-evaluar-accion" href="#" class="btn-evaluate">
                        <i class="bi bi-clipboard-plus me-1"></i><span id="txt-evaluar-accion">Evaluar Estudiante</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modelSelect = document.querySelector('.model-select');
    const actionBtn = document.getElementById('btn-evaluar-accion');
    const actionTxt = document.getElementById('txt-evaluar-accion');
    const actionIcon = actionBtn ? actionBtn.querySelector('i') : null;
    
    // Lista de modelos ya evaluados pasados desde el controlador
    const modelosEvaluados = <?php echo json_encode($modelosEvaluados ?? [], 15, 512) ?>;
    const baseEvalUrl = "<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>";

    function updateActionButton() {
        if (!actionBtn) return; // Si estamos en modo recuperatorio, el botón es diferente

        const selectedModel = modelSelect.value;
        const isEvaluated = modelosEvaluados.includes(parseInt(selectedModel));

        // Actualizar URL
        actionBtn.href = `${baseEvalUrl}?modelo_id=${selectedModel}`;

        if (isEvaluated) {
            // Modo Editar
            actionBtn.style.backgroundColor = '#f39c12'; // Orange
            actionTxt.textContent = "Editar Evaluación";
            if(actionIcon) {
                actionIcon.className = 'bi bi-pencil-square me-1';
            }
        } else {
            // Modo Evaluar (Crear)
            actionBtn.style.backgroundColor = ''; // Default (usually generic blue/green from CSS)
            actionTxt.textContent = "Evaluar Estudiante";
            if(actionIcon) {
                actionIcon.className = 'bi bi-clipboard-plus me-1';
            }
        }
        
        // Validación opcional: Deshabilitar si no hay modelo seleccionado
        if (!selectedModel) {
            actionBtn.classList.add('disabled');
            actionBtn.style.pointerEvents = 'none';
            actionBtn.style.opacity = '0.6';
        } else {
            actionBtn.classList.remove('disabled');
            actionBtn.style.pointerEvents = 'auto';
            actionBtn.style.opacity = '1';
        }
    }

    if (modelSelect) {
        modelSelect.addEventListener('change', updateActionButton);
        // Ejecutar al inicio para establecer estado correcto
        updateActionButton();
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/profesor/detalleEstudiante.blade.php ENDPATH**/ ?>