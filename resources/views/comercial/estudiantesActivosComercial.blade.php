@extends('administrador.baseAdministrador')
@section('title', 'Estudiantes Activos')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .card-soft {
            border: 1px solid #eee;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        }
        .chart-wrap {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
        }
        .toolbar {
            display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
        }
        .toolbar .form-select, .toolbar .form-control {
            border-radius: 12px;
        }
        .table thead th { white-space: nowrap; }
        .btn-ghost {
            border-radius: 10px;
            border: 1px solid #eaeaea;
            background: #fff;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">

    <!-- Título y controles superiores -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Reporte de Inscritos Activos</h2>
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-ghost dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Mes
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item disabled">Demo</span></li>
                </ul>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="toggleDemo" disabled>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="chart-wrap card-soft mb-4">
        <canvas id="chartActivos" style="height: 200px"></canvas>
    </div>

    <!-- Filtros + tabla -->
    <div class="card-soft p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="toolbar">
                

                <!-- Formulario de filtros -->
                <form class="d-flex gap-2" method="GET" action="{{ route('estudiantesActivos') }}">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                        <input type="date" name="from" value="{{ $from }}" class="form-control" placeholder="Desde">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                        <input type="date" name="to" value="{{ $to }}" class="form-control" placeholder="Hasta">
                    </div>
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-funnel"></i> Aplicar
                    </button>
                    @if($from || $to)
                        <a class="btn btn-outline-secondary" href="{{ route('estudiantesNoActivos') }}">Limpiar</a>
                    @endif
                </form>
            </div>

            
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha de activación</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($estudiantesActivos as $estudiante)
                    <tr>
                        <td class="fw-semibold">{{ $estudiante->nombre }}</td>
                        <td>{{ $estudiante->apellido }}</td>
                        <td>{{ $estudiante->fecha_activacion_fmt }}</td>
                        <td class="text-center">
                            <form action="{{ route('estudiantes.desactivar', $estudiante->id) }}" method="POST"
                                  onsubmit="return confirm('¿Desactivar a este estudiante?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-arrow-counterclockwise"></i> Desactivar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Sin estudiantes activos.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rawSeries = @json($fechasActivacion);

    const monthFmt = new Intl.DateTimeFormat('es-BO', { month: 'short' });
    const labels = rawSeries.map(s => monthFmt.format(new Date(s.mes_iso + 'T00:00:00')));
    const data   = rawSeries.map(s => Number(s.cantidad));

    const ctx = document.getElementById('chartActivos').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Estudiantes Activos',
                data,
                tension: 0.35,
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 6,
                fill: true,
                borderColor: 'rgba(121, 80, 242, 1)',
                backgroundColor: 'rgba(121, 80, 242, 0.12)',
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(121, 80, 242, 1)'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection