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
        <h1>Estudiantes Graduados</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoGraduadoModal">
            <i class="bi bi-plus-lg me-2"></i>Añadir Graduado
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar">
        </div>
    </div>

    @if($graduados->isEmpty())
        <div class="alert alert-warning">No hay graduados registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover" id="tablaGraduados">
                <thead>
                    <tr>
                        <th>Nombre Estudiante</th>
                        <th>Apellido Estudiante</th>
                        <th>Programa</th>
                        <th>Nombre Profesor</th>
                        <th>Apellido Profesor</th>
                        <th>Fecha Graduación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($graduados as $graduado)
                        <tr>
                            <td>{{ $graduado->estudiante->persona->Nombre ?? 'N/A' }}</td>
                            <td>{{ $graduado->estudiante->persona->Apellido ?? 'N/A' }}</td>
                            <td>{{ $graduado->programa->Nombre ?? 'N/A' }}</td>
                            <td>{{ $graduado->profesor->persona->Nombre ?? 'N/A' }}</td>
                            <td>{{ $graduado->profesor->persona->Apellido ?? 'N/A' }}</td>
                            <td>{{ $graduado->Fecha_graduado ? \Carbon\Carbon::parse($graduado->Fecha_graduado)->format('d/m/Y') : 'Sin fecha' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Editar -->
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editarGraduadoModal{{ $graduado->Id_graduado }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Ver -->
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#verGraduadoModal{{ $graduado->Id_graduado }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <!-- Eliminar -->
                                    <form action="{{ route('graduados.eliminar', $graduado->Id_graduado) }}" method="POST" onsubmit="return confirm('¿Eliminar este graduado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Graduado</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Estudiante</label>
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
                                                <label>Programa</label>
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
                                                <label>Profesor</label>
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
                                                <label>Fecha de Graduación</label>
                                                <input type="date" name="Fecha_graduado" class="form-control" value="{{ $graduado->Fecha_graduado }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
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
                                    <div class="modal-header">
                                        <h5 class="modal-title">Perfil del Graduado</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Estudiante:</strong> {{ $graduado->estudiante->persona->Nombre ?? '' }} {{ $graduado->estudiante->persona->Apellido ?? '' }}</p>
                                        <p><strong>Programa:</strong> {{ $graduado->programa->Nombre ?? '' }}</p>
                                        <p><strong>Profesor:</strong> {{ $graduado->profesor->persona->Nombre ?? '' }} {{ $graduado->profesor->persona->Apellido ?? '' }}</p>
                                        <p><strong>Fecha de Graduación:</strong> {{ $graduado->Fecha_graduado ? \Carbon\Carbon::parse($graduado->Fecha_graduado)->format('d/m/Y') : 'Sin fecha' }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

<!-- Modal Añadir Graduado -->
<div class="modal fade" id="nuevoGraduadoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('graduados.agregar') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Graduado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Estudiante</label>
                        <select name="estudiante_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            @foreach($estudiantes as $estudiante)
                                <option value="{{ $estudiante->Id_estudiantes }}">{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Programa</label>
                        <select name="programa_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            @foreach($programas as $programa)
                                <option value="{{ $programa->Id_programas }}">{{ $programa->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Profesor</label>
                        <select name="profesor_id" class="form-select" required>
                            <option value="">Seleccione</option>
                            @foreach($profesores as $profesor)
                                <option value="{{ $profesor->Id_profesores }}">{{ $profesor->persona->Nombre }} {{ $profesor->persona->Apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Fecha de Graduación</label>
                        <input type="date" name="Fecha_graduado" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
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
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll('#tablaGraduados tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});
</script>
@endsection
