@extends('/administrador/baseAdministrador')

@section('title', 'Programas')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #6366f1;
        --primary-dark: #4f46e5;
        --secondary-color: #8b5cf6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --dark-color: #1f2937;
        --light-bg: #f9fafb;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: var(--light-bg);
    }

    /* Modales */
    .modal-content { 
        border-radius: 16px; 
        border: none; 
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .modal-header { 
        background-color: var(--primary-color);
        color: white; 
        border-radius: 16px 16px 0 0; 
        padding: 24px 28px;
        border-bottom: none;
    }
    
    .modal-title { 
        font-weight: 700; 
        font-size: 1.4rem; 
        letter-spacing: -0.025em;
    }
    
    .btn-close-white { 
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }
    
    .btn-close-white:hover {
        opacity: 1;
    }

    /* Formularios */
    .form-label { 
        font-weight: 600; 
        color: var(--dark-color); 
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    
    .form-control, .form-select { 
        border: 2px solid #e5e7eb; 
        border-radius: 10px; 
        padding: 12px 16px; 
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus { 
        border-color: var(--primary-color); 
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }
    
    .modal-footer { 
        border-top: 1px solid #e5e7eb; 
        padding: 20px 28px;
        background-color: #f9fafb;
        border-radius: 0 0 16px 16px;
    }

    /* Header Principal */
    .page-header {
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        margin-bottom: 24px;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 4px;
        letter-spacing: -0.025em;
    }

    .page-header p {
        color: #6b7280;
        font-size: 1rem;
    }

    /* Botones */
    .btn {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-lg {
        padding: 14px 28px;
        font-size: 1rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: white;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary.active {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-outline-info {
        border: 2px solid var(--info-color);
        color: var(--info-color);
        background: white;
    }

    .btn-outline-info:hover,
    .btn-outline-info.active {
        background-color: var(--info-color);
        color: white;
    }

    /* Botones de acción pequeños */
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.875rem;
    }

    .btn-outline-dark {
        border: 2px solid var(--dark-color);
        color: var(--dark-color);
    }

    .btn-outline-dark:hover {
        background-color: var(--dark-color);
        color: white;
    }

    .btn-outline-warning {
        border: 2px solid var(--warning-color);
        color: var(--warning-color);
    }

    .btn-outline-warning:hover {
        background-color: var(--warning-color);
        color: white;
    }

    .btn-outline-danger {
        border: 2px solid var(--danger-color);
        color: var(--danger-color);
    }

    .btn-outline-danger:hover {
        background-color: var(--danger-color);
        color: white;
    }

    /* Alertas */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        font-weight: 500;
        box-shadow: var(--card-shadow);
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .alert-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    /* Buscador */
    .search-box {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: 2px solid #e5e7eb;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .search-box:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .search-box .input-group-text {
        background: white;
        border: none;
        padding-left: 20px;
    }

    .search-box .form-control {
        border: none;
        box-shadow: none;
        padding: 14px 20px 14px 8px;
    }

    .search-box .form-control:focus {
        box-shadow: none;
        border: none;
    }

    /* Tarjetas */
    .card {
        border-radius: 16px;
        border: none;
        box-shadow: var(--card-shadow);
        background: white;
    }

    .card-header {
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        border-radius: 16px 16px 0 0;
        padding: 20px;
        border: none;
    }

    /* Tabla */
    .table-responsive {
        border-radius: 16px;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f9fafb;
        color: var(--dark-color);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 16px;
        border-bottom: 2px solid #e5e7eb;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }

    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover { 
        background-color: #f9fafb;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 0.025em;
    }

    .badge.bg-primary {
        background-color: var(--primary-color) !important;
    }

    .badge.bg-info {
        background-color: var(--info-color) !important;
    }

    /* Grupo de botones de filtro */
    .btn-group {
        box-shadow: var(--card-shadow);
        border-radius: 10px;
        overflow: hidden;
    }

    /* Imágenes */
    .img-thumbnail {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 4px;
    }

    /* Paginación */
    .pagination {
        gap: 8px;
    }

    .page-link {
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        color: var(--primary-color);
        font-weight: 600;
        padding: 8px 16px;
        margin: 0;
    }

    .page-link:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Texto */
    .text-success {
        color: var(--success-color) !important;
    }

    .fw-semibold {
        font-weight: 600;
    }

    /* Contenedor de imagen actual */
    .bg-light {
        background-color: #f9fafb !important;
    }

    /* Animaciones suaves */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1>
                            <i class="fas fa-graduation-cap me-2" style="color: var(--primary-color);"></i>Gestión de Programas
                        </h1>
                        <p class="mb-0">Administra los programas y talleres educativos</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-lg shadow" data-bs-toggle="modal" data-bs-target="#nuevoProgramaModal">
                        <i class="fas fa-plus me-2"></i>Nuevo Programa
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
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
                <span class="input-group-text">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Buscar por nombre, tipo o descripción..." 
                       id="searchInput">
            </div>
        </div>
        <div class="col-lg-6 col-md-4 text-end">
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
                <div class="alert alert-warning d-flex align-items-center" role="alert">
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
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="programasTable">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Nombre</th>
                                        <th>Tipo</th>
                                        <th>Costo</th>
                                        <th>Rango de Edad</th>
                                        <th>Duración</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Modelos</th>
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
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modelosModal{{ $programa->Id_programas }}" 
                                                    title="Gestionar modelos">
                                                <i class="fas fa-cubes me-1"></i>
                                                <span class="badge bg-primary rounded-pill">{{ $programa->modelos_count ?? 0 }}</span>
                                            </button>
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

{{-- Modales de Gestión de Modelos --}}
@foreach ($programas as $programa)
<div class="modal fade" id="modelosModal{{ $programa->Id_programas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cubes me-2"></i>Modelos de: {{ $programa->Nombre }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Lista de modelos existentes --}}
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-list me-2"></i>Modelos Registrados
                    </h6>
                    
                    @php
                        $modelosPrograma = $programa->modelos;
                    @endphp
                    
                    @if($modelosPrograma->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No hay modelos registrados para este programa.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($modelosPrograma as $modelo)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-cube me-2 text-primary"></i>
                                    <strong>{{ $modelo->Nombre_modelo }}</strong>
                                </div>
                                <form action="{{ route('modelos.destroy', [$programa->Id_programas, $modelo->Id_modelos]) }}" 
                                      method="POST" 
                                      style="display:inline;"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este modelo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar modelo">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <hr>

                {{-- Formulario para agregar nuevo modelo --}}
                <div>
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Modelo
                    </h6>
                    <form action="{{ route('modelos.store', $programa->Id_programas) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>Nombre del Modelo
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombre_modelo" 
                                   placeholder="Ej: Modelo 1, Modelo Básico, etc."
                                   required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Guardar Modelo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
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

            // Actualizar estado visual de botones
            Array.from(filterGroup.querySelectorAll('button[data-filter]')).forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');

            applyFilters();
        });
    }

    // Búsqueda en tiempo real
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // Aplicar filtro inicial
    applyFilters();

    // Auto-cerrar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
    alerts.forEach(alert => {
        setTimeout(() => {
            try {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            } catch (e) {
                console.log('Error al cerrar alerta:', e);
            }
        }, 5000);
    });
});
</script>
@endsection