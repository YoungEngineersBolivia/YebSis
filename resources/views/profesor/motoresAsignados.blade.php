@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-bottom: 80px;
    }

    .container-mobile {
        max-width: 100%;
        padding: 15px;
    }

    .header-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .header-title h1 {
        font-size: 22px;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-subtitle {
        font-size: 14px;
        opacity: 0.9;
    }

    /* Estadísticas rápidas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .stat-card i {
        font-size: 24px;
        color: #667eea;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        margin-top: 4px;
    }

    /* Tabs de filtro */
    .tabs-container {
        background: white;
        padding: 12px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .tabs::-webkit-scrollbar {
        display: none;
    }

    .tab-btn {
        padding: 10px 20px;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.3s;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    /* Tarjetas de motores asignados */
    .motor-asignado-card {
        background: white;
        border-radius: 15px;
        padding: 18px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }

    .motor-asignado-card:active {
        transform: scale(0.98);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .motor-id-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: bold;
    }

    .estado-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .estado-proceso {
        background-color: #fff3cd;
        color: #856404;
    }

    .estado-completado {
        background-color: #d4edda;
        color: #155724;
    }

    .info-grid {
        display: grid;
        gap: 12px;
        margin-bottom: 15px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        color: #667eea;
        font-size: 16px;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 12px;
        color: #999;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 14px;
        color: #333;
        font-weight: 500;
    }

    /* Timeline de fechas */
    .date-timeline {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
    }

    .timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #667eea;
    }

    .timeline-content {
        flex: 1;
        font-size: 13px;
    }

    .timeline-label {
        color: #666;
    }

    .timeline-value {
        color: #333;
        font-weight: 600;
    }

    /* Botones de acción */
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .btn-action {
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .btn-action:active {
        transform: translateY(2px);
    }

    /* Modal estilos */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 9998;
        animation: fadeIn 0.3s;
    }

    .modal-container {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-radius: 25px 25px 0 0;
        z-index: 9999;
        animation: slideUp 0.3s ease-out;
        max-height: 90vh;
        overflow-y: auto;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #999;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 15px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .modal-footer {
        padding: 20px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        gap: 10px;
        position: sticky;
        bottom: 0;
        background: white;
    }

    .btn-cancel {
        flex: 1;
        padding: 14px;
        background: #f0f0f0;
        color: #666;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-submit {
        flex: 2;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #ddd;
    }

    .empty-state p {
        font-size: 16px;
        margin: 0;
    }

    /* Alertas */
    .alert-mobile {
        position: fixed;
        top: 15px;
        left: 15px;
        right: 15px;
        padding: 15px 20px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert-close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    /* Observaciones colapsables */
    .observaciones-box {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 10px;
        margin-top: 10px;
    }

    .observaciones-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .observaciones-text {
        font-size: 13px;
        color: #333;
        line-height: 1.5;
    }
</style>
@endsection

@section('content')
<div class="container-mobile">
    <!-- Alertas -->
    @if(session('success'))
        <div class="alert-mobile alert-success" id="alertSuccess">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="closeAlert('alertSuccess')">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-mobile alert-error" id="alertError">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button class="alert-close" onclick="closeAlert('alertError')">×</button>
        </div>
    @endif

    <!-- Header -->
    <div class="header-title">
        <h1>
            <i class="fas fa-tasks"></i>
            Mis Motores Asignados
        </h1>
        <div class="header-subtitle">Gestiona tus reparaciones</div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-cog"></i>
            <div class="stat-value">{{ $asignaciones->where('Estado_asignacion', 'En Proceso')->count() }}</div>
            <div class="stat-label">En Proceso</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-check-circle"></i>
            <div class="stat-value">{{ $asignaciones->where('Estado_asignacion', 'Completado')->count() }}</div>
            <div class="stat-label">Completados</div>
        </div>
    </div>

    <!-- Tabs de filtro -->
    <div class="tabs-container">
        <div class="tabs">
            <button class="tab-btn active" onclick="filtrarPorEstado('todos')">
                Todos
            </button>
            <button class="tab-btn" onclick="filtrarPorEstado('En Proceso')">
                En Proceso
            </button>
            <button class="tab-btn" onclick="filtrarPorEstado('Completado')">
                Completados
            </button>
        </div>
    </div>

    <!-- Lista de Motores Asignados -->
    <div id="motorsContainer">
        @forelse($asignaciones as $asignacion)
        <div class="motor-asignado-card" data-estado="{{ $asignacion->Estado_asignacion }}">
            <div class="card-header">
                <div class="motor-id-badge">
                    <i class="fas fa-cog"></i> {{ $asignacion->Id_motor }}
                </div>
                <span class="estado-badge estado-{{ strtolower(str_replace(' ', '', $asignacion->Estado_asignacion)) }}">
                    {{ $asignacion->Estado_asignacion }}
                </span>
            </div>

            <!-- Timeline de fechas -->
            <div class="date-timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-label">Fecha asignación</div>
                        <div class="timeline-value">{{ \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y') }}</div>
                    </div>
                </div>
                @if($asignacion->Fecha_entrega)
                <div class="timeline-item">
                    <div class="timeline-dot" style="background: #28a745;"></div>
                    <div class="timeline-content">
                        <div class="timeline-label">Fecha entrega</div>
                        <div class="timeline-value">{{ \Carbon\Carbon::parse($asignacion->Fecha_entrega)->format('d/m/Y') }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Información del motor -->
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Sucursal</div>
                        <div class="info-value">{{ $asignacion->Nombre_sucursal ?? 'No asignada' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Estado del motor</div>
                        <div class="info-value">{{ $asignacion->Estado_motor }}</div>
                    </div>
                </div>

                @if($asignacion->Observacion_inicial)
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Observaciones iniciales</div>
                        <div class="info-value">{{ $asignacion->Observacion_inicial }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Botones de acción -->
            <div class="action-buttons">
                <button class="btn-action btn-primary" onclick="verDetalle({{ $asignacion->Id_motores_asignados }})">
                    <i class="fas fa-eye"></i>
                    Ver Detalle
                </button>
                @if($asignacion->Estado_asignacion === 'En Proceso')
                <button class="btn-action btn-success" onclick="abrirModalDevolucion({{ $asignacion->Id_motores_asignados }}, '{{ $asignacion->Id_motor }}')">
                    <i class="fas fa-check"></i>
                    Devolver
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No tienes motores asignados</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Devolver Motor -->
<div class="modal-overlay" id="modalOverlay" onclick="cerrarModal()"></div>
<div class="modal-container" id="modalDevolucion">
    <div class="modal-header">
        <h3><i class="fas fa-check-circle"></i> Devolver Motor</h3>
        <button class="modal-close" onclick="cerrarModal()">×</button>
    </div>
    <form method="POST" id="formDevolucion">
        @csrf
        <input type="hidden" name="_method" value="POST">
        
        <div class="modal-body">
            <div class="alert-mobile alert-success" style="position: relative; margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i>
                <span>Motor: <strong id="motorIdDevolucion"></strong></span>
            </div>

            <div class="form-group">
                <label class="form-label">Sucursal de Devolución *</label>
                <select class="form-control" name="Id_sucursales" required>
                    <option value="">Seleccionar sucursal...</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->Id_Sucursales }}">{{ $sucursal->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Estado Final del Motor *</label>
                <select class="form-control" name="estado_final" required>
                    <option value="">Seleccionar estado...</option>
                    <option value="Funcionando">Funcionando</option>
                    <option value="Descompuesto">Descompuesto</option>
                    <option value="En proceso">En proceso</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Observaciones de Devolución *</label>
                <textarea class="form-control" 
                          name="observaciones_devolucion" 
                          placeholder="Describe el trabajo realizado y el estado del motor..."
                          required></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="cerrarModal()">
                Cancelar
            </button>
            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Confirmar Devolución
            </button>
        </div>
    </form>
</div>

<!-- Modal Detalle -->
<div class="modal-container" id="modalDetalle">
    <div class="modal-header">
        <h3><i class="fas fa-info-circle"></i> Detalle Completo</h3>
        <button class="modal-close" onclick="cerrarModalDetalle()">×</button>
    </div>
    <div class="modal-body" id="detalleContent">
        <!-- Contenido dinámico -->
    </div>
    <div class="modal-footer">
        <button type="button" class="btn-cancel" style="flex: 1;" onclick="cerrarModalDetalle()">
            Cerrar
        </button>
    </div>
</div>

<script>
    // Cerrar alertas automáticamente
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-mobile');
        alerts.forEach(alert => {
            if (alert) {
                alert.style.animation = 'slideDown 0.3s ease-out reverse';
                setTimeout(() => alert.remove(), 300);
            }
        });
    }, 5000);

    function closeAlert(id) {
        const alert = document.getElementById(id);
        if (alert) {
            alert.style.animation = 'slideDown 0.3s ease-out reverse';
            setTimeout(() => alert.remove(), 300);
        }
    }

    // Filtrar por estado
    function filtrarPorEstado(estado) {
        const cards = document.querySelectorAll('.motor-asignado-card');
        const tabs = document.querySelectorAll('.tab-btn');
        
        // Actualizar tabs activos
        tabs.forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        
        // Filtrar cards
        cards.forEach(card => {
            if (estado === 'todos' || card.dataset.estado === estado) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Abrir modal de devolución
    function abrirModalDevolucion(asignacionId, motorId) {
        document.getElementById('motorIdDevolucion').textContent = motorId;
        document.getElementById('formDevolucion').action = `/profesor/inventario-componentes/${asignacionId}/devolver`;
        document.getElementById('modalOverlay').style.display = 'block';
        document.getElementById('modalDevolucion').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Cerrar modal
    function cerrarModal() {
        document.getElementById('modalOverlay').style.display = 'none';
        document.getElementById('modalDevolucion').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('formDevolucion').reset();
    }

    // Ver detalle
    async function verDetalle(asignacionId) {
        try {
            const response = await fetch(`/profesor/inventario-componentes/${asignacionId}/detalle`);
            const data = await response.json();
            
            if (data.error) {
                alert(data.error);
                return;
            }
            
            mostrarDetalle(data);
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar el detalle');
        }
    }

    function mostrarDetalle(data) {
        const asig = data.asignacion;
        const movs = data.movimientos;
        
        let html = `
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-cog"></i></div>
                    <div class="info-content">
                        <div class="info-label">Motor</div>
                        <div class="info-value">${asig.Id_motor}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-user"></i></div>
                    <div class="info-content">
                        <div class="info-label">Profesor</div>
                        <div class="info-value">${asig.Nombre_profesor}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="info-content">
                        <div class="info-label">Sucursal</div>
                        <div class="info-value">${asig.Nombre_sucursal || 'N/A'}</div>
                    </div>
                </div>
            </div>
            
            <h4 style="margin: 20px 0 10px; font-size: 16px;">Historial de Movimientos</h4>
        `;
        
        if (movs.length > 0) {
            movs.forEach(mov => {
                html += `
                    <div class="observaciones-box" style="margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <strong>${mov.Tipo_movimiento}</strong>
                            <span style="font-size: 12px; color: #666;">${new Date(mov.Fecha).toLocaleDateString()}</span>
                        </div>
                        <div style="font-size: 13px; color: #666;">
                            ${mov.Estado_ubicacion} - ${mov.Nombre_sucursal || 'N/A'}
                        </div>
                        ${mov.Observacion ? `<div style="font-size: 12px; color: #999; margin-top: 5px;">${mov.Observacion}</div>` : ''}
                    </div>
                `;
            });
        } else {
            html += '<p style="color: #999; text-align: center; padding: 20px;">No hay movimientos registrados</p>';
        }
        
        document.getElementById('detalleContent').innerHTML = html;
        document.getElementById('modalOverlay').style.display = 'block';
        document.getElementById('modalDetalle').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalDetalle() {
        document.getElementById('modalOverlay').style.display = 'none';
        document.getElementById('modalDetalle').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Prevenir cierre al hacer click dentro del modal
    document.getElementById('modalDevolucion').addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    document.getElementById('modalDetalle').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal();
            cerrarModalDetalle();
        }
    });
</script>
@endsection