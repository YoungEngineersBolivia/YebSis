<?php $__env->startSection('title', 'Salida de Componentes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">Salida de Componentes</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Solicitudes Pendientes de Asignación -->
    <?php if($solicitudesPendientes->count() > 0): ?>
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5><i class="fas fa-exclamation-triangle"></i> Solicitudes Pendientes de Asignación (<?php echo e($solicitudesPendientes->count()); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha Solicitud</th>
                            <th>ID Motor</th>
                            <th>Estado Actual</th>
                            <th>Motivo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $solicitudesPendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitud): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($solicitud->Fecha_movimiento->format('d/m/Y H:i')); ?></td>
                            <td><strong><?php echo e($solicitud->motor->Id_motor); ?></strong></td>
                            <td><span class="badge bg-secondary"><?php echo e($solicitud->Estado_salida); ?></span></td>
                            <td><?php echo e(Str::limit($solicitud->Motivo_salida, 40)); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" 
                                        onclick="asignarTecnico(<?php echo e($solicitud->Id_movimientos); ?>, <?php echo e($solicitud->motor->Id_motores); ?>, '<?php echo e(addslashes($solicitud->Motivo_salida)); ?>', '<?php echo e($solicitud->Estado_salida); ?>', '<?php echo e($solicitud->motor->Id_motor); ?>')">
                                    <i class="fas fa-user-plus"></i> Asignar Técnico
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Registrar Nueva Salida -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-sign-out-alt"></i> Registrar Salida de Motor</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.componentes.registrar-salida')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Motor *</label>
                        <select class="form-select <?php $__errorArgs = ['Id_motores'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="Id_motores" id="selectMotor" required>
                            <option value="">Seleccionar...</option>
                            <?php $__currentLoopData = $motores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($motor->Id_motores); ?>" 
                                        data-estado="<?php echo e($motor->Estado); ?>" 
                                        data-sucursal="<?php echo e($motor->sucursal->Nombre ?? 'N/A'); ?>"
                                        <?php echo e(old('Id_motores') == $motor->Id_motores ? 'selected' : ''); ?>>
                                    <?php echo e($motor->Id_motor); ?> - <?php echo e($motor->Estado); ?>

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
                    <div class="col-md-3">
                        <label class="form-label">Técnico Asignado *</label>
                        <select class="form-select <?php $__errorArgs = ['Id_profesores'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="Id_profesores" required>
                            <option value="">Seleccionar...</option>
                            <?php $__currentLoopData = $tecnicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tecnico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tecnico->Id_profesores); ?>"
                                        <?php echo e(old('Id_profesores') == $tecnico->Id_profesores ? 'selected' : ''); ?>>
                                    <?php echo e($tecnico->persona->Nombre); ?> <?php echo e($tecnico->persona->Apellido); ?>

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
                    <div class="col-md-2">
                        <label class="form-label">Estado Salida *</label>
                        <select class="form-select <?php $__errorArgs = ['Estado_salida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                name="Estado_salida" id="estadoSalida" required>
                            <option value="Descompuesto" <?php echo e(old('Estado_salida') == 'Descompuesto' ? 'selected' : ''); ?>>Descompuesto</option>
                            <option value="En Reparacion" <?php echo e(old('Estado_salida') == 'En Reparacion' ? 'selected' : ''); ?>>En Reparación</option>
                            <option value="Disponible" <?php echo e(old('Estado_salida') == 'Disponible' ? 'selected' : ''); ?>>Disponible</option>
                        </select>
                        <?php $__errorArgs = ['Estado_salida'];
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
                    <div class="col-md-4">
                        <label class="form-label">Motivo de Salida *</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['Motivo_salida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               name="Motivo_salida" 
                               placeholder="Ej: Reparación de circuito" 
                               value="<?php echo e(old('Motivo_salida')); ?>"
                               required>
                        <?php $__errorArgs = ['Motivo_salida'];
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
                <div class="row mt-3">
                    <div class="col-md-8">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea class="form-control <?php $__errorArgs = ['Observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  name="Observaciones" 
                                  rows="2" 
                                  placeholder="Detalles adicionales..."><?php echo e(old('Observaciones')); ?></textarea>
                        <?php $__errorArgs = ['Observaciones'];
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
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i> Registrar Salida
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Motores Disponibles para Salida -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Motores Disponibles en Inventario (<?php echo e($motores->count()); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Motor</th>
                            <th>Estado</th>
                            <th>Sucursal</th>
                            <th>Observación</th>
                            <th>Acción Rápida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $motores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($motor->Id_motor); ?></strong></td>
                            <td>
                                <?php
                                    $badgeClass = [
                                        'Disponible' => 'bg-success',
                                        'Descompuesto' => 'bg-danger',
                                        'En Reparacion' => 'bg-warning'
                                    ][$motor->Estado] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($motor->Estado); ?></span>
                            </td>
                            <td><?php echo e($motor->sucursal->Nombre ?? 'N/A'); ?></td>
                            <td><?php echo e(Str::limit($motor->Observacion, 50) ?? '---'); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="llenarFormulario(<?php echo e($motor->Id_motores); ?>, '<?php echo e($motor->Estado); ?>')">
                                    <i class="fas fa-arrow-up"></i> Seleccionar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No hay motores disponibles para salida
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Técnico a Solicitud -->
<div class="modal fade" id="modalAsignarTecnico" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.componentes.registrar-salida')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="Id_motores" id="modal_motor_id">
                <input type="hidden" name="Motivo_salida" id="modal_motivo">
                <input type="hidden" name="Estado_salida" id="modal_estado">
                
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Técnico a Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Motor:</strong> <span id="modal_motor_display"></span><br>
                        <strong>Estado:</strong> <span id="modal_estado_display"></span><br>
                        <strong>Motivo:</strong> <span id="modal_motivo_display"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Técnico *</label>
                        <select class="form-select" name="Id_profesores" required>
                            <option value="">Seleccionar...</option>
                            <?php $__currentLoopData = $tecnicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tecnico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tecnico->Id_profesores); ?>">
                                    <?php echo e($tecnico->persona->Nombre); ?> <?php echo e($tecnico->persona->Apellido); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea class="form-control" name="Observaciones" rows="2" 
                                  placeholder="Observaciones adicionales sobre la asignación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Asignar y Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    const motores = <?php echo json_encode($motores, 15, 512) ?>;
    const solicitudes = <?php echo json_encode($solicitudesPendientes, 15, 512) ?>;

    // Llenar formulario automáticamente
    function llenarFormulario(motorId, estado) {
        $('#selectMotor').val(motorId);
        $('#estadoSalida').val(estado == 'Disponible' ? 'Descompuesto' : estado);
        $('html, body').animate({ scrollTop: 0 }, 300);
        $('#selectMotor').focus();
    }

    // Asignar técnico a solicitud pendiente
    function asignarTecnico(movimientoId, motorId, motivo, estado, motorNombre) {
        $('#modal_motor_id').val(motorId);
        $('#modal_motivo').val(motivo);
        $('#modal_estado').val(estado);
        $('#modal_motor_display').text(motorNombre);
        $('#modal_estado_display').text(estado);
        $('#modal_motivo_display').text(motivo);
        
        $('#modalAsignarTecnico').modal('show');
    }

    // Auto-completar estado según motor seleccionado
    $('#selectMotor').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const estado = selectedOption.data('estado');
        if (estado) {
            $('#estadoSalida').val(estado);
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/componentes/salidaComponentes.blade.php ENDPATH**/ ?>