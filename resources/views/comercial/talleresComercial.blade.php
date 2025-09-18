@extends('/administrador/baseAdministrador')

@section('title', 'Reporte de Talleres')

@section('content')
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
                            {{ $periodo }} meses
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
                                @foreach($talleres2024 as $estudiante)
                                <tr>
                                    <td>{{ $estudiante->taller }}</td>
                                    <td>{{ $estudiante->nombre_estudiante }}</td>
                                    <td>{{ \Carbon\Carbon::parse($estudiante->Fecha_inscripcion)->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
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
                                @foreach($talleres2025 as $estudiante)
                                <tr>
                                    <td>{{ $estudiante->taller }}</td>
                                    <td>{{ $estudiante->nombre_estudiante }}</td>
                                    <td>{{ \Carbon\Carbon::parse($estudiante->Fecha_inscripcion)->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
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
document.addEventListener('DOMContentLoaded', function() {
    // Datos desde PHP
    const datosGrafica = @json($datosGrafica);
    
    // Preparar datos para Chart.js
    const labels = datosGrafica.meses.map(m => m.nombre);
    const programas = datosGrafica.programas;
    
    // Colores para las barras
    const coloresActual = [
        '#6f42c1', '#7c4dff', '#9c27b0', '#e91e63', '#f44336',
        '#ff9800', '#4caf50', '#00bcd4', '#2196f3', '#3f51b5'
    ];
    
    const coloresAnterior = coloresActual.map(color => color + '80'); // Más transparente
    
    const datasets = [];
    
    // Dataset para cada programa (año actual)
    programas.forEach((programa, index) => {
        const dataActual = labels.map(mes => datosGrafica.datos[mes].actual[programa] || 0);
        const dataAnterior = labels.map(mes => datosGrafica.datos[mes].anterior[programa] || 0);
        
        // Datos año actual
        datasets.push({
            label: programa + ' (Actual)',
            data: dataActual,
            backgroundColor: coloresActual[index % coloresActual.length],
            borderColor: coloresActual[index % coloresActual.length],
            borderWidth: 1,
            barThickness: 20
        });
        
        // Datos año anterior
        datasets.push({
            label: programa + ' (Anterior)',
            data: dataAnterior,
            backgroundColor: coloresAnterior[index % coloresAnterior.length],
            borderColor: coloresActual[index % coloresActual.length],
            borderWidth: 1,
            barThickness: 20
        });
    });

    // Configuración del gráfico
    const ctx = document.getElementById('talleresChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' estudiantes';
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    grid: {
                        color: '#f8f9fa'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + 'k';
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
});
</script>

<style>
.card {
    border: none;
    border-radius: 12px;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
}

.table td {
    font-size: 0.875rem;
    border-bottom: 1px solid #f1f5f9;
    padding: 1rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.bg-success-subtle {
    background-color: rgba(34, 197, 94, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(239, 68, 68, 0.1) !important;
}

.text-success {
    color: #22c55e !important;
}

.text-danger {
    color: #ef4444 !important;
}

.btn-outline-secondary {
    border-color: #e2e8f0;
    color: #64748b;
}

.btn-outline-secondary:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
}
</style>
@endsection
