<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/evaluarAlumno.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="evaluation-container">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <h2 class="evaluation-title">
        <?php if($evaluacionesExistentes->count() > 0): ?>
            <i class="fas fa-edit me-2"></i>Editar Evaluación
        <?php else: ?>
            <i class="fas fa-clipboard-check me-2"></i>Evaluar Alumno
        <?php endif; ?>
    </h2>    
    
    
    <div class="student-info-banner">
        <div class="info-banner-item"><?php echo e($estudiante->persona->Nombre ?? 'Nombre Alumno'); ?> <?php echo e($estudiante->persona->Apellido ?? ''); ?></div>
        <div class="info-banner-item"><?php echo e($estudiante->programa->Nombre ?? 'Programa'); ?></div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Errores:</strong>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if($preguntas->isEmpty()): ?>
        
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>No hay preguntas configuradas</strong><br>
            Este programa no tiene preguntas de evaluación. Contacta al administrador para que las agregue.
        </div>
    <?php else: ?>
        <form action="<?php echo e(route('profesor.guardar-evaluacion')); ?>" method="POST" id="evaluationForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="estudiante_id" value="<?php echo e($estudiante->Id_estudiantes ?? ''); ?>">
            
            
            <div class="evaluation-section">
                <div class="section-title">
                    <i class="fas fa-cubes me-2"></i>Selecciona el Modelo
                </div>
                <select name="modelo_id" id="modelo_select" class="form-select" required>
                    <option value="">-- Selecciona un modelo --</option>
                    <?php $__currentLoopData = $modelos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($modelo->Id_modelos); ?>" 
                                <?php echo e($modeloSeleccionado == $modelo->Id_modelos ? 'selected' : ''); ?>>
                            <?php echo e($modelo->Nombre_modelo); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <?php $__currentLoopData = $preguntas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pregunta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Obtener respuesta guardada si existe
                    $evaluacionPrevia = $evaluacionesExistentes->get($pregunta->Id_preguntas);
                    $respuestaSeleccionada = $evaluacionPrevia->Id_respuestas ?? null;
                ?>
                <div class="evaluation-section">
                    <div class="section-title"><?php echo e($pregunta->Pregunta); ?></div>
                    <div class="option-buttons">
                        <?php $__currentLoopData = $respuestas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $respuesta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" 
                                class="option-btn <?php echo e($respuestaSeleccionada == $respuesta->Id_respuestas ? 'selected-' . ($respuesta->Id_respuestas == 1 ? 'si' : ($respuesta->Id_respuestas == 2 ? 'no' : 'proceso')) : ''); ?>" 
                                data-question="pregunta_<?php echo e($pregunta->Id_preguntas); ?>" 
                                data-value="<?php echo e($respuesta->Id_respuestas); ?>">
                            <?php echo e($respuesta->Respuesta); ?>

                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <input type="hidden" 
                           name="respuestas[<?php echo e($pregunta->Id_preguntas); ?>]" 
                           id="pregunta_<?php echo e($pregunta->Id_preguntas); ?>_value"
                           value="<?php echo e($respuestaSeleccionada); ?>">
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            
            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn-preview" id="btnPreview">
                    <i class="fas fa-eye me-2"></i>Vista Previa
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i>
                    <?php echo e($evaluacionesExistentes->count() > 0 ? 'Actualizar Evaluación' : 'Guardar Evaluación'); ?>

                </button>
            </div>
        </form>
    <?php endif; ?>
</div>


<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-eye me-2"></i>Vista Previa de Evaluación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Revisa las respuestas antes de guardar. Puedes cerrar esta ventana para editar.
                </div>
                
                <h6 class="fw-bold mb-3">Estudiante: <?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?></h6>
                <h6 class="fw-bold mb-3">Programa: <?php echo e($estudiante->programa->Nombre); ?></h6>
                <h6 class="fw-bold mb-4">Modelo: <span id="preview-modelo-nombre"></span></h6>

                <h6 class="fw-bold mb-3">Respuestas:</h6>
                <div id="preview-respuestas" class="list-group">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-edit me-2"></i>Volver a Editar
                </button>
                <button type="button" class="btn btn-success" id="btnConfirmarGuardar">
                    <i class="fas fa-check me-2"></i>Confirmar y Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(auto_asset('js/profesor/evaluarAlumno.js')); ?>"></script>

<script>
// Script para vista previa
document.addEventListener('DOMContentLoaded', () => {
    const btnPreview = document.getElementById('btnPreview');
    const btnConfirmar = document.getElementById('btnConfirmarGuardar');
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    const form = document.getElementById('evaluationForm');

    // Datos de preguntas y respuestas
    const preguntas = <?php echo json_encode($preguntas, 15, 512) ?>;
    const respuestas = <?php echo json_encode($respuestas, 15, 512) ?>;
    const modelos = <?php echo json_encode($modelos, 15, 512) ?>;

    btnPreview.addEventListener('click', () => {
        // Validar que se seleccionó modelo
        const modeloSelect = document.getElementById('modelo_select');
        if (!modeloSelect.value) {
            alert('Por favor, selecciona un modelo antes de continuar');
            return;
        }

        // Validar que todas las preguntas tienen respuesta
        let todasRespondidas = true;
        const hiddenInputs = document.querySelectorAll('input[type="hidden"][name^="respuestas"]');
        hiddenInputs.forEach(input => {
            if (!input.value) {
                todasRespondidas = false;
            }
        });

        if (!todasRespondidas) {
            alert('Por favor, responde todas las preguntas antes de continuar');
            return;
        }

        // Generar vista previa
        generarVistaPrevia(modeloSelect.value, hiddenInputs);
        previewModal.show();
    });

    btnConfirmar.addEventListener('click', () => {
        form.submit();
    });

    function generarVistaPrevia(modeloId, hiddenInputs) {
        // Mostrar nombre del modelo
        const modeloNombre = modelos.find(m => m.Id_modelos == modeloId)?.Nombre_modelo || 'Desconocido';
        document.getElementById('preview-modelo-nombre').textContent = modeloNombre;

        // Generar lista de respuestas
        const previewContainer = document.getElementById('preview-respuestas');
        previewContainer.innerHTML = '';

        hiddenInputs.forEach(input => {
            const preguntaId = input.name.match(/\[(\d+)\]/)[1];
            const respuestaId = parseInt(input.value);
            
            const pregunta = preguntas.find(p => p.Id_preguntas == preguntaId);
            const respuesta = respuestas.find(r => r.Id_respuestas == respuestaId);

            if (pregunta && respuesta) {
                const colorClass = respuestaId == 1 ? 'success' : (respuestaId == 2 ? 'danger' : 'warning');
                const item = `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>${pregunta.Pregunta}</strong>
                            </div>
                            <span class="badge bg-${colorClass}">${respuesta.Respuesta}</span>
                        </div>
                    </div>
                `;
                previewContainer.innerHTML += item;
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/evaluarAlumno.blade.php ENDPATH**/ ?>