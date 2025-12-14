@extends('/administrador/baseAdministrador')

@section('title', 'Gestión de Preguntas')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #6366f1;
        --primary-dark: #4f46e5;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --light-bg: #f9fafb;
    }

    body {
        background-color: var(--light-bg);
    }

    .page-header {
        background: white;
        pad padding: 32px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .page-header p {
        color: #6b7280;
        font-size: 1rem;
    }

    .btn {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #6b7280;
    }

    .btn-outline-warning {
        border: 2px solid #f59e0b;
        color: #f59e0b;
    }

    .btn-outline-danger {
        border: 2px solid var(--danger-color);
        color: var(--danger-color);
    }

    .card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #f9fafb;
        color: #1f2937;
        font-weight: 700;
        padding: 16px;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
    }

    .alert {
        border-radius: 12px;
        padding: 16px 20px;
    }

    .modal-content {
        border-radius: 16px;
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        border-radius: 16px 16px 0 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1>
                            <i class="fas fa-question-circle me-2" style="color: var(--primary-color);"></i>
                            Preguntas de {{ $programa->Nombre }}
                        </h1>
                        <p class="mb-0">Gestiona las preguntas de evaluación para este programa</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaPreguntaModal">
                            <i class="fas fa-plus me-2"></i>Nueva Pregunta
                        </button>
                        <a href="{{ route('programas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a Programas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mensajes --}}
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

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de Preguntas --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    @if($preguntas->isEmpty())
                        <div class="p-5 text-center">
                            <i class="fas fa-question-circle fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay preguntas registradas</h5>
                            <p class="text-muted">Comienza agregando la primera pregunta para este programa</p>
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#nuevaPreguntaModal">
                                <i class="fas fa-plus me-2"></i>Agregar Primera Pregunta
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Pregunta</th>
                                        <th style="width: 150px;">Fecha Creación</th>
                                        <th class="text-center" style="width: 150px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preguntas as $index => $pregunta)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td class="fw-semibold">{{ $pregunta->Pregunta }}</td>
                                        <td class="text-muted">
                                            <i class="far fa-calendar me-1"></i>
                                            {{ $pregunta->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editarPreguntaModal{{ $pregunta->Id_preguntas }}"
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.preguntas.destroy', [$programa->Id_programas, $pregunta->Id_preguntas]) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('¿Estás seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Nueva Pregunta --}}
<div class="modal fade" id="nuevaPreguntaModal" tabindex="-1" aria-labelledby="nuevaPreguntaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaPreguntaLabel">
                    <i class="fas fa-plus-circle me-2"></i>Nueva Pregunta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.preguntas.store', $programa->Id_programas) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-question-circle me-1"></i>Pregunta
                        </label>
                        <textarea class="form-control" 
                                  name="pregunta" 
                                  rows="3" 
                                  placeholder="Ej: ¿Participa activamente en clases?"
                                  required></textarea>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Las respuestas serán: Sí, No, En proceso
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Guardar Pregunta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modales de Edición --}}
@foreach($preguntas as $pregunta)
<div class="modal fade" id="editarPreguntaModal{{ $pregunta->Id_preguntas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar Pregunta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.preguntas.update', [$programa->Id_programas, $pregunta->Id_preguntas]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-question-circle me-1"></i>Pregunta
                        </label>
                        <textarea class="form-control"
                                  name="pregunta" 
                                  rows="3" 
                                  required>{{ $pregunta->Pregunta }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
