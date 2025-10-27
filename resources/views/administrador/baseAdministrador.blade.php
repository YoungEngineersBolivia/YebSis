<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación Laravel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="font-sans ">

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light position-fixed" style="width: 280px; height: 100vh;">
            <!-- Logo -->
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img src="{{ auto_asset('img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="250px" class="me-2">
            </a>
            <hr>

            <!-- Menú con Scroll -->
            <div class="overflow-auto" style="flex-grow: 1; max-height: calc(100vh - 120px);">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="/administrador/dashboard" class="nav-link link-dark d-flex align-items-center gap-2 ">
                            <i class="bi bi-clipboard-data"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="nav-link link-dark d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#submenuRegistrar" role="button" aria-expanded="false" aria-controls="submenuRegistrar">
                            <i class="bi bi-journal-text"></i>
                            Registrar
                            <i class="bi bi-caret-down-fill ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="submenuRegistrar">
                            <ul class="nav flex-column">
                                <li><a class="nav-link" href="/administrador/registrosAdministrador">Administrador</a></li>
                                <li><a class="nav-link" href="/administrador/tutorEstudianteAdministrador">Registrar nuevo estudiante</a></li>
                                <li><a class="nav-link" href="/administrador/registrarProfesor">Profesor</a></li>
                                <li><a class="nav-link" href="/administrador/registrarEstudianteAntiguo">Registrar graduado</a></li>
                                
                                </ul>
                         </div>
                    </li>
                    <li>
                        <a href="/administrador/tutoresAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-person-workspace"></i>
                            Tutores
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/usuariosAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill"></i>
                            Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/horariosAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-week"></i>
                            Horarios
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/estudiantesAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-person-fill"></i>
                            Estudiantes
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/pubnotAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-send-fill"></i>
                            Publicar y Notificar
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/programasAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-folder-fill"></i>
                            Programas
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/graduadosAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill"></i>
                            Graduados
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/pagosAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-cash-coin"></i>
                            Pagos
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/egresosAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-layer-backward"></i>
                            Egresos
                        </a>
                    </li>
                    <li>
                        <a href="/administrador/sucursalesAdministrador" class="nav-link link-dark d-flex align-items-center gap-2">
                            <i class="bi bi-building"></i>
                            Sucursal
                        </a>
                    </li>
                    <li>
                        <a class="nav-link link-dark d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#submenuComercial" role="button" aria-expanded="false" aria-controls="submenuRegistrar">
                            <i class="bi bi-journal-text"></i>
                            Comercial
                            <i class="bi bi-caret-down-fill ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="submenuComercial">
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

            < <!-- Usuario y Cerrar sesión -->
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

    @yield('scripts')
    <script src="{{ asset('js/administrador/baseAdministrador.js') }}"></script>
    @stack('scripts')

</body>
</html>
