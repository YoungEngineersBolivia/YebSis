@extends('administrador.baseAdministrador')

@section('title', 'Modelos del Programa')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-gray: #f3f4f6;
            --medium-gray: #e5e7eb;
            --text-gray: #6b7280;
        }

        body {
            background-color: #f9fafb;
        }

        /* Header del Programa */
        .programa-header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.2);
        }

        .programa-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .info-badge {
            background: rgba(255, 255, 255, 0.25);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: white;
            color: var(--primary-color);
            border-color: white;
        }

        /* Stats Card */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid var(--medium-gray);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            background: var(--light-gray);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--primary-color);
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .stats-label {
            color: var(--text-gray);
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0;
        }

        /* Modelo Cards */
        .modelos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .modelo-card {
            background: white;
            border: 2px solid var(--medium-gray);
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .modelo-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.2);
        }

        .modelo-icon {
            width: 80px;
            height: 80px;
            background: var(--light-gray);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .modelo-card h6 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .modelo-date {
            color: var(--text-gray);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .modelo-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .modelo-actions .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-width: 2px;
        }

        /* Add Modelo Card */
        .add-modelo-card {
            background: var(--light-gray);
            border: 3px dashed var(--medium-gray);
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .add-modelo-card:hover {
            background: #ede9fe;
            border-color: var(--primary-color);
            border-style: solid;
        }

        .add-modelo-card i {
            font-size: 3rem;
            color: var(--text-gray);
            margin-bottom: 1rem;
        }

        .add-modelo-card:hover i {
            color: var(--primary-color);
        }

        .add-modelo-card p {
            color: var(--text-gray);
            font-weight: 600;
            font-size: 1rem;
        }

        .add-modelo-card:hover p {
            color: var(--primary-color);
        }

        /* Empty State */
        .empty-state {
            background: white;
            border: 2px solid var(--medium-gray);
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .empty-state-icon i {
            font-size: 4rem;
            color: var(--text-gray);
        }

        .empty-state h4 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-gray);
            font-size: 1.125rem;
            margin-bottom: 2rem;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-width: 2px;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-warning {
            color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-outline-warning:hover {
            background: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }

        .btn-outline-danger {
            color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-outline-danger:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }

        /* Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            border: none;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
        }

        .form-text {
            color: var(--text-gray);
            font-size: 0.875rem;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .programa-header {
                padding: 1.5rem;
            }

            .programa-header h2 {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .modelos-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header del Programa -->
    <div class="programa-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <a href="{{ route('programas.index') }}" class="btn btn-back mb-3">
                    <i class="bi bi-arrow-left me-2"></i>Volver a Programas
                </a>
                <h2>{{ $programa->Nombre }}</h2>
                @if($programa->Descripcion)
                    <p class="mb-3" style="font-size: 1rem; opacity: 0.95; line-height: 1.6;">
                        {{ $programa->Descripcion }}
                    </p>
                @endif
                <div class="d-flex flex-wrap gap-2">
                    <span class="info-badge">
                        <i class="bi bi-tag-fill"></i>
                        {{ $programa->Tipo === 'programa' ? 'Programa' : 'Taller' }}
                    </span>
                    <span class="info-badge">
                        <i class="bi bi-people-fill"></i>
                        {{ $programa->Rango_edad }}
                    </span>
                    <span class="info-badge">
                        <i class="bi bi-clock-fill"></i>
                        {{ $programa->Duracion }}
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoModeloModal">
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
    <div class="stats-grid">
        <div class="stats-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stats-icon">
                    <i class="bi bi-collection-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="stats-label">Total Modelos</p>
                    <h3 class="stats-value">{{ $modelos->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stats-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="stats-label">Creados Este Mes</p>
                    <h3 class="stats-value">{{ $modelos->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stats-icon" style="background: #dcfce7; color: #10b981;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="stats-label">Estado del Programa</p>
                    <h3 class="stats-value">{{ $modelos->count() > 0 ? 'Activo' : 'Sin Modelos' }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Modelos -->
    @if($modelos->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h4>No hay modelos registrados</h4>
            <p>Comienza agregando el primer modelo para este programa</p>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#nuevoModeloModal">
                <i class="bi bi-plus-lg me-2"></i>Agregar Primer Modelo
            </button>
        </div>
    @else
        <div class="modelos-grid">
            <!-- Card para agregar nuevo modelo -->
            <div class="add-modelo-card" data-bs-toggle="modal" data-bs-target="#nuevoModeloModal">
                <i class="bi bi-plus-circle-fill"></i>
                <p>Agregar Nuevo Modelo</p>
            </div>

            <!-- Cards de modelos existentes -->
            @foreach($modelos as $modelo)
            <div class="modelo-card">
                <div class="modelo-icon">
                    <i class="bi bi-layers-fill"></i>
                </div>
                <h6>{{ $modelo->Nombre_modelo }}</h6>
                <div class="modelo-date">
                    <i class="bi bi-calendar3"></i>
                    {{ $modelo->created_at->format('d/m/Y') }}
                </div>
                
                <div class="modelo-actions">
                    <button type="button" 
                            class="btn btn-sm btn-outline-warning" 
                            onclick="editarModelo({{ $programa->Id_programas }}, {{ $modelo->Id_modelos }})"
                            title="Editar">
                        <i class="bi bi-pencil-fill me-1"></i>Editar
                    </button>
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            onclick="eliminarModelo({{ $programa->Id_programas }}, {{ $modelo->Id_modelos }})"
                            title="Eliminar">
                        <i class="bi bi-trash-fill me-1"></i>Eliminar
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal para nuevo modelo -->
<div class="modal fade" id="nuevoModeloModal" tabindex="-1" aria-labelledby="nuevoModeloLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Modelo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formNuevoModelo" action="{{ route('modelos.store', $programa->Id_programas) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_modelo" class="form-label">
                            <i class="bi bi-tag-fill me-1"></i>Nombre del Modelo
                        </label>
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
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Programa:</strong> {{ $programa->Nombre }}
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save-fill me-2"></i>Guardar Modelo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar modelo -->
<div class="modal fade" id="editarModeloModal" tabindex="-1" aria-labelledby="editarModeloLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Editar Modelo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditarModelo" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre_modelo" class="form-label">
                            <i class="bi bi-tag-fill me-1"></i>Nombre del Modelo
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="edit_nombre_modelo" 
                               name="nombre_modelo" 
                               required>
                        <div class="form-text">Actualiza el nombre del modelo</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save-fill me-2"></i>Actualizar Modelo
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