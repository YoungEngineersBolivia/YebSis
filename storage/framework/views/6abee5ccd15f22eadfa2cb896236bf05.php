<?php $__env->startSection('title', 'Estudiantes Activos'); ?>

<?php $__env->startSection('styles'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo e(auto_asset('css/comercial/estudiantesActivosComercial.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <!-- Mensajes de éxito/error -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e($errors->first()); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header con estadísticas -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2 class="mb-0">Reporte de Inscritos Activos</h2>
            <p class="text-muted mb-0">
                <i class="bi bi-people-fill"></i> Total activos: 
                <strong><?php echo e($estudiantesActivos->count()); ?></strong>
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if($mesesDisponibles->isNotEmpty()): ?>
            <div class="dropdown">
                <button class="btn btn-ghost dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-calendar-month"></i> Filtro rápido
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Filtrar por mes</h6></li>
                    <?php $__currentLoopData = $mesesDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('estudiantesActivos', ['from' => $mes->mes . '-01', 'to' => date('Y-m-t', strtotime($mes->mes . '-01'))])); ?>">
                            <?php echo e($mes->mes_nombre); ?>

                            <span class="badge bg-primary rounded-pill float-end"><?php echo e($mes->total); ?></span>
                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?php echo e(route('estudiantesActivos')); ?>">
                            <i class="bi bi-x-circle"></i> Limpiar filtros
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="chart-wrap card-soft mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Estudiantes Activos</h5>
            <?php if($from || $to): ?>
            <span class="badge bg-info">
                <i class="bi bi-funnel-fill"></i> Filtrado
            </span>
            <?php endif; ?>
        </div>
        <canvas id="chartActivos"></canvas>
    </div>

    <!-- Filtros + tabla -->
    <div class="card-soft p-3">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET" action="<?php echo e(route('estudiantesActivos')); ?>">
                <div class="input-group" style="max-width: 200px;">
                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                    <input type="date" name="from" value="<?php echo e($from); ?>" class="form-control" placeholder="Desde">
                </div>
                <div class="input-group" style="max-width: 200px;">
                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                    <input type="date" name="to" value="<?php echo e($to); ?>" class="form-control" placeholder="Hasta">
                </div>
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-funnel"></i> Aplicar
                </button>
                <?php if($from || $to): ?>
                    <a class="btn btn-outline-secondary" href="<?php echo e(route('estudiantesActivos')); ?>">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </a>
                <?php endif; ?>
            </form>
            
            <!-- Botón de exportar (opcional) -->
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Sucursal</th>
                        <th>Fecha de activación</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $estudiantesActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted"><?php echo e($index + 1); ?></td>
                        <td class="fw-semibold"><?php echo e($estudiante->nombre); ?></td>
                        <td><?php echo e($estudiante->apellido); ?></td>
                        <td>
                            <span class="badge bg-secondary">
                                <i class="bi bi-building"></i> <?php echo e($estudiante->sucursal); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-calendar-check"></i> <?php echo e($estudiante->fecha_activacion_fmt); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <form action="<?php echo e(route('estudiantes.desactivar', $estudiante->id)); ?>" method="POST"
                                  onsubmit="return confirm('¿Está seguro que desea desactivar a <?php echo e($estudiante->nombre); ?> <?php echo e($estudiante->apellido); ?>?')"
                                  style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar estudiante">
                                    <i class="bi bi-person-x"></i> Desactivar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">No hay estudiantes activos en el período seleccionado.</p>
                            <?php if($from || $to): ?>
                                <a href="<?php echo e(route('estudiantesActivos')); ?>" class="btn btn-sm btn-primary mt-2">
                                    Ver todos los estudiantes
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rawSeries = <?php echo json_encode($fechasActivacion, 15, 512) ?>;
    
    // Formatear etiquetas de meses en español
    const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 
                        'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    const labels = rawSeries.map(s => {
        const date = new Date(s.mes_iso + 'T00:00:00');
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear();
        return `${month} ${year}`;
    });
    
    const data = rawSeries.map(s => Number(s.cantidad));

    const ctx = document.getElementById('chartActivos').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Estudiantes Activos',
                data,
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                borderColor: 'rgba(121, 80, 242, 1)',
                backgroundColor: 'rgba(121, 80, 242, 0.12)',
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(121, 80, 242, 1)',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            return 'Activaciones: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        precision: 0,
                        font: { size: 12 }
                    },
                    title: {
                        display: true,
                        text: 'Cantidad de estudiantes'
                    }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/comercial/estudiantesActivosComercial.blade.php ENDPATH**/ ?>