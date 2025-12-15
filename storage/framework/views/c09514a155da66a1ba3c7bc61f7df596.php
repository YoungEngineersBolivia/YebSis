<?php $__env->startSection('title', 'Reporte de Talleres'); ?>
<?php $__env->startSection('styles'); ?>
    <link href="<?php echo e(asset('css/comercial/talleresComercial.css')); ?>" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Gráfico de Talleres -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">Reporte de Talleres</h4>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                id="periodoDropdown" data-bs-toggle="dropdown">
                            <?php echo e($periodo); ?> meses
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?periodo=3">3 meses</a></li>
                            <li><a class="dropdown-item" href="?periodo=6">6 meses</a></li>
                            <li><a class="dropdown-item" href="?periodo=12">12 meses</a></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Gráfica -->
                    <div class="mb-4">
                        <div style="height: 400px;">
                            <canvas id="talleresChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas de Talleres -->
    <div class="row">
        <div class="col-6">
            <!-- Tabla Talleres 2024 -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0 fw-bold">Talleres 2024</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Taller</th>
                                    <th>Estudiante</th>
                                    <th>Fecha Inscripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $talleres2024; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($estudiante->taller); ?></td>
                                    <td><?php echo e($estudiante->nombre_estudiante); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($estudiante->Fecha_inscripcion)->format('d/m/Y')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <!-- Tabla Talleres 2025 -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0 fw-bold">Talleres 2025</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Taller</th>
                                    <th>Estudiante</th>
                                    <th>Fecha Inscripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $talleres2025; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudiante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($estudiante->taller); ?></td>
                                    <td><?php echo e($estudiante->nombre_estudiante); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($estudiante->Fecha_inscripcion)->format('d/m/Y')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
        const datosGrafica = <?php echo json_encode($datosGrafica, 15, 512) ?>;
</script>
<script src="<?php echo e(auto_asset('js/comercial/talleresComercial.js')); ?>"></script>

<style>

</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('/administrador/baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/comercial/talleresComercial.blade.php ENDPATH**/ ?>