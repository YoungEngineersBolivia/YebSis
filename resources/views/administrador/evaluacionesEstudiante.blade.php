@extends('administrador.baseAdministrador')

@section('title', 'Evaluaciones del Estudiante')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .evaluacion-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
        }
        .evaluacion-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .programa-badge {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 500;
        }
        .fecha-badge {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 0.85rem;
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')

@if(isset($estudiante) && $estudiante)
    {{-- Cuando ves un estudiante específico --}}
    @php
        $evaluaciones = $estudiante->evaluaciones;
    @endphp
@endif
<div class="container-fluid mt-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        @if(isset($estudiante) && $estudiante)
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-clipboard-check me-2"></i>Evaluaciones de {{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}
            </h2>
        @else
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-clipboard-check me-2"></i>Todas las Evaluaciones
            </h2>
        @endif
        
        <div class="d-flex gap-2">
            @if(isset($estudiante) && $estudiante)
                <a href="{{ route('estudiantes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver a Estudiantes
                </a>
                <a href="{{ route('estudiantes.ver', $estudiante->Id_estudiantes) }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-circle me-2"></i>Ver Perfil
                </a>
            @else
                <a href="{{ route('estudiantes.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-people me-2"></i>Ver Estudiantes
                </a>
            @endif
        </div>
    </div>

    {{-- Información del estudiante (solo si se está viendo un estudiante específico) --}}
    @if(isset($estudiante) && $estudiante)
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="mb-0 fw-bold me-3">{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</h5>
                            <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $estudiante->Cod_estudiante }}</span>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Programa:</strong> {{ $estudiante->programa->Nombre ?? 'Sin programa' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Sucursal:</strong> {{ $estudiante->sucursal->Nombre ?? 'Sin sucursal' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-0"><strong>Tutor:</strong> 
                                    {{ $estudiante->tutor->persona->Nombre ?? '' }} {{ $estudiante->tutor->persona->Apellido ?? 'Sin tutor' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-inline-block bg-white p-3 rounded-3 shadow-sm">
                            <h6 class="text-muted mb-2">Total de Evaluaciones</h6>
                            <h2 class="mb-0 text-primary fw-bold">{{ $evaluaciones->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Buscador (solo para vista de todas las evaluaciones) --}}
    @if(!isset($estudiante))
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <form action="{{ route('evaluaciones.index') }}" method="GET">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">Buscar Evaluaciones</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" id="searchInput" class="form-control border-start-0 ps-0" 
                                       placeholder="Filtrar por estudiante, código o programa..." 
                                       name="search" value="{{ request()->search }}">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary mt-3 mt-md-0">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Lista de evaluaciones --}}
    @if($evaluaciones->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                </div>
                @if(isset($estudiante) && $estudiante)
                    <h5 class="text-muted">No hay evaluaciones registradas</h5>
                    <p class="text-muted mb-4">Este estudiante no tiene evaluaciones en el sistema.</p>
                @else
                    <h5 class="text-muted">No hay evaluaciones registradas</h5>
                    <p class="text-muted mb-4">No se encontraron evaluaciones con los criterios de búsqueda.</p>
                @endif
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                @if(!isset($estudiante))
                                    <th class="ps-3 py-3" style="min-width:120px;">Estudiante</th>
                                @endif
                                <th class="py-3" style="min-width:200px;">Programa</th>
                                <th class="py-3" style="min-width:180px;">Pregunta</th>
                                <th class="py-3" style="min-width:180px;">Respuesta</th>
                                <th class="py-3" style="min-width:150px;">Modelo</th>
                                <th class="py-3" style="min-width:150px;">Profesor</th>
                                <th class="py-3" style="min-width:120px;">Fecha</th>
                                <th class="pe-3 py-3 text-end" style="min-width:100px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($evaluaciones as $evaluacion)
                                @php
                                    $estudianteEval = $evaluacion->estudiante ?? null;
                                    $personaEval = $estudianteEval->persona ?? null;
                                    $nombreEstudiante = $personaEval ? trim($personaEval->Nombre . ' ' . $personaEval->Apellido) : 'Estudiante no disponible';
                                    
                                    $profesor = $evaluacion->profesor ?? null;
                                    $personaProf = $profesor->persona ?? null;
                                    $nombreProfesor = $personaProf ? trim($personaProf->Nombre . ' ' . $personaProf->Apellido) : 'Profesor no asignado';
                                @endphp
                                <tr>
                                    @if(!isset($estudiante))
                                        <td class="ps-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-primary">{{ $estudianteEval->Cod_estudiante ?? 'Sin código' }}</span>
                                                <small class="text-muted">{{ $nombreEstudiante }}</small>
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <span class="badge programa-badge px-3 py-2">
                                            {{ $evaluacion->programa->Nombre ?? 'Sin programa' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $evaluacion->pregunta->Pregunta ?? 'Pregunta no disponible' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $evaluacion->respuesta->Respuesta ?? 'Sin respuesta' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $evaluacion->modelo->Nombre_modelo ?? 'Sin modelo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $nombreProfesor }}</span>
                                            <small class="text-muted">{{ $profesor->Profesion ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge fecha-badge px-3 py-2">
                                            @if($evaluacion->fecha_evaluacion)
                                                {{ \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') }}
                                            @else
                                                Sin fecha
                                            @endif
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            {{-- Botón Ver detalles --}}
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detalleModal{{ $evaluacion->Id_evaluaciones }}"
                                                    data-bs-toggle="tooltip" 
                                                    title="Ver detalles">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            {{-- Botón para ver perfil del estudiante (solo en vista general) --}}
                                            @if(!isset($estudiante) && $estudianteEval)
                                                <a href="{{ route('estudiantes.ver', $estudianteEval->Id_estudiantes) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip"
                                                   title="Ver estudiante">
                                                    <i class="bi bi-person-circle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal de detalles de la evaluación --}}
                                <div class="modal fade" id="detalleModal{{ $evaluacion->Id_evaluaciones }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-clipboard-data me-2"></i>Detalles de Evaluación
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    @if(!isset($estudiante))
                                                    <div class="col-md-12">
                                                        <div class="card bg-light mb-3">
                                                            <div class="card-body">
                                                                <h6 class="card-title fw-bold">Información del Estudiante</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <p class="mb-1"><strong>Nombre:</strong> {{ $nombreEstudiante }}</p>
                                                                        <p class="mb-0"><strong>Código:</strong> {{ $estudianteEval->Cod_estudiante ?? 'Sin código' }}</p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p class="mb-1"><strong>Estado:</strong> 
                                                                            @if($estudianteEval)
                                                                                @php $estadoLower = Str::lower($estudianteEval->Estado ?? ''); @endphp
                                                                                @if($estadoLower === 'activo')
                                                                                    <span class="badge bg-success">Activo</span>
                                                                                @elseif($estadoLower === 'inactivo')
                                                                                    <span class="badge bg-secondary">Inactivo</span>
                                                                                @else
                                                                                    <span class="badge bg-light text-dark">{{ $estudianteEval->Estado }}</span>
                                                                                @endif
                                                                            @else
                                                                                <span class="badge bg-light text-dark">No disponible</span>
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted">Programa</label>
                                                        <p class="form-control bg-light">{{ $evaluacion->programa->Nombre ?? 'Sin programa' }}</p>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted">Fecha de Evaluación</label>
                                                        <p class="form-control bg-light">
                                                            @if($evaluacion->fecha_evaluacion)
                                                                {{ \Carbon\Carbon::parse($evaluacion->fecha_evaluacion)->format('d/m/Y') }}
                                                            @else
                                                                No especificada
                                                            @endif
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold text-muted">Pregunta Evaluada</label>
                                                        <textarea class="form-control bg-light" rows="3" readonly>{{ $evaluacion->pregunta->Pregunta ?? 'Pregunta no disponible' }}</textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold text-muted">Respuesta del Estudiante</label>
                                                        <textarea class="form-control bg-light" rows="3" readonly>{{ $evaluacion->respuesta->Respuesta ?? 'Sin respuesta' }}</textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted">Modelo Utilizado</label>
                                                        <p class="form-control bg-light">{{ $evaluacion->modelo->Nombre_modelo ?? 'Sin modelo' }}</p>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold text-muted">Profesor Evaluador</label>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-person-circle me-2 text-primary"></i>
                                                            <p class="form-control bg-light mb-0">{{ $nombreProfesor }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold text-muted">Fecha de Registro</label>
                                                        <p class="form-control bg-light">
                                                            {{ $evaluacion->created_at->format('d/m/Y H:i:s') }}
                                                        </p>
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

    {{-- Paginación (solo para vista de todas las evaluaciones) --}}
    @if(!isset($estudiante) && $evaluaciones->hasPages())
        <div class="d-flex justify-content-center mt-4 mb-4">
            {{ $evaluaciones->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    // Auto-cerrar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection