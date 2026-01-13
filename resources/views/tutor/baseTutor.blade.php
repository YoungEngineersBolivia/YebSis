<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel del Tutor') - Young Engineers</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --success-color: #4CAF50;
            --text-dark: #333;
            --text-light: #666;
            --bg-light: #f5f5f5;
            --border-color: #ddd;
            --shadow: 0 2px 8px rgba(0,0,0,0.1);
            --shadow-hover: 0 4px 12px rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        /* Header */
        header {
            background: white;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo img {
            max-width: 180px;
            height: auto;
        }

        .navbar-toggle {
            display: none;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1.5rem;
            border-radius: 8px;
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .nav-links span {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 600;
        }

        .social-icons {
            display: flex;
            gap: 0.5rem;
        }

        .social-icon {
            transition: transform 0.3s;
        }

        .social-icon:hover {
            transform: scale(1.1);
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .user-button:hover {
            background: #45a049;
        }

        .dropdown-icon {
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid white;
            transition: transform 0.3s;
        }

        .dropdown-icon.rotate {
            transform: rotate(180deg);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-hover);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1001;
        }

        .dropdown-menu-custom.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item-custom {
            padding: 1rem;
            cursor: pointer;
            transition: background 0.3s;
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-item-custom:last-child {
            border-bottom: none;
            border-radius: 0 0 12px 12px;
        }

        .dropdown-item-custom:first-child {
            border-radius: 12px 12px 0 0;
        }

        .dropdown-item-custom:hover {
            background: var(--bg-light);
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .tutor-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .tutor-card h1 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .tutor-card h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .info-grid p {
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4CAF50;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                padding-top: 1rem;
            }

            .nav-links.active {
                display: flex;
            }

            .logo img {
                max-width: 150px;
            }

            .header-content {
                padding: 0.75rem;
            }

            .tutor-card h1 {
                font-size: 1.5rem;
            }

            .tutor-card h2 {
                font-size: 1.25rem;
            }

            .tutor-card {
                padding: 1rem;
            }

            .main-content {
                margin: 1rem auto;
                padding: 0 0.75rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .logo img {
                max-width: 120px;
            }

            .tutor-card h1 {
                font-size: 1.25rem;
            }

            .tutor-card h2 {
                font-size: 1.125rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('img/ES_logo-02.webp') }}" alt="Logo YE Bolivia">
            </div>
            <button class="navbar-toggle" onclick="toggleNavbar()">☰</button>
            <nav class="nav-links" id="mainNavLinks">
                <span>RECONOCIDO POR:</span>
                <img src="{{ asset('img/recognized_by.png') }}" alt="Reconocimiento" style="max-width: 150px; height: auto;">
                <div class="social-icons">
                    <a href="https://www.facebook.com/youngengineerszonasurlapaz/" class="social-icon" target="_blank">
                        <img src="{{ asset('img/facebook.svg') }}" alt="Facebook" width="40" height="40">
                    </a>
                    <a href="https://www.tiktok.com/@youngengineersbolivia" class="social-icon" target="_blank">
                        <img src="{{ asset('img/tiktok.svg') }}" alt="tiktok" width="35" height="35">
                    </a>
                </div>
                
                <div class="user-dropdown">
                    <button class="user-button" onclick="toggleDropdown()">
                        <span>{{ $tutor->persona->Nombre ?? 'Usuario' }}</span>
                        <div class="dropdown-icon" id="dropdownIcon"></div>
                    </button>
                    <div class="dropdown-menu-custom" id="dropdownMenu">
                        <div class="dropdown-item-custom" onclick="window.location.href='{{ route('tutor.home') }}'">
                            🏠 Inicio
                        </div>
                        <div class="dropdown-item-custom" onclick="window.location.href='#'">
                            👤 Ver Perfil
                        </div>
                        <div class="dropdown-item-custom" onclick="logout()">
                            🚪 Cerrar Sesión
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="main-content">
        <!-- Información del Tutor (solo se muestra en la página principal) -->
        @if(Route::currentRouteName() === 'tutor.home')
            <div class="tutor-card">
                <h1>Bienvenido, {{ $tutor->persona->Nombre }} {{ $tutor->persona->Apellido }}</h1>
                <div class="info-grid">
                    <div>
                        <p><strong>Celular:</strong> {{ $tutor->persona->Celular ?? 'No registrado' }}</p>
                        <p><strong>Dirección:</strong> {{ $tutor->persona->Direccion_domicilio ?? 'No registrada' }}</p>
                        <p><strong>Parentesco:</strong> {{ $tutor->Parentesco ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <p><strong>NIT:</strong> {{ $tutor->Nit ?? 'No registrado' }}</p>
                        <p><strong>Nombre para Factura:</strong> {{ $tutor->Nombre_factura ?? 'No registrado' }}</p>
                        @if($tutor->Descuento)
                            <p style="color: var(--success-color);"><strong>Descuento:</strong> {{ $tutor->Descuento }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido de las páginas hijas -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle navbar en mobile
        function toggleNavbar() {
            const navLinks = document.getElementById('mainNavLinks');
            navLinks.classList.toggle('active');
        }

        // Toggle dropdown usuario
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const icon = document.getElementById('dropdownIcon');
            dropdown.classList.toggle('show');
            icon.classList.toggle('rotate');
        }

        // Cerrar dropdown al hacer click fuera
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = document.querySelector('.user-button');
            
            if (button && !button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
                const icon = document.getElementById('dropdownIcon');
                if (icon) icon.classList.remove('rotate');
            }
        });

        // Función para cerrar sesión
        function logout() {
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                window.location.href = '/logout';
            }
        }

        // Función auxiliar para mostrar alertas
        function mostrarAlerta(mensaje, tipo) {
            const alertaExistente = document.querySelector('.alert');
            if (alertaExistente) {
                alertaExistente.remove();
            }

            const alerta = document.createElement('div');
            alerta.className = `alert alert-${tipo}`;
            alerta.innerHTML = `
                <strong>${tipo === 'success' ? '✅' : '⚠️'}</strong> ${mensaje}
            `;

            const mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alerta, mainContent.firstChild);

            setTimeout(() => {
                alerta.style.transition = 'opacity 0.5s';
                alerta.style.opacity = '0';
                setTimeout(() => alerta.remove(), 500);
            }, 5000);
        }
    </script>
    
    @yield('scripts')
</body>
</html>