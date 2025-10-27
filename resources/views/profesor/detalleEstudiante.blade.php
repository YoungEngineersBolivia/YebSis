{{-- resources/views/profesor/detalle_estudiante.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/detallesEstudiante.css') }}">
@endsection


@section('content')
<div class="student-detail-container">
    <input 
        type="text" 
        class="search-box-detail" 
        placeholder="Codigo o nombre del Estudiante"
    >

    <div class="student-card">
        <div class="student-left">
            <img src="{{ auto_asset('images/' . ($estudiante->genero ?? 'M') == 'M' ? 'avatar-boy.png' : 'avatar-girl.png') }}" alt="Estudiante" class="student-detail-avatar">
            <div class="student-code">
                Código del<br>estudiante
            </div>
        </div>
        
        <div class="student-right">
            <div class="student-detail-info">
                <div class="info-label">Nombre del Estudiante</div>
                <div class="info-value">{{ $estudiante->persona->Nombre ?? 'Nombre del Estudiante' }}</div>

            </div>
            
            <div class="student-detail-info">
                <div class="info-label">Programa</div>
                <div class="info-value">{{ $estudiante->programa->Nombre ?? 'Programa' }}</div>
            </div>
            
            
            <div class="student-detail-info">
                <div class="info-label">Horario</div>
                @if($estudiante->horario)
                    <div class="info-value">
                        {{ $estudiante->horario->Dia_clase_uno }}: {{ $estudiante->horario->Horario_clase_uno }} <br>
                        {{ $estudiante->horario->Dia_clase_dos }}: {{ $estudiante->horario->Horario_clase_dos }}
                    </div>
                @else
                    <div class="info-value">Sin horario asignado</div>
                @endif
            </div>
            <div class="model-select-wrapper">
                <span class="model-label">Modelo</span>
                <select name="modelo" class="model-select">
                    <option value="1" {{ ($estudiante->modelo ?? '') == '1' ? 'selected' : '' }}>▼</option>
                    <option value="2" {{ ($estudiante->modelo ?? '') == '2' ? 'selected' : '' }}>Modelo 2</option>
                    <option value="3" {{ ($estudiante->modelo ?? '') == '3' ? 'selected' : '' }}>Modelo 3</option>
                </select>
            </div>
            
            <div class="action-buttons">
                <button class="btn-edit" onclick="window.location.href='{{ route('profesor.editar-estudiante', $estudiante->id ?? 1) }}'">
                    Editar
                </button>
                <button class="btn-evaluate" onclick="window.location.href='{{ route('profesor.evaluar-estudiante', $estudiante->id ?? 1) }}'">
                    Evaluar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection