<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('styles'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="<?php echo e(auto_asset('css/administrador/dashboard.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="mt-2 text-start">

        
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1><i class="fas fa-chart-line text-primary"></i> Dashboard</h1>
                <p class="text-muted mb-0">Visualizando datos de <strong><?php echo e($estadisticasTiempo['mes_nombre']); ?>

                        <?php echo e($estadisticasTiempo['anio_actual']); ?></strong></p>
            </div>
            <div class="col-md-6 text-end">
                <form action="<?php echo e(route('admin.dashboard')); ?>" method="GET" class="d-inline-flex gap-2">
                    <select name="mes" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php for($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo e($m); ?>" <?php echo e($estadisticasTiempo['mes_num'] == $m ? 'selected' : ''); ?>>
                                <?php echo e(Carbon\Carbon::create()->month($m)->monthName); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                    <input type="number" name="anio" class="form-control form-control-sm" min="2000"
                        value="<?php echo e($estadisticasTiempo['anio_actual']); ?>" onchange="this.form.submit()">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm btn-outline-secondary"
                        title="Reiniciar filtros">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </form>
            </div>
        </div>

        
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
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

            <div class="col-md-4 mb-3">
                <div class="card metric-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted"><i class="fas fa-minus-circle"></i> Egresos Este Mes</h6>
                                <h3 class="text-danger">Bs <?php echo e(number_format($egresosMesActual, 2, '.', ',')); ?></h3>
                                <small class="text-muted">Gastos registrados</small>
                            </div>
                            <div class="text-danger">
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card metric-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted"><i class="fas fa-hand-holding-usd"></i> Balance Mes</h6>
                                <h3 class="<?php echo e($balanceMesActual >= 0 ? 'text-success' : 'text-danger'); ?>">
                                    Bs <?php echo e(number_format($balanceMesActual, 2, '.', ',')); ?>

                                </h3>
                                <small class="text-muted">Neto mensual</small>
                            </div>
                            <div class="<?php echo e($balanceMesActual >= 0 ? 'text-success' : 'text-danger'); ?>">
                                <i class="fas fa-balance-scale fa-2x"></i>
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
                                    <?php
                                        $fechaHoraClase = \Carbon\Carbon::parse($clase->Fecha_clase . ' ' . $clase->Hora_clase);
                                        $esPasada = $fechaHoraClase->isPast();
                                    ?>
                                    <div class="list-group-item <?php echo e($esPasada ? 'bg-danger-subtle' : ''); ?>">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <strong><?php echo e($clase->Nombre_Estudiante); ?></strong>
                                                <?php if($esPasada && $clase->Asistencia === 'pendiente'): ?>
                                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1"
                                                        title="Clase atrasada"></i>
                                                <?php endif; ?>
                                                <div class="small text-muted">
                                                    <i class="bi bi-calendar-event me-1"></i><?php echo e(\Carbon\Carbon::parse($clase->Fecha_clase)->format('d/m/Y')); ?>

                                                    <i class="bi bi-clock ms-2 me-1"></i><?php echo e(\Carbon\Carbon::parse($clase->Hora_clase)->format('H:i')); ?>

                                                </div>
                                                <?php if($clase->Asistencia !== 'pendiente'): ?>
                                                    <div class="mt-1">
                                                        <span class="badge <?php echo e($clase->Asistencia === 'asistio' ? 'bg-success' : 'bg-danger'); ?>">
                                                            <?php echo e($clase->Asistencia === 'asistio' ? 'Asistió' : 'No asistió'); ?>

                                                        </span>
                                                        <small class="text-muted ms-1">
                                                            Marcado por:
                                                            <?php
                                                                $marcadoPor = $clase->usuarioAsistencia?->persona?->nombre_completo
                                                                    ?? $clase->usuarioAsistencia?->Correo
                                                                    ?? 'Sistema';
                                                            ?>
                                                            <strong><?php echo e($marcadoPor); ?></strong>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <?php if($clase->Asistencia === 'pendiente'): ?>
                                                    <button onclick="confirmarAsistenciaAdmin(<?php echo e($clase->Id_clasePrueba); ?>, 'asistio')"
                                                        class="btn btn-outline-success" title="Marcar como Asistió">
                                                        <i class="bi bi-check-lg me-1"></i> Asistió
                                                    </button>
                                                    <button onclick="confirmarAsistenciaAdmin(<?php echo e($clase->Id_clasePrueba); ?>, 'no_asistio')"
                                                        class="btn btn-outline-danger" title="Marcar como Falta">
                                                        <i class="bi bi-x-lg me-1"></i> No Asistió
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="descartarNotificacion(<?php echo e($clase->Id_clasePrueba); ?>)"
                                                        class="btn btn-primary btn-sm px-3" title="Quitar del dashboard">
                                                        <i class="bi bi-check-circle me-1"></i> OK
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if($clase->Asistencia === 'pendiente'): ?>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                    id="comentario_admin_<?php echo e($clase->Id_clasePrueba); ?>" value="<?php echo e($clase->Comentarios); ?>"
                                                    placeholder="Añadir comentario (Recomendado)">
                                                <button class="btn btn-outline-secondary"
                                                    onclick="guardarComentarioAdmin(<?php echo e($clase->Id_clasePrueba); ?>)"
                                                    title="Guardar comentario solo">
                                                    <i class="bi bi-save"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-center">
                            <small class="text-muted">Mostrando todas las clases de prueba pendientes</small>
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
                        <i class="fas fa-chart-bar"></i> Comparativa Ingresos vs Egresos por Mes
                        (<?php echo e($estadisticasTiempo['anio_actual']); ?>)
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="comparativaAnual"></canvas>
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
                                        $porcentajeMes = ($estadisticasTiempo['dias_transcurridos_mes'] / $estadisticasTiempo['dias_totales_mes']) * 100;
                                    ?>
                                    <div class="progress-bar bg-success" style="width: <?php echo e($porcentajeMes); ?>%"></div>
                                </div>
                                <small><?php echo e(round($porcentajeMes, 1)); ?>% del mes seleccionado completado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row mb-4">
            <div class="col-12">

                
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <h5 class="mb-0"><i class="fas fa-users text-primary me-2"></i>Alumnos por Programa</h5>

                    <div class="d-flex flex-wrap gap-2 align-items-center">

                        
                        <div class="btn-group btn-group-sm" role="group" id="filtroEstado">
                            <button type="button" class="btn btn-primary active" data-estado="todos">
                                <i class="fas fa-users me-1"></i>Total
                            </button>
                            <button type="button" class="btn btn-outline-success" data-estado="activo">
                                <i class="fas fa-check-circle me-1"></i>Activos
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-estado="inactivo">
                                <i class="fas fa-times-circle me-1"></i>Inactivos
                            </button>
                        </div>

                        
                        <div class="btn-group btn-group-sm" role="group" id="filtroSucursal">
                            <button type="button" class="btn btn-secondary active" data-sucursal="todas">
                                <i class="fas fa-globe me-1"></i>Todas
                            </button>
                            <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="btn btn-outline-secondary"
                                    data-sucursal="<?php echo e($sucursal->Id_sucursales); ?>">
                                    <?php echo e($sucursal->Nombre); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                    </div>
                </div>

                
                <div class="row mb-3" id="resumenGlobal">
                    <div class="col-4">
                        <div class="card border-0 bg-primary bg-opacity-10 text-center py-2">
                            <div class="fw-bold fs-5 text-primary" id="resumenTotal">
                                <?php echo e($sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('total'))); ?>

                            </div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 bg-success bg-opacity-10 text-center py-2">
                            <div class="fw-bold fs-5 text-success" id="resumenActivos">
                                <?php echo e($sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('activos'))); ?>

                            </div>
                            <small class="text-muted">Activos</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 bg-danger bg-opacity-10 text-center py-2">
                            <div class="fw-bold fs-5 text-danger" id="resumenInactivos">
                                <?php echo e($sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('inactivos'))); ?>

                            </div>
                            <small class="text-muted">Inactivos</small>
                        </div>
                    </div>
                </div>

                
                <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mt-3 shadow-sm sucursal-card" data-sucursal-id="<?php echo e($sucursal->Id_sucursales); ?>">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-map-marker-alt me-1"></i><?php echo e($sucursal->Nombre); ?>

                            </span>
                            <span class="badge bg-white text-primary" id="badge-suc-<?php echo e($sucursal->Id_sucursales); ?>">
                                <?php echo e($alumnosPorSucursal[$sucursal->Id_sucursales]->sum('total')); ?> alumnos
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Programa</th>
                                            <th class="text-center">
                                                <span class="badge bg-primary">Total</span>
                                            </th>
                                            <th class="text-center">
                                                <span class="badge bg-success">Activos</span>
                                            </th>
                                            <th class="text-center">
                                                <span class="badge bg-danger">Inactivos</span>
                                            </th>
                                            <th class="text-center col-filtrado">
                                                <span class="badge bg-dark">Filtrado</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $alumnosPorSucursal[$sucursal->Id_sucursales]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr
                                                data-total="<?php echo e($row->total ?? 0); ?>"
                                                data-activos="<?php echo e($row->activos ?? 0); ?>"
                                                data-inactivos="<?php echo e($row->inactivos ?? 0); ?>"
                                            >
                                                <td><?php echo e($row->programa); ?></td>
                                                <td class="text-center">
                                                    <strong><?php echo e($row->total ?? 0); ?></strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-success fw-semibold"><?php echo e($row->activos ?? 0); ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-danger fw-semibold"><?php echo e($row->inactivos ?? 0); ?></span>
                                                </td>
                                                <td class="text-center col-filtrado">
                                                    <span class="badge bg-secondary fila-filtrada"><?php echo e($row->total ?? 0); ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox me-1"></i>No hay datos disponibles
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot class="table-light fw-bold border-top">
                                        <tr>
                                            <td>Subtotal</td>
                                            <td class="text-center">
                                                <?php echo e($alumnosPorSucursal[$sucursal->Id_sucursales]->sum('total')); ?>

                                            </td>
                                            <td class="text-center text-success">
                                                <?php echo e($alumnosPorSucursal[$sucursal->Id_sucursales]->sum('activos')); ?>

                                            </td>
                                            <td class="text-center text-danger">
                                                <?php echo e($alumnosPorSucursal[$sucursal->Id_sucursales]->sum('inactivos')); ?>

                                            </td>
                                            <td class="text-center col-filtrado">
                                                <span class="badge bg-dark pie-filtrado"
                                                    id="pie-suc-<?php echo e($sucursal->Id_sucursales); ?>">
                                                    <?php echo e($alumnosPorSucursal[$sucursal->Id_sucursales]->sum('total')); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
        

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.ingresosPorDia = <?php echo json_encode($ingresosPorDia->pluck('total'), 15, 512) ?>;
            window.fechasPorDia = <?php echo json_encode($ingresosPorDia->pluck('fecha'), 15, 512) ?>;
            window.graficoAnual = <?php echo json_encode($graficoMensual, 15, 512) ?>;

            // ── Totales globales originales para el resumen ──────────────
            const TOTALES_GLOBALES = {
                todos:    <?php echo json_encode($sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('total')), 15, 512) ?>,
                activos:  <?php echo json_encode($sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('activos')), 15, 512) ?>,
                inactivos:<?php echo json_encode($sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('inactivos')), 15, 512) ?>,
            };

            // ── Filtros ──────────────────────────────────────────────────
            let estadoActivo   = 'todos';
            let sucursalActiva = 'todas';

            const campoEstado = { todos: 'total', activo: 'activos', inactivo: 'inactivos' };
            const colorEstado = { todos: 'primary', activo: 'success', inactivo: 'danger' };

            // Botones de estado
            document.querySelectorAll('#filtroEstado button').forEach(btn => {
                btn.addEventListener('click', function () {
                    estadoActivo = this.dataset.estado;
                    document.querySelectorAll('#filtroEstado button').forEach(b => {
                        const c = colorEstado[b.dataset.estado];
                        b.className = b.dataset.estado === estadoActivo
                            ? `btn btn-${c} active`
                            : `btn btn-outline-${c}`;
                    });
                    aplicarFiltros();
                });
            });

            // Botones de sucursal
            document.querySelectorAll('#filtroSucursal button').forEach(btn => {
                btn.addEventListener('click', function () {
                    sucursalActiva = this.dataset.sucursal;
                    document.querySelectorAll('#filtroSucursal button').forEach(b => {
                        b.className = b.dataset.sucursal === sucursalActiva
                            ? 'btn btn-secondary active'
                            : 'btn btn-outline-secondary';
                    });
                    aplicarFiltros();
                });
            });

            function aplicarFiltros() {
                const campo = campoEstado[estadoActivo];
                let totalGlobal = 0;

                document.querySelectorAll('.sucursal-card').forEach(card => {
                    const idSuc = card.dataset.sucursalId;

                    // Visibilidad de la tarjeta
                    const mostrarCard = sucursalActiva === 'todas' || sucursalActiva === idSuc;
                    card.style.display = mostrarCard ? '' : 'none';
                    if (!mostrarCard) return;

                    let subtotal = 0;

                    card.querySelectorAll('tbody tr[data-total]').forEach(fila => {
                        const valor = parseInt(fila.dataset[campo] ?? 0);
                        // En filtros activo/inactivo, ocultar filas con valor 0
                        const mostrarFila = estadoActivo === 'todos' || valor > 0;
                        fila.style.display = mostrarFila ? '' : 'none';
                        if (mostrarFila) {
                            subtotal += valor;
                            fila.querySelector('.fila-filtrada').textContent = valor;

                            // Colorear badge según estado activo
                            fila.querySelector('.fila-filtrada').className =
                                'badge fila-filtrada bg-' + (colorEstado[estadoActivo] ?? 'secondary');
                        }
                    });

                    totalGlobal += subtotal;

                    // Actualizar badge del header de la tarjeta
                    const badgeSuc = document.getElementById(`badge-suc-${idSuc}`);
                    if (badgeSuc) badgeSuc.textContent = subtotal + ' alumnos';

                    // Actualizar badge del pie de tabla
                    const pieSuc = document.getElementById(`pie-suc-${idSuc}`);
                    if (pieSuc) pieSuc.textContent = subtotal;
                });

                // Actualizar resumen global
                actualizarResumen(campo, totalGlobal);
            }

            function actualizarResumen(campo, totalVisible) {
                // Si hay filtro de sucursal, recalcular sumando solo las visibles
                let sumTotal = 0, sumActivos = 0, sumInactivos = 0;

                document.querySelectorAll('.sucursal-card').forEach(card => {
                    if (card.style.display === 'none') return;
                    card.querySelectorAll('tbody tr[data-total]').forEach(fila => {
                        sumTotal    += parseInt(fila.dataset.total    ?? 0);
                        sumActivos  += parseInt(fila.dataset.activos  ?? 0);
                        sumInactivos+= parseInt(fila.dataset.inactivos?? 0);
                    });
                });

                document.getElementById('resumenTotal').textContent    = sumTotal;
                document.getElementById('resumenActivos').textContent   = sumActivos;
                document.getElementById('resumenInactivos').textContent = sumInactivos;
            }

            // Inicializar
            aplicarFiltros();

            // ── Funciones clases de prueba ───────────────────────────────
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
                            toast: true, position: 'top-end',
                            showConfirmButton: false, timer: 3000
                        });
                        toast.fire({ icon: 'success', title: 'Comentario guardado' });
                    }
                });
            }

            function confirmarAsistenciaAdmin(id, estado) {
                const commentInput = document.getElementById(`comentario_admin_${id}`);
                const comentario   = commentInput.value.trim();
                const textoAccion  = estado === 'asistio' ? 'marcar como ASISTIÓ' : 'marcar como FALTA';

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
                    confirmOptions.text  = `Estás a punto de ${textoAccion} SIN COMENTARIOS. ¿Estás seguro? Es recomendable añadir una observación.`;
                    confirmOptions.icon  = 'warning';
                    confirmOptions.confirmButtonColor = '#d33';
                }

                Swal.fire(confirmOptions).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/administrador/clases-prueba/${id}/comentarios`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                            body: JSON.stringify({ comentarios: commentInput.value })
                        })
                        .then(() => fetch(`/administrador/clases-prueba/${id}/asistencia`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                            body: JSON.stringify({ asistencia: estado })
                        }))
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Listo!', text: 'La asistencia ha sido registrada.',
                                    icon: 'success', timer: 1500, showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('Error', data.message || 'Error desconocido al guardar.', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Hubo un problema de conexión o del servidor.', 'error'));
                    }
                });
            }

            function descartarNotificacion(id) {
                fetch(`/administrador/clases-prueba/${id}/dismiss`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = document.querySelector(`button[onclick="descartarNotificacion(${id})"]`).closest('.list-group-item');
                        if (item) {
                            item.style.transition = 'all 0.3s ease';
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.remove();
                                const badge = document.querySelector('.card-header .badge');
                                if (badge) {
                                    let count = parseInt(badge.innerText);
                                    badge.innerText = Math.max(0, count - 1);
                                    if (count - 1 === 0) location.reload();
                                }
                            }, 300);
                        }
                    }
                });
            }
        </script>
        <script src="<?php echo e(auto_asset('js/administrador/dashboard.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\danil\Desktop\Laravel\Yebolivia\resources\views/administrador/dashboard.blade.php ENDPATH**/ ?>