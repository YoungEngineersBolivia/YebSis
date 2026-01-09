@extends('tutor.baseTutor')

@section('title', 'Panel del Tutor')

@section('styles')
<style>
    /* Student Cards */
    .students-container {
        display: grid;
        gap: 1rem;
    }

    .student-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: var(--shadow);
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .student-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-color);
    }

    .student-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .student-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        flex-shrink: 0;
    }

    .student-header-info {
        flex: 1;
        min-width: 0;
    }

    .student-header-info h3 {
        font-size: 1.125rem;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
        word-wrap: break-word;
    }

    .student-code {
        font-size: 0.875rem;
        color: var(--text-light);
    }

    .student-body {
        display: grid;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .info-row {
        display: flex;
        align-items: start;
        gap: 0.5rem;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 80px;
        font-size: 0.9rem;
    }

    .info-value {
        color: var(--text-light);
        flex: 1;
        font-size: 0.9rem;
        word-wrap: break-word;
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-inactive {
        background: #fce4ec;
        color: #c2185b;
    }

    /* Buttons */
    .student-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .btn {
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        white-space: nowrap;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-blue {
        background: var(--secondary-color);
        color: white;
    }

    .btn-blue:hover {
        background: #1976D2;
        transform: translateY(-2px);
        color: white;
    }

    .btn-yellow {
        background: var(--warning-color);
        color: white;
    }

    .btn-yellow:hover {
        background: #fb8c00;
        transform: translateY(-2px);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        padding: 1rem;
        overflow-y: auto;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--secondary-color);
        color: white;
        border-radius: 16px 16px 0 0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 2rem;
        color: white;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s;
    }

    .close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        padding: 1.5rem;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #45a049;
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-light);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .student-avatar {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .student-card {
            padding: 1rem;
        }

        .student-actions {
            grid-template-columns: 1fr;
        }

        .btn {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .modal-content {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }

        .modal-header {
            padding: 1rem;
        }

        .modal-body {
            padding: 1rem;
        }
    }

    @media (max-width: 480px) {
        .student-header {
            flex-direction: column;
            text-align: center;
        }

        .student-avatar {
            width: 70px;
            height: 70px;
            font-size: 1.5rem;
        }

        .info-label {
            min-width: 70px;
            font-size: 0.85rem;
        }

        .info-value {
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Lista de Estudiantes -->
<div class="tutor-card">
    <h2>Mis Estudiantes ({{ $estudiantes->count() }})</h2>

    @if($estudiantes->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <p>No hay estudiantes registrados</p>
        </div>
    @else
        <div class="students-container">
            @foreach($estudiantes as $estudiante)
                <div class="student-card">
                    <div class="student-header">
                        <div class="student-avatar">
                            {{ substr($estudiante->persona->Nombre, 0, 1) }}
                        </div>
                        <div class="student-header-info">
                            <h3>{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</h3>
                            <div class="student-code">
                                <strong>Código:</strong> {{ $estudiante->Cod_estudiante }}
                            </div>
                        </div>
                        @if(strtolower(trim($estudiante->Estado ?? '')) === 'activo')
                            <span class="badge badge-active">Activo</span>
                        @else
                            <span class="badge badge-inactive">Inactivo</span>
                        @endif
                    </div>

                    <div class="student-body">
                        <div class="info-row">
                            <span class="info-label">Programa:</span>
                            <span class="info-value">{{ $estudiante->programa->Nombre ?? 'No asignado' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Celular:</span>
                            <span class="info-value">{{ $estudiante->persona->Celular ?? 'Sin celular' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Sucursal:</span>
                            <span class="info-value">{{ $estudiante->sucursal->Nombre ?? 'No asignada' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Profesor:</span>
                            <span class="info-value">
                                @if($estudiante->profesor)
                                    {{ $estudiante->profesor->persona->Nombre }} {{ $estudiante->profesor->persona->Apellido }}
                                @else
                                    Sin asignar
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="student-actions">
                        <a href="{{ route('tutor.estudiantes.evaluaciones', $estudiante->Id_estudiantes) }}" 
                           class="btn btn-blue">
                            📊 Ver Evaluaciones
                        </a>
                        <button class="btn btn-yellow" 
                                onclick="abrirModalCita({{ $estudiante->Id_estudiantes }}, '{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}')">
                            📅 Agendar Cita
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal Agendar Cita -->
<div id="citaModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>📅 Agendar Cita</h2>
            <button class="close-btn" onclick="cerrarModalCita()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="citaForm" onsubmit="submitCita(event)">
                <input type="hidden" id="estudiante_id" name="estudiante_id">
                
                <div class="form-group">
                    <label>Estudiante</label>
                    <input type="text" id="estudiante_nombre" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="fecha_cita">Fecha de la Cita *</label>
                    <input type="date" id="fecha_cita" name="fecha" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="hora_cita">Hora de la Cita *</label>
                    <input type="time" id="hora_cita" name="hora" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="motivo_cita">Motivo de la Cita</label>
                    <textarea id="motivo_cita" name="motivo" class="form-control" placeholder="Describe el motivo de la cita..."></textarea>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">Agendar Cita</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Configurar CSRF token para todas las peticiones AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Abrir modal cita
    function abrirModalCita(estudianteId, nombreEstudiante) {
        document.getElementById('estudiante_id').value = estudianteId;
        document.getElementById('estudiante_nombre').value = nombreEstudiante;
        
        // Establecer fecha mínima como hoy
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_cita').setAttribute('min', today);
        
        // Limpiar campos
        document.getElementById('fecha_cita').value = '';
        document.getElementById('hora_cita').value = '';
        document.getElementById('motivo_cita').value = '';
        
        document.getElementById('citaModal').classList.add('active');
    }

    // Cerrar modal cita
    function cerrarModalCita() {
        document.getElementById('citaModal').classList.remove('active');
        document.getElementById('citaForm').reset();
    }

    // Submit cita
    async function submitCita(event) {
        event.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Agendando...';

        const formData = {
            estudiante_id: document.getElementById('estudiante_id').value,
            fecha: document.getElementById('fecha_cita').value,
            hora: document.getElementById('hora_cita').value,
            motivo: document.getElementById('motivo_cita').value
        };

        try {
            const response = await fetch('/tutor/citas/agendar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                mostrarAlerta('Cita agendada exitosamente', 'success');
                cerrarModalCita();
            } else {
                mostrarAlerta(data.error || 'Error al agendar la cita', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('Error al agendar la cita. Por favor, intente nuevamente.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Agendar Cita';
        }
    }

    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', function(event) {
        const citaModal = document.getElementById('citaModal');
        
        if (event.target === citaModal) {
            cerrarModalCita();
        }
    });
</script>
@endsection