<?php $__env->startSection('title', 'Profesores'); ?>

<?php $__env->startSection('styles'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="<?php echo e(auto_asset('css/administrador/profesoresAdministrador.css')); ?>" rel="stylesheet">
<style>
    /* Estilos adicionales para mejorar la visualización */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Asegurar que las acciones tengan suficiente espacio */
    .actions-container {
        min-width: 160px;
        display: flex;
        justify-content: flex-end;
    }
    
    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
    }
    
    /* Ajustes para pantallas más pequeñas */
    @media (max-width: 768px) {
        .action-buttons {
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .action-buttons .btn {
            padding: 4px 8px;
            font-size: 0.875rem;
        }
    }
    
    /* Hacer el correo más compacto */
    .email-cell {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">

    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-person-workspace me-2"></i>Lista de Profesores</h2>
        <a href="<?php echo e(route('administrador.formProfesor')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Registrar Profesor
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form action="<?php echo e(route('profesores.index')); ?>" method="GET" class="w-100">
                <div class="row align-items-center">
                    <div class="col-12 col-sm-8 col-lg-6">
                        <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">Buscar Profesor</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input id="searchInput" type="text" class="form-control border-start-0 ps-0" placeholder="Filtrar por nombre, apellido o correo..." name="search" value="<?php echo e(request()->search); ?>" data-table-filter="teachersTable">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($profesores->isEmpty()): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-person-slash text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-muted">No hay profesores registrados.</h5>
                <a href="<?php echo e(route('administrador.formProfesor')); ?>" class="btn btn-primary mt-3">
                    <i class="fas fa-user-plus me-2"></i>Registrar el primero
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="teachersTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-3 py-3">Nombre</th>
                                <th class="py-3">Apellido</th>
                                <th class="py-3">Teléfono</th>
                                <th class="py-3">Correo</th>
                                <th class="py-3">Rol componentes</th>
                                <th class="pe-3 py-3 text-end actions-container">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profesor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-3 fw-semibold"><?php echo e($profesor->persona->Nombre ?? ''); ?></td>
                                <td><?php echo e($profesor->persona->Apellido ?? ''); ?></td>
                                <td><?php echo e($profesor->persona->Celular ?? ''); ?></td>
                                <td class="email-cell" title="<?php echo e($profesor->usuario->Correo ?? ''); ?>">
                                    <?php echo e($profesor->usuario->Correo ?? ''); ?>

                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo e($profesor->Rol_componentes ?? 'Ninguno'); ?>

                                    </span>
                                </td>
                                <td class="pe-3 text-end actions-container">
                                    <div class="action-buttons">
                                        <!-- Ver -->
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#verProfesorModal<?php echo e($profesor->Id_profesores); ?>" title="Ver detalles">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>

                                        <!-- Editar -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editarProfesorModal<?php echo e($profesor->Id_profesores); ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Eliminar -->
                                        <form action="<?php echo e(route('profesores.destroy', $profesor->Id_profesores)); ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este profesor?')" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Ver Profesor -->
                            <div class="modal fade" id="verProfesorModal<?php echo e($profesor->Id_profesores); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>Detalles del Profesor</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Nombre</label>
                                                    <div class="fs-5"><?php echo e($profesor->persona->Nombre ?? ''); ?></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Apellido</label>
                                                    <div class="fs-5"><?php echo e($profesor->persona->Apellido ?? ''); ?></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Celular</label>
                                                    <div class="fs-5"><?php echo e($profesor->persona->Celular ?? ''); ?></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Correo</label>
                                                    <div class="fs-5"><?php echo e($profesor->usuario->Correo ?? ''); ?></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Profesión</label>
                                                    <div class="fs-5"><?php echo e($profesor->Profesion ?? 'No especificada'); ?></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Rol Componentes</label>
                                                    <div class="fs-5"><?php echo e($profesor->Rol_componentes ?? 'Ninguno'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Editar Profesor -->
                            <div class="modal fade" id="editarProfesorModal<?php echo e($profesor->Id_profesores); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Profesor</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?php echo e(route('profesores.update', $profesor->Id_profesores)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Nombre</label>
                                                        <input type="text" name="nombre" class="form-control" value="<?php echo e($profesor->persona->Nombre ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Apellido</label>
                                                        <input type="text" name="apellido" class="form-control" value="<?php echo e($profesor->persona->Apellido ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Celular</label>
                                                        <input type="text" name="celular" class="form-control" value="<?php echo e($profesor->persona->Celular ?? ''); ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Correo electrónico</label>
                                                        <input type="email" name="correo" class="form-control" value="<?php echo e($profesor->usuario->Correo ?? ''); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Nueva contraseña (opcional)</label>
                                                        <input type="password" name="contrasenia" class="form-control" placeholder="••••••••">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Profesión</label>
                                                        <input type="text" name="profesion" class="form-control" value="<?php echo e($profesor->Profesion ?? ''); ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Rol en Componentes</label>
                                                        <select name="rol_componentes" class="form-select">
                                                            <option value="Ninguno" <?php echo e($profesor->Rol_componentes == 'Ninguno' ? 'selected' : ''); ?>>Ninguno</option>
                                                            <option value="Tecnico" <?php echo e($profesor->Rol_componentes == 'Tecnico' ? 'selected' : ''); ?>>Técnico</option>
                                                            <option value="Inventario" <?php echo e($profesor->Rol_componentes == 'Inventario' ? 'selected' : ''); ?>>Inventario</option>
                                                        </select>
                                                    </div>
                                                </div>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center mt-4 mb-4">
        <?php echo e($profesores->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Asegurar que la tabla sea responsive en móviles
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar tooltips a los botones de acción
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Ajustar visualización en pantallas pequeñas
        function adjustTableForMobile() {
            const table = document.getElementById('teachersTable');
            if (!table) return;
            
            const isMobile = window.innerWidth < 768;
            const actionCells = table.querySelectorAll('.actions-container');
            
            if (isMobile) {
                actionCells.forEach(cell => {
                    cell.style.minWidth = '180px';
                });
            } else {
                actionCells.forEach(cell => {
                    cell.style.minWidth = '160px';
                });
            }
        }
        
        // Ejecutar al cargar y al redimensionar
        adjustTableForMobile();
        window.addEventListener('resize', adjustTableForMobile);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/profesoresAdministrador.blade.php ENDPATH**/ ?>