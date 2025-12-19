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

                    <?php if(isset($esRecuperatoria) && $esRecuperatoria): ?>
                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7em; vertical-align: middle;">
                            <i class="bi bi-calendar-event me-1"></i>CLASE RECUPERATORIA
                        </span>
                    <?php endif; ?>
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
                    <button id="btn-evaluar-recuperatoria" class="btn-evaluate" onclick="window.location.href='<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>?modelo_id=' + document.querySelector('.model-select').value">
                        <i class="bi bi-clipboard-check me-1"></i><span id="txt-evaluar-recuperatoria">Evaluar y Finalizar</span>
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
    
    // Elementos del botón normal
    const actionBtnNormal = document.getElementById('btn-evaluar-accion');
    const actionTxtNormal = document.getElementById('txt-evaluar-accion');
    const actionIconNormal = actionBtnNormal ? actionBtnNormal.querySelector('i') : null;

    // Elementos del botón recuperatorio
    const actionBtnRecup = document.getElementById('btn-evaluar-recuperatoria');
    const actionTxtRecup = document.getElementById('txt-evaluar-recuperatoria');
    const actionIconRecup = actionBtnRecup ? actionBtnRecup.querySelector('i') : null;

    // Lista de modelos ya evaluados pasados desde el controlador
    const modelosEvaluados = <?php echo json_encode($modelosEvaluados ?? [], 15, 512) ?>;
    const baseEvalUrl = "<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>";

    function updateActionButton() {
        const selectedModel = modelSelect.value;
        const isEvaluated = modelosEvaluados.includes(parseInt(selectedModel));

        // ACTUALIZAR BOTÓN NORMAL (Si existe)
        if (actionBtnNormal) {
            actionBtnNormal.href = `${baseEvalUrl}?modelo_id=${selectedModel}`;
            
            if (isEvaluated) {
                // Modo Editar
                actionBtnNormal.style.backgroundColor = '#f39c12'; // Orange
                actionTxtNormal.textContent = "Editar Evaluación";
                if(actionIconNormal) actionIconNormal.className = 'bi bi-pencil-square me-1';
            } else {
                // Modo Evaluar (Crear)
                actionBtnNormal.style.backgroundColor = ''; // Default (usually generic blue/green from CSS)
                actionTxtNormal.textContent = "Evaluar Estudiante";
                if(actionIconNormal) actionIconNormal.className = 'bi bi-clipboard-plus me-1';
            }

            toggleDisable(actionBtnNormal, selectedModel);
        }

        // ACTUALIZAR BOTÓN RECUPERATORIO (Si existe)
        if (actionBtnRecup) {
            // El onclick ya obtiene el valor de forma dinámica
            
            if (isEvaluated) {
                // Si ya está evaluado, cambiamos a modo Editar aunque sea recuperatorio
                actionBtnRecup.style.backgroundColor = '#f39c12';
                actionTxtRecup.textContent = "Editar Evaluación";
                if(actionIconRecup) actionIconRecup.className = 'bi bi-pencil-square me-1';
            } else {
                // Si no, mantenemos el modo "Evaluar y Finalizar"
                actionBtnRecup.style.backgroundColor = '';
                actionTxtRecup.textContent = "Evaluar y Finalizar";
                if(actionIconRecup) actionIconRecup.className = 'bi bi-clipboard-check me-1';
            }

            toggleDisable(actionBtnRecup, selectedModel);
        }
    }
    
    function toggleDisable(btn, model) {
        if (!model) {
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.6';
        } else {
            btn.classList.remove('disabled');
            btn.style.pointerEvents = 'auto';
            btn.style.opacity = '1';
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