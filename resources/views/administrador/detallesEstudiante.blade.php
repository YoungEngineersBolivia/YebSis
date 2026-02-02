@extends('administrador.baseAdministrador')

@section('title', 'Detalles del Estudiante')

@section('styles')
    <link href="{{ auto_asset('css/administrador/detallesEstudiantes.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="mt-2 mb-5 container-with-footer">
        @php
            $persona = $estudiante->persona ?? null;
            $nombreCompleto = $persona ? trim(($persona->Nombre ?? '') . ' ' . ($persona->Apellido ?? '')) : 'Sin nombre';
            $iniciales = $persona ? strtoupper(substr($persona->Nombre ?? 'S', 0, 1) . substr($persona->Apellido ?? 'N', 0, 1)) : 'SN';

            $programa = $estudiante->programa ?? null;
            $sucursal = $estudiante->sucursal ?? null;
            $tutor = $estudiante->tutor ?? null;
            $tutorPersona = $tutor?->persona ?? null;

            $profesor = $estudiante->profesor ?? null;
            $profesorPersona = $profesor?->persona ?? null;
            $profesorNombre = $profesorPersona
                ? trim(($profesorPersona->Nombre ?? '') . ' ' . ($profesorPersona->Apellido ?? ''))
                : 'Sin asignar';

            $estadoLower = Str::lower($estudiante->Estado ?? '');
            $esActivo = $estadoLower === 'activo';

            $edad = null;
            if ($persona && $persona->Fecha_nacimiento) {
                $fechaNac = \Carbon\Carbon::parse($persona->Fecha_nacimiento);
                $edad = $fechaNac->age;
            }

            // Obtener horarios desde la relación
            $horarios = $estudiante->horarios ?? collect();
        @endphp

        {{-- Botón de regresar --}}
        <div class="mb-3">
            <a href="{{ route('estudiantes.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>

        {{-- Mensajes de éxito/error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header del estudiante --}}
        <div class="student-header">
            <div class="student-avatar">
                {{ $iniciales }}
            </div>
            <h2>{{ $nombreCompleto }}</h2>
            <p class="mb-1">Código: <strong>{{ $estudiante->Cod_estudiante }}</strong></p>
            <span class="status-badge {{ $esActivo ? 'status-active' : 'status-inactive' }}">
                <i class="bi bi-circle-fill"></i> {{ $esActivo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Grid de información --}}
        <div class="info-grid">
            {{-- Datos personales --}}
            <div class="info-card">
                <div class="info-title">
                    <i class="bi bi-person-circle"></i> Datos personales
                </div>
                @if($persona)
                    <div class="info-item">
                        <span class="info-label">Nombre completo</span>
                        <span class="info-value">{{ $nombreCompleto }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Género</span>
                        <span class="info-value">
                            @if($persona->Genero === 'M')
                                <i class="bi bi-gender-male text-primary"></i> Masculino
                            @elseif($persona->Genero === 'F')
                                <i class="bi bi-gender-female text-danger"></i> Femenino
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de nacimiento</span>
                        <span class="info-value">
                            {{ $persona->Fecha_nacimiento ? \Carbon\Carbon::parse($persona->Fecha_nacimiento)->format('d/m/Y') : '—' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Edad</span>
                        <span class="info-value">
                            {{ $edad ? $edad . ' años' : '—' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Celular</span>
                        <span class="info-value">
                            @if($persona->Celular)
                                <a href="tel:{{ $persona->Celular }}" class="text-decoration-none">
                                    <i class="bi bi-phone"></i> {{ $persona->Celular }}
                                </a>
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dirección</span>
                        <span class="info-value">{{ $persona->Direccion_domicilio ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de registro</span>
                        <span class="info-value">
                            {{ $persona->Fecha_registro ? \Carbon\Carbon::parse($persona->Fecha_registro)->format('d/m/Y') : '—' }}
                        </span>
                    </div>
                @else
                    <p class="text-muted text-center mt-3 mb-0">Datos personales no disponibles</p>
                @endif
            </div>

            {{-- Información académica --}}
            <div class="info-card">
                <div class="info-title">
                    <i class="bi bi-mortarboard-fill"></i> Información académica
                </div>
                <div class="info-item">
                    <span class="info-label">Programa</span>
                    <span class="info-value">
                        <strong>{{ $programa->Nombre ?? '—' }}</strong>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tipo de programa</span>
                    <span class="info-value">
                        @if($programa && $programa->Tipo)
                            <span class="badge bg-info">{{ $programa->Tipo }}</span>
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Sucursal</span>
                    <span class="info-value">
                        @if($sucursal)
                            <i class="bi bi-building"></i> {{ $sucursal->Nombre }}
                            @if($sucursal->Direccion)
                                <br><small class="text-muted">{{ $sucursal->Direccion }}</small>
                            @endif
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Estado actual</span>
                    <span class="info-value">
                        <span class="badge {{ $esActivo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $estudiante->Estado }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Cambio de estado</span>
                    <span class="info-value">
                        {{ $estudiante->Fecha_estado ? \Carbon\Carbon::parse($estudiante->Fecha_estado)->format('d/m/Y') : '—' }}
                    </span>
                </div>
                @if($programa && $programa->Duracion)
                    <div class="info-item">
                        <span class="info-label">Duración del programa</span>
                        <span class="info-value">{{ $programa->Duracion }}</span>
                    </div>
                @endif
            </div>

            {{-- Tutor --}}
            <div class="info-card">
                <div class="info-title">
                    <i class="bi bi-people-fill"></i> Tutor / Responsable
                </div>
                @if($tutor && $tutorPersona)
                    <div class="info-item">
                        <span class="info-label">Nombre completo</span>
                        <span class="info-value">
                            <strong>{{ trim(($tutorPersona->Nombre ?? '') . ' ' . ($tutorPersona->Apellido ?? '')) }}</strong>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Celular</span>
                        <span class="info-value">
                            @if($tutorPersona->Celular)
                                <a href="tel:{{ $tutorPersona->Celular }}" class="text-decoration-none">
                                    <i class="bi bi-phone"></i> {{ $tutorPersona->Celular }}
                                </a>
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Parentesco</span>
                        <span class="info-value">{{ $tutor->Parentesco ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIT / CI</span>
                        <span class="info-value">{{ $tutor->Nit ?? '—' }}</span>
                    </div>
                    @if($tutor->Nombre_factura)
                        <div class="info-item">
                            <span class="info-label">Nombre para factura</span>
                            <span class="info-value">{{ $tutor->Nombre_factura }}</span>
                        </div>
                    @endif
                    @if($tutor->Descuento)
                        <div class="info-item">
                            <span class="info-label">Descuento aplicable</span>
                            <span class="info-value">
                                <span class="badge bg-success">{{ $tutor->Descuento }}%</span>
                            </span>
                        </div>
                    @endif
                @else
                    <p class="text-muted text-center mt-3 mb-0">
                        <i class="bi bi-exclamation-circle"></i><br>
                        No hay tutor asignado
                    </p>
                @endif
            </div>

            {{-- Horarios --}}
            <div class="info-card">
                <div class="info-title">
                    <i class="bi bi-calendar-week-fill"></i> Horarios de clase
                </div>
                @if($horarios->isEmpty())
                    <p class="text-muted text-center mt-3 mb-0">
                        <i class="bi bi-calendar-x"></i><br>
                        No hay horarios asignados
                    </p>
                @else
                    @foreach($horarios->sortBy('Dia') as $horario)
                        @php
                            $programaHorario = $horario->programa?->Nombre ?? 'Sin programa';
                            $profesorHorario = $horario->profesor?->persona;
                            $profesorNombre = $profesorHorario ? ($profesorHorario->Nombre . ' ' . $profesorHorario->Apellido) : 'No asignado';
                        @endphp

                        <div class="mb-3 p-2 border-start border-success border-3 bg-light rounded">
                            <div class="info-item mb-1">
                                <span class="info-label">
                                    <i class="bi bi-calendar-day"></i> {{ ucfirst(strtolower($horario->Dia)) }}
                                </span>
                                <span class="info-value">
                                    <strong>{{ \Carbon\Carbon::parse($horario->Hora)->format('H:i') }}</strong>
                                </span>
                            </div>

                            <div class="info-item mb-1">
                                <span class="info-label">
                                    <i class="bi bi-person-badge"></i> Profesor
                                </span>
                                <span class="info-value">{{ $profesorNombre }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-book"></i> Programa
                                </span>
                                <span class="info-value">{{ $programaHorario }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Progreso de Modelos --}}
            <div class="info-card">
                <div class="info-title">
                    <i class="bi bi-bookmark-star-fill"></i> Progreso de Modelos
                </div>

                @if(isset($ultimoModelo) || isset($proximoModelo))
                    {{-- Último Modelo Evaluado --}}
                    @if(isset($ultimoModelo) && $ultimoModelo)
                        <div class="mb-3 p-3 border-start border-success border-4 bg-light rounded">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <h6 class="mb-0 fw-bold">Último Modelo Completado</h6>
                            </div>
                            <div class="info-item mb-1">
                                <span class="info-label">
                                    <i class="bi bi-bookmark-check"></i> Modelo
                                </span>
                                <span class="info-value">
                                    <strong>{{ $ultimoModelo->Nombre_modelo }}</strong>
                                </span>
                            </div>
                            @if(isset($ultimaEvaluacion) && $ultimaEvaluacion)
                                <div class="info-item">
                                    <span class="info-label">
                                        <i class="bi bi-calendar-check"></i> Fecha evaluación
                                    </span>
                                    <span class="info-value">
                                        {{ \Carbon\Carbon::parse($ultimaEvaluacion->fecha_evaluacion)->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Próximo Modelo --}}
                    @if(isset($proximoModelo) && $proximoModelo)
                        <div class="p-3 border-start border-primary border-4 bg-light rounded">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-arrow-right-circle-fill text-primary me-2"></i>
                                <h6 class="mb-0 fw-bold">Próximo Modelo</h6>
                            </div>
                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-bookmark"></i> Modelo
                                </span>
                                <span class="info-value">
                                    <strong>{{ $proximoModelo->Nombre_modelo }}</strong>
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-info">
                                    <i class="bi bi-hourglass-split"></i> Pendiente de evaluación
                                </span>
                            </div>
                        </div>
                    @elseif(isset($ultimoModelo) && $ultimoModelo)
                        <div class="p-3 border-start border-warning border-4 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-trophy-fill text-warning me-2"></i>
                                <h6 class="mb-0 fw-bold text-success">
                                    ¡Programa Completado!
                                </h6>
                            </div>
                            <p class="mb-0 mt-2 text-muted small">
                                El estudiante ha completado todos los modelos del programa.
                            </p>
                        </div>
                    @endif
                @else
                    <p class="text-muted text-center mt-3 mb-0">
                        <i class="bi bi-info-circle"></i><br>
                        Aún no hay evaluaciones registradas
                        @if(isset($proximoModelo) && $proximoModelo)
                            <br>
                            <small class="mt-2 d-block">
                                Primer modelo del programa: <strong>{{ $proximoModelo->Nombre_modelo }}</strong>
                            </small>
                        @endif
                    </p>
                @endif
            </div>

        </div>

    </div>

    {{-- Footer con acciones --}}
    <div class="action-footer d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('estudiantes.planesPago', $estudiante->Id_estudiantes) }}" class="btn btn-primary">
            <i class="bi bi-cash-stack"></i> Planes de Pago
        </a>
        <a href="{{ route('estudiantes.evaluaciones', $estudiante->Id_estudiantes) }}" class="btn btn-info text-white">
            <i class="bi bi-clipboard-check-fill"></i> Evaluaciones
        </a>
        <form action="{{ route('estudiantes.cambiarEstado', $estudiante->Id_estudiantes) }}" method="POST"
            onsubmit="return confirm('¿Está seguro de cambiar el estado del estudiante?')" class="d-inline">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-toggle-{{ $esActivo ? 'off' : 'on' }}"></i>
                {{ $esActivo ? 'Desactivar' : 'Activar' }}
            </button>
        </form>
    </div>
@endsection