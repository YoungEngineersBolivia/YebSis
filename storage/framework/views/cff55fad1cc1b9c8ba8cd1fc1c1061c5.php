<?php $__env->startSection('title','Publicaciones y notificaciones'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h1>Publicaciones y Notificaciones</h1>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>


    
    <div class="card mb-4">
        <div class="card-header">Crear nueva publicación en la página web</div>
        <div class="card-body">
            <form action="<?php echo e(route('publicaciones.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Título</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                    <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control" required></textarea>
                    <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Archivo / Imagen (opcional)</label>
                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*,application/pdf">
                    <?php $__errorArgs = ['imagen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <button type="submit" class="btn btn-primary">Crear Publicación</button>
            </form>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header">Enviar notificación a tutores</div>
        <div class="card-body">
            <form action="<?php echo e(route('notificaciones.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="nombre_notif" class="form-label">Título</label>
                    <input type="text" name="nombre" id="nombre_notif" class="form-control" required>
                    <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label for="descripcion_notif" class="form-label">Mensaje</label>
                    <textarea name="descripcion" id="descripcion_notif" rows="3" class="form-control" required></textarea>
                    <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label for="imagen_notif" class="form-label">Archivo / Imagen (opcional)</label>
                    <input type="file" name="imagen" id="imagen_notif" class="form-control" accept="image/*,application/pdf">
                    <?php $__errorArgs = ['imagen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Buscar tutor</label>
                    <input type="text" id="buscador-tutor" class="form-control" placeholder="Buscar por nombre o apellido...">
                </div>
                <div class="mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="select-all-tutores">
                        <label class="form-check-label" for="select-all-tutores">Seleccionar todos los tutores</label>
                    </div>
                    <div id="lista-tutores" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                        <?php $__currentLoopData = $tutores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check tutor-item">
                                <input class="form-check-input tutor-checkbox" type="checkbox" name="tutores[]" value="<?php echo e($tutor->Id_tutores); ?>" id="tutor-<?php echo e($tutor->Id_tutores); ?>">
                                <label class="form-check-label" for="tutor-<?php echo e($tutor->Id_tutores); ?>">
                                    <?php echo e($tutor->persona->Nombre ?? ''); ?> <?php echo e($tutor->persona->Apellido ?? ''); ?>

                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning">Enviar Notificación</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscador-tutor');
            const listaTutores = document.getElementById('lista-tutores');
            const selectAll = document.getElementById('select-all-tutores');
            buscador.addEventListener('input', function() {
                const filtro = buscador.value.toLowerCase();
                listaTutores.querySelectorAll('.tutor-item').forEach(function(item) {
                    const label = item.textContent.toLowerCase();
                    item.style.display = label.includes(filtro) ? '' : 'none';
                });
            });
            selectAll.addEventListener('change', function() {
                const checked = selectAll.checked;
                listaTutores.querySelectorAll('.tutor-checkbox').forEach(function(cb) {
                    cb.checked = checked;
                });
            });
        });
    </script>

    
    <h3>Publicaciones</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Archivo</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $publicaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($pub->Nombre); ?></td>
                    <td><?php echo e($pub->Descripcion); ?></td>
                    <td>
                        <?php if($pub->Imagen): ?>
                            <a href="<?php echo e(auto_asset('storage/' . $pub->Imagen)); ?>" target="_blank">Ver archivo</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($pub->created_at->format('d/m/Y')); ?></td>
                    <td>
                        <form action="<?php echo e(route('publicaciones.destroy', $pub->Id_publicaciones)); ?>" method="POST" onsubmit="return confirm('¿Eliminar publicación?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center">No hay publicaciones aún.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    
    <h3>Notificaciones para Tutores</h3>
    <ul class="list-group mb-4">
        <?php $__empty_1 = true; $__currentLoopData = $notificaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo e($notif->Nombre); ?></strong> - <?php echo e($notif->Descripcion); ?>

                </div>
                <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($notif->Fecha)->format('d/m/Y')); ?></small>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item">No hay notificaciones.</li>
        <?php endif; ?>
    </ul>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('/administrador/baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/pubnotAdministrador.blade.php ENDPATH**/ ?>