<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Panel del Profesor'); ?></title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    
    <link rel="stylesheet" href="<?php echo e(auto_asset('css/profesor/baseProfesor.css')); ?>">
    
    
    <?php echo $__env->yieldContent('styles'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <div class="logo-icon">e</div>
                
                <span class="username">
                   <?php echo e(Auth::user()->Correo); ?>

                </span>
            </div>

            <div class="menu-icon" onclick="toggleMenu()">
                <div class="menu-line"></div>
                <div class="menu-line"></div>
                <div class="menu-line"></div>
            </div>

            <!-- Menú desplegable -->
            <div class="menu-dropdown" id="menuDropdown">
                <a href="<?php echo e(route('profesor.asistencia.index')); ?>" class="menu-item">
                    <i class="bi bi-calendar-check"></i> Registro de Asistencia
                </a>
                <a href="<?php echo e(route('profesor.listado-alumnos', 'asignados')); ?>" class="menu-item">
                    <i class="bi bi-people-fill"></i> Alumnos Asignados
                </a>
                <a href="<?php echo e(route('profesor.listado-alumnos', 'recuperatoria')); ?>" class="menu-item">
                    <i class="bi bi-calendar-event"></i> Clase Recuperatoria
                </a>
                <a href="#" class="menu-item menu-item-logout"
                   onclick="document.getElementById('logout-form').submit(); return false;">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>

            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </header>

        <main>
            
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
    <script>
        function toggleMenu() {
            const dropdown = document.getElementById('menuDropdown');
            dropdown.classList.toggle('show');
        }

        // Cerrar el menú si se hace clic fuera de él
        window.onclick = function(event) {
            if (!event.target.matches('.menu-icon') && !event.target.matches('.menu-line')) {
                const dropdown = document.getElementById('menuDropdown');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>

    
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/profesor/baseProfesor.blade.php ENDPATH**/ ?>