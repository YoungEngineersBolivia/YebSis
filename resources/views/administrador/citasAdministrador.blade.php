@extends('administrador.baseAdministrador')

@section('title', 'Administración de Citas')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .cita-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        .cita-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .cita-pendiente {
            border-left-color: #ffc107;
        }
        .cita-completada {
            border-left-color: #198754;
        }
        .cita-cancelada {
            border-left-color: #dc3545;
        }
        .cita-futura {
            border-left-color: #0d6efd;
        }
        .badge-estado {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        .tabla-scroll {
            max-height: 500px;
            overflow-y: auto;
        }
        .filtros-card {
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .estudiante-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .tab-content {
            min-height: 400px;
        }
        .hoy-badge {
            background-color: #0d6efd;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
        }
        .proxima-semana {
            background-color: #ffc107;
            color: #000;
        }
        .mes-actual {
            background-color: #198754;
            color: white;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold">
                <i class="bi bi-calendar-check me-2"></i>Administración de Citas
            </h2>
            <p class="text-muted mb-0">Gestiona todas las citas del sistema</p>
        </div>
    </div>

    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Citas</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalCitas ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-calendar-week display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50 mb-1">Pendientes</h6>
                            <h2 class="mb-0 fw-bold">{{ $pendientes ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-clock-history display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Completadas</h6>
                            <h2 class="mb-0 fw-bold">{{ $completadas ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check-circle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Esta Semana</h6>
                            <h2 class="mb-0 fw-bold">{{ $citasSemana ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-calendar-day display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros y búsqueda --}}
    <div class="card shadow-sm mb-4 filtros-card">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="filtroFecha" class="form-label mb-1 fw-semibold">Filtrar por fecha:</label>
                    <select id="filtroFecha" class="form-select">
                        <option value="todas">Todas las fechas</option>
                        <option value="hoy">Hoy</option>
                        <option value="semana">Esta semana</option>
                        <option value="mes">Este mes</option>
                        <option value="futuras">Futuras</option>
                        <option value="pasadas">Pasadas</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label for="filtroEstado" class="form-label mb-1 fw-semibold">Filtrar por estado:</label>
                    <select id="filtroEstado" class="form-select">
                        <option value="todos">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="buscador" class="form-label mb-1 fw-semibold">Buscar:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="buscador" class="form-control border-start-0" 
                               placeholder="Nombre de estudiante o tutor...">
                    </div>
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()" title="Limpiar filtros">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs para citas próximas y pasadas --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-3">
            <ul class="nav nav-tabs nav-justified" id="citasTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="proximas-tab" data-bs-toggle="tab" 
                            data-bs-target="#proximas" type="button" role="tab">
                        <i class="bi bi-calendar-plus me-2"></i>Citas Próximas
                        <span class="badge bg-primary ms-2">{{ $citasProximas->count() ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pasadas-tab" data-bs-toggle="tab" 
                            data-bs-target="#pasadas" type="button" role="tab">
                        <i class="bi bi-calendar-check me-2"></i>Citas Pasadas
                        <span class="badge bg-secondary ms-2">{{ $citasPasadas->count() ?? 0 }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="citasTabContent">
                
                {{-- Tab de citas próximas --}}
                <div class="tab-pane fade show active" id="proximas" role="tabpanel">
                    @if($citasProximas->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">No hay citas próximas</h5>
                            <p class="text-muted">No se encontraron citas programadas para fechas futuras.</p>
                        </div>
                    @else
                        <div class="tabla-scroll">
                            <table class="table table-hover align-middle" id="tablaProximas">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Tutor</th>
                                        <th>Fecha y Hora</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($citasProximas as $cita)
                                        @php
                                            $fechaCita = \Carbon\Carbon::parse($cita->Fecha);
                                            $hoy = \Carbon\Carbon::today();
                                            $diferenciaDias = $hoy->diffInDays($fechaCita, false);
                                            
                                            $badgeClase = '';
                                            if($diferenciaDias == 0) {
                                                $badgeClase = 'hoy-badge';
                                                $textoFecha = 'Hoy';
                                            } elseif($diferenciaDias > 0 && $diferenciaDias <= 7) {
                                                $badgeClase = 'proxima-semana';
                                                $textoFecha = 'Próxima semana';
                                            } else {
                                                $badgeClase = 'mes-actual';
                                                $textoFecha = 'Este mes';
                                            }
                                            
                                            $claseEstado = 'cita-futura';
                                            if($cita->estado == 'pendiente') {
                                                $claseEstado = 'cita-pendiente';
                                            } elseif($cita->estado == 'completada') {
                                                $claseEstado = 'cita-completada';
                                            } elseif($cita->estado == 'cancelada') {
                                                $claseEstado = 'cita-cancelada';
                                            }
                                        @endphp
                                        <tr class="cita-card {{ $claseEstado }}" 
                                            data-estado="{{ $cita->estado }}"
                                            data-fecha="{{ $fechaCita->format('Y-m-d') }}"
                                            data-estudiante="{{ strtolower($cita->estudiante->persona->Nombre ?? '') }} {{ strtolower($cita->estudiante->persona->Apellido ?? '') }}"
                                            data-tutor="{{ strtolower($cita->tutor->persona->Nombre ?? '') }} {{ strtolower($cita->tutor->persona->Apellido ?? '') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="estudiante-avatar me-3">
                                                        {{ substr($cita->estudiante->persona->Nombre ?? 'E', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $cita->estudiante->persona->Nombre ?? '' }} {{ $cita->estudiante->persona->Apellido ?? '' }}</div>
                                                        <small class="text-muted">{{ $cita->estudiante->Cod_estudiante ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $cita->tutor->persona->Nombre ?? '' }} {{ $cita->tutor->persona->Apellido ?? '' }}
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $fechaCita->format('d/m/Y') }}</div>
                                                <div class="text-muted">{{ \Carbon\Carbon::parse($cita->Hora)->format('H:i') }}</div>
                                                <span class="badge {{ $badgeClase }} mt-1">{{ $textoFecha }}</span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" 
                                                     title="{{ $cita->motivo ?? 'Sin motivo especificado' }}">
                                                    {{ $cita->motivo ?? 'Sin motivo especificado' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($cita->estado == 'pendiente')
                                                    <span class="badge bg-warning text-dark badge-estado">Pendiente</span>
                                                @elseif($cita->estado == 'completada')
                                                    <span class="badge bg-success badge-estado">Completada</span>
                                                @else
                                                    <span class="badge bg-danger badge-estado">Cancelada</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#detalleCitaModal{{ $cita->Id_citas }}"
                                                            title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    @if($cita->estado == 'pendiente')
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="marcarCompletada({{ $cita->Id_citas }})"
                                                                title="Marcar como completada">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="cancelarCita({{ $cita->Id_citas }})"
                                                                title="Cancelar cita">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    @endif
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                
                {{-- Tab de citas pasadas --}}
                <div class="tab-pane fade" id="pasadas" role="tabpanel">
                    @if($citasPasadas->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-calendar-minus text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">No hay citas pasadas</h5>
                            <p class="text-muted">No se encontraron citas en fechas anteriores.</p>
                        </div>
                    @else
                        <div class="tabla-scroll">
                            <table class="table table-hover align-middle" id="tablaPasadas">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Tutor</th>
                                        <th>Fecha y Hora</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($citasPasadas as $cita)
                                        @php
                                            $fechaCita = \Carbon\Carbon::parse($cita->Fecha);
                                            $diasPasados = \Carbon\Carbon::now()->diffInDays($fechaCita);
                                            
                                            $claseEstado = 'cita-futura';
                                            if($cita->estado == 'completada') {
                                                $claseEstado = 'cita-completada';
                                            } elseif($cita->estado == 'cancelada') {
                                                $claseEstado = 'cita-cancelada';
                                            } elseif($cita->estado == 'pendiente') {
                                                $claseEstado = 'cita-pendiente';
                                            }
                                        @endphp
                                        <tr class="cita-card {{ $claseEstado }}"
                                            data-estado="{{ $cita->estado }}"
                                            data-fecha="{{ $fechaCita->format('Y-m-d') }}"
                                            data-estudiante="{{ strtolower($cita->estudiante->persona->Nombre ?? '') }} {{ strtolower($cita->estudiante->persona->Apellido ?? '') }}"
                                            data-tutor="{{ strtolower($cita->tutor->persona->Nombre ?? '') }} {{ strtolower($cita->tutor->persona->Apellido ?? '') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="estudiante-avatar me-3">
                                                        {{ substr($cita->estudiante->persona->Nombre ?? 'E', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $cita->estudiante->persona->Nombre ?? '' }} {{ $cita->estudiante->persona->Apellido ?? '' }}</div>
                                                        <small class="text-muted">{{ $cita->estudiante->Cod_estudiante ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $cita->tutor->persona->Nombre ?? '' }} {{ $cita->tutor->persona->Apellido ?? '' }}
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $fechaCita->format('d/m/Y') }}</div>
                                                <div class="text-muted">{{ \Carbon\Carbon::parse($cita->Hora)->format('H:i') }}</div>
                                                <small class="text-muted">Hace {{ $diasPasados }} día{{ $diasPasados != 1 ? 's' : '' }}</small>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;"
                                                     title="{{ $cita->motivo ?? 'Sin motivo especificado' }}">
                                                    {{ $cita->motivo ?? 'Sin motivo especificado' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($cita->estado == 'completada')
                                                    <span class="badge bg-success badge-estado">Completada</span>
                                                @elseif($cita->estado == 'cancelada')
                                                    <span class="badge bg-danger badge-estado">Cancelada</span>
                                                @else
                                                    <span class="badge bg-warning text-dark badge-estado">Pendiente</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#detalleCitaModal{{ $cita->Id_citas }}"
                                                            title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    @if($cita->estado != 'completada')
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="marcarCompletada({{ $cita->Id_citas }})"
                                                                title="Marcar como completada">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    @endif
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal para crear nueva cita --}}
<div class="modal fade" id="crearCitaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-plus me-2"></i>Agendar Nueva Cita
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCrearCita" action="{{ route('citas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estudiante <span class="text-danger">*</span></label>
                            <select class="form-select" name="estudiante_id" required>
                                <option value="">Seleccionar estudiante...</option>
                                @foreach($estudiantes ?? [] as $estudiante)
                                    <option value="{{ $estudiante->Id_estudiantes }}" {{ old('estudiante_id') == $estudiante->Id_estudiantes ? 'selected' : '' }}>
                                        {{ $estudiante->persona->Nombre ?? '' }} {{ $estudiante->persona->Apellido ?? '' }} - {{ $estudiante->Cod_estudiante }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tutor <span class="text-danger">*</span></label>
                            <select class="form-select" name="tutor_id" required>
                                <option value="">Seleccionar tutor...</option>
                                @foreach($tutores ?? [] as $tutor)
                                    <option value="{{ $tutor->Id_tutores }}" {{ old('tutor_id') == $tutor->Id_tutores ? 'selected' : '' }}>
                                        {{ $tutor->persona->Nombre ?? '' }} {{ $tutor->persona->Apellido ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha" 
                                   value="{{ old('fecha') }}"
                                   min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hora <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="hora" 
                                   value="{{ old('hora') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Motivo</label>
                            <textarea class="form-control" name="motivo" rows="3" 
                                      placeholder="Describe el motivo de la cita...">{{ old('motivo') }}</textarea>
                            <small class="text-muted">Opcional - Puedes especificar el motivo de la consulta</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Agendar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modales de detalles para cada cita --}}
@foreach($citasProximas->concat($citasPasadas) as $cita)
<div class="modal fade" id="detalleCitaModal{{ $cita->Id_citas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-event me-2"></i>Detalles de Cita #{{ $cita->Id_citas }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Estudiante</label>
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <div class="estudiante-avatar me-3">
                                {{ substr($cita->estudiante->persona->Nombre ?? 'E', 0, 1) }}
                            </div>
                            <div>
                                <p class="fw-bold mb-0">{{ $cita->estudiante->persona->Nombre ?? '' }} {{ $cita->estudiante->persona->Apellido ?? '' }}</p>
                                <small class="text-muted">{{ $cita->estudiante->Cod_estudiante ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Tutor</label>
                        <p class="form-control bg-light">
                            {{ $cita->tutor->persona->Nombre ?? '' }} {{ $cita->tutor->persona->Apellido ?? '' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Fecha</label>
                        <p class="form-control bg-light">
                            {{ \Carbon\Carbon::parse($cita->Fecha)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Hora</label>
                        <p class="form-control bg-light">{{ \Carbon\Carbon::parse($cita->Hora)->format('H:i') }}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted">Estado</label>
                        <p class="form-control bg-light">
                            @if($cita->estado == 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($cita->estado == 'completada')
                                <span class="badge bg-success">Completada</span>
                            @else
                                <span class="badge bg-danger">Cancelada</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted">Motivo</label>
                        <textarea class="form-control bg-light" rows="4" readonly>{{ $cita->motivo ?? 'Sin motivo especificado' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Fecha de Creación</label>
                        <p class="form-control bg-light">
                            {{ $cita->created_at ? $cita->created_at->format('d/m/Y H:i:s') : 'No disponible' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Última Actualización</label>
                        <p class="form-control bg-light">
                            {{ $cita->updated_at ? $cita->updated_at->format('d/m/Y H:i:s') : 'No disponible' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if($cita->estado == 'pendiente')
                <button type="button" class="btn btn-success" onclick="marcarCompletada({{ $cita->Id_citas }})">
                    <i class="bi bi-check-circle me-2"></i>Marcar Completada
                </button>
                <button type="button" class="btn btn-danger" onclick="cancelarCita({{ $cita->Id_citas }})">
                    <i class="bi bi-x-circle me-2"></i>Cancelar Cita
                </button>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endforeach

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
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Configurar fecha mínima para el campo de fecha
        const fechaInput = document.querySelector('input[name="fecha"]');
        if(fechaInput) {
            fechaInput.min = new Date().toISOString().split('T')[0];
        }

        // Reaplicar filtros cuando se cambia de tab
        document.querySelectorAll('#citasTab button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function () {
                filtrarCitas();
            });
        });

        // Aplicar filtros al cargar si hay errores de validación
        @if($errors->any() || old('estudiante_id'))
            const modal = new bootstrap.Modal(document.getElementById('crearCitaModal'));
            modal.show();
        @endif
    });

    // Función para limpiar filtros
    function limpiarFiltros() {
        document.getElementById('filtroFecha').value = 'todas';
        document.getElementById('filtroEstado').value = 'todos';
        document.getElementById('buscador').value = '';
        filtrarCitas();
    }

    // Función para filtrar citas
    function filtrarCitas() {
        const filtroFecha = document.getElementById('filtroFecha').value;
        const filtroEstado = document.getElementById('filtroEstado').value;
        const buscador = document.getElementById('buscador').value.toLowerCase();
        
        // Obtener el tab activo
        const tabActiva = document.querySelector('.tab-pane.active');
        const filas = tabActiva.querySelectorAll('tbody tr');
        
        let contadorVisibles = 0;
        
        filas.forEach(fila => {
            let mostrar = true;
            
            // Filtrar por búsqueda
            if (buscador) {
                const estudiante = fila.getAttribute('data-estudiante');
                const tutor = fila.getAttribute('data-tutor');
                if (!estudiante.includes(buscador) && !tutor.includes(buscador)) {
                    mostrar = false;
                }
            }
            
            // Filtrar por estado
            if (filtroEstado !== 'todos') {
                const estado = fila.getAttribute('data-estado');
                if (estado !== filtroEstado) {
                    mostrar = false;
                }
            }
            
            // Filtrar por fecha (solo para citas próximas)
            if (filtroFecha !== 'todas' && tabActiva.id === 'proximas') {
                const fechaFila = new Date(fila.getAttribute('data-fecha'));
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                
                switch (filtroFecha) {
                    case 'hoy':
                        const manana = new Date(hoy);
                        manana.setDate(manana.getDate() + 1);
                        if (fechaFila < hoy || fechaFila >= manana) {
                            mostrar = false;
                        }
                        break;
                    case 'semana':
                        const finSemana = new Date(hoy);
                        finSemana.setDate(finSemana.getDate() + 7);
                        if (fechaFila < hoy || fechaFila > finSemana) {
                            mostrar = false;
                        }
                        break;
                    case 'mes':
                        if (fechaFila.getMonth() !== hoy.getMonth() || 
                            fechaFila.getFullYear() !== hoy.getFullYear()) {
                            mostrar = false;
                        }
                        break;
                }
            }
            
            if (mostrar) {
                fila.style.display = '';
                contadorVisibles++;
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        mostrarMensajeSinResultados(tabActiva, contadorVisibles);
    }

    // Función para mostrar mensaje cuando no hay resultados
    function mostrarMensajeSinResultados(tabActiva, contador) {
        let mensajeExistente = tabActiva.querySelector('.sin-resultados-mensaje');
        
        if (contador === 0) {
            if (!mensajeExistente) {
                const mensaje = document.createElement('div');
                mensaje.className = 'sin-resultados-mensaje text-center py-5';
                mensaje.innerHTML = `
                    <div class="mb-3">
                        <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">No se encontraron resultados</h5>
                    <p class="text-muted">Intenta con otros filtros de búsqueda.</p>
                `;
                tabActiva.querySelector('.tabla-scroll').style.display = 'none';
                tabActiva.appendChild(mensaje);
            }
        } else {
            if (mensajeExistente) {
                mensajeExistente.remove();
                tabActiva.querySelector('.tabla-scroll').style.display = 'block';
            }
        }
    }

    // Función para marcar cita como completada
    function marcarCompletada(citaId) {
        if (confirm('¿Está seguro de marcar esta cita como completada?')) {
            fetch(`/citas/${citaId}/completar`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal si está abierto
                    const modal = bootstrap.Modal.getInstance(document.querySelector(`#detalleCitaModal${citaId}`));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Mostrar mensaje de éxito
                    mostrarMensaje('success', data.message);
                    
                    // Recargar la página después de 1 segundo
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    mostrarMensaje('danger', data.message || 'Error al completar la cita');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('danger', 'Error al procesar la solicitud');
            });
        }
    }

    // Función para cancelar cita
    function cancelarCita(citaId) {
        if (confirm('¿Está seguro de cancelar esta cita? Esta acción no se puede deshacer.')) {
            fetch(`/citas/${citaId}/cancelar`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal si está abierto
                    const modal = bootstrap.Modal.getInstance(document.querySelector(`#detalleCitaModal${citaId}`));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Mostrar mensaje de éxito
                    mostrarMensaje('success', data.message);
                    
                    // Recargar la página después de 1 segundo
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    mostrarMensaje('danger', data.message || 'Error al cancelar la cita');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('danger', 'Error al procesar la solicitud');
            });
        }
    }

    // Función para mostrar mensajes de alerta
    function mostrarMensaje(tipo, mensaje) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            <i class="bi bi-${tipo === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'} me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Validación del formulario de creación de cita
    document.getElementById('formCrearCita')?.addEventListener('submit', function(e) {
        const fecha = this.querySelector('input[name="fecha"]').value;
        const hora = this.querySelector('input[name="hora"]').value;
        
        if (!fecha || !hora) {
            e.preventDefault();
            mostrarMensaje('warning', 'Por favor complete todos los campos obligatorios.');
            return;
        }
        
        const fechaHora = new Date(fecha + 'T' + hora);
        const ahora = new Date();
        
        if (fechaHora < ahora) {
            e.preventDefault();
            mostrarMensaje('warning', 'La fecha y hora de la cita deben ser futuras.');
        }
    });

    // Agregar eventos a los filtros
    document.getElementById('filtroFecha').addEventListener('change', filtrarCitas);
    document.getElementById('filtroEstado').addEventListener('change', filtrarCitas);
    document.getElementById('buscador').addEventListener('keyup', filtrarCitas);
</script>
@endsection