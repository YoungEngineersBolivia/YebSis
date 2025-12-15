<?php $__env->startSection('title', 'Usuarios'); ?>
<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(auto_asset('css/style.css')); ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Usuarios</h1>
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

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="<?php echo e(route('usuarios.index')); ?>" method="GET">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Buscar por nombre" value="<?php echo e(request()->search); ?>">
                </div>
            </form>
        </div>
    </div>

    <?php if($usuarios->isEmpty()): ?>
        <div class="alert alert-warning">No hay usuarios registrados.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($usuario->persona->Nombre ?? ''); ?></td>
                            <td><?php echo e($usuario->persona->Apellido ?? ''); ?></td>
                            <td><?php echo e($usuario->Correo); ?></td>
                            <td><?php echo e($usuario->persona->rol->Nombre_rol ?? ''); ?></td>
                            <td class="d-flex gap-2">
                                <!-- Botón que abre modal -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal<?php echo e($usuario->Id_usuarios); ?>">
                                    Editar
                                </button>

                                <!-- Formulario Eliminar -->
                                <form action="<?php echo e(route('usuarios.destroy', $usuario->Id_usuarios)); ?>" method="POST" onsubmit="return confirm('¿Seguro de eliminar este usuario?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal de edición -->
                        <div class="modal fade" id="editarUsuarioModal<?php echo e($usuario->Id_usuarios); ?>" tabindex="-1" aria-labelledby="editarUsuarioLabel<?php echo e($usuario->Id_usuarios); ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="<?php echo e(route('usuarios.update', $usuario->Id_usuarios)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editarUsuarioLabel<?php echo e($usuario->Id_usuarios); ?>">Editar Usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" class="form-control" value="<?php echo e($usuario->persona->Nombre); ?>" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Apellido</label>
                                                <input type="text" name="apellido" class="form-control" value="<?php echo e($usuario->persona->Apellido); ?>" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Correo</label>
                                                <input type="email" name="correo" class="form-control" value="<?php echo e($usuario->Correo); ?>" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Rol</label>
                                                <input type="text" class="form-control" value="<?php echo e($usuario->persona->rol->Nombre_rol ?? ''); ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <?php echo e($usuarios->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/usuariosAdministrador.blade.php ENDPATH**/ ?>