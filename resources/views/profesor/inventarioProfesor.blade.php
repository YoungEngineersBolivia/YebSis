<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventario de Motores</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .header h1 {
            color: #667eea;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .search-box {
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .filter-tabs::-webkit-scrollbar {
            height: 4px;
        }

        .filter-tabs::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: #667eea;
            color: white;
        }

        .motor-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .motor-card:active {
            transform: scale(0.98);
        }

        .motor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .motor-id {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .estado-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .estado-funcionando {
            background: #d4edda;
            color: #155724;
        }

        .estado-descompuesto {
            background: #f8d7da;
            color: #721c24;
        }

        .estado-en-proceso {
            background: #fff3cd;
            color: #856404;
        }

        .motor-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-row {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .info-row svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            color: #667eea;
            flex-shrink: 0;
        }

        .info-label {
            font-weight: 600;
            margin-right: 5px;
        }

        .observacion {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 13px;
            color: #555;
        }

        .no-results {
            text-align: center;
            color: white;
            font-size: 16px;
            margin-top: 40px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .loading {
            text-align: center;
            color: white;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="{{ route('profesor.home') }}" class="back-btn">
                ← Volver
            </a>
            <h1>📦 Inventario de Motores</h1>
            <p>Gestión de componentes</p>
        </div>

        <!-- Estadísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['funcionando'] }}</div>
                <div class="stat-label">Funcionando</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['descompuesto'] }}</div>
                <div class="stat-label">Descompuestos</div>
            </div>
        </div>

        <!-- Búsqueda y Filtros -->
        <div class="search-box">
            <input type="text" class="search-input" id="searchInput" placeholder="🔍 Buscar motor...">
            <div class="filter-tabs">
                <button class="filter-btn active" data-filter="todos">Todos</button>
                <button class="filter-btn" data-filter="Funcionando">Funcionando</button>
                <button class="filter-btn" data-filter="Descompuesto">Descompuesto</button>
                <button class="filter-btn" data-filter="En Proceso">En Proceso</button>
            </div>
        </div>

        <!-- Lista de Motores -->
        <div id="motoresContainer">
            @foreach($motores as $motor)
            <div class="motor-card">
                <div class="motor-header">
                    <div class="motor-id">Motor {{ $motor->Id_motor }}</div>
                    <span class="estado-badge estado-{{ strtolower(str_replace(' ', '-', $motor->Estado)) }}">
                        {{ $motor->Estado }}
                    </span>
                </div>
                <div class="motor-info">
                    <div class="info-row">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="info-label">Ubicación:</span> 
                        {{ $motor->sucursal ? $motor->sucursal->Nombre : 'Sin asignar' }}
                    </div>
                    <div class="info-row">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="info-label">Actualizado:</span> 
                        {{ $motor->updated_at->format('d/m/Y') }}
                    </div>
                </div>
                @if($motor->Observacion)
                <div class="observacion">
                    <strong>📝 Observación:</strong><br>
                    {{ $motor->Observacion }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <div id="noResults" class="no-results" style="display: none;">
            No se encontraron motores
        </div>
    </div>

    <script>
        let motoresOriginales = @json($motores->map(function($motor) {
            return [
                'id_motor' => $motor->Id_motor,
                'estado' => $motor->Estado,
                'sucursal' => $motor->sucursal ? $motor->sucursal->Nombre : 'Sin asignar',
                'observacion' => $motor->Observacion,
                'updated_at' => $motor->updated_at->format('d/m/Y')
            ];
        }));

        let currentFilter = 'todos';

        // Función para renderizar motores
        function renderMotores(motores) {
            const container = document.getElementById('motoresContainer');
            const noResults = document.getElementById('noResults');

            if (motores.length === 0) {
                container.innerHTML = '';
                noResults.style.display = 'block';
                return;
            }

            noResults.style.display = 'none';
            container.innerHTML = motores.map(motor => `
                <div class="motor-card">
                    <div class="motor-header">
                        <div class="motor-id">Motor ${motor.id_motor}</div>
                        <span class="estado-badge estado-${motor.estado.toLowerCase().replace(' ', '-')}">
                            ${motor.estado}
                        </span>
                    </div>
                    <div class="motor-info">
                        <div class="info-row">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="info-label">Ubicación:</span> ${motor.sucursal}
                        </div>
                        <div class="info-row">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="info-label">Actualizado:</span> ${motor.updated_at}
                        </div>
                    </div>
                    ${motor.observacion ? `
                        <div class="observacion">
                            <strong>📝 Observación:</strong><br>
                            ${motor.observacion}
                        </div>
                    ` : ''}
                </div>
            `).join('');
        }

        // Función para filtrar motores
        function filterMotores() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            let filteredMotores = motoresOriginales.filter(motor => {
                const matchesSearch = motor.id_motor.toLowerCase().includes(searchTerm) ||
                                     motor.sucursal.toLowerCase().includes(searchTerm) ||
                                     (motor.observacion && motor.observacion.toLowerCase().includes(searchTerm));
                
                const matchesFilter = currentFilter === 'todos' || motor.estado === currentFilter;
                
                return matchesSearch && matchesFilter;
            });

            renderMotores(filteredMotores);
        }

        // Event listeners
        document.getElementById('searchInput').addEventListener('input', filterMotores);

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                filterMotores();
            });
        });
    </script>
</body>
</html>