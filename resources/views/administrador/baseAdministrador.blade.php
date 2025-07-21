<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación Laravel')</title>
    <!-- Aquí puedes incluir tus CSS globales -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('head_extra') {{-- Para CSS o meta tags adicionales en el head --}}
</head>
<body class="font-sans antialiased">
    <header>
        <nav>
            <!-- Barra de navegación común a todas las páginas -->
            <ul>
                <li><a href="/">Inicio</a></li>
                <li><a href="/administrador/inicioAdministrador">Administrador</a></li>
                <li><a href="/contacto">Contacto</a></li>
                <li><a href="/prueba">PEPE/a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            @yield('content') {{-- Esta es la sección principal de contenido --}}
        </div>
    </main>

    <footer>
        <!-- Pie de página común a todas las páginas -->
        <p>&copy; {{ date('Y') }} Mi Aplicación Laravel. Todos los derechos reservados.</p>
    </footer>

    <!-- Aquí puedes incluir tus JS globales -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts_extra') {{-- Para scripts JS adicionales al final del body --}}
</body>
</html>
