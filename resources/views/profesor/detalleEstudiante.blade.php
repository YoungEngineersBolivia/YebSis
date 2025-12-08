{{-- resources/views/profesor/detalle_estudiante.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profesor/detallesEstudiante.css') }}">
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
            <img src="{{ auto_asset('img/' . (($estudiante->genero ?? 'M') == 'M' ? 'boy.png' : 'girl.png')) }}" 
     alt="Estudiante" class="student-detail-avatar">

            <div class="student-code">
                Código del<br>estudiante <br> <b>{{ $estudiante->Cod_estudiante ?? 'Sin código' }}<b>
            </div>

        </div>
        
        <div class="student-right">
            <div class="student-detail-info">
                <div class="info-label">Nombre del Estudiante</div>
                <div class="info-value">
                    {{ $estudiante->full_name }}
                </div>


            </div>
            
            <div class="student-detail-info">
                <div class="info-label">Programa</div>
                <div class="info-value">{{ $estudiante->programa->Nombre ?? 'Programa' }}</div>
            </div>
            
            
            <div class="student-detail-info">
                <div class="info-label">Horario</div>
                @php
                    $horario = $estudiante->horarios->first(); // toma el primer horario
                @endphp

                @if($horario)
                    <div class="info-value">
                        {{ $horario->Dia }}: {{ $horario->Hora }}
                    </div>
                @else
                    <div class="info-value">Sin horario asignado</div>
                @endif
            </div>

            <div class="model-select-wrapper">
                <span class="model-label">Modelo</span>
                <select name="modelo" class="model-select">
                    <option value="">Seleccione...</option>
                    @if($estudiante->programa && $estudiante->programa->modelos)
                        @foreach($estudiante->programa->modelos as $modelo)
                            <option value="{{ $modelo->Id_modelos }}" {{ ($estudiante->Id_modelo ?? '') == $modelo->Id_modelos ? 'selected' : '' }}>
                                {{ $modelo->Nombre_modelo }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="action-buttons">
                <button class="btn-edit" onclick="window.location.href='{{ route('profesor.editar-estudiante', $estudiante->Id_estudiantes) }}'">
                    Editar
                </button>
                <button class="btn-evaluate" onclick="window.location.href='{{ route('profesor.evaluar-estudiante', $estudiante->Id_estudiantes) }}'">
                    Evaluar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection