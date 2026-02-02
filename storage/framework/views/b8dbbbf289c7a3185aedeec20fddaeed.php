<?php $__env->startSection('title', 'Tutores'); ?>

<?php $__env->startSection('styles'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="<?php echo e(auto_asset('css/style.css')); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="mt-2 text-start">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Lista de Tutores</h2>
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
                <form id="formBuscador" action="<?php echo e(route('tutores.index')); ?>" method="GET" class="w-100">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label for="searchTutor" class="form-label mb-1 fw-semibold text-muted">Buscar Tutor</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-search text-muted"></i></span>
                                <input type="text" id="searchTutor" class="form-control border-start-0 ps-0" name="search"
                                    placeholder="Buscar por nombre, apellido o celular..." value="<?php echo e($search ?? ''); ?>"
                                    data-table-filter="tutorsTable">
                                <button type="submit" class="btn btn-primary ms-2 rounded">Buscar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($tutores->isEmpty()): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-person-slash text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">No hay tutores registrados</h5>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0" id="tutorsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3 py-3">Nombre</th>
                                    <th class="py-3">Apellido</th>
                                    <th class="py-3">Celular</th>
                                    <th class="py-3">Dirección</th>
                                    <th class="py-3">Correo</th>
                                    <th class="py-3">Parentesco</th>
                                    <th class="pe-3 py-3 text-end" style="min-width:140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tutores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?php echo e($tutor->persona->Nombre ?? '—'); ?></td>
                                        <td><?php echo e($tutor->persona->Apellido ?? '—'); ?></td>
                                        <td><?php echo e($tutor->persona->Celular ?? '—'); ?></td>
                                        <td><?php echo e($tutor->persona->Direccion_domicilio ?? '—'); ?></td>
                                        <td><?php echo e($tutor->usuario->Correo ?? '—'); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo e($tutor->Parentesco ?? '—'); ?></span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                
                                                <a href="<?php echo e(route('tutores.detalles', $tutor->Id_tutores)); ?>"
                                                    class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditar<?php echo e($tutor->Id_tutores); ?>" title="Editar">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php $__currentLoopData = $tutores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <div class="modal fade" id="modalEditar<?php echo e($tutor->Id_tutores); ?>" tabindex="-1"
                    aria-labelledby="modalEditarLabel<?php echo e($tutor->Id_tutores); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form action="<?php echo e(route('tutores.update', $tutor->Id_tutores)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalEditarLabel<?php echo e($tutor->Id_tutores); ?>">
                                        <i class="bi bi-pencil-square me-2"></i>Editar Tutor
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Nombre</label>
                                            <input type="text" name="nombre" class="form-control"
                                                value="<?php echo e($tutor->persona->Nombre ?? ''); ?>" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Apellido</label>
                                            <input type="text" name="apellido" class="form-control"
                                                value="<?php echo e($tutor->persona->Apellido ?? ''); ?>" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Celular</label>
                                            <input type="text" name="celular" class="form-control"
                                                value="<?php echo e($tutor->persona->Celular ?? ''); ?>">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Dirección</label>
                                            <input type="text" name="direccion_domicilio" class="form-control"
                                                value="<?php echo e($tutor->persona->Direccion_domicilio ?? ''); ?>">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Correo</label>
                                            <input type="email" name="correo" class="form-control"
                                                value="<?php echo e($tutor->usuario->Correo ?? ''); ?>" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Parentesco</label>
                                            <input type="text" name="parentezco" class="form-control"
                                                value="<?php echo e($tutor->Parentesco ?? ''); ?>" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Descuento (%)</label>
                                            <input type="number" name="descuento" class="form-control" step="0.01" min="0" max="100"
                                                value="<?php echo e($tutor->Descuento ?? ''); ?>">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">NIT</label>
                                            <input type="text" name="nit" class="form-control" value="<?php echo e($tutor->Nit ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
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

            
            <div class="d-flex justify-content-center mt-4 mb-4">
                <?php echo e($tutores->links('pagination::bootstrap-5')); ?>

            </div>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        let timer;
        let lastValue = "<?php echo e($search ?? ''); ?>";


        window.onload = function () {
            const input = document.getElementById('searchTutor');
            if (input) {
                input.focus();
                input.setSelectionRange(input.value.length, input.value.length);
            }
        };

        /* MIGRADO A baseAdministrador.blade.php - Se eliminó auto-submit para usar filtro cliente */
        /*
        document.getElementById('searchTutor').addEventListener('input', function () {
            const value = this.value;


            if (value === lastValue) return;

            clearTimeout(timer);


            timer = setTimeout(() => {
                lastValue = value;
                document.getElementById('formBuscador').submit();
            }, 900); 
        });
        */
    </script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/tutoresAdministrador.blade.php ENDPATH**/ ?>