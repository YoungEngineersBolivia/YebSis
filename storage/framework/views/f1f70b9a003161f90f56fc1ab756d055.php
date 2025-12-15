<?php $__env->startSection('title', 'Graduados'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Estudiantes Graduados</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoGraduadoModal">
            <i class="bi bi-plus-lg me-2"></i>Añadir Graduado
        </button>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar">
        </div>
    </div>

    <?php if($graduados->isEmpty()): ?>
        <div class="alert alert-warning">No hay graduados registrados.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover" id="tablaGraduados">
                <thead>
                    <tr>
                        <th>Nombre Estudiante</th>
                        <th>Apellido Estudiante</th>
                        <th>Programa</th>
                        <th>Nombre Profesor</th>
                        <th>Apellido Profesor</th>
                        <th>Fecha Graduación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $graduados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $graduado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($graduado->estudiante->persona->Nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($graduado->estudiante->persona->Apellido ?? 'N/A'); ?></td>
                            <td><?php echo e($graduado->programa->Nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($graduado->profesor->persona->Nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($graduado->profesor->persona->Apellido ?? 'N/A'); ?></td>
                            <td><?php echo e($graduado->Fecha_graduado ? \Carbon\Carbon::parse($graduado->Fecha_graduado)->format('d/m/Y') : 'Sin fecha'); ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Editar -->
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarGraduadoModal<?php echo e($graduado->Id_graduado); ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Ver -->
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#verGraduadoModal<?php echo e($graduado->Id_graduado); ?>">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <!-- Eliminar -->
                                    <form action="<?php echo e(route('graduados.eliminar', $graduado->Id_graduado)); ?>" method="POST" onsubmit="return confirm('¿Eliminar este graduado?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar Graduado -->
                        <div class="modal fade" id="editarGraduadoModal<?php echo e($graduado->Id_graduado); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?php echo e(route('graduados.actualizar', $graduado->Id_graduado)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Graduado</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Estudiante</label>
                                                <select name="estudiante_id" class="form-select" required>
                                                    <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($estudiante->Id_estudiantes); ?>" 
                                                            <?php echo e($graduado->Id_estudiantes == $estudiante->Id_estudiantes ? 'selected' : ''); ?>>
                                                            <?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Programa</label>
                                                <select name="programa_id" class="form-select" required>
                                                    <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($programa->Id_programas); ?>" 
                                                            <?php echo e($graduado->Id_programas == $programa->Id_programas ? 'selected' : ''); ?>>
                                                            <?php echo e($programa->Nombre); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Profesor</label>
                                                <select name="profesor_id" class="form-select" required>
                                                    <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profesor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($profesor->Id_profesores); ?>" 
                                                            <?php echo e($graduado->Id_profesores == $profesor->Id_profesores ? 'selected' : ''); ?>>
                                                            <?php echo e($profesor->persona->Nombre); ?> <?php echo e($profesor->persona->Apellido); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Fecha de Graduación</label>
                                                <input type="date" name="Fecha_graduado" class="form-control" value="<?php echo e($graduado->Fecha_graduado); ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cerrar</button>
                                            <button class="btn btn-primary" type="submit">Actualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Ver Graduado -->
                        <div class="modal fade" id="verGraduadoModal<?php echo e($graduado->Id_graduado); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Perfil del Graduado</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Estudiante:</strong> <?php echo e($graduado->estudiante->persona->Nombre ?? ''); ?> <?php echo e($graduado->estudiante->persona->Apellido ?? ''); ?></p>
                                        <p><strong>Programa:</strong> <?php echo e($graduado->programa->Nombre ?? ''); ?></p>
                                        <p><strong>Profesor:</strong> <?php echo e($graduado->profesor->persona->Nombre ?? ''); ?> <?php echo e($graduado->profesor->persona->Apellido ?? ''); ?></p>
                                        <p><strong>Fecha de Graduación:</strong> <?php echo e($graduado->Fecha_graduado ? \Carbon\Carbon::parse($graduado->Fecha_graduado)->format('d/m/Y') : 'Sin fecha'); ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Añadir Graduado -->
<div class="modal fade" id="nuevoGraduadoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('graduados.agregar')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Graduado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Estudiante</label>
                        <select name="estudiante_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($estudiante->Id_estudiantes); ?>"><?php echo e($estudiante->persona->Nombre); ?> <?php echo e($estudiante->persona->Apellido); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Programa</label>
                        <select name="programa_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($programa->Id_programas); ?>"><?php echo e($programa->Nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Profesor</label>
                        <select name="profesor_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profesor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($profesor->Id_profesores); ?>"><?php echo e($profesor->persona->Nombre); ?> <?php echo e($profesor->persona->Apellido); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Fecha de Graduación</label>
                        <input type="date" name="Fecha_graduado" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('#tablaGraduados tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/graduadosAdministrador.blade.php ENDPATH**/ ?>