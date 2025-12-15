<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/homeProfesor.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main class="main-content">
        
        <a href="<?php echo e(route('profesor.alumnos')); ?>">
            <button class="menu-button assigned">
                <i class="bi bi-mortarboard"></i> Alumnos
            </button>
        </a>

        <?php
            $profesor = auth()->user()->persona->profesor ?? null;
        ?>

        
        <?php if($profesor && $profesor->Rol_componentes === 'Inventario'): ?>
            <a href="<?php echo e(route('profesor.componentes.inventario')); ?>">
                <button class="menu-button assigned">
                    <i class="bi bi-box-seam"></i> Inventario
                </button>
            </a>
        <?php endif; ?>

        
        <?php if($profesor && $profesor->Rol_componentes === 'Tecnico'): ?>
            <a href="<?php echo e(route('profesor.componentes.motores-asignados')); ?>">
                <button class="menu-button assigned">
                    <i class="bi bi-tools"></i> Componentes asignados
                </button>
            </a>
        <?php endif; ?>

        
        <a href="<?php echo e(route('profesor.clases-prueba.index')); ?>">
            <button class="menu-button assigned">
                <i class="bi bi-chalkboard-teacher"></i> Clases de Prueba
            </button>
        </a>


<script src="<?php echo e(auto_asset('js/profesor/homeProfesor.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/homeProfesor.blade.php ENDPATH**/ ?>