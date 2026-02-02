<?php $__env->startSection('title', 'Programas'); ?>

<?php $__env->startSection('styles'); ?>
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

    .btn-outline-success {
        border: 2px solid var(--success-color);
        color: var(--success-color);
    }

    .btn-outline-success:hover {
        background-color: var(--success-color);
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

    /* Tabla - Desktop */
    .table-responsive {
        border-radius: 16px;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        padding: 16px;
        border-bottom: none;
        white-space: nowrap;
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

    /* Vista de tarjetas para móviles */
    .program-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .program-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-2px);
    }

    .program-card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 20px;
    }

    .program-card-header h5 {
        font-weight: 700;
        margin-bottom: 8px;
        font-size: 1.25rem;
    }

    .program-card-body {
        padding: 20px;
    }

    .program-info-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f3f4f6;
    }

    .program-info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .program-info-item i {
        width: 24px;
        color: var(--primary-color);
        margin-right: 12px;
    }

    .program-info-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-right: 8px;
        min-width: 80px;
    }

    .program-card-actions {
        padding: 16px 20px;
        background-color: #f9fafb;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .program-card-actions .btn {
        flex: 1;
        min-width: calc(50% - 4px);
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

    /* Mejorar visualización de tabla en diferentes tamaños */
    @media (max-width: 1200px) {
        .desktop-view .table-responsive {
            font-size: 0.9rem;
        }
        
        .desktop-view .btn-group .btn {
            padding: 4px 6px;
        }
    }

    @media (max-width: 992px) {
        .desktop-view th:nth-child(4),
        .desktop-view td:nth-child(4) {
            display: none;
        }
        
        .desktop-view .btn-sm {
            padding: 3px 5px;
            font-size: 0.75rem;
        }
        
        .desktop-view .badge {
            padding: 4px 8px;
            font-size: 0.7rem;
        }
    }

    /* Asegurar que las acciones tengan suficiente espacio */
    .desktop-view .btn-group {
        display: flex;
        flex-wrap: nowrap;
        gap: 2px;
    }

    .desktop-view .btn-group .btn {
        flex: 1;
        min-width: 32px;
    }

    /* Responsive Utilities */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.5rem;
        }

        .btn-lg {
            padding: 12px 20px;
            font-size: 0.95rem;
        }

        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-title {
            font-size: 1.2rem;
        }

        /* Ocultar tabla en móviles */
        .desktop-view {
            display: none !important;
        }

        /* Mostrar cards en móviles */
        .mobile-view {
            display: block !important;
        }

        /* Ajustar filtros en móvil */
        .btn-group {
            display: flex;
            width: 100%;
        }

        .btn-group .btn {
            flex: 1;
            font-size: 0.85rem;
            padding: 8px 12px;
        }

        .btn-group .btn i {
            display: none;
        }
    }

    @media (min-width: 769px) {
        /* Ocultar cards en desktop */
        .mobile-view {
            display: none !important;
        }

        /* Mostrar tabla en desktop */
        .desktop-view {
            display: block !important;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            padding: 20px;
        }

        .program-card-actions .btn {
            min-width: 100%;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    
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

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

     
    <div class="row mb-4">
        <div class="col-lg-6 col-md-12 mb-3 mb-lg-0">
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
        <div class="col-lg-6 col-md-12">
            <div class="btn-group w-100" id="filterButtonsGroup" role="group" aria-label="Filtrar programas por tipo">
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

    
    <?php if($programas->isEmpty()): ?>
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
    <?php else: ?>
        
        <div class="row desktop-view">
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
                                        <th class="d-none d-md-table-cell">Rango de Edad</th>
                                        <th class="text-center">Modelos</th>
                                        <th class="text-center">Preguntas</th>
                                        <th class="text-center pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-tipo="<?php echo e($programa->Tipo); ?>">
                                        <td class="ps-4 fw-semibold">
                                            <div class="d-flex flex-column">
                                                <span><?php echo e($programa->Nombre); ?></span>
                                                <small class="text-muted"><?php echo e(Str::limit($programa->Descripcion, 40)); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($programa->Tipo === 'programa'): ?>
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-book me-1"></i>Programa
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-tools me-1"></i>Taller
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-success"><?php echo e(number_format($programa->Costo, 2)); ?> Bs</td>
                                        <td class="d-none d-md-table-cell">
                                            <i class="fas fa-users text-muted me-1"></i><?php echo e($programa->Rango_edad); ?>

                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modelosModal<?php echo e($programa->Id_programas); ?>" 
                                                    title="Gestionar modelos">
                                                <i class="fas fa-cubes me-1"></i>
                                                <span class="badge bg-primary rounded-pill"><?php echo e($programa->modelos_count ?? 0); ?></span>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('admin.preguntas.index', $programa->Id_programas)); ?>" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Gestionar preguntas">
                                                <i class="fas fa-question-circle me-1"></i>Preguntas
                                            </a>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('programas.show', $programa->Id_programas)); ?>" 
                                                   class="btn btn-sm btn-outline-dark" 
                                                   title="Ver detalles">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editarProgramaModal<?php echo e($programa->Id_programas); ?>" 
                                                        title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <form action="<?php echo e(route('programas.destroy', $programa->Id_programas)); ?>" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('¿Estás seguro de eliminar este programa?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Eliminar">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row mobile-view" id="programsCardsContainer">
            <?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 program-card-wrapper" data-tipo="<?php echo e($programa->Tipo); ?>">
                <div class="program-card">
                    <div class="program-card-header">
                        <h5 class="mb-0"><?php echo e($programa->Nombre); ?></h5>
                        <?php if($programa->Tipo === 'programa'): ?>
                            <span class="badge bg-light text-primary mt-2">
                                <i class="fas fa-book me-1"></i>Programa
                            </span>
                        <?php else: ?>
                            <span class="badge bg-light text-info mt-2">
                                <i class="fas fa-tools me-1"></i>Taller
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="program-card-body">
                        <div class="program-info-item">
                            <i class="fas fa-dollar-sign"></i>
                            <span class="program-info-label">Costo:</span>
                            <span class="fw-bold text-success"><?php echo e(number_format($programa->Costo, 2)); ?> Bs</span>
                        </div>
                        
                        <div class="program-info-item">
                            <i class="fas fa-users"></i>
                            <span class="program-info-label">Edad:</span>
                            <span><?php echo e($programa->Rango_edad); ?></span>
                        </div>
                        
                        <div class="program-info-item">
                            <i class="fas fa-align-left"></i>
                            <span class="program-info-label">Descripción:</span>
                        </div>
                        <p class="text-muted mb-3"><?php echo e(Str::limit($programa->Descripcion, 100)); ?></p>
                        
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary flex-fill" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#modelosModal<?php echo e($programa->Id_programas); ?>">
                                <i class="fas fa-cubes me-1"></i>Modelos 
                                <span class="badge bg-primary"><?php echo e($programa->modelos_count ?? 0); ?></span>
                            </button>
                            
                            <a href="<?php echo e(route('admin.preguntas.index', $programa->Id_programas)); ?>" 
                               class="btn btn-sm btn-outline-success flex-fill">
                                <i class="fas fa-question-circle me-1"></i>Preguntas
                            </a>
                        </div>
                    </div>
                    
                    <div class="program-card-actions">
                        <a href="<?php echo e(route('programas.show', $programa->Id_programas)); ?>" 
                           class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-eye-fill me-1"></i>Ver
                        </a>
                        
                        <button type="button" 
                                class="btn btn-sm btn-outline-warning" 
                                data-bs-toggle="modal"
                                data-bs-target="#editarProgramaModal<?php echo e($programa->Id_programas); ?>">
                            <i class="bi bi-pencil-square me-1"></i>Editar
                        </button>
                        
                        <form action="<?php echo e(route('programas.destroy', $programa->Id_programas)); ?>" 
                              method="POST" 
                              style="display:inline; flex: 1;"
                              onsubmit="return confirm('¿Estás seguro de eliminar este programa?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash3-fill me-1"></i>Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <?php echo e($programas->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="nuevoProgramaModal" tabindex="-1" aria-labelledby="nuevoProgramaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoProgramaLabel">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Programa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('programas.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
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
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-users me-1"></i>Rango de Edad
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="rango_edad" 
                                   placeholder="Ej: 6-12 años"
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


<?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editarProgramaModal<?php echo e($programa->Id_programas); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar: <?php echo e($programa->Nombre); ?>

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('programas.update', $programa->Id_programas)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>Nombre del Programa
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombre" 
                                   value="<?php echo e($programa->Nombre); ?>" 
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-layer-group me-1"></i>Tipo
                            </label>
                            <select class="form-select" name="tipo" required>
                                <option value="programa" <?php echo e($programa->Tipo === 'programa' ? 'selected' : ''); ?>>
                                    Programa
                                </option>
                                <option value="taller" <?php echo e($programa->Tipo === 'taller' ? 'selected' : ''); ?>>
                                    Taller
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Costo (Bs)
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   name="costo" 
                                   step="0.01" 
                                   value="<?php echo e($programa->Costo); ?>" 
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-users me-1"></i>Rango de Edad
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="rango_edad" 
                                   value="<?php echo e($programa->Rango_edad); ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-clock me-1"></i>Duración
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   name="duracion" 
                                   value="<?php echo e($programa->Duracion ?? ''); ?>" 
                                   placeholder="Ej: 3 meses, 12 semanas, 40 horas">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Opcional: Especifica la duración del programa
                            </small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>Descripción
                        </label>
                        <textarea class="form-control" 
                                  name="descripcion" 
                                  rows="4" 
                                  required><?php echo e($programa->Descripcion); ?></textarea>
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
                        
                        <?php if($programa->Imagen): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <p class="text-muted mb-2 small">
                                <i class="fas fa-image me-1"></i>Imagen actual:
                            </p>
                            <img src="<?php echo e(asset('storage/'.$programa->Imagen)); ?>" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px; max-height: 150px; object-fit: cover;">
                        </div>
                        <?php endif; ?>
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
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $programas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modelosModal<?php echo e($programa->Id_programas); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cubes me-2"></i>Modelos de: <?php echo e($programa->Nombre); ?>

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-list me-2"></i>Modelos Registrados
                    </h6>
                    
                    <?php
                        $modelosPrograma = $programa->modelos;
                    ?>
                    
                    <?php if($modelosPrograma->isEmpty()): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No hay modelos registrados para este programa.
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $modelosPrograma; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-cube me-2 text-primary"></i>
                                    <strong><?php echo e($modelo->Nombre_modelo); ?></strong>
                                </div>
                                <form action="<?php echo e(route('modelos.destroy', [$programa->Id_programas, $modelo->Id_modelos])); ?>" 
                                      method="POST" 
                                      style="display:inline;"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este modelo?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar modelo">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <hr>

                
                <div>
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Modelo
                    </h6>
                    <form action="<?php echo e(route('modelos.store', $programa->Id_programas)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
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
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('programasTable');
    const cardsContainer = document.getElementById('programsCardsContainer');
    const filterGroup = document.getElementById('filterButtonsGroup');

    const normalize = (s) => (s || '').toString().toLowerCase().trim();
    let currentFilter = 'all';

    function applyFilters() {
        const query = searchInput ? normalize(searchInput.value) : '';

        // Filtrar tabla (desktop)
        if (table) {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const tipo = normalize(row.getAttribute('data-tipo'));
                const text = normalize(row.textContent);
                const matchesFilter = (currentFilter === 'all') || (tipo === currentFilter);
                const matchesSearch = !query || text.includes(query);

                row.style.display = (matchesFilter && matchesSearch) ? '' : 'none';
            });
        }

        // Filtrar cards (móvil)
        if (cardsContainer) {
            const cards = cardsContainer.querySelectorAll('.program-card-wrapper');
            cards.forEach(card => {
                const tipo = normalize(card.getAttribute('data-tipo'));
                const text = normalize(card.textContent);
                const matchesFilter = (currentFilter === 'all') || (tipo === currentFilter);
                const matchesSearch = !query || text.includes(query);

                card.style.display = (matchesFilter && matchesSearch) ? '' : 'none';
            });
        }
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('/administrador/baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/programasAdministrador.blade.php ENDPATH**/ ?>