<?php $__env->startSection('title', 'Registrar Profesor'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-person-plus-fill me-2"></i>Registrar Profesor</h1>
            <p class="mb-0 text-muted">Añadir un nuevo profesor al sistema</p>
        </div>
        <a href="<?php echo e(route('profesores.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success shadow-sm border-0 mb-4 role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="fw-bold text-primary mb-0">Información Personal y Profesional</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('administrador.registrarP')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="nombre" class="form-label fw-semibold">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" 
                                       value="<?php echo e(old('nombre')); ?>" required placeholder="Ingrese nombre">
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="apellido" class="form-label fw-semibold">Apellido</label>
                                <input type="text" id="apellido" name="apellido" class="form-control" 
                                       value="<?php echo e(old('apellido')); ?>" required placeholder="Ingrese apellido">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">Género</label>
                                <div class="d-flex gap-4 pt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" 
                                               <?php echo e(old('genero', 'M') == 'M' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="generoM">Masculino</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="generoF" value="F"
                                               <?php echo e(old('genero') == 'F' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="generoF">Femenino</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" 
                                       value="<?php echo e(old('fecha_nacimiento')); ?>" required>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="celular" class="form-label fw-semibold">Teléfono / Celular</label>
                                <input type="tel" id="celular" name="celular" class="form-control" 
                                       value="<?php echo e(old('celular')); ?>" required placeholder="Ingrese teléfono">
                            </div>

                            <div class="col-md-4">
                                <label for="profesion" class="form-label fw-semibold">Profesión</label>
                                <input type="text" id="profesion" name="profesion" class="form-control" 
                                       value="<?php echo e(old('profesion')); ?>" required placeholder="Ej: Ingeniero">
                            </div>
                        </div>

                        <div class="row mb-4">
                             <div class="col-md-8 mb-3 mb-md-0">
                                <label for="direccion_domicilio" class="form-label fw-semibold">Dirección Domiciliaria</label>
                                <input type="text" id="direccion_domicilio" name="direccion_domicilio" class="form-control" 
                                       value="<?php echo e(old('direccion_domicilio')); ?>" required placeholder="Dirección completa">
                            </div>
                            <div class="col-md-4">
                                <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" id="correo" name="correo" class="form-control" 
                                       value="<?php echo e(old('correo')); ?>" required placeholder="ejemplo@correo.com">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                             <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-light">Cancelar</a>
                             <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-person-check me-2"></i>Registrar Profesor
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/registrarProfesor.blade.php ENDPATH**/ ?>