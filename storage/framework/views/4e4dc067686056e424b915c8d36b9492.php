<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencia</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .badge { padding: 3px 6px; border-radius: 3px; color: white; display: inline-block; }
        .bg-success { background-color: #198754; color: white; }
        .bg-danger { background-color: #dc3545; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-info { background-color: #0dcaf0; color: black; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte General de Asistencia</h2>
        <p>Generado el: <?php echo e(date('d/m/Y H:i')); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Estudiante</th>
                <th>Profesor</th>
                <th>Programa</th>
                <th>Estado</th>
                <th>Observación</th>
                <th>Reprogramado</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(\Carbon\Carbon::parse($asistencia->Fecha)->format('d/m/Y')); ?></td>
                    <td>
                        <?php echo e($asistencia->estudiante->persona->Nombre); ?> <?php echo e($asistencia->estudiante->persona->Apellido); ?>

                        <br><small><?php echo e($asistencia->estudiante->Cod_estudiante); ?></small>
                    </td>
                    <td><?php echo e($asistencia->profesor->persona->Nombre); ?> <?php echo e($asistencia->profesor->persona->Apellido); ?></td>
                    <td><?php echo e($asistencia->programa->Nombre ?? '-'); ?></td>
                    <td>
                        <?php if($asistencia->Estado == 'Asistio'): ?>
                            <span class="badge bg-success">Asistió</span>
                        <?php elseif($asistencia->Estado == 'Falta'): ?>
                            <span class="badge bg-danger">Falta</span>
                        <?php elseif($asistencia->Estado == 'Licencia'): ?>
                            <span class="badge bg-warning">Licencia</span>
                        <?php elseif($asistencia->Estado == 'Reprogramado'): ?>
                            <span class="badge bg-info">Reprogramado</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($asistencia->Observacion ?? '-'); ?></td>
                    <td>
                        <?php if($asistencia->Fecha_reprogramada): ?>
                            <?php echo e(\Carbon\Carbon::parse($asistencia->Fecha_reprogramada)->format('d/m/Y')); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\DANTE\Desktop\Laravel\YebSis\resources\views/administrador/pdf/asistenciaReporte.blade.php ENDPATH**/ ?>