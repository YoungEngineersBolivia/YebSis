@extends('administrador.baseAdministrador')

@section('title', 'Motores Asignados')

@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Filtro por estado
        const filtroEstado = document.getElementById('filtroEstado');
        if (filtroEstado) {
            filtroEstado.addEventListener('change', function() {
                let estado = this.value.toLowerCase();
                let rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    if (estado === 'todos') {
                        row.style.display = '';
                    } else {
                        let estadoRow = row.getAttribute('data-estado').toLowerCase();
                        row.style.display = estadoRow.includes(estado) ? '' : 'none';
                    }
                });
            });
        }
    });
</script>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Motores Asignados a Técnicos</h1>
        <a href="{{ route('motores.asignar.create') }}" class="btn btn-primary">
            <i class="bi bi-tools me-2"></i>Asignar Motor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar..." id="searchInput">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="filtroEstado">
                <option value="todos">Todos los estados</option>
                <option value="En Proceso">En Proceso</option>
                <option value="Completado">Completado</option>
                <option value="Cancelado">Cancelado</option>
            </select>
        </div>
    </div>

    @if($asignaciones->isEmpty())
        <div class="alert alert-warning">No hay motores asignados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold text-dark">Fecha Asignación</th>
                        <th class="fw-bold text-dark">ID Motor</th>
                        <th class="fw-bold text-dark">Estado Motor</th>
                        <th class="fw-bold text-dark">Técnico</th>
                        <th class="fw-bold text-dark">Estado Asignación</th>
                        <th class="fw-bold text-dark">Fecha Entrega</th>
                        <th class="fw-bold text-dark">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asignaciones as $asignacion)
                        <tr data-estado="{{ $asignacion->Estado_asignacion }}">
                            <td>{{ \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y') }}</td>
                            <td>{{ $asignacion->motor->Id_motor }}</td>
                            <td>
                                <span class="badge 
                                    @if($asignacion->motor->Estado == 'Funcionando') bg-success
                                    @elseif($asignacion->motor->Estado == 'Descompuesto') bg-danger
                                    @else bg-warning text-dark
                                    @endif">
                                    {{ $asignacion->motor->Estado }}
                                </span>
                            </td>
                            <td>
                                {{ $asignacion->profesor->persona->Nombre ?? '' }} 
                                {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($asignacion->Estado_asignacion == 'En Proceso') bg-info
                                    @elseif($asignacion->Estado_asignacion == 'Completado') bg-success
                                    @else bg-secondary
                                    @endif">
                                    {{ $asignacion->Estado_asignacion }}
                                </span>
                            </td>
                            <td>{{ $asignacion->Fecha_entrega ? \Carbon\Carbon::parse($asignacion->Fecha_entrega)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if($asignacion->Estado_asignacion == 'En Proceso')
                                        <button class="btn btn-sm btn-outline-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#entregaModal{{ $asignacion->Id_motores_asignados }}"
                                                title="Registrar Entrega">
                                            <i class="bi bi-box-arrow-in-down"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#reporteModal{{ $asignacion->Id_motores_asignados }}"
                                                title="Agregar Reporte">
                                            <i class="bi bi-clipboard-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detalleModal{{ $asignacion->Id_motores_asignados }}"
                                            title="Ver Detalles">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal: Registrar Entrega (Entrada al Inventario) -->
                        <div class="modal fade" id="entregaModal{{ $asignacion->Id_motores_asignados }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="bi bi-box-arrow-in-down me-2"></i>Registrar Entrada de Motor</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('motores.registrar.entrada', $asignacion->Id_motores_asignados) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Motor:</strong> {{ $asignacion->motor->Id_motor }}<br>
                                                <strong>Técnico:</strong> {{ $asignacion->profesor->persona->Nombre ?? '' }} {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                                            </div>

                                            <div class="mb-3">
                                                <label for="fecha_entrega{{ $asignacion->Id_motores_asignados }}" class="form-label">Fecha de Entrega <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" 
                                                       id="fecha_entrega{{ $asignacion->Id_motores_asignados }}" 
                                                       name="fecha_entrega" value="{{ date('Y-m-d') }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="estado_final{{ $asignacion->Id_motores_asignados }}" class="form-label">Estado Final del Motor <span class="text-danger">*</span></label>
                                                <select class="form-select" 
                                                        id="estado_final{{ $asignacion->Id_motores_asignados }}" 
                                                        name="estado_final" required>
                                                    <option value="">Seleccione estado</option>
                                                    <option value="Funcionando">Funcionando</option>
                                                    <option value="Descompuesto">Descompuesto</option>
                                                    <option value="En Proceso">En Proceso</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="Id_sucursales{{ $asignacion->Id_motores_asignados }}" class="form-label">Sucursal de Entrada <span class="text-danger">*</span></label>
                                                <select class="form-select" 
                                                        id="Id_sucursales{{ $asignacion->Id_motores_asignados }}" 
                                                        name="Id_sucursales" required>
                                                    <option value="">Seleccione sucursal</option>
                                                    @foreach($sucursales as $sucursal)
                                                        <option value="{{ $sucursal->Id_Sucursales }}">{{ $sucursal->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="observaciones_entrega{{ $asignacion->Id_motores_asignados }}" class="form-label">Observaciones</label>
                                                <textarea class="form-control" 
                                                          id="observaciones_entrega{{ $asignacion->Id_motores_asignados }}" 
                                                          name="observaciones" rows="3" 
                                                          placeholder="Reparaciones realizadas, estado final, etc."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-2"></i>Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-check-circle me-2"></i>Registrar Entrada
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal: Agregar Reporte de Mantenimiento -->
                        <div class="modal fade" id="reporteModal{{ $asignacion->Id_motores_asignados }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title"><i class="bi bi-clipboard-check me-2"></i>Reporte de Mantenimiento</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('motores.reporte.store', $asignacion->Id_motores_asignados) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Motor:</strong> {{ $asignacion->motor->Id_motor }}<br>
                                                <strong>Técnico:</strong> {{ $asignacion->profesor->persona->Nombre ?? '' }} {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                                            </div>

                                            <div class="mb-3">
                                                <label for="fecha_reporte{{ $asignacion->Id_motores_asignados }}" class="form-label">Fecha del Reporte <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" 
                                                       id="fecha_reporte{{ $asignacion->Id_motores_asignados }}" 
                                                       name="fecha_reporte" value="{{ date('Y-m-d') }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="estado_final_reporte{{ $asignacion->Id_motores_asignados }}" class="form-label">Estado del Motor <span class="text-danger">*</span></label>
                                                <select class="form-select" 
                                                        id="estado_final_reporte{{ $asignacion->Id_motores_asignados }}" 
                                                        name="estado_final" required>
                                                    <option value="">Seleccione estado</option>
                                                    <option value="Funcionando">Funcionando</option>
                                                    <option value="Descompuesto">Descompuesto</option>
                                                    <option value="En Proceso">En Proceso</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="observaciones_reporte{{ $asignacion->Id_motores_asignados }}" class="form-label">Observaciones <span class="text-danger">*</span></label>
                                                <textarea class="form-control" 
                                                          id="observaciones_reporte{{ $asignacion->Id_motores_asignados }}" 
                                                          name="observaciones" rows="4" required
                                                          placeholder="Describa el trabajo realizado, problemas encontrados, etc."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-2"></i>Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-floppy me-2"></i>Guardar Reporte
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal: Ver Detalles -->
                        <div class="modal fade" id="detalleModal{{ $asignacion->Id_motores_asignados }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Detalles de Asignación</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6 class="border-bottom pb-2 mb-3">Información General</h6>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>ID Motor:</strong> {{ $asignacion->motor->Id_motor }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Estado Actual:</strong> 
                                                <span class="badge 
                                                    @if($asignacion->motor->Estado == 'Funcionando') bg-success
                                                    @elseif($asignacion->motor->Estado == 'Descompuesto') bg-danger
                                                    @else bg-warning text-dark
                                                    @endif">
                                                    {{ $asignacion->motor->Estado }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Técnico:</strong> {{ $asignacion->profesor->persona->Nombre ?? '' }} {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Estado Asignación:</strong> 
                                                <span class="badge 
                                                    @if($asignacion->Estado_asignacion == 'En Proceso') bg-info
                                                    @elseif($asignacion->Estado_asignacion == 'Completado') bg-success
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ $asignacion->Estado_asignacion }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <strong>Fecha Asignación:</strong> {{ \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y') }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Fecha Entrega:</strong> {{ $asignacion->Fecha_entrega ? \Carbon\Carbon::parse($asignacion->Fecha_entrega)->format('d/m/Y') : 'Pendiente' }}
                                            </div>
                                        </div>

                                        @if($asignacion->Observacion_inicial)
                                            <h6 class="border-bottom pb-2 mb-3 mt-4">Observación Inicial</h6>
                                            <p>{{ $asignacion->Observacion_inicial }}</p>
                                        @endif

                                        @if($asignacion->reportes->count() > 0)
                                            <h6 class="border-bottom pb-2 mb-3 mt-4">Reportes de Mantenimiento</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Estado</th>
                                                            <th>Observaciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($asignacion->reportes as $reporte)
                                                            <tr>
                                                                <td>{{ \Carbon\Carbon::parse($reporte->Fecha_reporte)->format('d/m/Y') }}</td>
                                                                <td>
                                                                    <span class="badge 
                                                                        @if($reporte->Estado_final == 'Funcionando') bg-success
                                                                        @elseif($reporte->Estado_final == 'Descompuesto') bg-danger
                                                                        @else bg-warning text-dark
                                                                        @endif">
                                                                        {{ $reporte->Estado_final }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ Str::limit($reporte->Observaciones, 50) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-2"></i>Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection