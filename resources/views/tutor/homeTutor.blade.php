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
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 0.5rem;
        }

        .btn {
            padding: 0.65rem 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            white-space: nowrap;
        }

        .btn-green {
            background: var(--success-color);
            color: white;
        }

        .btn-green:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn-red {
            background: var(--danger-color);
            color: white;
        }

        .btn-red:hover {
            background: #da190b;
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
            max-width: 500px;
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

        .modal form {
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

            .modal form {
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
                                <button class="btn btn-green" onclick="verDetalles({{ $estudiante->Id_estudiantes }})">
                                    📋 Ver
                                </button>
                                <button class="btn btn-red" onclick="verEvaluaciones({{ $estudiante->Id_estudiantes }})">
                                    📊 Evaluaciones
                                </button>
                                <button class="btn btn-yellow" onclick="openModal({{ $estudiante->Id_estudiantes }}, '{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}')">
                                    📅 Agendar Cita
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Agendar Cita -->
    <div id="citaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📅 Agendar Cita</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
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

                <button type="submit" class="btn-submit">Agendar Cita</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/tutor/homeTutor.js') }}"></script>
</body>
</html>