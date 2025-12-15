@extends('profesor.baseProfesor')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center gap-3 mb-3">
                <a href="{{ route('profesor.home') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
            <h2 class="text-uppercase fw-bold text-dark">
                <i class="bi bi-calendar-check me-2"></i> Registro de Asistencia
            </h2>
            <p class="text-muted mb-0">Profesor: {{ $profesor->persona->Nombre }} {{ $profesor->persona->Apellido }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> <strong>Error:</strong> Por favor revise el formulario.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('profesor.asistencia.store') }}" method="POST">
                @csrf
                <input type="hidden" name="profesor_id" value="{{ $profesor->Id_profesores }}">
                
                <div class="row mb-4 align-items-end">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="fecha" class="form-label fw-bold">Fecha de Clase</label>
                        <input type="date" name="fecha" id="fecha" class="form-control form-control-lg" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button type="submit" class="btn btn-primary btn-lg w-100 w-md-auto">
                            <i class="bi bi-save me-2"></i> Guardar Asistencia
                        </button>
                    </div>
                </div>

                {{-- Vista de tabla para pantallas grandes --}}
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%">Estudiante</th>
                                    <th style="width: 15%">Programa</th>
                                    <th style="width: 35%">Estado</th>
                                    <th style="width: 25%">Detalles / Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estudiantes as $estudiante)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</div>
                                            <small class="text-muted">{{ $estudiante->Cod_estudiante }}</small>
                                            <input type="hidden" name="programa_id[{{ $estudiante->Id_estudiantes }}]" value="{{ $estudiante->Id_programas }}">
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $estudiante->programa->Nombre ?? 'Sin programa' }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group w-100" role="group">
                                                <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="asistio_{{ $estudiante->Id_estudiantes }}" value="Asistio" checked onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, false)">
                                                <label class="btn btn-outline-success btn-sm" for="asistio_{{ $estudiante->Id_estudiantes }}">Asistió</label>

                                                <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="falta_{{ $estudiante->Id_estudiantes }}" value="Falta" onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, false)">
                                                <label class="btn btn-outline-danger btn-sm" for="falta_{{ $estudiante->Id_estudiantes }}">Falta</label>

                                                <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="licencia_{{ $estudiante->Id_estudiantes }}" value="Licencia" onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, false)">
                                                <label class="btn btn-outline-warning btn-sm" for="licencia_{{ $estudiante->Id_estudiantes }}">Licencia</label>

                                                <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="reprogramado_{{ $estudiante->Id_estudiantes }}" value="Reprogramado" onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, true)">
                                                <label class="btn btn-outline-info btn-sm" for="reprogramado_{{ $estudiante->Id_estudiantes }}">Reprog.</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="observacion[{{ $estudiante->Id_estudiantes }}]" class="form-control form-control-sm mb-2" placeholder="Observación (opcional)">
                                            
                                            <div id="reprogramacion_div_{{ $estudiante->Id_estudiantes }}" style="display: none;">
                                                <label class="form-label small text-info fw-bold">Nueva Fecha:</label>
                                                <input type="date" name="fecha_reprogramada[{{ $estudiante->Id_estudiantes }}]" class="form-control form-control-sm border-info">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Vista de cards para móviles/tablets --}}
                <div class="d-lg-none">
                    @foreach($estudiantes as $estudiante)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <input type="hidden" name="programa_id[{{ $estudiante->Id_estudiantes }}]" value="{{ $estudiante->Id_programas }}">
                                
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</h6>
                                        <small class="text-muted">{{ $estudiante->Cod_estudiante }}</small>
                                    </div>
                                    <span class="badge bg-secondary">{{ $estudiante->programa->Nombre ?? 'Sin programa' }}</span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small mb-2">Estado de Asistencia:</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="asistio_mobile_{{ $estudiante->Id_estudiantes }}" value="Asistio" checked onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, false)">
                                            <label class="btn btn-outline-success w-100" for="asistio_mobile_{{ $estudiante->Id_estudiantes }}">Asistió</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="falta_mobile_{{ $estudiante->Id_estudiantes }}" value="Falta" onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, false)">
                                            <label class="btn btn-outline-danger w-100" for="falta_mobile_{{ $estudiante->Id_estudiantes }}">Falta</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="licencia_mobile_{{ $estudiante->Id_estudiantes }}" value="Licencia" onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, false)">
                                            <label class="btn btn-outline-warning w-100" for="licencia_mobile_{{ $estudiante->Id_estudiantes }}">Licencia</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="asistencia[{{ $estudiante->Id_estudiantes }}]" id="reprogramado_mobile_{{ $estudiante->Id_estudiantes }}" value="Reprogramado" onclick="toggleReprogramacion({{ $estudiante->Id_estudiantes }}, true)">
                                            <label class="btn btn-outline-info w-100" for="reprogramado_mobile_{{ $estudiante->Id_estudiantes }}">Reprogramado</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Observación:</label>
                                    <input type="text" name="observacion[{{ $estudiante->Id_estudiantes }}]" class="form-control" placeholder="Observación (opcional)">
                                </div>

                                <div id="reprogramacion_div_{{ $estudiante->Id_estudiantes }}" style="display: none;">
                                    <label class="form-label small text-info fw-bold">Nueva Fecha:</label>
                                    <input type="date" name="fecha_reprogramada[{{ $estudiante->Id_estudiantes }}]" class="form-control border-info">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($estudiantes->isEmpty())
                    <div class="text-center py-5">
                        <p class="text-muted lead">No hay estudiantes activos asignados a este profesor.</p>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    function toggleReprogramacion(estudianteId, show) {
        const div = document.getElementById('reprogramacion_div_' + estudianteId);
        if (show) {
            div.style.display = 'block';
            div.querySelector('input').required = true;
        } else {
            div.style.display = 'none';
            div.querySelector('input').required = false;
        }
    }
</script>
@endsection
