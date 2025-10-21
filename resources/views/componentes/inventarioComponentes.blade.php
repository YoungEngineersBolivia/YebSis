@extends('administrador.baseAdministrador')

@section('title', 'Inventario de Componentes')

@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda en tiempo real
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Inventario de Componentes</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoComponenteModal">
            <i class="fas fa-plus me-2"></i>Nuevo Componente
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar componente..." id="searchInput">
            </div>
        </div>
    </div>

    @if($componentes->isEmpty())
        <div class="alert alert-warning">No hay componentes registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold text-dark">ID Motor</th>
                        <th class="fw-bold text-dark">Estado</th>
                        <th class="fw-bold text-dark">Sucursal</th>
                        <th class="fw-bold text-dark">Observación</th>
                        <th class="fw-bold text-dark">Fecha Registro</th>
                        <th class="fw-bold text-dark">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($componentes as $componente)
                        <tr>
                            <td>{{ $componente->Id_motor }}</td>
                            <td>
                                <span class="badge 
                                    @if($componente->Estado == 'Funcionando') bg-success
                                    @elseif($componente->Estado == 'Descompuesto') bg-danger
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ $componente->Estado }}
                                </span>
                            </td>
                            <td>{{ $componente->sucursal->Nombre ?? 'N/A' }}</td>
                            <td>{{ Str::limit($componente->Observacion ?? '-', 50) }}</td>
                            <td>{{ $componente->created_at ? $componente->created_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarComponenteModal{{ $componente->Id_motores }}"
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('componentes.destroy', $componente) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Está seguro de eliminar este componente?')"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal: Editar Componente -->
                        <div class="modal fade" id="editarComponenteModal{{ $componente->Id_motores }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Componente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('componentes.update', $componente) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="id_motor_edit{{ $componente->Id_motores }}" class="form-label">ID Motor <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="id_motor_edit{{ $componente->Id_motores }}" 
                                                       name="id_motor" value="{{ $componente->Id_motor }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="estado_edit{{ $componente->Id_motores }}" class="form-label">Estado <span class="text-danger">*</span></label>
                                                <select class="form-select" id="estado_edit{{ $componente->Id_motores }}" name="estado" required>
                                                    <option value="Funcionando" {{ $componente->Estado == 'Funcionando' ? 'selected' : '' }}>Funcionando</option>
                                                    <option value="Descompuesto" {{ $componente->Estado == 'Descompuesto' ? 'selected' : '' }}>Descompuesto</option>
                                                    <option value="En Proceso" {{ $componente->Estado == 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="sucursal_edit{{ $componente->Id_motores }}" class="form-label">Sucursal <span class="text-danger">*</span></label>
                                                <select class="form-select" id="sucursal_edit{{ $componente->Id_motores }}" name="Id_sucursales" required>
                                                    @foreach($sucursales as $sucursal)
                                                        <option value="{{ $sucursal->Id_Sucursales }}" 
                                                                {{ $componente->Id_sucursales == $sucursal->Id_Sucursales ? 'selected' : '' }}>
                                                            {{ $sucursal->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="observacion_edit{{ $componente->Id_motores }}" class="form-label">Observación</label>
                                                <textarea class="form-control" id="observacion_edit{{ $componente->Id_motores }}" 
                                                          name="observacion" rows="3">{{ $componente->Observacion }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Actualizar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal: Nuevo Componente -->
<div class="modal fade" id="nuevoComponenteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Componente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('componentes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_motor_nuevo" class="form-label">ID Motor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="id_motor_nuevo" name="id_motor" required placeholder="Ej: M001">
                    </div>
                    <div class="mb-3">
                        <label for="estado_nuevo" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-select" id="estado_nuevo" name="estado" required>
                            <option value="">Seleccione estado</option>
                            <option value="Funcionando" selected>Funcionando</option>
                            <option value="Descompuesto">Descompuesto</option>
                            <option value="En Proceso">En Proceso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sucursal_nuevo" class="form-label">Sucursal <span class="text-danger">*</span></label>
                        <select class="form-select" id="sucursal_nuevo" name="Id_sucursales" required>
                            <option value="">Seleccione sucursal</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->Id_Sucursales }}">{{ $sucursal->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observacion_nuevo" class="form-label">Observación</label>
                        <textarea class="form-control" id="observacion_nuevo" name="observacion" rows="3" 
                                  placeholder="Ingrese observaciones sobre el componente"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection