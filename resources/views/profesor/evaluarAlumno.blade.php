{{-- resources/views/profesor/evaluar_alumno.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profesor/evaluarAlumno.css') }}">
@endsection

@section('content')
<div class="evaluation-container">
    <h2 class="evaluation-title">Evaluar alumno</h2>    
    <div class="student-info-banner">
        <div class="info-banner-item">{{ $estudiante->persona->Nombre ?? 'Nombre Alumno' }}</div>
        <div class="info-banner-item">{{ $estudiante->programa->Nombre ?? 'Programa' }}</div>
        <div class="info-banner-item">{{ $estudiante->modelo->Nombre_modelo ?? 'Modelo' }}</div>
    </div>
    
    <form action="{{ route('profesor.guardar-evaluacion') }}" method="POST" id="evaluationForm">
        @csrf
        <input type="hidden" name="estudiante_id" value="{{ $estudiante->id ?? '' }}">
        
        <!-- Sección 1: Participa en clases -->
        <div class="evaluation-section">
            <div class="section-title">Participa en clases</div>
            <div class="option-buttons">
                <button type="button" class="option-btn" data-question="participa" data-value="si">Sí</button>
                <button type="button" class="option-btn" data-question="participa" data-value="no">No</button>
                <button type="button" class="option-btn" data-question="participa" data-value="en_proceso">En proceso</button>
            </div>
            <input type="hidden" name="participa" id="participa_value">
        </div>
        
        <!-- Sección 2: Realiza acciones de manera secuenciada -->
        <div class="evaluation-section">
            <div class="section-title">Realiza acciones de manera secuenciada u ordenadamente</div>
            <div class="option-buttons">
                <button type="button" class="option-btn" data-question="secuenciada" data-value="si">Sí</button>
                <button type="button" class="option-btn" data-question="secuenciada" data-value="no">No</button>
                <button type="button" class="option-btn" data-question="secuenciada" data-value="en_proceso">En proceso</button>
            </div>
            <input type="hidden" name="secuenciada" id="secuenciada_value">
        </div>
        
        <!-- Sección 3: Es paciente y presta atención -->
        <div class="evaluation-section">
            <div class="section-title">Es paciente y presta atención</div>
            <div class="option-buttons">
                <button type="button" class="option-btn" data-question="paciente" data-value="si">Sí</button>
                <button type="button" class="option-btn" data-question="paciente" data-value="no">No</button>
                <button type="button" class="option-btn" data-question="paciente" data-value="en_proceso">En proceso</button>
            </div>
            <input type="hidden" name="paciente" id="paciente_value">
        </div>
        
        <button type="submit" class="btn-save">
            Guardar Evaluación
        </button>
    </form>
</div>

<script>
// Manejo de botones de opciones
document.querySelectorAll('.option-btn').forEach(button => {
    button.addEventListener('click', function() {
        const question = this.dataset.question;
        const value = this.dataset.value;
        
        // Remover selección de otros botones de la misma pregunta
        document.querySelectorAll(`[data-question="${question}"]`).forEach(btn => {
            btn.classList.remove('selected-si', 'selected-no', 'selected-proceso');
        });
        
        // Marcar este botón como seleccionado según su valor
        if (value === 'si') {
            this.classList.add('selected-si');
        } else if (value === 'no') {
            this.classList.add('selected-no');
        } else if (value === 'en_proceso') {
            this.classList.add('selected-proceso');
        }
        
        // Actualizar el campo oculto
        document.getElementById(`${question}_value`).value = value;
    });
});

// Validación del formulario
document.getElementById('evaluationForm').addEventListener('submit', function(e) {
    const participa = document.getElementById('participa_value').value;
    const secuenciada = document.getElementById('secuenciada_value').value;
    const paciente = document.getElementById('paciente_value').value;
    
    if (!participa || !secuenciada || !paciente) {
        e.preventDefault();
        alert('Por favor, responde todas las preguntas antes de guardar la evaluación.');
    }
});
</script>
@endsection