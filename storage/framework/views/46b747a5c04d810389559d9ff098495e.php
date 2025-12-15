<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('styles'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="<?php echo e(auto_asset('css/administrador/dashboard.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-chart-line"></i> Dashboard Administrativo</h1>
        </div>
        <div class="text-end">
            <p class="mb-0"><strong><?php echo e($estadisticasTiempo['fecha_actual']); ?></strong></p>
            <small class="text-muted"><?php echo e($estadisticasTiempo['mes_actual']); ?> <?php echo e($estadisticasTiempo['año_actual']); ?> | Semana <?php echo e($estadisticasTiempo['semana_año']); ?></small>
        </div>
    </div>

    
    
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted"><i class="fas fa-calendar-alt"></i> Ingresos Este Mes</h6>
                            <h3>Bs <?php echo e(number_format($ingresosMesActual, 2, '.', ',')); ?></h3>
                            <?php if($crecimientoIngresos != 0): ?>
                                <small class="<?php echo e($crecimientoIngresos > 0 ? 'growth-positive' : 'growth-negative'); ?>">
                                    <i class="fas fa-arrow-<?php echo e($crecimientoIngresos > 0 ? 'up' : 'down'); ?>"></i>
                                    <?php echo e(abs(round($crecimientoIngresos, 1))); ?>% vs mes anterior
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted"><i class="fas fa-chart-bar"></i> Total Ingresos</h6>
                            <h3>Bs <?php echo e(number_format($ingresosTotales, 2, '.', ',')); ?></h3>
                            <small class="text-muted">Histórico completo</small>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted"><i class="fas fa-chart-line"></i> Proyección Mes</h6>
                            <h3>Bs <?php echo e(number_format($proyeccionMes, 2, '.', ',')); ?></h3>
                            <small class="text-muted">Basada en <?php echo e($estadisticasTiempo['dias_transcurridos_mes']); ?> días</small>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-crystal-ball fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted"><i class="fas fa-building"></i> Sucursales</h6>
                            <h3><?php echo e(count($sucursales)); ?></h3>
                            <small class="text-muted">Total activas</small>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(isset($clasesPruebaPendientes) && $clasesPruebaPendientes->count() > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning shadow-sm">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Clases de Prueba Pendientes</h5>
                        <span class="badge bg-dark"><?php echo e($clasesPruebaPendientes->count()); ?></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $clasesPruebaPendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong><?php echo e($clase->Nombre_Estudiante); ?></strong>
                                            <div class="small text-muted">
                                                <i class="bi bi-calendar-event me-1"></i><?php echo e(\Carbon\Carbon::parse($clase->Fecha_clase)->format('d/m/Y')); ?>

                                                <i class="bi bi-clock ms-2 me-1"></i><?php echo e(\Carbon\Carbon::parse($clase->Hora_clase)->format('H:i')); ?>

                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button onclick="confirmarAsistenciaAdmin(<?php echo e($clase->Id_clasePrueba); ?>, 'asistio')" class="btn btn-outline-success" title="Marcar como Asistió">
                                                <i class="bi bi-check-lg me-1"></i> Asistió
                                            </button>
                                            <button onclick="confirmarAsistenciaAdmin(<?php echo e($clase->Id_clasePrueba); ?>, 'no_asistio')" class="btn btn-outline-danger" title="Marcar como Falta">
                                                <i class="bi bi-x-lg me-1"></i> No Asistió
                                            </button>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="comentario_admin_<?php echo e($clase->Id_clasePrueba); ?>" value="<?php echo e($clase->Comentarios); ?>" placeholder="Añadir comentario (Recomendado)">
                                        <button class="btn btn-outline-secondary" onclick="guardarComentarioAdmin(<?php echo e($clase->Id_clasePrueba); ?>)" title="Guardar comentario solo">
                                            <i class="bi bi-save"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center">
                        <small class="text-muted">Mostrando las 5 más próximas</small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-chart-area"></i> Ingresos Últimos 30 Días
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ingresosDiarios"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-trophy"></i> Top 5 Mejores Días del Mes
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php $__empty_1 = true; $__currentLoopData = $topDiasIngresos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo e($dia['fecha']); ?></strong><br>
                                    <small class="text-muted"><?php echo e($dia['dia_semana']); ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill"><?php echo e($index + 1); ?></span>
                                    <strong>Bs <?php echo e(number_format($dia['total'], 2, '.', ',')); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="list-group-item text-center text-muted">
                                No hay datos disponibles
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-bar"></i> Ingresos por Mes (<?php echo e($estadisticasTiempo['año_actual']); ?>)
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ingresosMensuales"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-clock"></i> Información Temporal
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-primary"><?php echo e($estadisticasTiempo['dias_transcurridos_mes']); ?></h4>
                            <small>Días del mes</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-danger"><?php echo e($estadisticasTiempo['dias_restantes_mes']); ?></h4>
                            <small>Días restantes</small>
                        </div>
                        <div class="col-12 mb-3">
                            <h4 class="text-info">Q<?php echo e($estadisticasTiempo['trimestre_actual']); ?></h4>
                            <small>Trimestre actual</small>
                        </div>
                        <div class="col-12">
                            <div class="progress mb-2">
                                <?php
                                    $porcentajeMes = ($estadisticasTiempo['dias_transcurridos_mes'] / ($estadisticasTiempo['dias_transcurridos_mes'] + $estadisticasTiempo['dias_restantes_mes'])) * 100;
                                ?>
                                <div class="progress-bar bg-success" style="width: <?php echo e($porcentajeMes); ?>%"></div>
                            </div>
                            <small><?php echo e(round($porcentajeMes, 1)); ?>% del mes completado</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mt-3 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-users"></i> Alumnos por programa en <?php echo e($sucursal->Nombre); ?>

            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Programa</th>
                            <th>Total de Alumnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $alumnosPorSucursal[$sucursal->Id_sucursales]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($row->programa); ?></td>
                                <td><strong><?php echo e($row->total); ?></strong></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted">No hay datos disponibles</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.ingresosPorDia = <?php echo json_encode($ingresosPorDia->pluck('total'), 15, 512) ?>;
    window.fechasPorDia = <?php echo json_encode($ingresosPorDia->pluck('fecha'), 15, 512) ?>;
    window.ingresosPorMes = <?php echo json_encode($ingresosPorMes->pluck('total'), 15, 512) ?>;
    window.mesesPorMes = <?php echo json_encode($ingresosPorMes->pluck('mes_nombre'), 15, 512) ?>;

    function guardarComentarioAdmin(id) {
        const comment = document.getElementById(`comentario_admin_${id}`).value;
        fetch(`/administrador/clases-prueba/${id}/comentarios`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ comentarios: comment })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                toast.fire({
                    icon: 'success',
                    title: 'Comentario guardado'
                });
            }
        });
    }

    function confirmarAsistenciaAdmin(id, estado) {
        const commentInput = document.getElementById(`comentario_admin_${id}`);
        const comentario = commentInput.value.trim();
        const textoAccion = estado === 'asistio' ? 'marcar como ASISTIÓ' : 'marcar como FALTA';

        let confirmOptions = {
            title: '¿Confirmar asistencia?',
            text: `Vas a ${textoAccion}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
        };

        if (comentario === '') {
            confirmOptions.title = '¡Atención!';
            confirmOptions.text = `Estás a punto de ${textoAccion} SIN COMENTARIOS. ¿Estás seguro? Es recomendable añadir una observación.`;
            confirmOptions.icon = 'warning';
            confirmOptions.confirmButtonColor = '#d33';
        }

        Swal.fire(confirmOptions).then((result) => {
            if (result.isConfirmed) {
                // Paso 1: Guardar comentario
                fetch(`/administrador/clases-prueba/${id}/comentarios`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: JSON.stringify({ comentarios: commentInput.value })
                })
                .then(() => {
                    // Paso 2: Guardar asistencia
                    return fetch(`/administrador/clases-prueba/${id}/asistencia`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                        body: JSON.stringify({ asistencia: estado })
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '¡Listo!',
                            text: 'La asistencia ha sido registrada.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        
                        // Eliminar el elemento de la lista visualmente
                        const row = commentInput.closest('.list-group-item');
                        if (row) {
                            row.remove();
                            
                            // Actualizar el contador
                            const badge = document.querySelector('.card-header .badge');
                            if (badge) {
                                let count = parseInt(badge.innerText);
                                if (!isNaN(count)) {
                                    badge.innerText = Math.max(0, count - 1);
                                    if (count - 1 === 0) {
                                        // Ocultar todo el widget si ya no hay
                                        const widget = document.querySelector('.card.border-warning').closest('.row');
                                        if (widget) widget.remove();
                                    }
                                }
                            }
                        }
                    } else {
                        Swal.fire('Error', data.message || 'Error desconocido al guardar.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Intentar leer el mensaje de error de la respuesta si es posible, sino genérico
                    Swal.fire('Error', 'Hubo un problema de conexión o del servidor.', 'error');
                });
            }
        });
    }
</script>
<script src="<?php echo e(auto_asset('js/administrador/dashboard.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/dashboard.blade.php ENDPATH**/ ?>