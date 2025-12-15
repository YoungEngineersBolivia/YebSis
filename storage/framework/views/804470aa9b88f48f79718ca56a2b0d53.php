<?php $__env->startSection('title', 'Sucursal'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-building-fill me-2"></i>Sucursales</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarSucursal">
            <i class="bi bi-plus-lg me-2"></i>Agregar Sucursal
        </button>
    </div>

    <!-- Mensajes de éxito -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla de sucursales -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="ps-3 py-3">Nombre</th>
                            <th class="py-3">Dirección</th>
                            <th class="pe-3 py-3 text-end" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="ps-3 fw-semibold"><?php echo e($sucursal->Nombre); ?></td>
                            <td><?php echo e($sucursal->Direccion); ?></td>
                            <td class="pe-3 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            title="Editar" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarSucursal"
                                            data-id="<?php echo e($sucursal->Id_sucursales); ?>"
                                            data-nombre="<?php echo e($sucursal->Nombre); ?>"
                                            data-direccion="<?php echo e($sucursal->Direccion); ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminarSucursal"
                                            data-id="<?php echo e($sucursal->Id_sucursales); ?>"
                                            data-nombre="<?php echo e($sucursal->Nombre); ?>">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle me-2"></i>No hay sucursales registradas
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Sucursal -->
<div class="modal fade" id="modalAgregarSucursal" tabindex="-1" aria-labelledby="modalLabelSucursal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('sucursales.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabelSucursal"><i class="bi bi-plus-circle me-2"></i>Agregar Sucursal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre</label>
                        <input type="text" class="form-control" name="Nombre" placeholder="Ej: Sede Central" required>
                    </div>
                </div>
                <div class="modal-body pt-0">
                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-bold">Dirección</label>
                        <input type="text" class="form-control" name="Direccion" placeholder="Ej: Av. Principal #123" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Sucursal -->
<div class="modal fade" id="modalEditarSucursal" tabindex="-1" aria-labelledby="modalLabelEditarSucursal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditarSucursal" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabelEditarSucursal"><i class="bi bi-pencil-square me-2"></i>Editar Sucursal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label fw-bold">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" name="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDireccion" class="form-label fw-bold">Dirección</label>
                        <input type="text" class="form-control" id="editDireccion" name="Direccion" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Sucursal -->
<div class="modal fade" id="modalEliminarSucursal" tabindex="-1" aria-labelledby="modalLabelEliminarSucursal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEliminarSucursal" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalLabelEliminarSucursal"><i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Sucursal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <i class="bi bi-trash3 text-danger mb-3" style="font-size: 3rem;"></i>
                        <p>¿Está seguro que desea eliminar la sucursal <strong id="nombreSucursalEliminar"></strong>?</p>
                        <p class="text-danger small mb-0">Esta acción no se puede deshacer.</p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para el modal de editar
    document.addEventListener('DOMContentLoaded', function() {
        const modalEditarSucursal = document.getElementById('modalEditarSucursal');
        modalEditarSucursal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const direccion = button.getAttribute('data-direccion');
            
            const form = document.getElementById('formEditarSucursal');
            form.action = `/administrador/sucursalesAdministrador/${id}`;
            
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editDireccion').value = direccion;
        });

        // Script para el modal de eliminar
        const modalEliminarSucursal = document.getElementById('modalEliminarSucursal');
        modalEliminarSucursal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            
            const form = document.getElementById('formEliminarSucursal');
            form.action = `/administrador/sucursalesAdministrador/${id}`;
            
            document.getElementById('nombreSucursalEliminar').textContent = nombre;
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/administrador/sucursalesAdministrador.blade.php ENDPATH**/ ?>