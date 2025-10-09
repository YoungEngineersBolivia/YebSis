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
        <h1 class="mb-0">Componentes</h1>
        <div>
            <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#nuevoComponenteModal">
                <i class="fas fa-plus me-2"></i>Nuevo Componente
            </button>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#registrarEntradaModal">
                <i class="fas fa-arrow-down me-2"></i>Registrar Entrada
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registrarSalidaModal">
                <i class="fas fa-arrow-up me-2"></i>Registrar Salida
            </button>
        </div>
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
                <input type="text" class="form-control" placeholder="Buscar Componente" id="searchInput">
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
                        <th class="fw-bold text-dark">
                            ID Motor
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Estado
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Sucursal
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Último Técnico
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Estado Ubicación
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Observación
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Fecha
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Acciones
                            <i class="fbi bi-arrow-down"></i>
                        </th>
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
                            <td>{{ $componente->ultimoMovimiento->Ultimo_tecnico ?? '-' }}</td>
                            <td>{{ $componente->ultimoMovimiento->Estado_ubicacion ?? '-' }}</td>
                            <td>{{ Str::limit($componente->Observacion ?? '-', 40) }}</td>
                            <td>{{ $componente->ultimoMovimiento ? \Carbon\Carbon::parse($componente->ultimoMovimiento->Fecha)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarComponenteModal{{ $componente->Id_componentes }}"
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#historialModal{{ $componente->Id_componentes }}"
                                            title="Ver historial">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- MODALES -->

<!-- Modal: Nuevo Componente -->
<div class="modal fade" id="nuevoComponenteModal" tabindex="-1" aria-labelledby="nuevoComponenteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoComponenteModalLabel">Registrar Nuevo Componente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('componentes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_motor_nuevo" class="form-label">ID Motor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="id_motor_nuevo" name="id_motor" required placeholder="001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado_nuevo" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="estado_nuevo" name="estado" required>
                                <option value="">Seleccione estado</option>
                                <option value="Funcionando" selected>Funcionando</option>
                                <option value="Descompuesto">Descompuesto</option>
                                <option value="En Proceso">En Proceso</option>
                            </select>
                        </div>
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
                        <textarea class="form-control" id="observacion_nuevo" name="observacion" rows="3" placeholder="Ingrese observaciones sobre el componente"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Componente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Registrar Entrada -->
<div class="modal fade" id="registrarEntradaModal" tabindex="-1" aria-labelledby="registrarEntradaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrarEntradaModalLabel">Registrar Entrada de Componente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('componentes.registrarEntrada') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_motor" class="form-label">ID Motor</label>
                            <input type="text" class="form-control" id="id_motor" name="id_motor" required placeholder="001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="">Seleccione estado</option>
                                <option value="Funcionando">Funcionando</option>
                                <option value="Descompuesto">Descompuesto</option>
                                <option value="En Proceso">En Proceso</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sucursal" class="form-label">Sucursal</label>
                            <select class="form-select" id="sucursal" name="Id_sucursales" required>
                                <option value="">Seleccione sucursal</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->Id_Sucursales }}">{{ $sucursal->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ultimo_tecnico" class="form-label">Último Técnico</label>
                            <input type="text" class="form-control" id="ultimo_tecnico" name="ultimo_tecnico" placeholder="Nombre del técnico">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea class="form-control" id="observacion" name="observacion" rows="3" placeholder="Ingrese observaciones sobre el componente"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Registrar Entrada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Registrar Salida -->
<div class="modal fade" id="registrarSalidaModal" tabindex="-1" aria-labelledby="registrarSalidaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrarSalidaModalLabel">Registrar Salida de Componente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('componentes.registrarSalida') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_componente_salida" class="form-label">Componente</label>
                        <select class="form-select" id="id_componente_salida" name="Id_componentes" required>
                            <option value="">Seleccione un componente</option>
                            @foreach($componentes as $comp)
                                <option value="{{ $comp->Id_componentes }}">
                                    {{ $comp->Id_motor }} - {{ $comp->Estado }} - {{ $comp->sucursal->Nombre ?? 'Sin sucursal' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sucursal_salida" class="form-label">Sucursal Destino</label>
                            <select class="form-select" id="sucursal_salida" name="Id_sucursales" required>
                                <option value="">Seleccione sucursal destino</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->Id_Sucursales }}">{{ $sucursal->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ultimo_tecnico_salida" class="form-label">Último Técnico</label>
                            <input type="text" class="form-control" id="ultimo_tecnico_salida" name="ultimo_tecnico" placeholder="Nombre del técnico">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_salida" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha_salida" name="fecha" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="observacion_salida" class="form-label">Observación</label>
                        <textarea class="form-control" id="observacion_salida" name="observacion" rows="3" placeholder="Motivo de la salida o detalles"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Registrar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection