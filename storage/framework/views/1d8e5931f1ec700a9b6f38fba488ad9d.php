<?php $__env->startSection('title', 'Usuarios'); ?>
<?php $__env->startSection('styles'); ?>
    <link href="<?php echo e(auto_asset('css/style.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="mt-2 text-start">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i>Usuarios</h2>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
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

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">Buscar Usuario</label>
                        <form action="<?php echo e(route('usuarios.index')); ?>" method="GET">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" name="search"
                                    placeholder="Nombre, apellido o correo..." value="<?php echo e(request()->search); ?>"
                                    data-table-filter="usersTable">
                                <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if($usuarios->isEmpty()): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-person-x text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">No hay usuarios registrados.</h5>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0" id="usersTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3 py-3">Nombre</th>
                                    <th class="py-3">Apellido</th>
                                    <th class="py-3">Correo</th>
                                    <th class="py-3">Rol</th>
                                    <th class="pe-3 py-3 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?php echo e($usuario->persona->Nombre ?? ''); ?></td>
                                        <td><?php echo e($usuario->persona->Apellido ?? ''); ?></td>
                                        <td><?php echo e($usuario->Correo); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo e($usuario->persona->rol->Nombre_rol ?? ''); ?></span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <!-- Botón que abre modal -->
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editarUsuarioModal<?php echo e($usuario->Id_usuarios); ?>" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Formulario Eliminar -->
                                                <form action="<?php echo e(route('usuarios.destroy', $usuario->Id_usuarios)); ?>" method="POST"
                                                    onsubmit="return confirm('¿Seguro de eliminar este usuario?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal de edición -->
                                    <div class="modal fade" id="editarUsuarioModal<?php echo e($usuario->Id_usuarios); ?>" tabindex="-1"
                                        aria-labelledby="editarUsuarioLabel<?php echo e($usuario->Id_usuarios); ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="<?php echo e(route('usuarios.update', $usuario->Id_usuarios)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="editarUsuarioLabel<?php echo e($usuario->Id_usuarios); ?>">
                                                            <i class="bi bi-person-gear me-2"></i>Editar Usuario
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Nombre</label>
                                                            <input type="text" name="nombre" class="form-control"
                                                                value="<?php echo e($usuario->persona->Nombre); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Apellido</label>
                                                            <input type="text" name="apellido" class="form-control"
                                                                value="<?php echo e($usuario->persona->Apellido); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Correo</label>
                                                            <input type="email" name="correo" class="form-control"
                                                                value="<?php echo e($usuario->Correo); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Rol</label>
                                                            <input type="text" class="form-control bg-light"
                                                                value="<?php echo e($usuario->persona->rol->Nombre_rol ?? ''); ?>" disabled>
                                                            <small class="text-muted">El rol no se puede editar desde aquí.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <?php echo e($usuarios->links('pagination::bootstrap-5')); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/usuariosAdministrador.blade.php ENDPATH**/ ?>