<?php $__env->startSection('title', 'Tutores'); ?>

<?php $__env->startSection('styles'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="<?php echo e(auto_asset('css/style.css')); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Tutores</h1>
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

    
    <div class="row mb-3">
        <div class="col-md-6">
            <form action="<?php echo e(route('tutores.index')); ?>" method="GET" class="w-100">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Buscar Tutor" value="<?php echo e($search ?? ''); ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <?php if($tutores->isEmpty()): ?>
        <div class="alert alert-warning">No hay tutores registrados.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Celular</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th>Parentesco</th>
                        <th style="min-width:140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $tutores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($tutor->persona->Nombre ?? '—'); ?></td>
                            <td><?php echo e($tutor->persona->Apellido ?? '—'); ?></td>
                            <td><?php echo e($tutor->persona->Celular ?? '—'); ?></td>
                            <td><?php echo e($tutor->persona->Direccion_domicilio ?? '—'); ?></td>
                            <td><?php echo e($tutor->usuario->Correo ?? '—'); ?></td>
                            <td><?php echo e($tutor->Parentesco ?? '—'); ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    
                                    <a href="<?php echo e(route('tutores.detalles', $tutor->Id_tutores)); ?>" 
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Ver detalles">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar<?php echo e($tutor->Id_tutores); ?>">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>

                                
                                </div>
                            </td>
                        </tr>

                        
                        <div class="modal fade" id="modalEditar<?php echo e($tutor->Id_tutores); ?>" tabindex="-1" aria-labelledby="modalEditarLabel<?php echo e($tutor->Id_tutores); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <form action="<?php echo e(route('tutores.update', $tutor->Id_tutores)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEditarLabel<?php echo e($tutor->Id_tutores); ?>">Editar Tutor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" name="nombre" class="form-control" value="<?php echo e($tutor->persona->Nombre ?? ''); ?>" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Apellido</label>
                                                    <input type="text" name="apellido" class="form-control" value="<?php echo e($tutor->persona->Apellido ?? ''); ?>" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Celular</label>
                                                    <input type="text" name="celular" class="form-control" value="<?php echo e($tutor->persona->Celular ?? ''); ?>">
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Dirección</label>
                                                    <input type="text" name="direccion_domicilio" class="form-control" value="<?php echo e($tutor->persona->Direccion_domicilio ?? ''); ?>">
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Correo</label>
                                                    <input type="email" name="correo" class="form-control" value="<?php echo e($tutor->usuario->Correo ?? ''); ?>" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Parentesco</label>
                                                    <input type="text" name="parentezco" class="form-control" value="<?php echo e($tutor->Parentesco ?? ''); ?>" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Descuento (%)</label>
                                                    <input type="number" name="descuento" class="form-control" step="0.01" min="0" max="100" value="<?php echo e($tutor->Descuento ?? ''); ?>">
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">NIT</label>
                                                    <input type="text" name="nit" class="form-control" value="<?php echo e($tutor->Nit ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save me-1"></i>Guardar Cambios
                                            </button>
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
            <?php echo e($tutores->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
(function () {
    // Autocierre de alertas después de 5 segundos
    document.querySelectorAll('.alert').forEach(alertEl => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
            bsAlert.close();
        }, 5000);
    });
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/tutoresAdministrador.blade.php ENDPATH**/ ?>