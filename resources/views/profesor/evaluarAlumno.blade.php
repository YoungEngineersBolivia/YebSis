{{-- resources/views/profesor/evaluarAlumno.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/evaluarAlumno.css') }}">
@endsection

@section('content')
<div class="evaluation-container">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <h2 class="evaluation-title">
        @if($evaluacionesExistentes->count() > 0)
            <i class="fas fa-edit me-2"></i>Editar Evaluación
        @else
            <i class="fas fa-clipboard-check me-2"></i>Evaluar Alumno
        @endif
    </h2>    
    
    {{-- Banner de información del estudiante --}}
    <div class="student-info-banner">
        <div class="info-banner-item">{{ $estudiante->persona->Nombre ?? 'Nombre Alumno' }} {{ $estudiante->persona->Apellido ?? '' }}</div>
        <div class="info-banner-item">{{ $estudiante->programa->Nombre ?? 'Programa' }}</div>
    </div>

    {{-- Mensajes de alerta --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Errores:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($preguntas->isEmpty())
        {{-- No hay preguntas --}}
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>No hay preguntas configuradas</strong><br>
            Este programa no tiene preguntas de evaluación. Contacta al administrador para que las agregue.
        </div>
    @else
        <form action="{{ route('profesor.guardar-evaluacion') }}" method="POST" id="evaluationForm">
            @csrf
            <input type="hidden" name="estudiante_id" value="{{ $estudiante->Id_estudiantes ?? '' }}">
            
            {{-- Selector de Modelo --}}
            <div class="evaluation-section">
                <div class="section-title">
                    <i class="fas fa-cubes me-2"></i>Selecciona el Modelo
                </div>
                <select name="modelo_id" id="modelo_select" class="form-select" required>
                    <option value="">-- Selecciona un modelo --</option>
                    @foreach($modelos as $modelo)
                        <option value="{{ $modelo->Id_modelos }}" 
                                {{ $modeloSeleccionado == $modelo->Id_modelos ? 'selected' : '' }}>
                            {{ $modelo->Nombre_modelo }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Preguntas dinámicas --}}
            @foreach($preguntas as $index => $pregunta)
                @php
                    // Obtener respuesta guardada si existe
                    $evaluacionPrevia = $evaluacionesExistentes->get($pregunta->Id_preguntas);
                    $respuestaSeleccionada = $evaluacionPrevia->Id_respuestas ?? null;
                @endphp
                <div class="evaluation-section">
                    <div class="section-title">{{ $pregunta->Pregunta }}</div>
                    <div class="option-buttons">
                        @foreach($respuestas as $respuesta)
                        <button type="button" 
                                class="option-btn {{ $respuestaSeleccionada == $respuesta->Id_respuestas ? 'selected-' . ($respuesta->Id_respuestas == 1 ? 'si' : ($respuesta->Id_respuestas == 2 ? 'no' : 'proceso')) : '' }}" 
                                data-question="pregunta_{{ $pregunta->Id_preguntas }}" 
                                data-value="{{ $respuesta->Id_respuestas }}">
                            {{ $respuesta->Respuesta }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" 
                           name="respuestas[{{ $pregunta->Id_preguntas }}]" 
                           id="pregunta_{{ $pregunta->Id_preguntas }}_value"
                           value="{{ $respuestaSeleccionada }}">
                </div>
            @endforeach
            
            {{-- Botones de acción --}}
            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn-preview" id="btnPreview">
                    <i class="fas fa-eye me-2"></i>Vista Previa
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i>
                    {{ $evaluacionesExistentes->count() > 0 ? 'Actualizar Evaluación' : 'Guardar Evaluación' }}
                </button>
            </div>
        </form>
    @endif
</div>

{{-- Modal de Vista Previa --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-eye me-2"></i>Vista Previa de Evaluación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Revisa las respuestas antes de guardar. Puedes cerrar esta ventana para editar.
                </div>
                
                <h6 class="fw-bold mb-3">Estudiante: {{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</h6>
                <h6 class="fw-bold mb-3">Programa: {{ $estudiante->programa->Nombre }}</h6>
                <h6 class="fw-bold mb-4">Modelo: <span id="preview-modelo-nombre"></span></h6>

                <h6 class="fw-bold mb-3">Respuestas:</h6>
                <div id="preview-respuestas" class="list-group">
                    {{-- Se llenará dinámicamente con JavaScript --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-edit me-2"></i>Volver a Editar
                </button>
                <button type="button" class="btn btn-success" id="btnConfirmarGuardar">
                    <i class="fas fa-check me-2"></i>Confirmar y Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ auto_asset('js/profesor/evaluarAlumno.js') }}"></script>

<script>
// Script para vista previa
document.addEventListener('DOMContentLoaded', () => {
    const btnPreview = document.getElementById('btnPreview');
    const btnConfirmar = document.getElementById('btnConfirmarGuardar');
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    const form = document.getElementById('evaluationForm');

    // Datos de preguntas y respuestas
    const preguntas = @json($preguntas);
    const respuestas = @json($respuestas);
    const modelos = @json($modelos);

    btnPreview.addEventListener('click', () => {
        // Validar que se seleccionó modelo
        const modeloSelect = document.getElementById('modelo_select');
        if (!modeloSelect.value) {
            alert('Por favor, selecciona un modelo antes de continuar');
            return;
        }

        // Validar que todas las preguntas tienen respuesta
        let todasRespondidas = true;
        const hiddenInputs = document.querySelectorAll('input[type="hidden"][name^="respuestas"]');
        hiddenInputs.forEach(input => {
            if (!input.value) {
                todasRespondidas = false;
            }
        });

        if (!todasRespondidas) {
            alert('Por favor, responde todas las preguntas antes de continuar');
            return;
        }

        // Generar vista previa
        generarVistaPrevia(modeloSelect.value, hiddenInputs);
        previewModal.show();
    });

    btnConfirmar.addEventListener('click', () => {
        form.submit();
    });

    function generarVistaPrevia(modeloId, hiddenInputs) {
        // Mostrar nombre del modelo
        const modeloNombre = modelos.find(m => m.Id_modelos == modeloId)?.Nombre_modelo || 'Desconocido';
        document.getElementById('preview-modelo-nombre').textContent = modeloNombre;

        // Generar lista de respuestas
        const previewContainer = document.getElementById('preview-respuestas');
        previewContainer.innerHTML = '';

        hiddenInputs.forEach(input => {
            const preguntaId = input.name.match(/\[(\d+)\]/)[1];
            const respuestaId = parseInt(input.value);
            
            const pregunta = preguntas.find(p => p.Id_preguntas == preguntaId);
            const respuesta = respuestas.find(r => r.Id_respuestas == respuestaId);

            if (pregunta && respuesta) {
                const colorClass = respuestaId == 1 ? 'success' : (respuestaId == 2 ? 'danger' : 'warning');
                const item = `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>${pregunta.Pregunta}</strong>
                            </div>
                            <span class="badge bg-${colorClass}">${respuesta.Respuesta}</span>
                        </div>
                    </div>
                `;
                previewContainer.innerHTML += item;
            }
        });
    }
});
</script>
@endsection