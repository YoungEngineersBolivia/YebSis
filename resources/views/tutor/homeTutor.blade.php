<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Tutor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        header {
            background-color: white;
            border-bottom: 3px solid #333;
            padding: 1rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo img {
            max-width: 250px;
            height: auto;
        }

        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-links span {
            font-weight: 600;
            color: #333;
        }

        .user-dropdown {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #fff;
            border: 2px solid #333;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .user-button:hover {
            background-color: #f0f0f0;
        }

        .dropdown-icon {
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #333;
            transition: transform 0.3s;
        }

        .dropdown-icon.open {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 2px solid #333;
            border-radius: 8px;
            margin-top: 0.5rem;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            border-bottom: 1px solid #eee;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .main-content {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .tutor-card {
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .student-item {
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            display: grid;
            grid-template-columns: 80px 1fr 1fr 150px;
            gap: 1rem;
            align-items: center;
        }

        .student-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }

        .student-info h3 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .student-details {
            font-size: 0.9rem;
            color: #666;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-green {
            background-color: #4CAF50;
            color: white;
        }

        .btn-green:hover {
            background-color: #45a049;
        }

        .btn-red {
            background-color: #dc3545;
            color: white;
        }

        .btn-red:hover {
            background-color: #c82333;
        }

        .btn-yellow {
            background-color: #cddc39;
            color: #333;
        }

        .btn-yellow:hover {
            background-color: #c0ca33;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideDown 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }

        .modal-header h2 {
            margin: 0;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #999;
        }

        .close-btn:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .navbar-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                padding: 1rem 0;
            }

            .nav-links.show {
                display: flex;
            }

            .student-item {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .student-avatar {
                margin: 0 auto;
            }
        }
    </style>
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

    <script>
        function toggleNavbar() {
            const navLinks = document.getElementById('mainNavLinks');
            navLinks.classList.toggle('show');
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const icon = document.getElementById('dropdownIcon');
            dropdown.classList.toggle('show');
            icon.classList.toggle('open');
        }

        // Cerrar dropdown al hacer clic fuera
        window.onclick = function(event) {
            if (!event.target.matches('.user-button') && !event.target.closest('.user-button')) {
                const dropdown = document.getElementById('dropdownMenu');
                const icon = document.getElementById('dropdownIcon');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    icon.classList.remove('open');
                }
            }
        }

        function logout() {
            if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
                window.location.href = '/logout';
            }
        }

        function openModal(estudianteId, estudianteNombre) {
            document.getElementById('estudiante_id').value = estudianteId;
            document.getElementById('estudiante_nombre').value = estudianteNombre;
            document.getElementById('citaModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('citaModal').classList.remove('show');
            document.getElementById('citaForm').reset();
        }

        function submitCita(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = {
                estudiante_id: formData.get('estudiante_id'),
                fecha: formData.get('fecha'),
                hora: formData.get('hora'),
                motivo: formData.get('motivo')
            };

            console.log('Datos de la cita:', data);
            
            // Aquí iría tu lógica para enviar los datos al servidor
            // Por ahora solo mostramos un mensaje
            alert('Cita agendada exitosamente!\nFecha: ' + data.fecha + '\nHora: ' + data.hora);
            closeModal();
        }

        function verDetalles(estudianteId) {
            console.log('Ver detalles del estudiante:', estudianteId);
            // Aquí puedes redirigir o mostrar más información
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('citaModal').onclick = function(event) {
            if (event.target === this) {
                closeModal();
            }
        }
    </script>
</body>
</html>