@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/listadoAlumnos.css') }}">
@endsection

@section('content')
<div class="students-container">
    {{-- Título y búsqueda --}}
    <div class="header-section">
        <h2 class="page-title">
            @if($tipo === 'evaluar')
                <i class="bi bi-clipboard-check"></i> Evaluar Estudiantes
            @elseif($tipo === 'asignados')
                <i class="bi bi-people-fill"></i> Alumnos Asignados
            @else
                <i class="bi bi-calendar-event"></i> Clase Recuperatoria
            @endif
        </h2>
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input 
                type="text" 
                class="search-box" 
                placeholder="Buscar por nombre..."
                id="searchInput"
            >
        </div>
        <div class="students-count">
            <span class="count-badge">{{ $estudiantes->count() }}</span> estudiantes
        </div>
    </div>

    {{-- Lista de estudiantes --}}
    <div class="students-grid" id="studentsList">
        @if(isset($estudiantes) && $estudiantes->count() > 0)
            @foreach($estudiantes as $estudiante)
            <div 
                class="student-card" 
                onclick="window.location.href='{{ route('profesor.detalle-estudiante', $estudiante->Id_estudiantes) }}'"
                data-name="{{ strtolower($estudiante->persona?->Nombre ?? '') }} {{ strtolower($estudiante->persona?->Apellido ?? '') }}"
            >
                {{-- Avatar --}}
                <div class="card-avatar">
                    <img 
                        src="{{ auto_asset('img/' . ($estudiante->persona?->Genero === 'M' ? 'boy.png' : 'girl.png')) }}" 
                        alt="{{ $estudiante->persona?->Nombre ?? 'Estudiante' }}"
                    >
                </div>

                {{-- Información --}}
                <div class="card-info">
                    <h3 class="student-name">
                        {{ $estudiante->persona?->Nombre }} {{ $estudiante->persona?->Apellido }}
                    </h3>
                    <p class="student-program">
                        <i class="bi bi-mortarboard-fill"></i>
                        {{ $estudiante->programa?->Nombre ?? 'Sin programa' }}
                    </p>
                    @if($estudiante->horarios && $estudiante->horarios->first())
                        @php
                            $horario = $estudiante->horarios->first();
                        @endphp
                        <p class="student-schedule">
                            <i class="bi bi-clock-fill"></i>
                            {{ $horario->Dia }}: {{ $horario->Hora }}
                        </p>
                    @endif
                </div>

                {{-- Indicador --}}
                <div class="card-arrow">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </div>
            @endforeach
        @else
            <div class="no-students">
                <i class="bi bi-inbox"></i>
                <p>No hay estudiantes disponibles</p>
            </div>
        @endif
    </div>
</div>

<script src="{{ auto_asset('js/profesor/listadoAlumnos.js') }}"></script>
@endsection
