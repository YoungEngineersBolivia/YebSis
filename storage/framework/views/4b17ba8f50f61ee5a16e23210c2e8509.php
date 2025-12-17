

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center gap-3 mb-3">
                <a href="<?php echo e(route('profesor.home')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
            <h2 class="text-uppercase fw-bold text-dark">
                <i class="bi bi-calendar-check me-2"></i> Registro de Asistencia
            </h2>
            <p class="text-muted mb-0">Profesor: <?php echo e($profesor->persona->Nombre); ?> <?php echo e($profesor->persona->Apellido); ?></p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <strong>Error:</strong> Por favor revise el formulario.
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="<?php echo e(route('profesor.asistencia.store')); ?>" method="POST" id="asistenciaForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="profesor_id" value="<?php echo e($profesor->Id_profesores); ?>">
                
                <div class="row mb-4 align-items-end">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="fecha" class="form-label fw-bold">Fecha de Clase</label>
                        <input type="date" name="fecha" id="fecha" class="form-control form-control-lg" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto">
                            <i class="bi bi-save me-2"></i> Guardar Asistencia
                        </button>
                    </div>
                </div>

                
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%">Estudiante</th>
                                    <th style="width: 15%">Programa</th>
                                    <th style="width: 35%">Estado</th>
                                    <th style="width: 25%">Detalles / Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?></div>
                                            <small class="text-muted"><?php echo e($estudiante->Cod_estudiante); ?></small>
                                            <input type="hidden" name="programa_id[<?php echo e($estudiante->Id_estudiantes); ?>]" value="<?php echo e($estudiante->Id_programas); ?>">
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo e($estudiante->programa->Nombre ?? 'Sin programa'); ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check desktop-input" 
                                                    name="asistencia[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                    id="asistio_desktop_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                    value="Asistio" 
                                                    checked
                                                    data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                    onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Asistio', 'desktop')">
                                                <label class="btn btn-outline-success btn-sm" for="asistio_desktop_<?php echo e($estudiante->Id_estudiantes); ?>">Asistió</label>

                                                <input type="radio" class="btn-check desktop-input" 
                                                    name="asistencia[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                    id="falta_desktop_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                    value="Falta"
                                                    data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                    onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Falta', 'desktop')">
                                                <label class="btn btn-outline-danger btn-sm" for="falta_desktop_<?php echo e($estudiante->Id_estudiantes); ?>">Falta</label>

                                                <input type="radio" class="btn-check desktop-input" 
                                                    name="asistencia[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                    id="licencia_desktop_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                    value="Licencia"
                                                    data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                    onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Licencia', 'desktop')">
                                                <label class="btn btn-outline-warning btn-sm" for="licencia_desktop_<?php echo e($estudiante->Id_estudiantes); ?>">Licencia</label>

                                                <input type="radio" class="btn-check desktop-input" 
                                                    name="asistencia[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                    id="reprogramado_desktop_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                    value="Reprogramado"
                                                    data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                    onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Reprogramado', 'desktop')">
                                                <label class="btn btn-outline-info btn-sm" for="reprogramado_desktop_<?php echo e($estudiante->Id_estudiantes); ?>">Reprog.</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                name="observacion[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                id="observacion_desktop_<?php echo e($estudiante->Id_estudiantes); ?>"
                                                class="form-control form-control-sm mb-2" 
                                                placeholder="Observación (opcional)"
                                                oninput="syncText(<?php echo e($estudiante->Id_estudiantes); ?>, 'observacion', 'desktop')">
                                            
                                            <div id="reprogramacion_div_desktop_<?php echo e($estudiante->Id_estudiantes); ?>" style="display: none;">
                                                <label class="form-label small text-info fw-bold">Nueva Fecha:</label>
                                                <input type="date" 
                                                    name="fecha_reprogramada[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                    id="fecha_reprogramada_desktop_<?php echo e($estudiante->Id_estudiantes); ?>"
                                                    class="form-control form-control-sm border-info"
                                                    onchange="syncText(<?php echo e($estudiante->Id_estudiantes); ?>, 'fecha_reprogramada', 'desktop')">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="d-lg-none">
                    <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?></h6>
                                        <small class="text-muted"><?php echo e($estudiante->Cod_estudiante); ?></small>
                                    </div>
                                    <span class="badge bg-secondary"><?php echo e($estudiante->programa->Nombre ?? 'Sin programa'); ?></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small mb-2">Estado de Asistencia:</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="radio" class="btn-check mobile-input" 
                                                name="asistencia_mobile[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                id="asistio_mobile_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                value="Asistio" 
                                                checked
                                                data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Asistio', 'mobile')">
                                            <label class="btn btn-outline-success w-100" for="asistio_mobile_<?php echo e($estudiante->Id_estudiantes); ?>">Asistió</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check mobile-input" 
                                                name="asistencia_mobile[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                id="falta_mobile_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                value="Falta"
                                                data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Falta', 'mobile')">
                                            <label class="btn btn-outline-danger w-100" for="falta_mobile_<?php echo e($estudiante->Id_estudiantes); ?>">Falta</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check mobile-input" 
                                                name="asistencia_mobile[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                id="licencia_mobile_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                value="Licencia"
                                                data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Licencia', 'mobile')">
                                            <label class="btn btn-outline-warning w-100" for="licencia_mobile_<?php echo e($estudiante->Id_estudiantes); ?>">Licencia</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check mobile-input" 
                                                name="asistencia_mobile[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                                id="reprogramado_mobile_<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                value="Reprogramado"
                                                data-student-id="<?php echo e($estudiante->Id_estudiantes); ?>"
                                                onchange="syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Reprogramado', 'mobile')">
                                            <label class="btn btn-outline-info w-100" for="reprogramado_mobile_<?php echo e($estudiante->Id_estudiantes); ?>">Reprogramado</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Observación:</label>
                                    <input type="text" 
                                        name="observacion_mobile[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                        id="observacion_mobile_<?php echo e($estudiante->Id_estudiantes); ?>"
                                        class="form-control" 
                                        placeholder="Observación (opcional)"
                                        oninput="syncText(<?php echo e($estudiante->Id_estudiantes); ?>, 'observacion', 'mobile')">
                                </div>

                                <div id="reprogramacion_div_mobile_<?php echo e($estudiante->Id_estudiantes); ?>" style="display: none;">
                                    <label class="form-label small text-info fw-bold">Nueva Fecha:</label>
                                    <input type="date" 
                                        name="fecha_reprogramada_mobile[<?php echo e($estudiante->Id_estudiantes); ?>]" 
                                        id="fecha_reprogramada_mobile_<?php echo e($estudiante->Id_estudiantes); ?>"
                                        class="form-control border-info"
                                        onchange="syncText(<?php echo e($estudiante->Id_estudiantes); ?>, 'fecha_reprogramada', 'mobile')">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($estudiantes->isEmpty()): ?>
                    <div class="text-center py-5">
                        <p class="text-muted lead">No hay estudiantes activos asignados a este profesor.</p>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<script>
    // Sincronizar inputs entre vista Desktop y Mobile
    function syncInputs(estudianteId, valor, source) {
        const target = source === 'desktop' ? 'mobile' : 'desktop';
        
        // IDs generados
        const targetRadioId = valor.toLowerCase() + '_' + target + '_' + estudianteId;
        const targetRadio = document.getElementById(targetRadioId);
        
        if (targetRadio) {
            targetRadio.checked = true;
        }

        // Manejar visibilidad de reprogramación en AMBOS
        toggleReprogramacion(estudianteId, valor === 'Reprogramado', 'desktop');
        toggleReprogramacion(estudianteId, valor === 'Reprogramado', 'mobile');
    }

    function syncText(estudianteId, fieldName, source) {
        const target = source === 'desktop' ? 'mobile' : 'desktop';
        const sourceId = fieldName + '_' + source + '_' + estudianteId;
        const targetId = fieldName + '_' + target + '_' + estudianteId;
        
        const sourceElem = document.getElementById(sourceId);
        const targetElem = document.getElementById(targetId);
        
        if (sourceElem && targetElem) {
            targetElem.value = sourceElem.value;
        }
    }

    function toggleReprogramacion(estudianteId, show, viewType) {
        const divId = 'reprogramacion_div_' + viewType + '_' + estudianteId;
        const inputId = 'fecha_reprogramada_' + viewType + '_' + estudianteId;
        
        const div = document.getElementById(divId);
        const input = document.getElementById(inputId);
        
        if (div) {
            div.style.display = show ? 'block' : 'none';
            if (show) {
                // Solo feedback visual, sin required nativo para evitar bloqueos
                if(div.offsetParent !== null) {
                   // Visible
                }
            } else {
                input.value = ''; // Limpiar si cambia de estado
            }
        }
    }

    // Inicializar estado al cargar (por si hay old inputs o default checks)
    document.addEventListener('DOMContentLoaded', function() {
        <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            // Sincronizar estado inicial basado en Desktop (que es el "master" por defecto en HTML)
            // Buscamos cuál está checkeado en desktop
            const checkedDesktop_<?php echo e($estudiante->Id_estudiantes); ?> = document.querySelector(`input[name="asistencia[<?php echo e($estudiante->Id_estudiantes); ?>]"]:checked`);
            if (checkedDesktop_<?php echo e($estudiante->Id_estudiantes); ?>) {
                syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, checkedDesktop_<?php echo e($estudiante->Id_estudiantes); ?>.value, 'desktop');
            } else {
                 // Fallback si nada está checkeado
                 syncInputs(<?php echo e($estudiante->Id_estudiantes); ?>, 'Asistio', 'desktop');
            }
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/asistenciaProfesor.blade.php ENDPATH**/ ?>