<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación Laravel')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (necesario para el AJAX que estás usando) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="font-sans ">

    <div class="d-flex">
    
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-light position-fixed" style="width: 280px; min-height: 100vh;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <img src="{{ Vite::asset('resources/img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="250px" class="me-2">
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="#">
                <a href="/administrador/estudiantesAdministrador" class="nav-link link-dark d-flex align-items-center gap-2 ">
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
                        <li><a class="nav-link" href="/administrador/registrarComercial">Comercial</a></li>
                        <li><a class="nav-link" href="/administrador/registrarTutor">Tutor</a></li>
                        <li><a class="nav-link" href="/estudiantes/registrar">Estudiante</a></li>
                        <li><a class="nav-link" href="/administrador/registrarProfesor">Profesor</a></li>
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
        </ul>
        <hr>
        
        <div>
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none gap-2">
                <button type="button" class="btn btn-danger w-75">Cerrar sesión</button>
                <img src="https://github.com/mdo.png" alt="perfil" width="32" height="32" class="rounded-circle">
            </a>
          
        
    </div>
    </div>
     <div class="flex-grow-1" style="margin-left: 280px;">
            <div class="container-fluid p-4">
                @yield('content')
            </div>
    </div>
    @yield('scripts')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>