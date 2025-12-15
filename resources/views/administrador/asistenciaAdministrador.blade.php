@extends('administrador.baseAdministrador')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="text-uppercase fw-bold text-dark">
            <i class="bi bi-calendar-range me-2"></i> Reporte de Asistencia
        </h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('asistencia.admin.pdf', request()->all()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
        </a>
        <a href="{{ route('asistencia.admin.excel', request()->all()) }}" class="btn btn-success ms-2">
            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-muted"><i class="bi bi-funnel me-1"></i> Filtros de Búsqueda</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('asistencia.admin.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Profesor</label>
                    <select name="profesor_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($profesores as $profe)
                            <option value="{{ $profe->Id_profesores }}" {{ request('profesor_id') == $profe->Id_profesores ? 'selected' : '' }}>
                                {{ $profe->persona->Nombre }} {{ $profe->persona->Apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Programa</label>
                    <select name="programa_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($programas as $prog)
                            <option value="{{ $prog->Id_programas }}" {{ request('programa_id') == $prog->Id_programas ? 'selected' : '' }}>
                                {{ $prog->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label small fw-bold">Buscar Estudiante</label>
                    <input type="text" name="estudiante_nombre" class="form-control" placeholder="Nombre o Apellido..." value="{{ request('estudiante_nombre') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('asistencia.admin.index') }}" class="btn btn-outline-secondary ms-2" title="Limpiar">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Profesor</th>
                        <th>Programa</th>
                        <th>Estado</th>
                        <th>Observación</th>
                        <th>Reprogramado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asistencias as $asistencia)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($asistencia->Fecha)->format('d/m/Y') }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $asistencia->estudiante->persona->Nombre }} {{ $asistencia->estudiante->persona->Apellido }}</div>
                                <small class="text-muted">{{ $asistencia->estudiante->Cod_estudiante }}</small>
                            </td>
                            <td>{{ $asistencia->profesor->persona->Nombre }} {{ $asistencia->profesor->persona->Apellido }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $asistencia->programa->Nombre }}</span></td>
                            <td>
                                @if($asistencia->Estado == 'Asistio')
                                    <span class="badge bg-success">Asistió</span>
                                @elseif($asistencia->Estado == 'Falta')
                                    <span class="badge bg-danger">Falta</span>
                                @elseif($asistencia->Estado == 'Licencia')
                                    <span class="badge bg-warning text-dark">Licencia</span>
                                @elseif($asistencia->Estado == 'Reprogramado')
                                    <span class="badge bg-info">Reprogramado</span>
                                @endif
                            </td>
                            <td>{{ $asistencia->Observacion ?? '-' }}</td>
                            <td>
                                @if($asistencia->Fecha_reprogramada)
                                    <span class="text-info fw-bold">
                                        <i class="bi bi-arrow-right-circle me-1"></i>
                                        {{ \Carbon\Carbon::parse($asistencia->Fecha_reprogramada)->format('d/m/Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-clipboard-x display-4"></i>
                                    <p class="mt-2">No se encontraron registros de asistencia con los filtros seleccionados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $asistencias->links() }}
    </div>
</div>
@endsection
