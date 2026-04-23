@extends('administrador.baseAdministrador')

@section('title', 'Dashboard')
@section('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ auto_asset('css/administrador/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="mt-2 text-start">

        {{-- Header con filtros --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1><i class="fas fa-chart-line text-primary"></i> Dashboard</h1>
                <p class="text-muted mb-0">Visualizando datos de <strong>{{ $estadisticasTiempo['mes_nombre'] }}
                        {{ $estadisticasTiempo['anio_actual'] }}</strong></p>
            </div>
            <div class="col-md-6 text-end">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-inline-flex gap-2">
                    <select name="mes" class="form-select form-select-sm" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $estadisticasTiempo['mes_num'] == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->monthName }}
                            </option>
                        @endfor
                    </select>
                    <input type="number" name="anio" class="form-control form-control-sm" min="2000"
                        value="{{ $estadisticasTiempo['anio_actual'] }}" onchange="this.form.submit()">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary"
                        title="Reiniciar filtros">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </form>
            </div>
        </div>

        {{-- Métricas principales --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card metric-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted"><i class="fas fa-calendar-alt"></i> Ingresos Este Mes</h6>
                                <h3>Bs {{ number_format($ingresosMesActual, 2, '.', ',') }}</h3>
                                @if($crecimientoIngresos != 0)
                                    <small class="{{ $crecimientoIngresos > 0 ? 'growth-positive' : 'growth-negative' }}">
                                        <i class="fas fa-arrow-{{ $crecimientoIngresos > 0 ? 'up' : 'down' }}"></i>
                                        {{ abs(round($crecimientoIngresos, 1)) }}% vs mes anterior
                                    </small>
                                @endif
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
                                <h3 class="text-danger">Bs {{ number_format($egresosMesActual, 2, '.', ',') }}</h3>
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
                                <h3 class="{{ $balanceMesActual >= 0 ? 'text-success' : 'text-danger' }}">
                                    Bs {{ number_format($balanceMesActual, 2, '.', ',') }}
                                </h3>
                                <small class="text-muted">Neto mensual</small>
                            </div>
                            <div class="{{ $balanceMesActual >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-balance-scale fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notificación de Clases de Prueba Pendientes --}}
        @if(isset($clasesPruebaPendientes) && $clasesPruebaPendientes->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-warning shadow-sm">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Clases de Prueba Pendientes</h5>
                            <span class="badge bg-dark">{{ $clasesPruebaPendientes->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($clasesPruebaPendientes as $clase)
                                    @php
                                        $fechaHoraClase = \Carbon\Carbon::parse($clase->Fecha_clase . ' ' . $clase->Hora_clase);
                                        $esPasada = $fechaHoraClase->isPast();
                                    @endphp
                                    <div class="list-group-item {{ $esPasada ? 'bg-danger-subtle' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <strong>{{ $clase->Nombre_Estudiante }}</strong>
                                                @if($esPasada && $clase->Asistencia === 'pendiente')
                                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1"
                                                        title="Clase atrasada"></i>
                                                @endif
                                                <div class="small text-muted">
                                                    <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($clase->Fecha_clase)->format('d/m/Y') }}
                                                    <i class="bi bi-clock ms-2 me-1"></i>{{ \Carbon\Carbon::parse($clase->Hora_clase)->format('H:i') }}
                                                </div>
                                                @if($clase->Asistencia !== 'pendiente')
                                                    <div class="mt-1">
                                                        <span class="badge {{ $clase->Asistencia === 'asistio' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $clase->Asistencia === 'asistio' ? 'Asistió' : 'No asistió' }}
                                                        </span>
                                                        <small class="text-muted ms-1">
                                                            Marcado por:
                                                            @php
                                                                $marcadoPor = $clase->usuarioAsistencia?->persona?->nombre_completo
                                                                    ?? $clase->usuarioAsistencia?->Correo
                                                                    ?? 'Sistema';
                                                            @endphp
                                                            <strong>{{ $marcadoPor }}</strong>
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                @if($clase->Asistencia === 'pendiente')
                                                    <button onclick="confirmarAsistenciaAdmin({{ $clase->Id_clasePrueba }}, 'asistio')"
                                                        class="btn btn-outline-success" title="Marcar como Asistió">
                                                        <i class="bi bi-check-lg me-1"></i> Asistió
                                                    </button>
                                                    <button onclick="confirmarAsistenciaAdmin({{ $clase->Id_clasePrueba }}, 'no_asistio')"
                                                        class="btn btn-outline-danger" title="Marcar como Falta">
                                                        <i class="bi bi-x-lg me-1"></i> No Asistió
                                                    </button>
                                                @else
                                                    <button onclick="descartarNotificacion({{ $clase->Id_clasePrueba }})"
                                                        class="btn btn-primary btn-sm px-3" title="Quitar del dashboard">
                                                        <i class="bi bi-check-circle me-1"></i> OK
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($clase->Asistencia === 'pendiente')
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                    id="comentario_admin_{{ $clase->Id_clasePrueba }}" value="{{ $clase->Comentarios }}"
                                                    placeholder="Añadir comentario (Recomendado)">
                                                <button class="btn btn-outline-secondary"
                                                    onclick="guardarComentarioAdmin({{ $clase->Id_clasePrueba }})"
                                                    title="Guardar comentario solo">
                                                    <i class="bi bi-save"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-light text-center">
                            <small class="text-muted">Mostrando todas las clases de prueba pendientes</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Gráficos de ingresos --}}
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
                            @forelse($topDiasIngresos as $index => $dia)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $dia['fecha'] }}</strong><br>
                                        <small class="text-muted">{{ $dia['dia_semana'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success rounded-pill">{{ $index + 1 }}</span>
                                        <strong>Bs {{ number_format($dia['total'], 2, '.', ',') }}</strong>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    No hay datos disponibles
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfico mensual e información temporal --}}
        <div class="row mb-4">
            <div class="col-lg-8 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-chart-bar"></i> Comparativa Ingresos vs Egresos por Mes
                        ({{ $estadisticasTiempo['anio_actual'] }})
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
                                <h4 class="text-primary">{{ $estadisticasTiempo['dias_transcurridos_mes'] }}</h4>
                                <small>Días del mes</small>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-danger">{{ $estadisticasTiempo['dias_restantes_mes'] }}</h4>
                                <small>Días restantes</small>
                            </div>
                            <div class="col-12 mb-3">
                                <h4 class="text-info">Q{{ $estadisticasTiempo['trimestre_actual'] }}</h4>
                                <small>Trimestre actual</small>
                            </div>
                            <div class="col-12">
                                <div class="progress mb-2">
                                    @php
                                        $porcentajeMes = ($estadisticasTiempo['dias_transcurridos_mes'] / $estadisticasTiempo['dias_totales_mes']) * 100;
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $porcentajeMes }}%"></div>
                                </div>
                                <small>{{ round($porcentajeMes, 1) }}% del mes seleccionado completado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             Alumnos por programa — con filtros de estado y sucursal
             ══════════════════════════════════════════════════════════ --}}
        <div class="row mb-4">
            <div class="col-12">

                {{-- Cabecera de sección --}}
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <h5 class="mb-0"><i class="fas fa-users text-primary me-2"></i>Alumnos por Programa</h5>

                    <div class="d-flex flex-wrap gap-2 align-items-center">

                        {{-- Filtro por estado --}}
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

                        {{-- Filtro por sucursal --}}
                        <div class="btn-group btn-group-sm" role="group" id="filtroSucursal">
                            <button type="button" class="btn btn-secondary active" data-sucursal="todas">
                                <i class="fas fa-globe me-1"></i>Todas
                            </button>
                            @foreach($sucursales as $sucursal)
                                <button type="button" class="btn btn-outline-secondary"
                                    data-sucursal="{{ $sucursal->Id_sucursales }}">
                                    {{ $sucursal->Nombre }}
                                </button>
                            @endforeach
                        </div>

                    </div>
                </div>

                {{-- Resumen global (se actualiza con JS) --}}
                <div class="row mb-3" id="resumenGlobal">
                    <div class="col-4">
                        <div class="card border-0 bg-primary bg-opacity-10 text-center py-2">
                            <div class="fw-bold fs-5 text-primary" id="resumenTotal">
                                {{ $sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('total')) }}
                            </div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 bg-success bg-opacity-10 text-center py-2">
                            <div class="fw-bold fs-5 text-success" id="resumenActivos">
                                {{ $sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('activos')) }}
                            </div>
                            <small class="text-muted">Activos</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-0 bg-danger bg-opacity-10 text-center py-2">
                            <div class="fw-bold fs-5 text-danger" id="resumenInactivos">
                                {{ $sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('inactivos')) }}
                            </div>
                            <small class="text-muted">Inactivos</small>
                        </div>
                    </div>
                </div>

                {{-- Tabla por sucursal --}}
                @foreach($sucursales as $sucursal)
                    <div class="card mt-3 shadow-sm sucursal-card" data-sucursal-id="{{ $sucursal->Id_sucursales }}">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $sucursal->Nombre }}
                            </span>
                            <span class="badge bg-white text-primary" id="badge-suc-{{ $sucursal->Id_sucursales }}">
                                {{ $alumnosPorSucursal[$sucursal->Id_sucursales]->sum('total') }} alumnos
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
                                        @forelse($alumnosPorSucursal[$sucursal->Id_sucursales] as $row)
                                            <tr
                                                data-total="{{ $row->total ?? 0 }}"
                                                data-activos="{{ $row->activos ?? 0 }}"
                                                data-inactivos="{{ $row->inactivos ?? 0 }}"
                                            >
                                                <td>{{ $row->programa }}</td>
                                                <td class="text-center">
                                                    <strong>{{ $row->total ?? 0 }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-success fw-semibold">{{ $row->activos ?? 0 }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-danger fw-semibold">{{ $row->inactivos ?? 0 }}</span>
                                                </td>
                                                <td class="text-center col-filtrado">
                                                    <span class="badge bg-secondary fila-filtrada">{{ $row->total ?? 0 }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox me-1"></i>No hay datos disponibles
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="table-light fw-bold border-top">
                                        <tr>
                                            <td>Subtotal</td>
                                            <td class="text-center">
                                                {{ $alumnosPorSucursal[$sucursal->Id_sucursales]->sum('total') }}
                                            </td>
                                            <td class="text-center text-success">
                                                {{ $alumnosPorSucursal[$sucursal->Id_sucursales]->sum('activos') }}
                                            </td>
                                            <td class="text-center text-danger">
                                                {{ $alumnosPorSucursal[$sucursal->Id_sucursales]->sum('inactivos') }}
                                            </td>
                                            <td class="text-center col-filtrado">
                                                <span class="badge bg-dark pie-filtrado"
                                                    id="pie-suc-{{ $sucursal->Id_sucursales }}">
                                                    {{ $alumnosPorSucursal[$sucursal->Id_sucursales]->sum('total') }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        {{-- ═══════════════ Fin sección alumnos ═══════════════ --}}

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // ── Datos desde PHP ──────────────────────────────────────────
            window.ingresosPorDia  = @json($ingresosPorDia->pluck('total')->map(fn($v) => (float)$v)->values());
            window.fechasPorDia    = @json($ingresosPorDia->pluck('fecha')->values());
            window.graficoAnual    = {
                labels:   @json(collect(range(1,12))->map(fn($m) => \Carbon\Carbon::create()->month($m)->isoFormat('MMM'))->values()),
                ingresos: @json($graficoMensual['ingresos']->values()),
                egresos:  @json($graficoMensual['egresos']->values()),
            };

            // ── Gráfica: Ingresos últimos 30 días ────────────────────────
            (function () {
                const ctx = document.getElementById('ingresosDiarios');
                if (!ctx) return;

                const labels = window.fechasPorDia;
                const datos  = window.ingresosPorDia;

                if (!labels.length) {
                    ctx.parentElement.innerHTML =
                        '<p class="text-center text-muted py-4"><i class="fas fa-inbox me-1"></i>Sin ingresos en este período</p>';
                    return;
                }

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Ingresos (Bs)',
                            data: datos,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13,110,253,0.12)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#0d6efd',
                            fill: true,
                            tension: 0.3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ' Bs ' + parseFloat(ctx.parsed.y).toLocaleString('es-BO', {minimumFractionDigits:2})
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => 'Bs ' + v.toLocaleString('es-BO')
                                }
                            }
                        }
                    }
                });
            })();

            // ── Gráfica: Comparativa anual Ingresos vs Egresos ──────────
            (function () {
                const ctx = document.getElementById('comparativaAnual');
                if (!ctx) return;

                const { labels, ingresos, egresos } = window.graficoAnual;

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Ingresos (Bs)',
                                data: ingresos,
                                backgroundColor: 'rgba(13,110,253,0.75)',
                                borderColor: '#0d6efd',
                                borderWidth: 1,
                                borderRadius: 4,
                            },
                            {
                                label: 'Egresos (Bs)',
                                data: egresos,
                                backgroundColor: 'rgba(220,53,69,0.75)',
                                borderColor: '#dc3545',
                                borderWidth: 1,
                                borderRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ' Bs ' + parseFloat(ctx.parsed.y).toLocaleString('es-BO', {minimumFractionDigits:2})
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => 'Bs ' + v.toLocaleString('es-BO')
                                }
                            }
                        }
                    }
                });
            })();

            // ── Filtros alumnos ──────────────────────────────────────────
            const TOTALES_GLOBALES = {
                todos:    @json((int)$sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('total'))),
                activos:  @json((int)$sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('activos'))),
                inactivos:@json((int)$sucursales->sum(fn($s) => $alumnosPorSucursal[$s->Id_sucursales]->sum('inactivos'))),
            };

            let estadoActivo   = 'todos';
            let sucursalActiva = 'todas';

            const campoEstado = { todos: 'total', activo: 'activos', inactivo: 'inactivos' };
            const colorEstado = { todos: 'primary', activo: 'success', inactivo: 'danger' };

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

                document.querySelectorAll('.sucursal-card').forEach(card => {
                    const idSuc = card.dataset.sucursalId;
                    const mostrarCard = sucursalActiva === 'todas' || sucursalActiva === idSuc;
                    card.style.display = mostrarCard ? '' : 'none';
                    if (!mostrarCard) return;

                    let subtotal = 0;
                    card.querySelectorAll('tbody tr[data-total]').forEach(fila => {
                        const valor = parseInt(fila.dataset[campo] ?? 0);
                        const mostrarFila = estadoActivo === 'todos' || valor > 0;
                        fila.style.display = mostrarFila ? '' : 'none';
                        if (mostrarFila) {
                            subtotal += valor;
                            const badge = fila.querySelector('.fila-filtrada');
                            badge.textContent = valor;
                            badge.className = 'badge fila-filtrada bg-' + (colorEstado[estadoActivo] ?? 'secondary');
                        }
                    });

                    const badgeSuc = document.getElementById(`badge-suc-${idSuc}`);
                    if (badgeSuc) badgeSuc.textContent = subtotal + ' alumnos';
                    const pieSuc = document.getElementById(`pie-suc-${idSuc}`);
                    if (pieSuc) pieSuc.textContent = subtotal;
                });

                // Recalcular resumen global solo con tarjetas visibles
                let sumTotal = 0, sumActivos = 0, sumInactivos = 0;
                document.querySelectorAll('.sucursal-card').forEach(card => {
                    if (card.style.display === 'none') return;
                    card.querySelectorAll('tbody tr[data-total]').forEach(fila => {
                        sumTotal     += parseInt(fila.dataset.total     ?? 0);
                        sumActivos   += parseInt(fila.dataset.activos   ?? 0);
                        sumInactivos += parseInt(fila.dataset.inactivos ?? 0);
                    });
                });
                document.getElementById('resumenTotal').textContent     = sumTotal;
                document.getElementById('resumenActivos').textContent   = sumActivos;
                document.getElementById('resumenInactivos').textContent = sumInactivos;
            }

            // ── Guard para dashboard.js ──────────────────────────────────
            // Evita que dashboard.js vuelva a inicializar los canvas si lo hace
            window.__chartsInitialized = true;

            function guardarComentarioAdmin(id) {
                const comment = document.getElementById(`comentario_admin_${id}`).value;
                fetch(`/administrador/clases-prueba/${id}/comentarios`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ comentarios: comment })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })
                            .fire({ icon: 'success', title: 'Comentario guardado' });
                    }
                });
            }

            function confirmarAsistenciaAdmin(id, estado) {
                const commentInput = document.getElementById(`comentario_admin_${id}`);
                const comentario   = commentInput.value.trim();
                const textoAccion  = estado === 'asistio' ? 'marcar como ASISTIÓ' : 'marcar como FALTA';

                let opts = {
                    title: '¿Confirmar asistencia?', text: `Vas a ${textoAccion}.`,
                    icon: 'question', showCancelButton: true,
                    confirmButtonText: 'Sí, enviar', cancelButtonText: 'Cancelar'
                };
                if (comentario === '') {
                    opts = { ...opts,
                        title: '¡Atención!',
                        text: `Estás a punto de ${textoAccion} SIN COMENTARIOS. ¿Estás seguro?`,
                        icon: 'warning', confirmButtonColor: '#d33'
                    };
                }

                Swal.fire(opts).then(result => {
                    if (!result.isConfirmed) return;
                    fetch(`/administrador/clases-prueba/${id}/comentarios`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ comentarios: commentInput.value })
                    })
                    .then(() => fetch(`/administrador/clases-prueba/${id}/asistencia`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ asistencia: estado })
                    }))
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({ title: '¡Listo!', text: 'Asistencia registrada.',
                                icon: 'success', timer: 1500, showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message || 'Error desconocido.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'Problema de conexión con el servidor.', 'error'));
                });
            }

            function descartarNotificacion(id) {
                fetch(`/administrador/clases-prueba/${id}/dismiss`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const item = document.querySelector(`button[onclick="descartarNotificacion(${id})"]`)?.closest('.list-group-item');
                    if (!item) return;
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.remove();
                        const badge = document.querySelector('.card-header .badge');
                        if (badge) {
                            const count = parseInt(badge.innerText) - 1;
                            badge.innerText = Math.max(0, count);
                            if (count <= 0) location.reload();
                        }
                    }, 300);
                });
            }
        </script>
        {{-- dashboard.js solo maneja lógica que NO sea las gráficas (ya están arriba) --}}
        <script src="{{ auto_asset('js/administrador/dashboard.js') }}"></script>
@endsection