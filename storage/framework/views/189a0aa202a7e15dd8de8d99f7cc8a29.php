<?php $__env->startSection('title', 'Estudiantes'); ?>

<?php $__env->startSection('styles'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo e(auto_asset('css/administrador/estudiantesAdministrador.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Lista de Estudiantes</h2>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('estudiantes.exportarPDF')); ?>" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i>Exportar PDF
            </a>
            <a href="<?php echo e(route('registroCombinado.registrar')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Registrar Estudiante
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form action="<?php echo e(route('estudiantes.index')); ?>" method="GET">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">Buscar Estudiante</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Filtrar por código, nombre o apellido..." name="search" value="<?php echo e(request()->search); ?>" data-table-filter="studentsTable">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($estudiantes->isEmpty()): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-muted">No hay estudiantes registrados</h5>
                <p class="text-muted mb-4">Comienza registrando un nuevo estudiante en el sistema.</p>
                <a href="<?php echo e(route('registroCombinado.registrar')); ?>" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Registrar el primero
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="studentsTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-3 py-3" style="min-width:120px;">Código</th>
                                <th class="py-3" style="min-width:220px;">Nombre</th>
                                <th class="py-3" style="min-width:180px;">Programa</th>
                                <th class="py-3" style="min-width:160px;">Sucursal</th>
                                <th class="py-3" style="min-width:120px;">Estado</th>
                                <th class="pe-3 py-3 text-end" style="min-width:140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $personaEst = $estudiante->persona ?? null;
                                    $nombreCompletoEst = $personaEst ? trim(($personaEst->Nombre ?? '').' '.($personaEst->Apellido ?? '')) : null;

                                    $programa = $estudiante->programa->Nombre ?? 'Sin programa';
                                    $sucursal = $estudiante->sucursal->Nombre ?? 'Sin sucursal';

                                    $prof = $estudiante->profesor ?? null;
                                    $profPersona = $prof?->persona ?? null;
                                    $profesorNombre = $profPersona
                                        ? trim(($profPersona->Nombre ?? '').' '.($profPersona->Apellido ?? ''))
                                        : ($prof->Nombre ?? null);
                                    $profesorNombre = $profesorNombre ?: 'Sin profesor';
                                    
                                    $estadoLower = Str::lower($estudiante->Estado ?? '');
                                    $esActivo = $estadoLower === 'activo';
                                ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-primary"><?php echo e($estudiante->Cod_estudiante); ?></td>
                                    <td class="fw-semibold"><?php echo e($nombreCompletoEst ?: 'Sin datos'); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo e($programa); ?></span></td>
                                    <td><?php echo e($sucursal); ?></td>
                                    <td>
                                        <?php if($esActivo): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Activo</span>
                                        <?php elseif($estadoLower === 'inactivo'): ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Inactivo</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><?php echo e($estudiante->Estado); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editarModal<?php echo e($estudiante->Id_estudiantes); ?>"
                                                    data-bs-toggle="tooltip" 
                                                    title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            
                                            <a href="<?php echo e(route('estudiantes.ver', $estudiante->Id_estudiantes ?? 0)); ?>" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip"
                                               title="Ver perfil">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
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

    
    <?php $__currentLoopData = $estudiantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $persona = $estudiante->persona ?? null;
    ?>
    <div class="modal fade" id="editarModal<?php echo e($estudiante->Id_estudiantes); ?>" tabindex="-1" aria-labelledby="editarModalLabel<?php echo e($estudiante->Id_estudiantes); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editarModalLabel<?php echo e($estudiante->Id_estudiantes); ?>">
                        <i class="bi bi-pencil-square me-2"></i>Editar Estudiante
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="<?php echo e(route('estudiantes.actualizar', $estudiante->Id_estudiantes)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Código estudiante -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="codigo<?php echo e($estudiante->Id_estudiantes); ?>">Código</label>
                                <input type="text" id="codigo<?php echo e($estudiante->Id_estudiantes); ?>" name="codigo_estudiante"
                                    class="form-control" value="<?php echo e($estudiante->Cod_estudiante); ?>" required>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="nombre<?php echo e($estudiante->Id_estudiantes); ?>">Nombre</label>
                                <input type="text" id="nombre<?php echo e($estudiante->Id_estudiantes); ?>" name="nombre"
                                    class="form-control" value="<?php echo e($persona->Nombre ?? ''); ?>" required>
                            </div>

                            <!-- Apellido -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="apellido<?php echo e($estudiante->Id_estudiantes); ?>">Apellido</label>
                                <input type="text" id="apellido<?php echo e($estudiante->Id_estudiantes); ?>" name="apellido"
                                    class="form-control" value="<?php echo e($persona->Apellido ?? ''); ?>" required>
                            </div>

                            <!-- Género -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="genero<?php echo e($estudiante->Id_estudiantes); ?>">Género</label>
                                <select class="form-select" name="genero" id="genero<?php echo e($estudiante->Id_estudiantes); ?>" required>
                                    <option value="M" <?php echo e($persona->Genero === 'M' ? 'selected' : ''); ?>>Masculino</option>
                                    <option value="F" <?php echo e($persona->Genero === 'F' ? 'selected' : ''); ?>>Femenino</option>
                                </select>
                            </div>

                            <!-- Fecha nacimiento -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="fecha_nacimiento<?php echo e($estudiante->Id_estudiantes); ?>">Fecha de nacimiento</label>
                                <input type="date" id="fecha_nacimiento<?php echo e($estudiante->Id_estudiantes); ?>" name="fecha_nacimiento"
                                    class="form-control" value="<?php echo e($persona->Fecha_nacimiento ?? ''); ?>" required>
                            </div>

                            <!-- Celular -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="celular<?php echo e($estudiante->Id_estudiantes); ?>">Celular</label>
                                <input type="text" id="celular<?php echo e($estudiante->Id_estudiantes); ?>" name="celular"
                                    class="form-control" value="<?php echo e($persona->Celular ?? ''); ?>" required>
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold" for="direccion<?php echo e($estudiante->Id_estudiantes); ?>">Dirección</label>
                                <input type="text" id="direccion<?php echo e($estudiante->Id_estudiantes); ?>" name="direccion_domicilio"
                                    class="form-control" value="<?php echo e($persona->Direccion_domicilio ?? ''); ?>" required>
                            </div>

                            <!-- Programa -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="programa<?php echo e($estudiante->Id_estudiantes); ?>">Programa</label>
                                <select class="form-select" id="programa<?php echo e($estudiante->Id_estudiantes); ?>" name="programa" required>
                                    <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($programa->Id_programas); ?>" 
                                            <?php echo e($programa->Id_programas == $estudiante->Id_programas ? 'selected' : ''); ?>>
                                            <?php echo e($programa->Nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Sucursal -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="sucursal<?php echo e($estudiante->Id_estudiantes); ?>">Sucursal</label>
                                <select class="form-select" id="sucursal<?php echo e($estudiante->Id_estudiantes); ?>" name="sucursal" required>
                                    <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sucursal->Id_sucursales); ?>" 
                                            <?php echo e($sucursal->Id_sucursales == $estudiante->Id_sucursales ? 'selected' : ''); ?>>
                                            <?php echo e($sucursal->Nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Tutor -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold" for="tutor<?php echo e($estudiante->Id_estudiantes); ?>">Tutor</label>
                                <select class="form-select" id="tutor<?php echo e($estudiante->Id_estudiantes); ?>" name="tutor_estudiante" required>
                                    <?php $__currentLoopData = $tutores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tutor->Id_tutores); ?>"
                                            <?php echo e($tutor->Id_tutores == $estudiante->Id_tutores ? 'selected' : ''); ?>>
                                            <?php echo e($tutor->persona->Nombre ?? ''); ?> <?php echo e($tutor->persona->Apellido ?? ''); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        </div> <!-- modal-body -->
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4 mb-4">
        <?php echo e($estudiantes->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Filtro de tabla en vivo
    // Filtro de tabla en vivo - MIGRADO A baseAdministrador.blade.php
    /*
    (function () {
        const input = document.querySelector('input[name="search"]');
        const table = document.getElementById('studentsTable');
        if (!input || !table) return;

        input.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    })();
    */

    // Auto-cerrar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/administrador/estudiantesAdministrador.blade.php ENDPATH**/ ?>