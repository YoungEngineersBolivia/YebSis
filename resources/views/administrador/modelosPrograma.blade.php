@extends('administrador.baseAdministrador')

@section('title', 'Modelos del Programa')

@section('styles')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .modelo-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            height: 100%;
            border-radius: 8px;
        }
        .modelo-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: #7950f2;
        }
        .modelo-card .card-body {
            padding: 1.25rem;
        }
        .programa-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .add-modelo-card {
            border: 2px dashed #dee2e6;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        .add-modelo-card:hover {
            background: #e7f5ff;
            border-color: #7950f2;
        }
        .add-modelo-card i {
            font-size: 2.5rem;
            color: #adb5bd;
            margin-bottom: 0.5rem;
        }
        .add-modelo-card:hover i {
            color: #7950f2;
        }
        .modelo-icon {
            font-size: 2.5rem;
            color: #7950f2;
            margin-bottom: 0.75rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }
        .info-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-block;
        }
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #e9ecef;
        }
        .stats-card i {
            font-size: 1.75rem;
            color: #7950f2;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <!-- Header del Programa -->
    <div class="programa-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('programas.index') }}" class="btn btn-light btn-sm mb-2">
                    <i class="bi bi-arrow-left"></i> Volver a Programas
                </a>
                <h2 class="mb-2 fw-bold">{{ $programa->Nombre }}</h2>
                <div>
                    <span class="info-badge">
                        <i class="bi bi-tag-fill me-1"></i>{{ $programa->Tipo === 'programa' ? 'Programa' : 'Taller' }}
                    </span>
                    <span class="info-badge mx-2">
                        <i class="bi bi-people-fill me-1"></i>{{ $programa->Rango_edad }}
                    </span>
                    <span class="info-badge">
                        <i class="bi bi-clock-fill me-1"></i>{{ $programa->Duracion }}
                    </span>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#nuevoModeloModal">
                    <i class="bi bi-plus-lg me-2"></i>Nuevo Modelo
                </button>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0 small">Total Modelos</p>
                        <h4 class="mb-0 fw-bold">{{ $modelos->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Modelos -->
    @if($modelos->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4>No hay modelos registrados</h4>
            <p class="text-muted">Comienza agregando el primer modelo para este programa</p>
            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#nuevoModeloModal">
                <i class="bi bi-plus-lg me-2"></i>Agregar Primer Modelo
            </button>
        </div>
    @else
        <div class="row g-3">
            <!-- Card para agregar nuevo modelo -->
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="add-modelo-card card" data-bs-toggle="modal" data-bs-target="#nuevoModeloModal">
                    <div class="text-center p-3">
                        <i class="bi bi-plus-circle"></i>
                        <p class="mb-0 text-muted">Agregar Nuevo Modelo</p>
                    </div>
                </div>
            </div>

            <!-- Cards de modelos existentes -->
            @foreach($modelos as $modelo)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card modelo-card">
                    <div class="card-body text-center">
                        <div class="modelo-icon">
                            <i class="bi bi-layers-fill"></i>
                        </div>
                        <h6 class="card-title mb-2 fw-bold">{{ $modelo->Nombre_modelo }}</h6>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-calendar3"></i>
                            {{ $modelo->created_at->format('d/m/Y') }}
                        </p>
                        
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-warning" 
                                    onclick="editarModelo({{ $programa->Id_programas }}, {{ $modelo->Id_modelos }})"
                                    title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="eliminarModelo({{ $programa->Id_programas }}, {{ $modelo->Id_modelos }})"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal para nuevo modelo -->
<div class="modal fade" id="nuevoModeloModal" tabindex="-1" aria-labelledby="nuevoModeloLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Modelo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formNuevoModelo" action="{{ route('modelos.store', $programa->Id_programas) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_modelo" class="form-label">Nombre del Modelo</label>
                        <input type="text" 
                               class="form-control" 
                               id="nombre_modelo" 
                               name="nombre_modelo" 
                               placeholder="Ej: Modelo 1, Nivel Básico, etc."
                               required 
                               value="{{ old('nombre_modelo') }}">
                        <div class="form-text">Ingresa un nombre descriptivo para el modelo</div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Programa:</strong> {{ $programa->Nombre }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Guardar Modelo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar modelo -->
<div class="modal fade" id="editarModeloModal" tabindex="-1" aria-labelledby="editarModeloLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Editar Modelo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditarModelo" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre_modelo" class="form-label">Nombre del Modelo</label>
                        <input type="text" 
                               class="form-control" 
                               id="edit_nombre_modelo" 
                               name="nombre_modelo" 
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Actualizar Modelo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
console.log('Script de modelos cargado');

// Función para editar modelo (GLOBAL)
window.editarModelo = function(programaId, modeloId) {
    console.log('Editando modelo:', programaId, modeloId);
    
    fetch(`/programas/${programaId}/modelos/${modeloId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data);
        
        if (data.success) {
            const modelo = data.modelo;
            
            // Llenar el formulario
            document.getElementById('edit_nombre_modelo').value = modelo.Nombre_modelo || '';
            
            // Actualizar la acción del formulario
            document.getElementById('formEditarModelo').action = `/programas/${programaId}/modelos/${modeloId}`;
            
            // Mostrar el modal
            const modalElement = document.getElementById('editarModeloModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            alert('Error al cargar los datos del modelo');
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        alert('Error al cargar los datos del modelo: ' + error.message);
    });
}

// Función para eliminar modelo (GLOBAL)
window.eliminarModelo = function(programaId, modeloId) {
    console.log('Eliminando modelo:', programaId, modeloId);
    
    if (confirm('¿Está seguro que desea eliminar este modelo? Esta acción no se puede deshacer.')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!csrfToken) {
            alert('Error: No se encontró el token CSRF.');
            return;
        }
        
        fetch(`/programas/${programaId}/modelos/${modeloId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Error al eliminar el modelo');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            alert('Error al eliminar el modelo: ' + error.message);
        });
    }
}

// Auto-cerrar alertas
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-info)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    console.log('Funciones disponibles:', {
        editarModelo: typeof window.editarModelo,
        eliminarModelo: typeof window.eliminarModelo
    });
});
</script>
@endsection