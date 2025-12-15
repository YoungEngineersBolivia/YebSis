@extends('administrador.baseAdministrador')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-out-alt"></i> Salidas de Motores
                    </h4>
                    <div>
                        <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarSalida">
                            <i class="fas fa-plus"></i> Registrar Nueva Salida
                        </button>
                        <a href="{{ route('componentes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver al Inventario
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Técnico</label>
                            <select class="form-select" id="filtroTecnico" onchange="filtrarSalidas()">
                                <option value="">Todos los técnicos</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->Id_profesores }}">
                                        {{ $tecnico->persona->Nombre }} {{ $tecnico->persona->Apellido_paterno }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Desde</label>
                            <input type="date" class="form-control" id="fechaDesde" onchange="filtrarSalidas()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hasta</label>
                            <input type="date" class="form-control" id="fechaHasta" onchange="filtrarSalidas()">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-sync"></i> Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de salidas -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha Salida</th>
                                    <th>ID Motor</th>
                                    <th>Técnico</th>
                                    <th>Estado al Salir</th>
                                    <th>Sucursal</th>
                                    <th>Motivo</th>
                                    <th>Registrado Por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salidas as $salida)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($salida->Fecha_movimiento)->format('d/m/Y H:i') }}</td>
                                        <td><strong>{{ $salida->motor->Id_motor }}</strong></td>
                                        <td>{{ $salida->Nombre_tecnico }}</td>
                                        <td>
                                            <span class="badge bg-{{ $salida->Estado_salida == 'Funcionando' ? 'success' : 'warning' }}">
                                                {{ $salida->Estado_salida }}
                                            </span>
                                        </td>
                                        <td>{{ $salida->sucursal->Nombre }}</td>
                                        <td><small>{{ Str::limit($salida->Motivo_salida, 40) }}</small></td>
                                        <td>
                                            @if($salida->usuario)
                                                {{ $salida->usuario->persona->Nombre ?? 'Sistema' }}
                                            @else
                                                Sistema
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm" 
                                                    onclick="verDetalleSalida({{ $salida->Id_movimientos }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay salidas registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-3">
                        {{ $salidas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Registrar Salida -->
<div class="modal fade" id="modalRegistrarSalida" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('motores.salidas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-out-alt"></i> Registrar Salida de Motor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Motor <span class="text-danger">*</span></label>
                            <select name="Id_motores" class="form-select" required id="selectMotor">
                                <option value="">Seleccione un motor...</option>
                                @foreach($motoresDisponibles ?? [] as $motor)
                                    <option value="{{ $motor->Id_motores }}">
                                        {{ $motor->Id_motor }} - {{ $motor->Estado }} 
                                        ({{ $motor->sucursal->Nombre ?? 'Sin sucursal' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Solo se muestran motores disponibles en inventario</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Técnico <span class="text-danger">*</span></label>
                            <select name="Id_profesores" class="form-select" required>
                                <option value="">Seleccione un técnico...</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->Id_profesores }}">
                                        {{ $tecnico->persona->Nombre }} {{ $tecnico->persona->Apellido_paterno }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de Salida <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="fecha_salida" class="form-control" required
                               value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motivo de la Salida <span class="text-danger">*</span></label>
                        <textarea name="motivo_salida" class="form-control" rows="3" required
                                  placeholder="Ejemplo: Reparación de motor quemado, cambio de bobinas..."></textarea>
                        <small class="text-muted">Mínimo 10 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea name="observaciones" class="form-control" rows="2"
                                  placeholder="Cualquier información adicional relevante..."></textarea>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i>
                        <strong>Importante:</strong> El motor será marcado como "En Reparación" y asignado al técnico seleccionado.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check"></i> Registrar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Detalle de Salida -->
<div class="modal fade" id="modalDetalleSalida" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalle de Salida</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
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
function filtrarSalidas() {
    const tecnico = document.getElementById('filtroTecnico').value;
    const desde = document.getElementById('fechaDesde').value;
    const hasta = document.getElementById('fechaHasta').value;
    
    let url = new URL(window.location.href);
    url.searchParams.set('tecnico_id', tecnico);
    url.searchParams.set('fecha_desde', desde);
    url.searchParams.set('fecha_hasta', hasta);
    
    window.location.href = url.toString();
}

function limpiarFiltros() {
    window.location.href = '{{ route('motores.salidas.index') }}';
}

function verDetalleSalida(id) {
    // Implementar según tu estructura
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleSalida'));
    modal.show();
}

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        timer: 3000
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}'
    });
@endif
</script>
@endpush