@extends('/administrador/baseAdministrador')

@section('title', 'Programas')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    .modal-content { 
        border-radius: 15px; 
        border: none; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.2); 
    }
    .modal-header { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
        color: white; 
        border-radius: 15px 15px 0 0; 
        padding: 20px 25px; 
    }
    .modal-title { 
        font-weight: 700; 
        font-size: 1.3rem; 
    }
    .btn-close-white { 
        filter: brightness(0) invert(1); 
    }
    .form-label { 
        font-weight: 600; 
        color: #495057; 
        margin-bottom: 8px; 
    }
    .form-control, .form-select { 
        border: 2px solid #e0e0e0; 
        border-radius: 8px; 
        padding: 10px 14px; 
        transition: all 0.3s ease; 
    }
    .form-control:focus, .form-select:focus { 
        border-color: #667eea; 
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.15); 
    }
    .modal-footer { 
        border-top: 1px solid #dee2e6; 
        padding: 15px 25px; 
    }
    .img-preview { 
        max-height: 200px; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
        object-fit: cover;
    }
    .badge-custom { 
        padding: 8px 15px; 
        border-radius: 8px; 
        font-weight: 600; 
    }
    .table-hover tbody tr:hover { 
        background-color: #f8f9fa; 
        transform: scale(1.01); 
        box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
        transition: all 0.3s ease; 
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }
    .search-box {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">
                        <i class="fas fa-graduation-cap text-primary me-2"></i>Gestión de Programas
                    </h1>
                    <p class="text-muted mb-0">Administra los programas y talleres educativos</p>
                </div>
                <button type="button" class="btn btn-primary btn-lg shadow" data-bs-toggle="modal" data-bs-target="#nuevoProgramaModal">
                    <i class="fas fa-plus me-2"></i>Nuevo Programa
                </button>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
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

    {{-- Search and Filter Section --}}
    <div class="row mb-4">
        <div class="col-lg-6 col-md-8 mb-3 mb-lg-0">
            <div class="input-group input-group-lg search-box">
                <span class="input-group-text bg-white border-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" 
                       class="form-control border-0" 
                       placeholder="Buscar por nombre, tipo o descripción..." 
                       id="searchInput">
            </div>
        </div>
        <div class="col-lg-6 col-md-4 text-end">
            <!-- Agregado id al grupo para delegación y accesibilidad aria-label -->
            <div class="btn-group" id="filterButtonsGroup" role="group" aria-label="Filtrar programas por tipo">
                <button type="button" class="btn btn-outline-primary active" data-filter="all" aria-pressed="true">
                    <i class="fas fa-list me-1"></i>Todos
                </button>
                <button type="button" class="btn btn-outline-primary" data-filter="programa" aria-pressed="false">
                    <i class="fas fa-book me-1"></i>Programas
                </button>
                <button type="button" class="btn btn-outline-info" data-filter="taller" aria-pressed="false">
                    <i class="fas fa-tools me-1"></i>Talleres
                </button>
            </div>
        </div>
    </div>

    {{-- Programs Table --}}
    @if($programas->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning shadow-sm d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-1">No hay programas registrados</h5>
                        <p class="mb-0">Comienza agregando tu primer programa usando el botón "Nuevo Programa"</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="programasTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Nombre</th>
                                        <th>Tipo</th>
                                        <th>Costo</th>
                                        <th>Rango de Edad</th>
                                        <th>Duración</th>
                                        <th>Descripción</th>
                                        <th class="text-center pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programas as $programa)
                                    <tr data-tipo="{{ $programa->Tipo }}">
                                        <td class="ps-4 fw-semibold">{{ $programa->Nombre }}</td>
                                        <td>
                                            @if($programa->Tipo === 'programa')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-book me-1"></i>Programa
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    <i class="fas fa-tools me-1"></i>Taller
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">{{ number_format($programa->Costo, 2) }} Bs</td>
                                        <td>
                                            <i class="fas fa-users text-muted me-1"></i>{{ $programa->Rango_edad }}
                                        </td>
                                        <td>
                                            <i class="far fa-clock text-muted me-1"></i>{{ $programa->Duracion }}
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit($programa->Descripcion, 50) }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('programas.show', $programa->Id_programas) }}" 
                                                   class="btn btn-sm btn-outline-dark" 
                                                   title="Ver detalles">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editarProgramaModal{{ $programa->Id_programas }}" 
                                                        title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <form action="{{ route('programas.destroy', $programa->Id_programas) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('¿Estás seguro de eliminar este programa?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Eliminar">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $programas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Modal Nuevo Programa --}}
