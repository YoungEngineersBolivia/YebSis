<?php $__env->startSection('title', 'Registrar'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center gap-3">
    <h1 class="me-2">Registrar Profesor</h1>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success d-flex align-items-center justify-content-center gap-2" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08 0l3.992-3.992a.75.75 0 1 0-1.06-1.06L7.5 9.439 5.53 7.47a.75.75 0 0 0-1.06 1.06l2.5 2.5z"/>
        </svg>
        <strong>¡Registro exitoso!</strong> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>


<form action="<?php echo e(route('administrador.registrarP')); ?>" method="POST">

    <?php echo csrf_field(); ?>

    <div class="d-flex align-items-end gap-3 mb-3">
        <div class="flex-grow-1">
            <label for="nombre" class="form-label">Nombre</label>
            <div class="input">
                <input type="text" id="nombre" name="nombre" class="form-control" 
                       value="<?php echo e(old('nombre')); ?>" required>
            </div>
        </div>
    
        <div class="flex-grow-1">
            <label for="apellido" class="form-label">Apellido</label>
            <div class="input">
                <input type="text" id="apellido" name="apellido" class="form-control" 
                       value="<?php echo e(old('apellido')); ?>" required>
            </div>
        </div>
    
        <div class="flex-grow-1">
            <label for="genero" class="form-label">Género</label>
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" 
                           <?php echo e(old('genero', 'M') == 'M' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="generoM">M</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="genero" id="generoF" value="F"
                           <?php echo e(old('genero') == 'F' ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="generoF">F</label>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-end gap-3 mb-3">
        <div style="flex-basis: 220px;">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <div class="input-group">
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" 
                       value="<?php echo e(old('fecha_nacimiento')); ?>" required>
            </div>
        </div>
    
        <div style="flex-basis: 220px;">
            <label for="celular" class="form-label">Teléfono</label>
            <input type="tel" id="celular" name="celular" class="form-control" 
                   value="<?php echo e(old('celular')); ?>" required>
        </div>
    
        <div class="flex-grow-1">
            <label for="direccion_domicilio" class="form-label">Dirección</label>
            <input type="text" id="direccion_domicilio" name="direccion_domicilio" class="form-control" 
                   value="<?php echo e(old('direccion_domicilio')); ?>" required>
        </div>
    </div>

    <div class="d-flex align-items-end gap-3">
        <div style="width: 220px;">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" name="correo" class="form-control" 
                   value="<?php echo e(old('correo')); ?>" required>
        </div>

        <div style="width: 220px;">
            <label for="profesion" class="form-label">Profesion</label>
            <input type="string" id="profesion" name="profesion" class="form-control" 
                   value="<?php echo e(old('profesion')); ?>" required>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/registrarProfesor.blade.php ENDPATH**/ ?>