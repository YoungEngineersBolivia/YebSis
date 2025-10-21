e{{-- resources/views/profesor/editar_evaluacion.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #fff;
    }

    .evaluation-container {
        padding: 20px;
        max-width: 100%;
    }
    
    .evaluation-title {
        font-size: 20px;
        font-weight: 700;
        color: #000;
        margin-bottom: 20px;
    }
    
    .search-box-eval {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        font-size: 14px;
        background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23999" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>');
        background-repeat: no-repeat;
        background-position: 15px center;
        margin-bottom: 20px;
        background-color: #fff;
    }
    
    .search-box-eval:focus {
        outline: none;
        border-color: #4CAF50;
    }

    .search-box-eval::placeholder {
        color: #999;
    }
    
    .student-info-banner {
        background: linear-gradient(135deg, #FF9800 0%, #FFB74D 100%);
        border-radius: 25px;
        padding: 15px 20px;
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .info-banner-item {
        color: #000;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }

    .evaluation-date {
        background-color: #f5f5f5;
        border-radius: 15px;
        padding: 12px 20px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 13px;
        color: #666;
    }

    .evaluation-date strong {
        color: #000;
        font-weight: 600;
    }
    
    .evaluation-section {
        background-color: #C5B3E6;
        border-radius: 25px;
        padding: 20px;
        margin-bottom: 15px;
    }
    
    .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #000;
        margin-bottom: 15px;
    }
    
    .option-buttons {
        display: flex;
        gap: 12px;
        justify-content: flex-start;
    }
    
    .option-btn {
        padding: 10px 24px;
        border-radius: 20px;
        border: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        background-color: #fff;
        color: #000;
    }
    
    .option-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    
    .option-btn.selected-si {
        background-color: #5C9EE5;
        color: #000;
    }
    
    .option-btn.selected-no {
        background-color: #fff;
        color: #000;
    }
    
    .option-btn.selected-proceso {
        background-color: #5C9EE5;
        color: #000;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }
    
    .btn-save {
        flex: 1;
        padding: 14px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-save:hover {
        background-color: #45a049;
        transform: translateY(-1px);
    }

    .btn-cancel {
        flex: 1;
        padding: 14px;
        background-color: #f44336;
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-cancel:hover {
        background-color: #da190b;
        transform: translateY(-1px);
    }

    .alert {
        padding: 12px 20px;
        border-radius: 15px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    @media (max-width: 400px) {
        .student-info-banner {
            flex-direction: column;
            gap: 8px;
        }
        
        .option-buttons {
            flex-wrap: wrap;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
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
        alert('Por favor, responde todas las preguntas antes de actualizar la evaluación.');
    }
});
</script>
@endsection