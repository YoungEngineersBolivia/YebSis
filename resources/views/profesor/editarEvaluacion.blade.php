{{-- resources/views/profesor/editar_evaluacion.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<link href="{{ auto_asset('css/profesor/editarEvaluacion.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="evaluation-container">
    <h2 class="evaluation-title">Editar Evaluación</h2>
    
    <input 
        type="text" 
        class="search-box-eval" 
        placeholder="Buscar Estudiantes"
        value="{{ $estudiante->nombre ?? '' }}"
        readonly
    >
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
    @endif
    
    <div class="student-info-banner">
        <div class="info-banner-item">{{ $estudiante->nombre ?? 'Nombre Alumno' }}</div>
        <div class="info-banner-item">{{ $estudiante->programa ?? 'Programa' }}</div>
        <div class="info-banner-item">{{ $estudiante->modelo ?? 'Modelo' }}</div>
    </div>

    @if(isset($evaluacion))
    <div class="evaluation-date">
        <strong>Evaluación realizada:</strong> {{ $evaluacion->created_at->format('d/m/Y H:i') }}
        @if($evaluacion->updated_at != $evaluacion->created_at)
        <br><strong>Última modificación:</strong> {{ $evaluacion->updated_at->format('d/m/Y H:i') }}
        @endif
    </div>
    @endif
    
    <form action="{{ route('profesor.actualizar-evaluacion', $evaluacion->id ?? 0) }}" method="POST" id="evaluationForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="estudiante_id" value="{{ $estudiante->id ?? '' }}">
        
        <!-- Sección 1: Participa en clases -->
        <div class="evaluation-section">
            <div class="section-title">Participa en clases</div>
            <div class="option-buttons">
                <button type="button" 
                    class="option-btn {{ ($evaluacion->participa ?? '') == 'si' ? 'selected-si' : '' }}" 
                    data-question="participa" 
                    data-value="si">
                    Sí
                </button>
                <button type="button" 
                    class="option-btn {{ ($evaluacion->participa ?? '') == 'no' ? 'selected-no' : '' }}" 
                    data-question="participa" 
                    data-value="no">
                    No
                </button>
                <button type="button" 
                    class="option-btn {{ ($evaluacion->participa ?? '') == 'en_proceso' ? 'selected-proceso' : '' }}" 
                    data-question="participa" 
                    data-value="en_proceso">
                    En proceso
                </button>
            </div>
            <input type="hidden" name="participa" id="participa_value" value="{{ $evaluacion->participa ?? '' }}">
        </div>
        
        <!-- Sección 2: Realiza acciones de manera secuenciada -->
        <div class="evaluation-section">
            <div class="section-title">Realiza acciones de manera secuenciada u ordenadamente</div>
            <div class="option-buttons">
                <button type="button" 
                    class="option-btn {{ ($evaluacion->secuenciada ?? '') == 'si' ? 'selected-si' : '' }}" 
                    data-question="secuenciada" 
                    data-value="si">
                    Sí
                </button>
                <button type="button" 
                    class="option-btn {{ ($evaluacion->secuenciada ?? '') == 'no' ? 'selected-no' : '' }}" 
                    data-question="secuenciada" 
                    data-value="no">
                    No
                </button>
                <button type="button" 
                    class="option-btn {{ ($evaluacion->secuenciada ?? '') == 'en_proceso' ? 'selected-proceso' : '' }}" 
                    data-question="secuenciada" 
                    data-value="en_proceso">
                    En proceso
                </button>
            </div>
            <input type="hidden" name="secuenciada" id="secuenciada_value" value="{{ $evaluacion->secuenciada ?? '' }}">
        </div>
        
        <!-- Sección 3: Es paciente y presta atención -->
        <div class="evaluation-section">
            <div class="section-title">Es paciente y presta atención</div>
            <div class="option-buttons">
                <button type="button" 
                    class="option-btn {{ ($evaluacion->paciente ?? '') == 'si' ? 'selected-si' : '' }}" 
                    data-question="paciente" 
                    data-value="si">
                    Sí
                </button>
                <button type="button" 
                    class="option-btn {{ ($evaluacion->paciente ?? '') == 'no' ? 'selected-no' : '' }}" 
                    data-question="paciente" 
                    data-value="no">
                    No
                </button>
                <button type="button" 
                    class="option-btn {{ ($evaluacion->paciente ?? '') == 'en_proceso' ? 'selected-proceso' : '' }}" 
                    data-question="paciente" 
                    data-value="en_proceso">
                    En proceso
                </button>
            </div>
            <input type="hidden" name="paciente" id="paciente_value" value="{{ $evaluacion->paciente ?? '' }}">
        </div>
        
        <div class="action-buttons">
            <button type="submit" class="btn-save">
                Actualizar Evaluación
            </button>
            <button type="button" class="btn-cancel" onclick="window.history.back()">
                Cancelar
            </button>
        </div>
    </form>
</div>

<script src="{{ auto_asset('js/profesor/editarEvaluacion.js') }}"></script>
@endsection