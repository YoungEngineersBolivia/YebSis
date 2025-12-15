<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel del Profesor')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- Estilos propios de cada vista --}}
    <link rel="stylesheet" href="{{ auto_asset('css/profesor/baseProfesor.css') }}">
    
    {{-- Estilos adicionales de cada vista --}}
    @yield('styles')
    @stack('styles')
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <div class="logo-icon">e</div>
                {{-- Correo del profesor (sin invitados) --}}
                <span class="username">
                   {{ Auth::user()->Correo }}
                </span>
            </div>

            <div class="menu-icon" onclick="toggleMenu()">
                <div class="menu-line"></div>
                <div class="menu-line"></div>
                <div class="menu-line"></div>
            </div>

            <!-- Menú desplegable -->
            <div class="menu-dropdown" id="menuDropdown">
                <a href="{{ route('profesor.asistencia.index') }}" class="menu-item">
                    <i class="bi bi-calendar-check"></i> Registro de Asistencia
                </a>
                <a href="{{ route('profesor.listado-alumnos', 'asignados') }}" class="menu-item">
                    <i class="bi bi-people-fill"></i> Alumnos Asignados
                </a>
                <a href="{{ route('profesor.listado-alumnos', 'recuperatoria') }}" class="menu-item">
                    <i class="bi bi-calendar-event"></i> Clase Recuperatoria
                </a>
                <a href="{{ route('logout') }}" class="menu-item menu-item-logout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </header>

        <main>
            {{-- Contenido principal de cada vista --}}
            @yield('content')
        </main>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Scripts base --}}
    <script>
        function toggleMenu() {
            const dropdown = document.getElementById('menuDropdown');
            dropdown.classList.toggle('show');
        }

        // Cerrar el menú si se hace clic fuera de él
        window.onclick = function(event) {
            if (!event.target.matches('.menu-icon') && !event.target.matches('.menu-line')) {
                const dropdown = document.getElementById('menuDropdown');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>

    {{-- Scripts adicionales de cada vista --}}
    @yield('scripts')
    @stack('scripts')
</body>
</html>