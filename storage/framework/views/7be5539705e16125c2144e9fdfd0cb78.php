

<?php $__env->startSection('title', 'Ingresos Mensuales'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1><i class="bi bi-calendar3 text-primary"></i> Ingresos Totales por Mes</h1>
            <p class="text-muted mb-0">Resumen histórico de recaudación mensual</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <form action="<?php echo e(route('pagos.mensuales')); ?>" method="GET" class="d-flex align-items-center gap-2">
                <label class="text-muted small fw-bold text-uppercase mb-0">Filtrar Año:</label>
                <input type="number" name="anio" class="form-control form-control-sm shadow-sm" style="width: 100px;"
                    min="2000" value="<?php echo e($anioSeleccionado); ?>" onchange="this.form.submit()">
                <a href="<?php echo e(route('pagos.mensuales')); ?>" class="btn btn-sm btn-outline-secondary shadow-sm"
                    title="Limpiar filtro">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </form>

            <div class="btn-group shadow-sm">
                <a href="<?php echo e(route('pagos.formato')); ?>" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-arrow-down"></i> Formato
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportarPagos">
                    <i class="bi bi-file-earmark-arrow-up"></i> Importar
                </button>
            </div>

            <a href="<?php echo e(route('pagos.form')); ?>" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Gestionar Pagos
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Periodo (Mes/Año)</th>
                            <th class="text-center">Cant. Pagos</th>
                            <th class="text-center">Total Recaudado</th>
                            <th class="pe-4 text-end">Último Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ingresosMensuales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingreso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                                            <i class="bi bi-calendar-event fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-capitalize"><?php echo e($ingreso->nombre_mes); ?></span>
                                            <span class="text-muted ms-1"><?php echo e($ingreso->anio); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('pagos.index', ['mes' => $ingreso->mes, 'anio' => $ingreso->anio])); ?>"
                                        class="badge bg-info text-white rounded-pill px-3 text-decoration-none hover-shadow"
                                        title="Ver detalles de estos pagos">
                                        <?php echo e($ingreso->cantidad_pagos); ?> pagos <i class="bi bi-eye ms-1"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-success fs-5">
                                        Bs. <?php echo e(number_format($ingreso->total, 2, '.', ',')); ?>

                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <small class="text-muted">
                                        <i class="bi bi-clock-history me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($ingreso->ultima_fecha)->format('d/m/Y H:i')); ?>

                                    </small>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                                    No se encontraron registros de pagos.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if($ingresosMensuales->isNotEmpty()): ?>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="ps-4 fw-bold text-end">Total Histórico:</td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary fs-4">
                                        Bs. <?php echo e(number_format($ingresosMensuales->sum('total'), 2, '.', ',')); ?>

                                    </span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Importar Pagos -->
    <div class="modal fade" id="modalImportarPagos" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="<?php echo e(route('pagos.importar')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Carga Masiva de
                            Ingresos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="importContent">
                            <div class="alert alert-info small border-0 shadow-sm mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Instrucciones:</strong>
                                <ol class="mb-0 mt-1">
                                    <li>Descarga el formato usando el botón <strong>"Formato"</strong>.</li>
                                    <li>Llena los totales mensuales o pagos individuales en Excel.</li>
                                    <li>Guarda el archivo y súbelo aquí.</li>
                                </ol>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Seleccionar archivo CSV</label>
                                <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
                                <small class="text-muted">Solo archivos .csv (Excel delimitado por comas)</small>
                            </div>
                        </div>

                        <!-- Barra de Carga (Oculta por defecto) -->
                        <div id="importProgress" class="d-none text-center py-4">
                            <div class="spinner-border text-success mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <h5 class="fw-bold text-dark">Procesando información...</h5>
                            <p class="text-muted small">Esto puede tardar unos segundos, por favor no cierres la ventana.
                            </p>
                            <div class="progress mt-3" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0" id="importFooter">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success px-4 shadow-sm" id="btnIniciarCarga">
                            <i class="bi bi-cloud-upload me-1"></i> Iniciar Carga
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.querySelector('#modalImportarPagos form').addEventListener('submit', function () {
            // Ocultar contenido y mostrar barra de carga
            document.getElementById('importContent').classList.add('d-none');
            document.getElementById('importFooter').classList.add('d-none');
            document.getElementById('importProgress').classList.remove('d-none');

            // Bloquear el cierre del modal
            const modalElement = document.getElementById('modalImportarPagos');
            const modal = bootstrap.Modal.getInstance(modalElement);
            // Deshabilitar el botón de cerrar de la cabecera
            modalElement.querySelector('.btn-close').classList.add('d-none');
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/pagosMensuales.blade.php ENDPATH**/ ?>