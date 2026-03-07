<?php $__env->startSection('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/profesor/detallesEstudiante.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="student-detail-container">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="<?php echo e(route('profesor.listado-alumnos', ['tipo' => request('source', 'asignados')])); ?>"
                class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>

        <input type="text" class="search-box-detail" placeholder="Codigo o nombre del Estudiante">

        <div class="student-card">
            <div class="student-left">
                <img src="<?php echo e(auto_asset('img/' . (($estudiante->genero ?? 'M') == 'M' ? 'boy.png' : 'girls.png'))); ?>"
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
                                    <?php echo e($modelo->Nombre_modelo); ?> <?php if(in_array($modelo->Id_modelos, $modelosEvaluados)): ?> ✅ <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="action-buttons">
                    <?php if(isset($esRecuperatoria) && $esRecuperatoria): ?>
                        <button id="btn-evaluar-recuperatoria" class="btn-evaluate"
                            onclick="window.location.href='<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>?modelo_id=' + document.querySelector('.model-select').value">
                            <i class="bi bi-clipboard-check me-1"></i><span id="txt-evaluar-recuperatoria">Evaluar y
                                Finalizar</span>
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
        document.addEventListener('DOMContentLoaded', function () {
            const modelSelect = document.querySelector('.model-select');
            
            // Elementos del botón normal
            const actionBtnNormal = document.getElementById('btn-evaluar-accion');
            const actionTxtNormal = document.getElementById('txt-evaluar-accion');
            
            // Elementos del botón recuperatorio
            const actionBtnRecup = document.getElementById('btn-evaluar-recuperatoria');
            const actionTxtRecup = document.getElementById('txt-evaluar-recuperatoria');

            // Asegurar que modelosEvaluados sea un Array (a veces Laravel envía objetos si los índices no son secuenciales)
            let modelosEvaluadosRaw = <?php echo json_encode($modelosEvaluados ?? [], 15, 512) ?>;
            const modelosEvaluados = Array.isArray(modelosEvaluadosRaw) ? modelosEvaluadosRaw : Object.values(modelosEvaluadosRaw);

            const baseEvalUrl = "<?php echo e(route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes)); ?>";

            function updateActionButton() {
                const selectedModel = modelSelect.value;
                
                // Buscar si el modelo seleccionado está en el array de evaluados
                const isEvaluated = selectedModel && modelosEvaluados.some(id => parseInt(id) === parseInt(selectedModel));
                
                console.log('Modelo:', selectedModel, '¿Evaluado?:', isEvaluated, 'Evaluados:', modelosEvaluados);

                // ACTUALIZAR BOTÓN NORMAL
                if (actionBtnNormal) {
                    actionBtnNormal.href = `${baseEvalUrl}?modelo_id=${selectedModel}`;
                    const icon = actionBtnNormal.querySelector('i');

                    if (isEvaluated) {
                        actionBtnNormal.style.setProperty('background-color', '#f39c12', 'important');
                        actionBtnNormal.style.setProperty('box-shadow', '0 4px 12px rgba(243, 156, 18, 0.3)', 'important');
                        actionTxtNormal.textContent = "Editar Evaluación";
                        if (icon) icon.className = 'bi bi-pencil-square me-1';
                    } else {
                        actionBtnNormal.style.removeProperty('background-color');
                        actionBtnNormal.style.removeProperty('box-shadow');
                        actionTxtNormal.textContent = "Evaluar Estudiante";
                        if (icon) icon.className = 'bi bi-clipboard-plus me-1';
                    }
                    toggleDisable(actionBtnNormal, selectedModel);
                }

                // ACTUALIZAR BOTÓN RECUPERATORIO
                if (actionBtnRecup) {
                    const icon = actionBtnRecup.querySelector('i');
                    if (isEvaluated) {
                        actionBtnRecup.style.setProperty('background-color', '#f39c12', 'important');
                        actionBtnRecup.style.setProperty('box-shadow', '0 4px 12px rgba(243, 156, 18, 0.3)', 'important');
                        actionTxtRecup.textContent = "Editar Evaluación";
                        if (icon) icon.className = 'bi bi-pencil-square me-1';
                    } else {
                        actionBtnRecup.style.removeProperty('background-color');
                        actionBtnRecup.style.removeProperty('box-shadow');
                        actionTxtRecup.textContent = "Evaluar y Finalizar";
                        if (icon) icon.className = 'bi bi-clipboard-check me-1';
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
                updateActionButton();
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/profesor/detalleEstudiante.blade.php ENDPATH**/ ?>