<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/menuAlumnosProfesor.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main class="main-content">
        <a href="<?php echo e(route('profesor.asistencia.index')); ?>" class="menu-link">
            <button class="menu-button evaluate">Asistencia</button>
        </a>
        
        <a href="<?php echo e(route('profesor.listado-alumnos', ['tipo' => 'asignados'])); ?>" class="menu-link">
            <button class="menu-button assigned">Alumnos Asignados</button>
        </a>
        
        <a href="<?php echo e(route('profesor.listado-alumnos', ['tipo' => 'recuperatoria'])); ?>" class="menu-link">
            <button class="menu-button recovery">Alumno Registrado<br>Clase Recuperatoria</button>
        </a>

        <a href="<?php echo e(route('profesor.clases-prueba.index')); ?>" class="menu-link">
            <button class="menu-button assigned">Clases de Prueba</button>
        </a>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/menuAlumnosProfesor.blade.php ENDPATH**/ ?>