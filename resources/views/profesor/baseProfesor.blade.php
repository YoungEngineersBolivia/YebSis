<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Profesor</title>

    {{-- Estilos propios de cada vista --}}
    <link rel="stylesheet" href="{{ auto_asset('css/profesor/baseProfesor.css') }}">
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

    <script>
        function toggleMenu() {
            alert("Aquí puedes abrir el menú lateral o desplegable.");
        }
    </script>
</body>
</html>
