<?php $__env->startSection('title', 'Asignar Motor a Técnico'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(auto_asset('css/style.css')); ?>" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Asignar Motor a Técnico</h1>
        <a href="<?php echo e(route('motores.asignaciones.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Ver Asignaciones
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

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Registro de Salida de Motor</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('motores.asignar.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_asignacion" class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control <?php $__errorArgs = ['fecha_asignacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="fecha_asignacion" name="fecha_asignacion" value="<?php echo e(old('fecha_asignacion', date('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['fecha_asignacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="Id_motores" class="form-label">ID Motor <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['Id_motores'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="Id_motores" name="Id_motores" required>
                            <option value="">Seleccione un motor</option>
                            <?php $__currentLoopData = $motoresDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($motor->Id_motores); ?>" <?php echo e(old('Id_motores') == $motor->Id_motores ? 'selected' : ''); ?>>
                                    <?php echo e($motor->Id_motor); ?> - <?php echo e($motor->Estado); ?> (<?php echo e($motor->sucursal->Nombre ?? 'Sin sucursal'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['Id_motores'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estado_motor" class="form-label">Estado del Motor <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['estado_motor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="estado_motor" name="estado_motor" required>
                            <option value="">Seleccione estado</option>
                            <option value="Funcionando" <?php echo e(old('estado_motor') == 'Funcionando' ? 'selected' : ''); ?>>Funcionando</option>
                            <option value="Descompuesto" <?php echo e(old('estado_motor') == 'Descompuesto' ? 'selected' : ''); ?>>Descompuesto</option>
                            <option value="En Proceso" <?php echo e(old('estado_motor') == 'En Proceso' ? 'selected' : ''); ?>>En Proceso</option>
                        </select>
                        <?php $__errorArgs = ['estado_motor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="Id_profesores" class="form-label">Técnico Asignado <span class="text-danger">*</span></label>
                        <select class="form-select <?php $__errorArgs = ['Id_profesores'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="Id_profesores" name="Id_profesores" required>
                            <option value="">Seleccione un técnico</option>
                            <?php $__currentLoopData = $tecnicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tecnico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tecnico->Id_profesores); ?>" <?php echo e(old('Id_profesores') == $tecnico->Id_profesores ? 'selected' : ''); ?>>
                                    <?php echo e($tecnico->persona->Nombre); ?> <?php echo e($tecnico->persona->Apellido_paterno); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['Id_profesores'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observacion_inicial" class="form-label">Observaciones</label>
                    <textarea class="form-control <?php $__errorArgs = ['observacion_inicial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              id="observacion_inicial" name="observacion_inicial" rows="4" 
                              placeholder="Describa el estado del motor, problemas detectados, etc."><?php echo e(old('observacion_inicial')); ?></textarea>
                    <?php $__errorArgs = ['observacion_inicial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('motores.asignaciones.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Asignar Motor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/componentes/asignarMotor.blade.php ENDPATH**/ ?>