<div class="modal fade" id="nuevoProgramaModal" tabindex="-1" aria-labelledby="nuevoProgramaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoProgramaLabel">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Programa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('programas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>Nombre del Programa
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombre" 
                                   placeholder="Ej: Robotica Educativa"
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-layer-group me-1"></i>Tipo
                            </label>
                            <select class="form-select" name="tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="programa">Programa</option>
                                <option value="taller">Taller</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Costo (Bs)
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   name="costo" 
                                   step="0.01" 
                                   placeholder="0.00"
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-users me-1"></i>Rango de Edad
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="rango_edad" 
                                   placeholder="Ej: 6-12 años"
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="far fa-clock me-1"></i>Duración
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="duracion" 
                                   placeholder="Ej: 3 meses"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>Descripción
                        </label>
                        <textarea class="form-control" 
                                  name="descripcion" 
                                  rows="4" 
                                  placeholder="Describe el contenido y objetivos del programa..."
                                  required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-image me-1"></i>Imagen
                        </label>
                        <input type="file" 
                               class="form-control" 
                               name="imagen" 
                               accept="image/*">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Formatos: JPG, PNG, GIF (máx. 2MB)
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Guardar Programa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modales de Edición --}}
@foreach ($programas as $programa)
<div class="modal fade" id="editarProgramaModal{{ $programa->Id_programas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar: {{ $programa->Nombre }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('programas.update', $programa->Id_programas) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>Nombre del Programa
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombre" 
                                   value="{{ $programa->Nombre }}" 
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-layer-group me-1"></i>Tipo
                            </label>
                            <select class="form-select" name="tipo" required>
                                <option value="programa" {{ $programa->Tipo === 'programa' ? 'selected' : '' }}>
                                    Programa
                                </option>
                                <option value="taller" {{ $programa->Tipo === 'taller' ? 'selected' : '' }}>
                                    Taller
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Costo (Bs)
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   name="costo" 
                                   step="0.01" 
                                   value="{{ $programa->Costo }}" 
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-users me-1"></i>Rango de Edad
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="rango_edad" 
                                   value="{{ $programa->Rango_edad }}" 
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="far fa-clock me-1"></i>Duración
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="duracion" 
                                   value="{{ $programa->Duracion }}" 
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>Descripción
                        </label>
                        <textarea class="form-control" 
                                  name="descripcion" 
                                  rows="4" 
                                  required>{{ $programa->Descripcion }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-image me-1"></i>Imagen
                        </label>
                        <input type="file" 
                               class="form-control" 
                               name="imagen" 
                               accept="image/*">
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i>Dejar vacío para mantener la imagen actual
                        </small>
                        
                        @if($programa->Imagen)
                        <div class="mt-3 p-3 bg-light rounded">
                            <p class="text-muted mb-2 small">
                                <i class="fas fa-image me-1"></i>Imagen actual:
                            </p>
                            <img src="{{ asset('storage/'.$programa->Imagen) }}" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px; max-height: 150px; object-fit: cover;">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Actualizar Programa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('programasTable');
    const filterGroup = document.getElementById('filterButtonsGroup');

    if (!table) return;

    const normalize = (s) => (s || '').toString().toLowerCase().trim();
    let currentFilter = 'all';

    function applyFilters() {
        const rows = table.querySelectorAll('tbody tr');
        const query = searchInput ? normalize(searchInput.value) : '';

        rows.forEach(row => {
            // tomar data-tipo en minúsculas
            const tipo = normalize(row.getAttribute('data-tipo'));
            const text = normalize(row.textContent);
            const matchesFilter = (currentFilter === 'all') || (tipo === currentFilter);
            const matchesSearch = !query || text.includes(query);

            row.style.display = (matchesFilter && matchesSearch) ? '' : 'none';
        });
    }

    // Delegación de eventos para los botones de filtro
    if (filterGroup) {
        filterGroup.addEventListener('click', function(e) {
            const btn = e.target.closest('button[data-filter]');
            if (!btn) return;
            currentFilter = normalize(btn.getAttribute('data-filter') || 'all');

            // actualizar estado visual de botones
            Array.from(filterGroup.querySelectorAll('button[data-filter]')).forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');

            applyFilters();
        });
    }

    // búsqueda en tiempo real
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // aplicar filtro inicial
    applyFilters();

    // auto-cerrar alertas
    const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
    alerts.forEach(alert => {
        setTimeout(() => {
            try {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            } catch (e) {}
        }, 5000);
    });
});
</script>

