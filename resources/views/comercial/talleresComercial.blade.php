@extends('/administrador/baseAdministrador')

@section('title', 'Reporte de Talleres')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">Reporte de Talleres</h4>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                id="periodoDropdown" data-bs-toggle="dropdown">
                            {{ $periodo }} months
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?periodo=3">3 months</a></li>
                            <li><a class="dropdown-item" href="?periodo=6">6 months</a></li>
                            <li><a class="dropdown-item" href="?periodo=12">12 months</a></li>
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

                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <h6 class="fw-bold mb-3">Filtrar</h6>
                            <select class="form-select" id="filtroSemana">
                                <option>Semanas</option>
                                <option>Última semana</option>
                                <option>Últimas 2 semanas</option>
                                <option>Último mes</option>
                            </select>
                        </div>
                        <div class="col-md-9 text-end">
                            <button class="btn btn-outline-secondary" id="filtroFecha">
                                <i class="fas fa-filter me-1"></i>
                                Filtrar por fecha
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de datos -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaEstudiantes">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Programa/Taller</span>
                                            <i class="fas fa-sort text-muted"></i>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Teléfono</span>
                                            <i class="fas fa-sort text-muted"></i>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Fecha de Registro</span>
                                            <i class="fas fa-sort text-muted"></i>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Contactado</span>
                                            <i class="fas fa-sort text-muted"></i>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Acciones</span>
                                            <i class="fas fa-sort text-muted"></i>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detalleTabla as $estudiante)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle me-2" 
                                                 style="width: 8px; height: 8px;"></div>
                                            <span class="fw-medium">{{ $estudiante->taller }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $estudiante->telefono ?? 'Sin teléfono' }}</td>
                                    <td class="text-muted">
                                        {{ \Carbon\Carbon::parse($estudiante->fecha_registro)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if($estudiante->contactado == 'Contactado')
                                            <span class="badge bg-success-subtle text-success rounded-pill">
                                                <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                                Contactado
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill">
                                                <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                                No Contactado
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="eliminar({{ $estudiante->Id_estudiantes }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
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

function eliminar(idEstudiante) {
    if (confirm('¿Está seguro de eliminar este estudiante?')) {
        // Implementar lógica de eliminación
        fetch(`/estudiantes/${idEstudiante}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar el estudiante');
            }
        });
    }
}
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