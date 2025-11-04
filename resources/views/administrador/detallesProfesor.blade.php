@extends('administrador.baseAdministrador')

@section('title', 'Detalles del Profesor')

@section('styles')
<link href="{{ auto_asset('css/administrador/detallesEstudiantes.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container mt-4 mb-5 container-with-footer">
    @php
        $persona = $profesor->persona ?? null;
        $usuario = $profesor->usuario ?? null;
        $nombreCompleto = $persona ? trim(($persona->Nombre ?? '').' '.($persona->Apellido ?? '')) : 'Sin nombre';
        $iniciales = $persona ? strtoupper(substr($persona->Nombre ?? 'S', 0, 1) . substr($persona->Apellido ?? 'N', 0, 1)) : 'SN';
        $rol = $persona->rol->Nombre_rol ?? 'Sin rol';
        $genero = $persona->Genero === 'M' ? 'Masculino' : ($persona->Genero === 'F' ? 'Femenino' : 'No especificado');
        $profesion = $profesor->Profesion ?? 'No registrada';
        $rolComponentes = $profesor->Rol_componentes ?? 'Ninguno';
    @endphp

    {{-- Botón de regresar --}}
    <div class="mb-3">
        <a href="{{ route('profesores.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    {{-- Header del profesor --}}
    <div class="student-header">
        <div class="student-avatar">
            {{ $iniciales }}
        </div>
        <h2>{{ $nombreCompleto }}</h2>
        <p class="mb-1">
            <i class="bi bi-envelope"></i> 
            <strong>{{ $usuario->Correo ?? 'Sin correo' }}</strong>
        </p>
        <p class="mb-1">
            <i class="bi bi-briefcase"></i> 
            Profesión: <strong>{{ $profesion }}</strong>
        </p>
        <p class="mb-1">
            <i class="bi bi-person-badge"></i> 
            Rol en el sistema: <strong>{{ $rol }}</strong>
        </p>
    </div>

    {{-- Grid información --}}
    <div class="info-grid mt-4">
        {{-- Datos personales --}}
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-person-lines-fill"></i> Datos personales
            </div>
            <div class="info-item">
                <span class="info-label">Nombre completo</span>
                <span class="info-value">{{ $nombreCompleto }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Género</span>
                <span class="info-value">{{ $genero }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Celular</span>
                <span class="info-value">
                    @if($persona->Celular)
                        <a href="tel:{{ $persona->Celular }}" class="text-decoration-none">
                            <i class="bi bi-phone"></i> {{ $persona->Celular }}
                        </a>
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Dirección</span>
                <span class="info-value">{{ $persona->Direccion_domicilio ?? '—' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha de nacimiento</span>
                <span class="info-value">
                    {{ $persona->Fecha_nacimiento ? \Carbon\Carbon::parse($persona->Fecha_nacimiento)->format('d/m/Y') : '—' }}
                </span>
            </div>
        </div>

        {{-- Horarios con estudiantes --}}
        <div class="info-card">
            <div class="info-title">
                <i class="bi bi-people-fill"></i> Estudiantes por horario
            </div>

            @php
                $horariosAgrupados = $profesor->horarios->groupBy('Dia') ?? collect();
            @endphp

            @if($horariosAgrupados->isEmpty())
                <p class="text-muted text-center mt-3 mb-0">
                    <i class="bi bi-calendar-x"></i><br>
                    No tiene horarios asignados
                </p>
            @else
                @foreach($horariosAgrupados as $dia => $clasesPorDia)
                    <h5 class="mt-4">{{ ucfirst(strtolower($dia)) }}</h5>
                    @foreach ($clasesPorDia as $clase)
                        @php
                            $estudiante = $clase->estudiante?->persona;
                            $programa = $clase->programa->Nombre ?? 'Sin programa';
                            $hora = \Carbon\Carbon::parse($clase->Hora)->format('H:i');
                        @endphp
                        <div class="mb-3 p-2 border-start border-primary border-3 bg-light rounded">
                            <div class="info-item mb-1">
                                <span class="info-label">
                                    <i class="bi bi-person-fill"></i> Estudiante
                                </span>
                                <span class="info-value">
                                    @if($estudiante)
                                        {{ $estudiante->Nombre }} {{ $estudiante->Apellido }}
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>

                            <div class="info-item mb-1">
                                <span class="info-label">
                                    <i class="bi bi-book"></i> Programa
                                </span>
                                <span class="info-value">{{ $programa }}</span>
                            </div>

                            <div class="info-item">
                                <span class="info-label">
                                    <i class="bi bi-clock"></i> Hora
                                </span>
                                <span class="info-value">{{ $hora }}</span>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>

    </div>

    {{-- Footer de acciones opcional --}}
    <div class="action-footer d-flex justify-content-center gap-3 flex-wrap mt-4">
        <a href="{{ route('profesores.edit', $profesor->Id_profesores) }}" 
           class="btn btn-warning text-dark">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="{{ route('profesores.index') }}" 
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al listado
        </a>
    </div>

</div>
@endsection
