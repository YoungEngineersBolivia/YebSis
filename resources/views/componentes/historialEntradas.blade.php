@extends('administrador.baseAdministrador')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt"></i> Historial de Entradas (Devoluciones)
                    </h4>
                    <a href="{{ route('componentes.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver al Inventario
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Estado Final</label>
                            <select class="form-select" id="filtroEstado" onchange="filtrarEntradas()">
                                <option value="">Todos los estados</option>
                                <option value="Funcionando">Funcionando</option>
                                <option value="Descompuesto">Descompuesto</option>
                                <option value="Requiere Revision">Requiere Revisión</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Técnico</label>
                            <select class="form-select" id="filtroTecnico" onchange="filtrarEntradas()">
                                <option value="">Todos los técnicos</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->Id_profesores }}">
                                        {{ $tecnico->persona->Nombre }} {{ $tecnico->persona->Apellido_paterno }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-sync"></i> Limpiar Filtros
                            </button>
                        </div>
                    </div>

                    <!-- Estadísticas Rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $entradas->where('Estado_entrada', 'Funcionando')->count() }}</h3>
                                    <small>Reparados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $entradas->where('Estado_entrada', 'Descompuesto')->count() }}</h3>
                                    <small>No Reparados</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $entradas->where('Estado_entrada', 'Requiere Revision')->count() }}</h3>
                                    <small>Requieren Revisión</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $entradas->count() }}</h3>
                                    <small>Total Entradas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Entradas -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha Entrada</th>
                                    <th>ID Motor</th>
                                    <th>Técnico</th>
                                    <th>Estado Entrada</th>
                                    <th>Sucursal Destino</th>
                                    <th>Trabajo Realizado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entradas as $entrada)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($entrada->Fecha_movimiento)->format('d/m/Y H:i') }}</td>
                                        <td><strong>{{ $entrada->motor->Id_motor }}</strong></td>
                                        <td>{{ $entrada->Nombre_tecnico }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($entrada->Estado_entrada) {
                                                    'Funcionando' => 'success',
                                                    'Descompuesto' => 'danger',
                                                    'Requiere Revision' => 'warning',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">
                                                {{ $entrada->Estado_entrada }}
                                            </span>
                                        </td>
                                        <td>{{ $entrada->sucursal->Nombre }}</td>
                                        <td>
                                            <small>{{ Str::limit($entrada->Trabajo_realizado, 50) }}</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm" 
                                                    onclick="verDetalleEntrada({{ $entrada->Id_movimientos }})">
                                                <i class="fas fa-eye"></i> Ver Detalle
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            No hay entradas registradas
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-3">
                        {{ $entradas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Detalle de Entrada -->
<div class="modal fade" id="modalDetalleEntrada" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalle Completo de Entrada
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleEntradaContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function filtrarEntradas() {
    const estado = document.getElementById('filtroEstado').value;
    const tecnico = document.getElementById('filtroTecnico').value;
    
    let url = new URL(window.location.href);
    url.searchParams.set('estado', estado);
    url.searchParams.set('tecnico_id', tecnico);
    
    window.location.href = url.toString();
}

function limpiarFiltros() {
    window.location.href = '{{ route('motores.entradas.index') }}';
}

function verDetalleEntrada(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleEntrada'));
    const content = document.getElementById('detalleEntradaContent');
    
    // Aquí puedes cargar el detalle vía AJAX
    fetch(`/administrador/motores/movimiento/${id}/detalle`)
        .then(res => res.json())
        .then(data => {
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Información del Motor</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>ID Motor:</th>
                                <td>${data.motor.Id_motor}</td>
                            </tr>
                            <tr>
                                <th>Estado Entrada:</th>
                                <td><span class="badge bg-success">${data.Estado_entrada}</span></td>
                            </tr>
                            <tr>
                                <th>Sucursal:</th>
                                <td>${data.sucursal.Nombre}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Información del Técnico</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Técnico:</th>
                                <td>${data.Nombre_tecnico}</td>
                            </tr>
                            <tr>
                                <th>Fecha Entrada:</th>
                                <td>${new Date(data.Fecha_movimiento).toLocaleDateString()}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="text-primary">Trabajo Realizado</h6>
                <p class="border p-3 bg-light">${data.Trabajo_realizado}</p>
                
                ${data.Observaciones ? `
                    <h6 class="text-primary">Observaciones</h6>
                    <p class="border p-3 bg-light">${data.Observaciones}</p>
                ` : ''}
            `;
        })
        .catch(err => {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error al cargar el detalle
                </div>
            `;
        });
    
    modal.show();
}
</script>
@endpush