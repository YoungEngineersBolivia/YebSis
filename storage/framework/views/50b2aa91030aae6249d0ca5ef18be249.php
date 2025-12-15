

<?php $__env->startSection('title', 'Detalles del Profesor'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(auto_asset('css/administrador/detallesEstudiantes.css')); ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4 mb-5 container-with-footer">
    <?php
        $persona = $profesor->persona ?? null;
        $usuario = $profesor->usuario ?? null;
        $nombreCompleto = $persona ? trim(($persona->Nombre ?? '').' '.($persona->Apellido ?? '')) : 'Sin nombre';
        $iniciales = $persona ? strtoupper(substr($persona->Nombre ?? 'S', 0, 1) . substr($persona->Apellido ?? 'N', 0, 1)) : 'SN';
        $rol = $persona->rol->Nombre_rol ?? 'Sin rol';
        $genero = $persona->Genero === 'M' ? 'Masculino' : ($persona->Genero === 'F' ? 'Femenino' : 'No especificado');
        $profesion = $profesor->Profesion ?? 'No registrada';
        $rolComponentes = $profesor->Rol_componentes ?? 'Ninguno';
    ?>

    
    <div class="mb-3">
        <a href="<?php echo e(route('profesores.index')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    
    <div class="student-header">
        <div class="student-avatar">
            <?php echo e($iniciales); ?>

        </div>
        <h2><?php echo e($nombreCompleto); ?></h2>
        <p class="mb-1">
            <i class="bi bi-envelope"></i> 
            <strong><?php echo e($usuario->Correo ?? 'Sin correo'); ?></strong>
        </p>
        <p class="mb-1">
            <i class="bi bi-briefcase"></i> 
            Profesión: <strong><?php echo e($profesion); ?></strong>
        </p>
        <p class="mb-1">
            <i class="bi bi-person-badge"></i> 
            Rol en el sistema: <strong><?php echo e($rol); ?></strong>
        </p>
    </div>

    
    <div class="info-grid mt-4">
        
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-person-lines-fill"></i> Datos personales
            </div>
            <div class="info-item">
                <span class="info-label">Nombre completo</span>
                <span class="info-value"><?php echo e($nombreCompleto); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Género</span>
                <span class="info-value"><?php echo e($genero); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Celular</span>
                <span class="info-value">
                    <?php if($persona->Celular): ?>
                        <a href="tel:<?php echo e($persona->Celular); ?>" class="text-decoration-none">
                            <i class="bi bi-phone"></i> <?php echo e($persona->Celular); ?>

                        </a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Dirección</span>
                <span class="info-value"><?php echo e($persona->Direccion_domicilio ?? '—'); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha de nacimiento</span>
                <span class="info-value">
                    <?php echo e($persona->Fecha_nacimiento ? \Carbon\Carbon::parse($persona->Fecha_nacimiento)->format('d/m/Y') : '—'); ?>

                </span>
            </div>
        </div>

        
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-people-fill"></i> Estudiantes por horario
            </div>

            <?php
                $horariosAgrupados = $profesor->horarios->groupBy('Dia') ?? collect();
            ?>

            <?php if($horariosAgrupados->isEmpty()): ?>
                <p class="text-muted text-center mt-3 mb-0">
                    <i class="bi bi-calendar-x"></i><br>
                    No tiene horarios asignados
                </p>
            <?php else: ?>
                <?php $__currentLoopData = $horariosAgrupados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia => $clasesPorDia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <h5 class="mt-4"><?php echo e(ucfirst(strtolower($dia))); ?></h5>
                    <?php $__currentLoopData = $clasesPorDia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $estudiante = $clase->estudiante?->persona;
                            $programa = $clase->programa->Nombre ?? 'Sin programa';
                            $hora = \Carbon\Carbon::parse($clase->Hora)->format('H:i');
                        ?>
                        <div class="mb-3 p-2 border-start border-primary border-3 bg-light rounded">
                            <div class="info-item mb-1">
                                <span class="info-label">
                                    <i class="bi bi-person-fill"></i> Estudiante
                                </span>
                                <span class="info-value">
                                    <?php if($estudiante): ?>
                                        <?php echo e($estudiante->Nombre); ?> <?php echo e($estudiante->Apellido); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </span>
                            </div>

                            <div class="info-item mb-1">
                                <span class="info-label">
                                    <i class="bi bi-book"></i> Programa
                                </span>
                                <span class="info-value"><?php echo e($programa); ?></span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-clock"></i> Hora
                                </span>
                                <span class="info-value"><?php echo e($hora); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

    </div>

    
    <div class="action-footer d-flex justify-content-center gap-3 flex-wrap mt-4">
        <a href="<?php echo e(route('profesores.edit', $profesor->Id_profesores)); ?>" 
           class="btn btn-warning text-dark">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="<?php echo e(route('profesores.index')); ?>" 
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al listado
        </a>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/detallesProfesor.blade.php ENDPATH**/ ?>