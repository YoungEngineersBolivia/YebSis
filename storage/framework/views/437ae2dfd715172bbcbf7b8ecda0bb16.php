<?php $__env->startSection('title', 'Detalles del Estudiante'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(auto_asset('css/administrador/detallesEstudiantes.css')); ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4 mb-5 container-with-footer">
    <?php
        $persona = $estudiante->persona ?? null;
        $nombreCompleto = $persona ? trim(($persona->Nombre ?? '').' '.($persona->Apellido ?? '')) : 'Sin nombre';
        $iniciales = $persona ? strtoupper(substr($persona->Nombre ?? 'S', 0, 1) . substr($persona->Apellido ?? 'N', 0, 1)) : 'SN';

        $programa = $estudiante->programa ?? null;
        $sucursal = $estudiante->sucursal ?? null;
        $tutor = $estudiante->tutor ?? null;
        $tutorPersona = $tutor?->persona ?? null;

        $profesor = $estudiante->profesor ?? null;
        $profesorPersona = $profesor?->persona ?? null;
        $profesorNombre = $profesorPersona 
            ? trim(($profesorPersona->Nombre ?? '').' '.($profesorPersona->Apellido ?? ''))
            : 'Sin asignar';

        $estadoLower = Str::lower($estudiante->Estado ?? '');
        $esActivo = $estadoLower === 'activo';

        $edad = null;
        if($persona && $persona->Fecha_nacimiento) {
            $fechaNac = \Carbon\Carbon::parse($persona->Fecha_nacimiento);
            $edad = $fechaNac->age;
        }

        // Obtener horarios desde la relación
        $horarios = $estudiante->horarios ?? collect();
    ?>

    
    <div class="mb-3">
        <a href="<?php echo e(route('estudiantes.index')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="student-header">
        <div class="student-avatar">
            <?php echo e($iniciales); ?>

        </div>
        <h2><?php echo e($nombreCompleto); ?></h2>
        <p class="mb-1">Código: <strong><?php echo e($estudiante->Cod_estudiante); ?></strong></p>
        <span class="status-badge <?php echo e($esActivo ? 'status-active' : 'status-inactive'); ?>">
            <i class="bi bi-circle-fill"></i> <?php echo e($esActivo ? 'Activo' : 'Inactivo'); ?>

        </span>
    </div>

    
    <div class="info-grid">
        
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-person-circle"></i> Datos personales
            </div>
            <?php if($persona): ?>
                <div class="info-item">
                    <span class="info-label">Nombre completo</span>
                    <span class="info-value"><?php echo e($nombreCompleto); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Género</span>
                    <span class="info-value">
                        <?php if($persona->Genero === 'M'): ?>
                            <i class="bi bi-gender-male text-primary"></i> Masculino
                        <?php elseif($persona->Genero === 'F'): ?>
                            <i class="bi bi-gender-female text-danger"></i> Femenino
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de nacimiento</span>
                    <span class="info-value">
                        <?php echo e($persona->Fecha_nacimiento ? \Carbon\Carbon::parse($persona->Fecha_nacimiento)->format('d/m/Y') : '—'); ?>

                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Edad</span>
                    <span class="info-value">
                        <?php echo e($edad ? $edad . ' años' : '—'); ?>

                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Celular</span>
                    <span class="info-value">
                        <?php if($persona->Celular): ?>
                            <a href="tel:<?php echo e($persona->Celular); ?>" class="text-decoration-none">
                                <i class="bi bi-phone"></i> <?php echo e($persona->Celular); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-muted">No registrado</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dirección</span>
                    <span class="info-value"><?php echo e($persona->Direccion_domicilio ?? '—'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de registro</span>
                    <span class="info-value">
                        <?php echo e($persona->Fecha_registro ? \Carbon\Carbon::parse($persona->Fecha_registro)->format('d/m/Y') : '—'); ?>

                    </span>
                </div>
            <?php else: ?>
                <p class="text-muted text-center mt-3 mb-0">Datos personales no disponibles</p>
            <?php endif; ?>
        </div>

        
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-mortarboard-fill"></i> Información académica
            </div>
            <div class="info-item">
                <span class="info-label">Programa</span>
                <span class="info-value">
                    <strong><?php echo e($programa->Nombre ?? '—'); ?></strong>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Tipo de programa</span>
                <span class="info-value">
                    <?php if($programa && $programa->Tipo): ?>
                        <span class="badge bg-info"><?php echo e($programa->Tipo); ?></span>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Sucursal</span>
                <span class="info-value">
                    <?php if($sucursal): ?>
                        <i class="bi bi-building"></i> <?php echo e($sucursal->Nombre); ?>

                        <?php if($sucursal->Direccion): ?>
                            <br><small class="text-muted"><?php echo e($sucursal->Direccion); ?></small>
                        <?php endif; ?>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Estado actual</span>
                <span class="info-value">
                    <span class="badge <?php echo e($esActivo ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($estudiante->Estado); ?>

                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Cambio de estado</span>
                <span class="info-value">
                    <?php echo e($estudiante->Fecha_estado ? \Carbon\Carbon::parse($estudiante->Fecha_estado)->format('d/m/Y') : '—'); ?>

                </span>
            </div>
            <?php if($programa && $programa->Duracion): ?>
                <div class="info-item">
                    <span class="info-label">Duración del programa</span>
                    <span class="info-value"><?php echo e($programa->Duracion); ?></span>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-people-fill"></i> Tutor / Responsable
            </div>
            <?php if($tutor && $tutorPersona): ?>
                <div class="info-item">
                    <span class="info-label">Nombre completo</span>
                    <span class="info-value">
                        <strong><?php echo e(trim(($tutorPersona->Nombre ?? '') . ' ' . ($tutorPersona->Apellido ?? ''))); ?></strong>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Celular</span>
                    <span class="info-value">
                        <?php if($tutorPersona->Celular): ?>
                            <a href="tel:<?php echo e($tutorPersona->Celular); ?>" class="text-decoration-none">
                                <i class="bi bi-phone"></i> <?php echo e($tutorPersona->Celular); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-muted">No registrado</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Parentesco</span>
                    <span class="info-value"><?php echo e($tutor->Parentesco ?? '—'); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIT / CI</span>
                    <span class="info-value"><?php echo e($tutor->Nit ?? '—'); ?></span>
                </div>
                <?php if($tutor->Nombre_factura): ?>
                    <div class="info-item">
                        <span class="info-label">Nombre para factura</span>
                        <span class="info-value"><?php echo e($tutor->Nombre_factura); ?></span>
                    </div>
                <?php endif; ?>
                <?php if($tutor->Descuento): ?>
                    <div class="info-item">
                        <span class="info-label">Descuento aplicable</span>
                        <span class="info-value">
                            <span class="badge bg-success"><?php echo e($tutor->Descuento); ?>%</span>
                        </span>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-muted text-center mt-3 mb-0">
                    <i class="bi bi-exclamation-circle"></i><br>
                    No hay tutor asignado
                </p>
            <?php endif; ?>
        </div>

        
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-calendar-week-fill"></i> Horarios de clase
            </div>
            <?php if($horarios->isEmpty()): ?>
                <p class="text-muted text-center mt-3 mb-0">
                    <i class="bi bi-calendar-x"></i><br>
                    No hay horarios asignados
                </p>
            <?php else: ?>
                <?php $__currentLoopData = $horarios->sortBy('Dia'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $horario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $programaHorario = $horario->programa?->Nombre ?? 'Sin programa';
                        $profesorHorario = $horario->profesor?->persona;
                        $profesorNombre = $profesorHorario ? ($profesorHorario->Nombre . ' ' . $profesorHorario->Apellido) : 'No asignado';
                    ?>

                    <div class="mb-3 p-2 border-start border-success border-3 bg-light rounded">
                        <div class="info-item mb-1">
                            <span class="info-label">
                                <i class="bi bi-calendar-day"></i> <?php echo e(ucfirst(strtolower($horario->Dia))); ?>

                            </span>
                            <span class="info-value">
                                <strong><?php echo e(\Carbon\Carbon::parse($horario->Hora)->format('H:i')); ?></strong>
                            </span>
                        </div>

                        <div class="info-item mb-1">
                            <span class="info-label">
                                <i class="bi bi-person-badge"></i> Profesor
                            </span>
                            <span class="info-value"><?php echo e($profesorNombre); ?></span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-book"></i> Programa
                            </span>
                            <span class="info-value"><?php echo e($programaHorario); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

    </div>

</div>


<div class="action-footer d-flex justify-content-center gap-3 flex-wrap">
    <a href="<?php echo e(route('estudiantes.planesPago', $estudiante->Id_estudiantes)); ?>" 
       class="btn btn-primary">
        <i class="bi bi-cash-stack"></i> Planes de Pago
    </a>
    <a href="<?php echo e(route('estudiantes.evaluaciones', $estudiante->Id_estudiantes)); ?>" 
       class="btn btn-info text-white">
        <i class="bi bi-clipboard-check-fill"></i> Evaluaciones
    </a>
    <form action="<?php echo e(route('estudiantes.cambiarEstado', $estudiante->Id_estudiantes)); ?>" 
          method="POST" 
          onsubmit="return confirm('¿Está seguro de cambiar el estado del estudiante?')"
          class="d-inline">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-toggle-<?php echo e($esActivo ? 'off' : 'on'); ?>"></i> 
            <?php echo e($esActivo ? 'Desactivar' : 'Activar'); ?>

        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/administrador/detallesEstudiante.blade.php ENDPATH**/ ?>