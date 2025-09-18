@extends('administrador.baseAdministrador')

@section('title', 'Dashboard')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.metric-card {
    transition: transform 0.2s;
    border-left: 4px solid #007bff;
}
.metric-card:hover {
    transform: translateY(-2px);
}
.chart-container {
    position: relative;
    height: 300px;
}
.growth-positive { color: #28a745; }
.growth-negative { color: #dc3545; }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">

    {{-- Header con información temporal --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-chart-line"></i> Dashboard Administrativo</h1>
        </div>
        <div class="text-end">
            <p class="mb-0"><strong>{{ $estadisticasTiempo['fecha_actual'] }}</strong></p>
            <small class="text-muted">{{ $estadisticasTiempo['mes_actual'] }} {{ $estadisticasTiempo['año_actual'] }} | Semana {{ $estadisticasTiempo['semana_año'] }}</small>
        </div>
    </div>

    {{-- Métricas principales mejoradas --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
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
        
        <div class="col-md-3 mb-3">
            <div class="card metric-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted"><i class="fas fa-chart-bar"></i> Total Ingresos</h6>
                            <h3>Bs {{ number_format($ingresosTotales, 2, '.', ',') }}</h3>
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
                            <h3>Bs {{ number_format($proyeccionMes, 2, '.', ',') }}</h3>
                            <small class="text-muted">Basada en {{ $estadisticasTiempo['dias_transcurridos_mes'] }} días</small>
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
                            <h3>{{ count($sucursales) }}</h3>
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

    {{-- Gráfico mensual y información temporal --}}
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-bar"></i> Ingresos por Mes ({{ $estadisticasTiempo['año_actual'] }})
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
                                    $porcentajeMes = ($estadisticasTiempo['dias_transcurridos_mes'] / ($estadisticasTiempo['dias_transcurridos_mes'] + $estadisticasTiempo['dias_restantes_mes'])) * 100;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $porcentajeMes }}%"></div>
                            </div>
                            <small>{{ round($porcentajeMes, 1) }}% del mes completado</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alumnos por programa (manteniendo tu estructura original) --}}
    @foreach($sucursales as $sucursal)
        <div class="card mt-3 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-users"></i> Alumnos por programa en {{ $sucursal->Nombre }}
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Programa</th>
                            <th>Total de Alumnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumnosPorSucursal[$sucursal->Id_Sucursales] as $row)
                            <tr>
                                <td>{{ $row->programa }}</td>
                                <td><strong>{{ $row->total }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No hay datos disponibles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Configuración de Chart.js
Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
Chart.defaults.color = '#666';

// Gráfico de ingresos diarios
const ctxDiarios = document.getElementById('ingresosDiarios').getContext('2d');
new Chart(ctxDiarios, {
    type: 'line',
    data: {
        labels: {!! json_encode($ingresosPorDia->pluck('fecha')) !!},
        datasets: [{
            label: 'Ingresos Diarios',
            data: {!! json_encode($ingresosPorDia->pluck('total')) !!},
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Bs ' + value.toLocaleString();
                    }
                }
            }
        },
        elements: {
            point: {
                radius: 4,
                hoverRadius: 8
            }
        }
    }
});

// Gráfico de ingresos mensuales
const ctxMensuales = document.getElementById('ingresosMensuales').getContext('2d');
new Chart(ctxMensuales, {
    type: 'bar',
    data: {
        labels: {!! json_encode($ingresosPorMes->pluck('mes_nombre')) !!},
        datasets: [{
            label: 'Ingresos Mensuales',
            data: {!! json_encode($ingresosPorMes->pluck('total')) !!},
            backgroundColor: 'rgba(40, 167, 69, 0.8)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Bs ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endsection