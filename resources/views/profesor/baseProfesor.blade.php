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
        </header>

        <main>
            {{-- Contenido principal de cada vista --}}
            @yield('content')
        </main>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Scripts base --}}
    <script>
        function toggleMenu() {
            alert("Aquí puedes abrir el menú lateral o desplegable.");
        }
    </script>

    {{-- Scripts adicionales de cada vista --}}
    @yield('scripts')
    @stack('scripts')
</body>
</html>