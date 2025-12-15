<?php $__env->startSection('title', 'Gestión de Preguntas'); ?>

<?php $__env->startSection('styles'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #6366f1;
        --secondary-color: #8b5cf6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --dark-color: #1f2937;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold"><i class="fas fa-question-circle me-2"></i>Preguntas de <?php echo e($programa->Nombre); ?></h2>
            <p class="text-muted mb-0">Gestiona las preguntas de evaluación para este programa</p>
        </div>
        <div>
            <a href="<?php echo e(route('programas.index')); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaPreguntaModal">
                <i class="fas fa-plus me-2"></i>Nueva Pregunta
            </button>
        </div>
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

    <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <?php if($preguntas->isEmpty()): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-question-circle text-muted mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-muted">No hay preguntas registradas</h5>
                <p class="text-muted">Comienza agregando la primera pregunta para este programa</p>
                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#nuevaPreguntaModal">
                    <i class="fas fa-plus me-2"></i>Agregar Primera Pregunta
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-3 py-3" style="width: 60px;">#</th>
                                <th class="py-3">Pregunta</th>
                                <th class="py-3" style="width: 150px;">Fecha Creación</th>
                                <th class="pe-3 py-3 text-end" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $preguntas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pregunta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-3 text-muted fw-bold"><?php echo e($index + 1); ?></td>
                                <td class="fw-semibold"><?php echo e($pregunta->Pregunta); ?></td>
                                <td class="text-muted">
                                    <i class="far fa-calendar me-1"></i>
                                    <?php echo e($pregunta->created_at->format('d/m/Y')); ?>

                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editarPreguntaModal<?php echo e($pregunta->Id_preguntas); ?>"
                                                title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <form action="<?php echo e(route('admin.preguntas.destroy', [$programa->Id_programas, $pregunta->Id_preguntas])); ?>" 
                                                method="POST" 
                                                style="display:inline;"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="nuevaPreguntaModal" tabindex="-1" aria-labelledby="nuevaPreguntaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaPreguntaLabel">
                    <i class="fas fa-plus-circle me-2"></i>Nueva Pregunta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.preguntas.store', $programa->Id_programas)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-question-circle me-1"></i>Pregunta
                        </label>
                        <textarea class="form-control" 
                                  name="pregunta" 
                                  rows="3" 
                                  placeholder="Ej: ¿Participa activamente en clases?"
                                  required></textarea>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Las respuestas serán: Sí, No, En proceso
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Guardar Pregunta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__currentLoopData = $preguntas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pregunta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editarPreguntaModal<?php echo e($pregunta->Id_preguntas); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar Pregunta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.preguntas.update', [$programa->Id_programas, $pregunta->Id_preguntas])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-question-circle me-1"></i>Pregunta
                        </label>
                        <textarea class="form-control"
                                  name="pregunta" 
                                  rows="3" 
                                  required><?php echo e($pregunta->Pregunta); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('/administrador/baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/preguntasPrograma.blade.php ENDPATH**/ ?>