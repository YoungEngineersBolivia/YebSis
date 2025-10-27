<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Tutor</title>
    <link rel="stylesheet" href="{{ asset('css/tutor/homeTutor.css') }}">
  
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="{{ Vite::asset('resources/img/ES_logo-02.webp') }}" alt="Logo YE Bolivia" width="250px">
                </div>
                <button class="navbar-toggle" onclick="toggleNavbar()">&#9776;</button>
                <nav class="nav-links" id="mainNavLinks">
                    <span>RECONOCIDO POR:</span>
                    <img src="{{ Vite::asset('resources/img/recognized_by.png') }}" alt="Reconocimiento" width="200px">
                    <span>REDES SOCIALES</span>
                    
                    <!-- Dropdown Usuario -->
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
        </div>
    </header>

    <div class="main-content">
        <!-- Información del Tutor -->
        <div class="tutor-card">
            <h1 style="font-size: 2rem; margin-bottom: 1rem; color: #333;">
                Bienvenido, {{ $tutor->persona->Nombre }} {{ $tutor->persona->Apellido }}
            </h1>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <div>
                    <p style="color: #666;"><strong>Celular:</strong> {{ $tutor->persona->Celular ?? 'No registrado' }}</p>
                    <p style="color: #666;"><strong>Dirección:</strong> {{ $tutor->persona->Direccion_domicilio ?? 'No registrada' }}</p>
                    <p style="color: #666;"><strong>Parentesco:</strong> {{ $tutor->Parentesco ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p style="color: #666;"><strong>NIT:</strong> {{ $tutor->Nit ?? 'No registrado' }}</p>
                    <p style="color: #666;"><strong>Nombre para Factura:</strong> {{ $tutor->Nombre_factura ?? 'No registrado' }}</p>
                    @if($tutor->Descuento)
                        <p style="color: #4CAF50;"><strong>Descuento:</strong> {{ $tutor->Descuento }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Lista de Estudiantes -->
        <div class="tutor-card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem; color: #333;">
                Mis Estudiantes ({{ $estudiantes->count() }})
            </h2>

            @if($estudiantes->isEmpty())
                <div style="text-align: center; padding: 3rem; color: #999;">
                    <p>No hay estudiantes registrados</p>
                </div>
            @else
                @foreach($estudiantes as $estudiante)
                    <div class="student-item">
                        <div class="student-avatar">
                            {{ substr($estudiante->persona->Nombre, 0, 1) }}
                        </div>
                        
                        <div class="student-info">
                            <h3>{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}</h3>
                            <div class="student-details">
                                <p><strong>Código:</strong> {{ $estudiante->Cod_estudiante }}</p>
                                <p><strong>Programa:</strong> {{ $estudiante->programa->Nombre ?? 'No asignado' }}</p>
                                <p><strong>Celular:</strong> {{ $estudiante->persona->Celular ?? 'Sin celular' }}</p>
                            </div>
                        </div>

                        <div class="student-details">
                            <p><strong>Sucursal:</strong> {{ $estudiante->sucursal->Nombre ?? 'No asignada' }}</p>
                            <p><strong>Profesor:</strong> 
                                @if($estudiante->profesor)
                                    {{ $estudiante->profesor->persona->Nombre }} {{ $estudiante->profesor->persona->Apellido }}
                                @else
                                    <span style="color: #999;">Sin asignar</span>
                                @endif
                            </p>
                            <p>
                                @if($estudiante->Estado == 'activo')
                                    <span class="badge badge-active">Activo</span>
                                @else
                                    <span class="badge badge-inactive">Inactivo</span>
                                @endif
                            </p>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <button class="btn btn-green" onclick="verDetalles({{ $estudiante->Id_estudiantes }})">Ver</button>
                            <button class="btn btn-red">Evaluaciones</button>
                            <button class="btn btn-yellow" onclick="openModal({{ $estudiante->Id_estudiantes }}, '{{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}')">Agendar Cita</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Modal Agendar Cita -->
    <div id="citaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Agendar Cita</h2>
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
                    <textarea id="motivo_cita" name="motivo" class="form-control" rows="4" placeholder="Describe el motivo de la cita..."></textarea>
                </div>

                <button type="submit" class="btn-submit">Agendar Cita</button>
            </form>
        </div>
    </div>

    <script src="{{ auto_asset('js/tutor/homeTutor.js') }}"></script>
</body>
</html>