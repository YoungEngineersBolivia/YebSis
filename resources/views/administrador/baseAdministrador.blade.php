<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YE Bolivia - Administrador')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @yield('styles')
    <style>
        /* Variables Globales (Estilo Programas) */
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
            overflow-x: hidden;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
            color: var(--dark-color);
        }

        /* --- Estilos Globales para Paneles Administrativos --- */

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
            margin-bottom: 0;
        }

        /* Tarjetas y Contenedores */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }
        
        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 24px;
            font-weight: 600;
        }

        .card-header-primary {
             background-color: var(--primary-color) !important;
             color: white !important;
        }

        /* Tablas */
        .table-responsive {
            border-radius: 16px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            padding: 16px;
            border: none;
        }

        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            color: #4b5563;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9fafb;
        }

        .table-hover tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Botones */
        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
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

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: white;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
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
        }
        
        .btn-close-white { 
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }
        
        .modal-footer { 
            border-top: 1px solid #e5e7eb; 
            padding: 20px 28px;
            background-color: #f9fafb;
            border-radius: 0 0 16px 16px;
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
            color: #9ca3af;
        }

        .search-box .form-control {
            border: none;
            box-shadow: none;
            padding: 14px 20px 14px 8px;
        }

        /* Sidebar fijo */
        .sidebar-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background-color: white;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            overflow-x: hidden;
            z-index: 1000;
            border-right: 1px solid #e5e7eb;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }

        /* Logo */
        .sidebar-logo {
            max-width: 100%;
            height: auto;
            margin-bottom: 1rem;
        }

        /* Área de scroll del menú */
        .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            max-height: calc(100vh - 200px);
            padding-right: 8px;
        }

        /* Ocultar scrollbar pero mantener funcionalidad */
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Estilos de links (Sidebar) */
        .sidebar-link {
            transition: all 0.2s ease;
            border-radius: 10px;
            padding: 12px 16px;
            margin: 4px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #4b5563;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar-link:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background-color: var(--primary-color);
            color: white !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .sidebar-link.active i {
            color: white !important;
        }

        .submenu-item {
            padding-left: 48px;
            font-size: 0.9rem;
            color: #6b7280;
            position: relative;
        }
        
        .submenu-item:hover {
            color: var(--primary-color);
            background-color: transparent;
            transform: translateX(4px);
        }

        .nav-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            padding-left: 16px;
            letter-spacing: 0.05em;
        }

        /* Usuario footer */
        .sidebar-footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 1.5rem;
            margin-top: auto;
        }

        /* Contenido principal */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            width: calc(100% - 280px);
            background-color: var(--light-bg);
            transition: margin-left 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-container {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar-container">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-4 mb-md-0 me-md-auto link-dark text-decoration-none px-2">
            <img src="{{ auto_asset('img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="200" class="img-fluid">
        </a>

        <!-- Menú con Scroll -->
        <div class="sidebar-menu">
            <ul class="nav nav-pills flex-column mb-auto">

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Sección: Gestión de Personas -->
                <div class="nav-section-title">GESTIÓN DE PERSONAS</div>

                <!-- Personal -->
                <li>
                    <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-3" data-bs-toggle="collapse" href="#submenuPersonal" role="button" aria-expanded="false">
                        <i class="bi bi-people"></i>
                        <span>Personal</span>
                        <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse" id="submenuPersonal">
                        <ul class="nav flex-column gap-1 mt-1">
                            <li><a class="nav-link link-dark submenu-item" href="{{ url('administrador/registrosAdministrador') }}">Administradores</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ url('administrador/profesores') }}">Profesores</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ url('administrador/tutoresAdministrador') }}">Tutores</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('usuarios.index') }}">Todos los Usuarios</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Estudiantes -->
                <li>
                    <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-3" data-bs-toggle="collapse" href="#submenuEstudiantes" role="button" aria-expanded="false">
                        <i class="bi bi-mortarboard"></i>
                        <span>Estudiantes</span>
                        <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse" id="submenuEstudiantes">
                        <ul class="nav flex-column gap-1 mt-1">
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('admin.estudiantes') }}">Ver Todos</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('registroCombinado.form') }}">Registrar Nuevo</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('inscripcionEstudiante.mostrar') }}">Inscribir a Taller</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('estudiantesActivos') }}">Estudiantes Activos</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('estudiantesNoActivos') }}">Estudiantes Inactivos</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('graduados.mostrar') }}">Graduados</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Sección: Académico -->
                <div class="nav-section-title">ACADÉMICO</div>

                <!-- Programas -->
                <li>
                    <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-3" data-bs-toggle="collapse" href="#submenuProgramas" role="button" aria-expanded="false">
                        <i class="bi bi-book"></i>
                        <span>Programas</span>
                        <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse" id="submenuProgramas">
                        <ul class="nav flex-column gap-1 mt-1">
                            <li><a class="nav-link link-dark submenu-item" href="{{ url('administrador/programasAdministrador') }}">Ver Programas</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Horarios -->
                <li>
                    <a href="{{ route('asistencia.admin.index') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Asistencia</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('horarios.index') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-week"></i>
                        <span>Horarios</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('citas.index') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-week"></i>
                        <span>Citas</span>
                    </a>
                </li>
                <!-- Sección: Finanzas -->
                <div class="nav-section-title">FINANZAS</div>

                <!-- Pagos -->
                <li>
                    <a href="{{ route('pagos.form') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-cash-coin"></i>
                        <span>Pagos</span>
                    </a>
                </li>

                <!-- Egresos -->
                <li>
                    <a href="{{ route('egresos.index') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-arrow-down-circle"></i>
                        <span>Egresos</span>
                    </a>
                </li>

                <!-- Sección: Operaciones -->
                <div class="nav-section-title">OPERACIONES</div>

                <!-- Inventario de Componentes/Motores -->
                <li>
                    <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-3" data-bs-toggle="collapse" href="#submenuInventario" role="button" aria-expanded="false">
                        <i class="bi bi-box-seam"></i>
                        <span>Inventario</span>
                        <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse" id="submenuInventario">
                        <ul class="nav flex-column gap-1 mt-1">
                            <li>
                                <a class="nav-link link-dark submenu-item" href="{{ route('admin.componentes.inventario') }}">
                                    <i class="bi bi-list-ul me-2"></i>Ver Inventario
                                </a>
                            </li>
                            <li>
                                <a class="nav-link link-dark submenu-item" href="{{ route('admin.componentes.salida') }}">
                                    <i class="bi bi-box-arrow-right me-2"></i>Salida Componentes
                                </a>
                            </li>
                            <li>
                                <a class="nav-link link-dark submenu-item" href="{{ route('admin.componentes.entrada') }}">
                                    <i class="bi bi-box-arrow-in-left me-2"></i>Entrada Componentes
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Sucursales -->
                <li>
                    <a href="{{ route('sucursales.index') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-building"></i>
                        <span>Sucursales</span>
                    </a>
                </li>

                <!-- Sección: Comunicación -->
                <div class="nav-section-title">COMUNICACIÓN</div>

                <!-- Publicar -->
                <li>
                    <a href="{{ route('publicaciones.index') }}" class="nav-link link-dark sidebar-link d-flex align-items-center gap-3">
                        <i class="bi bi-megaphone"></i>
                        <span>Publicar y Notificar</span>
                    </a>
                </li>

                <!-- Comercial -->
                <li>
                    <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-3" data-bs-toggle="collapse" href="#submenuComercial" role="button" aria-expanded="false">
                        <i class="bi bi-graph-up"></i>
                        <span>Comercial</span>
                        <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse" id="submenuComercial">
                        <ul class="nav flex-column gap-1 mt-1">
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('prospectos.comercial') }}">Prospectos</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('estudiantesActivos') }}">Est. Activos</a></li>
                            <li><a class="nav-link link-dark submenu-item" href="{{ route('estudiantesNoActivos') }}">Est. Inactivos</a></li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>

        <!-- Usuario y Cerrar sesión -->
        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->persona->Nombre ?? 'Usuario' }}&background=6366f1&color=fff" alt="perfil" width="40" height="40" class="rounded-circle flex-shrink-0 shadow-sm">
                    <div class="d-flex flex-column overflow-hidden" style="line-height: 1.3;">
                        <span class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">{{ auth()->user()->persona->Nombre ?? 'Usuario' }}</span>
                        <span class="text-muted text-truncate" style="font-size: 0.75rem;">{{ ucfirst(auth()->user()->rol ?? 'Admin') }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0 flex-shrink-0 ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2" title="Cerrar sesión">
                        <i class="bi bi-box-arrow-right fs-6"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="container-fluid p-4 p-md-5">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const links = document.querySelectorAll('.sidebar-link');
        
        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (href === currentPath || currentPath.includes(href) && href !== '/')) {
                link.classList.add('active');
                const collapse = link.getAttribute('data-bs-toggle') === 'collapse' ? 
                                document.querySelector(link.getAttribute('href')) : 
                                link.closest('.collapse');
                
                if (collapse) {
                    collapse.classList.add('show');
                    // If it's a parent link opening a submenu
                    if(link.getAttribute('aria-expanded') !== null){
                         link.setAttribute('aria-expanded', 'true');
                         link.classList.remove('collapsed');
                    }
                }
            }
        });
        
        // Also check submenu items
        const subLinks = document.querySelectorAll('.submenu-item');
        subLinks.forEach(link => {
            const href = link.getAttribute('href');
             if (href && (href === currentPath || currentPath.includes(href))) {
                 link.style.color = 'var(--primary-color)';
                 link.closest('.collapse').classList.add('show');
                 const parentBtn = document.querySelector(`[href="#${link.closest('.collapse').id}"]`);
                 if(parentBtn) {
                     parentBtn.classList.add('active');
                     parentBtn.classList.remove('collapsed');
                     parentBtn.setAttribute('aria-expanded', 'true');
                 }
             }
        });

        // --- GLOBAL TABLE FILTER ---
        // Usage: <input type="text" data-table-filter="limit-table-id">
        const filterInputs = document.querySelectorAll('input[data-table-filter]');
        
        filterInputs.forEach(input => {
            input.addEventListener('input', function() {
                const targetId = this.getAttribute('data-table-filter');
                const targetTable = document.getElementById(targetId);
                const query = this.value.toLowerCase().trim();

                if (!targetTable) return;

                const rows = targetTable.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    // Only search in visible text content of the row
                    const text = row.innerText.toLowerCase();
                    if (text.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@yield('scripts')
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ auto_asset('js/administrador/baseAdministrador.js') }}"></script>
</body>
</html>