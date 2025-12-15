@extends('administrador.baseAdministrador')

@section('title', 'Graduados')

@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold"><i class="bi bi-mortarboard me-2"></i>Estudiantes Graduados</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoGraduadoModal">
                <i class="bi bi-plus-lg me-2"></i>Añadir Graduado
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">Buscar Graduado</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Nombre, programa..." data-table-filter="tablaGraduados">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($graduados->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-people text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">No hay graduados registrados.</h5>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaGraduados">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3 py-3">Nombre Estudiante</th>
                                    <th class="py-3">Apellido Estudiante</th>
                                    <th class="py-3">Programa</th>
                                    <th class="py-3">Nombre Profesor</th>
                                    <th class="py-3">Apellido Profesor</th>
                                    <th class="py-3">Fecha Graduación</th>
                                    <th class="pe-3 py-3 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($graduados as $graduado)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $graduado->estudiante->persona->Nombre ?? 'N/A' }}</td>
                                        <td>{{ $graduado->estudiante->persona->Apellido ?? 'N/A' }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $graduado->programa->Nombre ?? 'N/A' }}</span></td>
                                        <td>{{ $graduado->profesor->persona->Nombre ?? 'N/A' }}</td>
                                        <td>{{ $graduado->profesor->persona->Apellido ?? 'N/A' }}</td>
                                        <td>{{ $graduado->Fecha_graduado ? \Carbon\Carbon::parse($graduado->Fecha_graduado)->format('d/m/Y') : 'Sin fecha' }}</td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <!-- Editar -->
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editarGraduadoModal{{ $graduado->Id_graduado }}"
                                                        title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Ver -->
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#verGraduadoModal{{ $graduado->Id_graduado }}"
                                                        title="Ver detalles">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>

                                                <!-- Eliminar -->
                                                <form action="{{ route('graduados.eliminar', $graduado->Id_graduado) }}" method="POST" onsubmit="return confirm('¿Eliminar este graduado?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Editar Graduado -->
                                    <div class="modal fade" id="editarGraduadoModal{{ $graduado->Id_graduado }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('graduados.actualizar', $graduado->Id_graduado) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Graduado</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Estudiante</label>
                                                            <select name="estudiante_id" class="form-select" required>
                                                                @foreach($estudiantes as $estudiante)
                                                                    <option value="{{ $estudiante->Id_estudiantes }}" 
                                                                        {{ $graduado->Id_estudiantes == $estudiante->Id_estudiantes ? 'selected' : '' }}>
                                                                        {{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Programa</label>
                                                            <select name="programa_id" class="form-select" required>
                                                                @foreach($programas as $programa)
                                                                    <option value="{{ $programa->Id_programas }}" 
                                                                        {{ $graduado->Id_programas == $programa->Id_programas ? 'selected' : '' }}>
                                                                        {{ $programa->Nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Profesor</label>
                                                            <select name="profesor_id" class="form-select" required>
                                                                @foreach($profesores as $profesor)
                                                                    <option value="{{ $profesor->Id_profesores }}" 
                                                                        {{ $graduado->Id_profesores == $profesor->Id_profesores ? 'selected' : '' }}>
                                                                        {{ $profesor->persona->Nombre }} {{ $profesor->persona->Apellido }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Fecha de Graduación</label>
                                                            <input type="date" name="Fecha_graduado" class="form-control" value="{{ $graduado->Fecha_graduado }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cerrar</button>
                                                        <button class="btn btn-primary" type="submit">Actualizar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Ver Graduado -->
                                    <div class="modal fade" id="verGraduadoModal{{ $graduado->Id_graduado }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title"><i class="bi bi-person-badge me-2"></i>Perfil del Graduado</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-4">
                                                        <div class="avatar-placeholder rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2" style="width: 80px; height: 80px;">
                                                             <i class="bi bi-person text-secondary" style="font-size: 2.5rem;"></i>
                                                        </div>
                                                        <h5 class="fw-bold">{{ $graduado->estudiante->persona->Nombre ?? '' }} {{ $graduado->estudiante->persona->Apellido ?? '' }}</h5>
                                                        <p class="text-muted">{{ $graduado->programa->Nombre ?? '' }}</p>
                                                    </div>
                                                    <hr>
                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <label class="small text-muted fw-bold">Profesor</label>
                                                            <p>{{ $graduado->profesor->persona->Nombre ?? '' }} {{ $graduado->profesor->persona->Apellido ?? '' }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="small text-muted fw-bold">Fecha Graduación</label>
                                                            <p>{{ $graduado->Fecha_graduado ? \Carbon\Carbon::parse($graduado->Fecha_graduado)->format('d/m/Y') : 'Sin fecha' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Añadir Graduado -->
    <div class="modal fade" id="nuevoGraduadoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('graduados.agregar') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Añadir Graduado</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Estudiante</label>
                            <select name="estudiante_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->Id_estudiantes }}">{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Programa</label>
                            <select name="programa_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($programas as $programa)
                                    <option value="{{ $programa->Id_programas }}">{{ $programa->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Profesor</label>
                            <select name="profesor_id" class="form-select" required>
                                <option value="">Seleccione</option>
                                @foreach($profesores as $profesor)
                                    <option value="{{ $profesor->Id_profesores }}">{{ $profesor->persona->Nombre }} {{ $profesor->persona->Apellido }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha de Graduación</label>
                            <input type="date" name="Fecha_graduado" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cerrar</button>
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* MIGRADO A baseAdministrador.blade.php */
/*
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('#tablaGraduados tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});
*/
</script>
@endsection
