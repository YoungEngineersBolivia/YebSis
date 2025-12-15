<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/homeProfesor.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row g-3 justify-content-center">
            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php echo e(route('profesor.alumnos')); ?>" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg py-4 shadow-sm">
                            <i class="bi bi-mortarboard display-6 mb-2 d-block"></i>
                            <span class="h5">Alumnos</span>
                        </button>
                    </div>
                </a>
            </div>

            <?php
                $profesor = auth()->user()->persona->profesor ?? null;
            ?>

            
            <?php if($profesor && $profesor->Rol_componentes === 'Inventario'): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php echo e(route('profesor.componentes.inventario')); ?>" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-success btn-lg py-4 shadow-sm">
                            <i class="bi bi-box-seam display-6 mb-2 d-block"></i>
                            <span class="h5">Inventario</span>
                        </button>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            
            <?php if($profesor && $profesor->Rol_componentes === 'Tecnico'): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php echo e(route('profesor.componentes.motores-asignados')); ?>" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-warning btn-lg py-4 shadow-sm text-white">
                            <i class="bi bi-tools display-6 mb-2 d-block"></i>
                            <span class="h5">Componentes asignados</span>
                        </button>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php echo e(route('profesor.clases-prueba.index')); ?>" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-info btn-lg py-4 shadow-sm text-white">
                            <i class="bi bi-chalkboard-teacher display-6 mb-2 d-block"></i>
                            <span class="h5">Clases de Prueba</span>
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>


<script src="<?php echo e(auto_asset('js/profesor/homeProfesor.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/profesor/homeProfesor.blade.php ENDPATH**/ ?>