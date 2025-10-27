<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YE Bolivia - Administrador')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .sidebar-link {
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 10px 12px;
            margin: 2px 0;
        }
        .sidebar-link:hover {
            background-color: #e9ecef;
            transform: translateX(5px);
        }
        .sidebar-link.active {
            background-color: #0d6efd;
            color: white !important;
        }
        .submenu-item {
            padding-left: 45px;
            font-size: 0.9rem;
        }
        .nav-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            padding-left: 12px;
        }
    </style>
</head>
<body class="font-sans">

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light position-fixed" style="width: 280px; height: 100vh;">
            <!-- Logo -->
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img src="{{ auto_asset('img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="250px" class="me-2">
            </a>
            <hr>

            <!-- Menú con Scroll -->
            <div class="overflow-auto" style="flex-grow: 1; max-height: calc(100vh - 150px);">
                <ul class="nav nav-pills flex-column mb-auto">
                    
                    <!-- Dashboard -->
                    <li>
                        <a href="/administrador/dashboard" class="nav-link link-dark sidebar-link d-flex align-items-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Sección: Gestión de Personas -->
                    <div class="nav-section-title">GESTIÓN DE PERSONAS</div>

                    <!-- Personal -->
                    <li>
                        <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#submenuPersonal" role="button" aria-expanded="false">
                            <i class="bi bi-people"></i>
                            <span>Personal</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="submenuPersonal">
                            <ul class="nav flex-column">
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/registrosAdministrador">Administradores</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/profesores">Profesores</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/tutoresAdministrador">Tutores</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/usuariosAdministrador">Todos los Usuarios</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Estudiantes -->
                    <li>
                        <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#submenuEstudiantes" role="button" aria-expanded="false">
                            <i class="bi bi-mortarboard"></i>
                            <span>Estudiantes</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="submenuEstudiantes">
                            <ul class="nav flex-column">
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/estudiantesAdministrador">Ver Todos</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/tutorEstudianteAdministrador">Registrar Nuevo</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/registrarEstudianteAntiguo">Inscribir a Taller</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/estudiantesActivos">Estudiantes Activos</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/estudiantesNoActivos">Estudiantes Inactivos</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/graduadosAdministrador">Graduados</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Sección: Académico -->
                    <div class="nav-section-title">ACADÉMICO</div>

                    <!-- Programas y Talleres -->
                    <li>
                        <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#submenuProgramas" role="button" aria-expanded="false">
                            <i class="bi bi-book"></i>
                            <span>Programas</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="submenuProgramas">
                            <ul class="nav flex-column">
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/programasAdministrador">Ver Programas</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/nuevosProgramasAdministrador">Crear Programa</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/talleresComercial">Reportes de Talleres</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Horarios -->
                    <li>
                        <a href="/administrador/horariosAdministrador" class="nav-link link-dark sidebar-link d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-week"></i>
                            <span>Horarios</span>
                        </a>
                    </li>

                    <!-- Sección: Finanzas -->
                    <div class="nav-section-title">FINANZAS</div>

                    <!-- Pagos -->
                    <li>
                        <a href="/administrador/pagosAdministrador" class="nav-link link-dark sidebar-link d-flex align-items-center gap-2">
                            <i class="bi bi-cash-coin"></i>
                            <span>Pagos</span>
                        </a>
                    </li>

                    <!-- Egresos -->
                    <li>
                        <a href="/administrador/egresosAdministrador" class="nav-link link-dark sidebar-link d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-down-circle"></i>
                            <span>Egresos</span>
                        </a>
                    </li>

                    <!-- Sección: Operaciones -->
                    <div class="nav-section-title">OPERACIONES</div>

                    <!-- Inventario/Componentes -->
                    <li>
                        <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#submenuInventario" role="button" aria-expanded="false">
                            <i class="bi bi-box-seam"></i>
                            <span>Inventario</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="submenuInventario">
                            <ul class="nav flex-column">
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/componentes">Componentes/Motores</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/motores/asignaciones">Asignaciones</a></li>
                                <li><a class="nav-link link-dark submenu-item" href="/administrador/motores/asignar">Asignar Motor</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Sucursales -->
                    <li>
                        <a href="/administrador/sucursalesAdministrador" class="nav-link link-dark sidebar-link d-flex align-items-center gap-2">
                            <i class="bi bi-building"></i>
                            <span>Sucursales</span>
                        </a>
                    </li>

                    <!-- Sección: Comunicación -->
                    <div class="nav-section-title">COMUNICACIÓN</div>

                    <!-- Publicaciones -->
                    <li>
                        <a href="/administrador/pubnotAdministrador" class="nav-link link-dark sidebar-link d-flex align-items-center gap-2">
                            <i class="bi bi-megaphone"></i>
                            <span>Publicar y Notificar</span>
                        </a>
                    </li>

                    <!-- Comercial -->
                    <li>
                        <a class="nav-link link-dark sidebar-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#submenuComercial" role="button" aria-expanded="false">
                            <i class="bi bi-graph-up"></i>
                            <span>Comercial</span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="submenuComercial">
                            <ul class="nav flex-column">
                                <li><a class="nav-link" href="{{ route('prospectos.comercial') }}">Prospectos</a></li>
                                <li><a class="nav-link" href="{{ route('estudiantesActivos') }}">Estudiantes activos</a></li>
                                <li><a class="nav-link" href="{{ route('estudiantesNoActivos') }}">Estudiantes no activos</a></li>
                                <li><a class="nav-link" href="{{ route('reportes.talleres') }}">Talleres</a></li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>

            <hr>

            <!-- Usuario y Cerrar sesión -->
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->persona->Nombre ?? 'Usuario' }}&background=0d6efd&color=fff" alt="perfil" width="32" height="32" class="rounded-circle">
                    <div class="d-flex flex-column" style="line-height: 1.2;">
                        <small class="fw-bold">{{ auth()->user()->persona->Nombre ?? 'Usuario' }}</small>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ ucfirst(auth()->user()->rol ?? 'Admin') }}</small>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger" title="Cerrar sesión">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="flex-grow-1" style="margin-left: 280px;">

            <!-- Contenido -->
            <div class="container-fluid p-4">
                <!-- Mensajes de éxito/error -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Mantener el dropdown abierto si está activo
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const links = document.querySelectorAll('.nav-link');
            
            links.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    
                    // Si está dentro de un collapse, abrirlo
                    const collapse = link.closest('.collapse');
                    if (collapse) {
                        collapse.classList.add('show');
                    }
                }
            });
        });

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @yield('scripts')
    @stack('scripts')

</body>
</html>