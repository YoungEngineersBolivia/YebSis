@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/listadoAlumnos.css') }}">
@endsection

@section('content')
<div class="students-container">
    {{-- Título y búsqueda --}}
    <div class="container py-4">
        {{-- Header & Search --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('profesor.menu-alumnos') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                    <h2 class="h4 mb-0 fw-bold text-dark">
                        @if($tipo === 'evaluar')
                            <i class="bi bi-clipboard-check me-2"></i> Evaluar
                        @elseif($tipo === 'asignados')
                            <i class="bi bi-people-fill me-2"></i> Mis Alumnos
                        @else
                            <i class="bi bi-calendar-event me-2"></i> Recuperatoria
                        @endif
                    </h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        class="form-control border-start-0 ps-0" 
                        placeholder="Buscar estudiante..." 
                        id="searchInput"
                        autocomplete="off"
                    >
                    <span class="input-group-text bg-light fw-bold text-primary">
                        {{ $estudiantes->count() }}
                    </span>
                </div>
            </div>
        </div>

    {{-- Lista de estudiantes --}}
    {{-- Lista de estudiantes --}}
    <div class="row g-3" id="studentsList">
        @if(isset($estudiantes) && $estudiantes->count() > 0)
            @foreach($estudiantes as $estudiante)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div 
                    class="card h-100 shadow-sm border-0 student-card p-3" 
                    style="cursor: pointer; transition: transform 0.2s;"
                    onclick="window.location.href='{{ route('profesor.detalle-estudiante', ['id' => $estudiante->Id_estudiantes, 'source' => $tipo]) }}'"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='none'"
                    data-name="{{ strtolower($estudiante->persona?->Nombre ?? '') }} {{ strtolower($estudiante->persona?->Apellido ?? '') }}"
                >
                    <div class="d-flex align-items-center gap-3">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ auto_asset('img/' . ($estudiante->persona?->Genero === 'M' ? 'boy.png' : 'girl.png')) }}" 
                                alt="{{ $estudiante->persona?->Nombre ?? 'Estudiante' }}"
                                class="rounded-circle bg-light p-1"
                                style="width: 60px; height: 60px; object-fit: cover;"
                            >
                        </div>

                        {{-- Información --}}
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="mb-1 fw-bold text-dark text-wrap" style="word-break: break-word;">
                                {{ $estudiante->persona?->Nombre }} {{ $estudiante->persona?->Apellido }}
                            </h5>
                            <p class="mb-1 text-muted small text-truncate">
                                <i class="bi bi-mortarboard-fill me-1 text-primary"></i>
                                {{ $estudiante->programa?->Nombre ?? 'Sin programa' }}
                            </p>
                            @if($estudiante->horarios && $estudiante->horarios->first())
                                @php
                                    $horario = $estudiante->horarios->first();
                                @endphp
                                <p class="mb-0 text-muted small text-truncate">
                                    <i class="bi bi-clock-fill me-1 text-warning"></i>
                                    {{ $horario->Dia }}: {{ $horario->Hora }}
                                </p>
                            @endif
                        </div>

                        {{-- Indicador --}}
                        <div class="flex-shrink-0 text-muted">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-3 mb-3 d-block"></i>
                    <p class="h5">No hay estudiantes disponibles</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script src="{{ auto_asset('js/profesor/listadoAlumnos.js') }}"></script>
@endsection
