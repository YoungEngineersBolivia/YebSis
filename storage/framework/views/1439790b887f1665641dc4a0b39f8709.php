<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/menuAlumnosProfesor.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <h2 class="text-center mb-4 fw-bold text-secondary">Menú de Alumnos</h2>
        <div class="row g-3 justify-content-center">
            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php echo e(route('profesor.asistencia.index')); ?>" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg py-4 shadow-sm">
                            <i class="bi bi-calendar-check display-6 mb-2 d-block"></i>
                            <span class="h5">Asistencia</span>
                        </button>
                    </div>
                </a>
            </div>
            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php echo e(route('profesor.listado-alumnos', ['tipo' => 'asignados'])); ?>" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-success btn-lg py-4 shadow-sm">
                            <i class="bi bi-people-fill display-6 mb-2 d-block"></i>
                            <span class="h5">Alumnos Asignados</span>
                        </button>
                    </div>
                </a>
            </div>
            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php echo e(route('profesor.listado-alumnos', ['tipo' => 'recuperatoria'])); ?>" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-warning btn-lg py-4 shadow-sm text-white">
                            <i class="bi bi-arrow-repeat display-6 mb-2 d-block"></i>
                            <span class="h5">Clase Recuperatoria</span>
                        </button>
                    </div>
                </a>
            </div>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/menuAlumnosProfesor.blade.php ENDPATH**/ ?>