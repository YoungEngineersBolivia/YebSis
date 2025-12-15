<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Tutor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .dropdown-menu {
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
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            padding: 1rem;
            cursor: pointer;
            transition: background 0.3s;
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
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

        /* Student Cards */
        .students-container {
            display: grid;
            gap: 1rem;
        }

        .student-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .student-card:hover {
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }

        .student-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        .student-header-info {
            flex: 1;
            min-width: 0;
        }

        .student-header-info h3 {
            font-size: 1.125rem;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
            word-wrap: break-word;
        }

        .student-code {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .student-body {
            display: grid;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            align-items: start;
            gap: 0.5rem;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-dark);
            min-width: 80px;
            font-size: 0.9rem;
        }

        .info-value {
            color: var(--text-light);
            flex: 1;
            font-size: 0.9rem;
            word-wrap: break-word;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-active {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .badge-inactive {
            background: #fce4ec;
            color: #c2185b;
        }

        /* Buttons */
        .student-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .btn {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            white-space: nowrap;
        }

        .btn-blue {
            background: var(--secondary-color);
            color: white;
        }

        .btn-blue:hover {
            background: #1976D2;
            transform: translateY(-2px);
        }

        .btn-yellow {
            background: var(--warning-color);
            color: white;
        }

        .btn-yellow:hover {
            background: #fb8c00;
            transform: translateY(-2px);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            padding: 1rem;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--text-dark);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            color: var(--text-light);
            cursor: pointer;
            padding: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .close-btn:hover {
            background: var(--bg-light);
            color: var(--text-dark);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: #45a049;
        }

        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Evaluaciones */
        .evaluacion-item {
            background: var(--bg-light);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--secondary-color);
        }

        .evaluacion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .evaluacion-fecha {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .evaluacion-modelo {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .evaluacion-pregunta {
            font-size: 0.9rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .evaluacion-respuesta {
            font-size: 0.9rem;
            color: var(--text-light);
            padding: 0.5rem;
            background: white;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }

        .evaluacion-profesor {
            font-size: 0.85rem;
            color: var(--text-light);
            font-style: italic;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-light);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

            .student-avatar {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .student-card {
                padding: 1rem;
            }

            .student-actions {
                grid-template-columns: 1fr;
            }

            .btn {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .modal-content {
                margin: 1rem;
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-body {
                padding: 1rem;
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

            .student-header {
                flex-direction: column;
                text-align: center;
            }

            .student-avatar {
                width: 70px;
                height: 70px;
                font-size: 1.5rem;
            }

            .info-label {
                min-width: 70px;
                font-size: 0.85rem;
            }

            .info-value {
                font-size: 0.85rem;
            }
        }
    </style>
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
                    <div class="dropdown-menu" id="dropdownMenu">
                        <div class="dropdown-item" onclick="window.location.href='#'">
                            👤 Ver Perfil
                        </div>
                        <div class="dropdown-item" onclick="logout()">
                            🚪 Cerrar Sesión
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="main-content">
        <!-- Información del Tutor -->
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

        <!-- Lista de Estudiantes -->
        <div class="tutor-card">
            <h2>Mis Estudiantes ({{ $estudiantes->count() }})</h2>

            @if($estudiantes->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">📚</div>
                    <p>No hay estudiantes registrados</p>
                </div>
            @else
                <div class="students-container">
                    @foreach($estudiantes as $estudiante)
                        <div class="student-card">
                            <div class="student-header">
                                <div class="student-avatar">
                                    {{ substr($estudiante->persona->Nombre, 0, 1) }}
                                </div>
                                <div class="student-header-info">
                                    <h3>{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</h3>
                                    <div class="student-code">
                                        <strong>Código:</strong> {{ $estudiante->Cod_estudiante }}
                                    </div>
                                </div>
                                @if(strtolower(trim($estudiante->Estado ?? '')) === 'activo')
                                    <span class="badge badge-active">Activo</span>
                                @else
                                    <span class="badge badge-inactive">Inactivo</span>
                                @endif
                            </div>

                            <div class="student-body">
                                <div class="info-row">
                                    <span class="info-label">Programa:</span>
                                    <span class="info-value">{{ $estudiante->programa->Nombre ?? 'No asignado' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Celular:</span>
                                    <span class="info-value">{{ $estudiante->persona->Celular ?? 'Sin celular' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Sucursal:</span>
                                    <span class="info-value">{{ $estudiante->sucursal->Nombre ?? 'No asignada' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Profesor:</span>
                                    <span class="info-value">
                                        @if($estudiante->profesor)
                                            {{ $estudiante->profesor->persona->Nombre }} {{ $estudiante->profesor->persona->Apellido }}
                                        @else
                                            Sin asignar
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="student-actions">
                                <button class="btn btn-blue" onclick="verEvaluaciones({{ $estudiante->Id_estudiantes }}, '{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}')">
                                    📊 Ver Evaluaciones
                                </button>
                                <button class="btn btn-yellow" onclick="abrirModalCita({{ $estudiante->Id_estudiantes }}, '{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}')">
                                    📅 Agendar Cita
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Evaluaciones -->
    <div id="evaluacionesModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📊 Evaluaciones del Estudiante</h2>
                <button class="close-btn" onclick="cerrarModalEvaluaciones()">&times;</button>
            </div>
            <div class="modal-body">
                <h3 id="estudianteNombreEval" style="margin-bottom: 1rem; color: var(--text-dark);"></h3>
                <div id="evaluacionesContent">
                    <div class="loading">
                        <div class="spinner"></div>
                        <p style="margin-top: 1rem;">Cargando evaluaciones...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agendar Cita -->
    <div id="citaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📅 Agendar Cita</h2>
                <button class="close-btn" onclick="cerrarModalCita()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="citaForm" onsubmit="submitCita(event)">
                    <input type="hidden" id="estudiante_id" name="estudiante_id">
                    
                    <div class="form-group">
                        <label>Estudiante</label>
                        <input type="text" id="estudiante_nombre" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="fecha_cita">Fecha de la Cita *</label>
                        <input type="date" id="fecha_cita" name="fecha" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="hora_cita">Hora de la Cita *</label>
                        <input type="time" id="hora_cita" name="hora" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="motivo_cita">Motivo de la Cita</label>
                        <textarea id="motivo_cita" name="motivo" class="form-control" placeholder="Describe el motivo de la cita..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">Agendar Cita</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Configurar CSRF token para todas las peticiones AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
                document.getElementById('dropdownIcon').classList.remove('rotate');
            }
        });

        // Función para cerrar sesión
        function logout() {
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                window.location.href = '/logout';
            }
        }

        // Ver evaluaciones
        async function verEvaluaciones(estudianteId, nombreEstudiante) {
            const modal = document.getElementById('evaluacionesModal');
            const content = document.getElementById('evaluacionesContent');
            const nombreElem = document.getElementById('estudianteNombreEval');
            
            nombreElem.textContent = nombreEstudiante;
            modal.classList.add('active');
            
            // Mostrar loading
            content.innerHTML = `
                <div class="loading">
                    <div class="spinner"></div>
                    <p style="margin-top: 1rem;">Cargando evaluaciones...</p>
                </div>
            `;

            try {
                const response = await fetch(`/tutor/estudiantes/${estudianteId}/evaluaciones`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    if (data.evaluaciones.length === 0) {
                        content.innerHTML = `
                            <div class="empty-state">
                                <div class="empty-state-icon">📝</div>
                                <p>No hay evaluaciones registradas para este estudiante</p>
                            </div>
                        `;
                    } else {
                        let html = '';
                        data.evaluaciones.forEach(eval => {
                            html += `
                                <div class="evaluacion-item">
                                    <div class="evaluacion-header">
                                        <span class="evaluacion-modelo">📦 ${eval.Nombre_modelo || 'Modelo no especificado'}</span>
                                        <span class="evaluacion-fecha">${formatearFecha(eval.fecha_evaluacion)}</span>
                                    </div>
                                    <div class="evaluacion-pregunta">
                                        <strong>Pregunta:</strong> ${eval.Pregunta || 'N/A'}
                                    </div>
                                    <div class="evaluacion-respuesta">
                                        <strong>Respuesta:</strong> ${eval.Respuesta || 'N/A'}
                                    </div>
                                    <div class="evaluacion-profesor">
                                        👨‍🏫 Evaluado por: ${eval.profesor_nombre} ${eval.profesor_apellido}
                                    </div>
                                </div>
                            `;
                        });
                        content.innerHTML = html;
                    }
                } else {
                    mostrarError(content, data.error || 'Error al cargar las evaluaciones');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarError(content, 'Error al cargar las evaluaciones. Por favor, intente nuevamente.');
            }
        }

        // Cerrar modal evaluaciones
        function cerrarModalEvaluaciones() {
            document.getElementById('evaluacionesModal').classList.remove('active');
        }

        // Abrir modal cita
        function abrirModalCita(estudianteId, nombreEstudiante) {
            document.getElementById('estudiante_id').value = estudianteId;
            document.getElementById('estudiante_nombre').value = nombreEstudiante;
            
            // Establecer fecha mínima como hoy
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fecha_cita').setAttribute('min', today);
            
            // Limpiar campos
            document.getElementById('fecha_cita').value = '';
            document.getElementById('hora_cita').value = '';
            document.getElementById('motivo_cita').value = '';
            
            document.getElementById('citaModal').classList.add('active');
        }

        // Cerrar modal cita
        function cerrarModalCita() {
            document.getElementById('citaModal').classList.remove('active');
            document.getElementById('citaForm').reset();
        }

        // Submit cita
        async function submitCita(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Agendando...';

            const formData = {
                estudiante_id: document.getElementById('estudiante_id').value,
                fecha: document.getElementById('fecha_cita').value,
                hora: document.getElementById('hora_cita').value,
                motivo: document.getElementById('motivo_cita').value
            };

            try {
                const response = await fetch('/tutor/citas/agendar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('Cita agendada exitosamente', 'success');
                    cerrarModalCita();
                } else {
                    mostrarAlerta(data.error || 'Error al agendar la cita', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('Error al agendar la cita. Por favor, intente nuevamente.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Agendar Cita';
            }
        }

        // Función auxiliar para formatear fecha
        function formatearFecha(fecha) {
            if (!fecha) return 'Fecha no disponible';
            const date = new Date(fecha);
            const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('es-ES', opciones);
        }

        // Función auxiliar para mostrar error en modal
        function mostrarError(elemento, mensaje) {
            elemento.innerHTML = `
                <div class="alert alert-error">
                    <strong>⚠️ Error:</strong> ${mensaje}
                </div>
            `;
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

        // Cerrar modales al hacer clic fuera
        window.addEventListener('click', function(event) {
            const citaModal = document.getElementById('citaModal');
            const evalModal = document.getElementById('evaluacionesModal');
            
            if (event.target === citaModal) {
                cerrarModalCita();
            }
            if (event.target === evalModal) {
                cerrarModalEvaluaciones();
            }
        });
    </script>
</body>
</html>