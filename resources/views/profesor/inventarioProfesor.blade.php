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
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-container {
        background: white;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .search-box {
        position: relative;
        width: 100%;
    }

    .search-box input {
        width: 100%;
        padding: 12px 45px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        font-size: 15px;
        transition: all 0.3s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
    }

    .search-box .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 18px;
    }

    .search-box .clear-btn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        font-size: 18px;
        cursor: pointer;
        display: none;
    }

    .motor-card {
        background: white;
        border-radius: 15px;
        padding: 18px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .motor-card:active {
        transform: scale(0.98);
    }

    .motor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .motor-id {
        font-size: 20px;
        font-weight: bold;
        color: #333;
    }

    .motor-estado {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .estado-funcionando {
        background-color: #d4edda;
        color: #155724;
    }

    .estado-descompuesto {
        background-color: #f8d7da;
        color: #721c24;
    }

    .estado-proceso {
        background-color: #fff3cd;
        color: #856404;
    }

    .motor-info {
        margin-bottom: 15px;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 8px 0;
        font-size: 14px;
        color: #666;
    }

    .info-row i {
        width: 30px;
        color: #667eea;
        font-size: 16px;
    }

    .info-row strong {
        color: #333;
        margin-right: 5px;
    }

    .btn-solicitar {
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }

    .btn-solicitar:active {
        transform: translateY(2px);
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    }

    .btn-solicitar:disabled {
        background: #ccc;
        cursor: not-allowed;
        box-shadow: none;
    }

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

    /* Modal Styles */
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

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
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

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
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
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
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

    .loading {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
            <i class="fas fa-cogs"></i>
            Inventario de Motores
        </h1>
    </div>

    <!-- Búsqueda -->
    <div class="search-container">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" 
                   id="searchInput" 
                   placeholder="Buscar por ID de motor..."
                   onkeyup="filterMotors()">
            <button class="clear-btn" id="clearBtn" onclick="clearSearch()">×</button>
        </div>
    </div>

    <!-- Lista de Motores -->
    <div id="motorsContainer">
        @forelse($motoresDisponibles as $motor)
        <div class="motor-card" data-motor-id="{{ $motor->Id_motor }}">
            <div class="motor-header">
                <div class="motor-id">Motor {{ $motor->Id_motor }}</div>
                <span class="motor-estado estado-{{ strtolower($motor->Estado) }}">
                    {{ $motor->Estado }}
                </span>
            </div>
            
            <div class="motor-info">
                <div class="info-row">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Sucursal:</strong> {{ $motor->Nombre_sucursal ?? 'No asignada' }}
                    </div>
                </div>
                <div class="info-row">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Estado:</strong> Disponible para solicitud
                    </div>
                </div>
            </div>

            <button class="btn-solicitar" onclick="abrirModalSolicitud({{ $motor->Id_motores }}, '{{ $motor->Id_motor }}')">
                <i class="fas fa-paper-plane"></i>
                Solicitar Salida
            </button>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>No hay motores disponibles</p>
        </div>
        @endforelse
    </div>

    <!-- Estado vacío cuando no hay resultados de búsqueda -->
    <div class="empty-state" id="emptySearch" style="display: none;">
        <i class="fas fa-search"></i>
        <p>No se encontraron motores</p>
    </div>
</div>

<!-- Modal Solicitar Salida -->
<div class="modal-overlay" id="modalOverlay" onclick="cerrarModal()"></div>
<div class="modal-container" id="modalSolicitud">
    <div class="modal-header">
        <h3><i class="fas fa-paper-plane"></i> Solicitar Salida</h3>
        <button class="modal-close" onclick="cerrarModal()">×</button>
    </div>
    <form method="POST" action="{{ route('profesor.inventario.solicitar') }}" id="formSolicitud">
        @csrf
        <input type="hidden" name="Id_motores" id="motorIdInput">
        
        <div class="modal-body">
            <div class="alert-mobile alert-success" style="position: relative; margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i>
                <span>Motor: <strong id="motorIdText"></strong></span>
            </div>

            <div class="form-group">
                <label class="form-label">Motivo de la Solicitud *</label>
                <textarea class="form-control" 
                          name="motivo" 
                          placeholder="Describe por qué necesitas este motor..."
                          required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Observaciones Adicionales</label>
                <textarea class="form-control" 
                          name="observaciones" 
                          placeholder="Información adicional (opcional)..."></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="cerrarModal()">
                Cancelar
            </button>
            <button type="submit" class="btn-submit">
                <i class="fas fa-check"></i> Enviar Solicitud
            </button>
        </div>
    </form>

    <div class="loading" id="loadingIndicator">
        <div class="spinner"></div>
        <p>Enviando solicitud...</p>
    </div>
</div>

<script>
    // Cerrar alertas automáticamente después de 5 segundos
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

    function abrirModalSolicitud(motorId, motorIdText) {
        document.getElementById('motorIdInput').value = motorId;
        document.getElementById('motorIdText').textContent = motorIdText;
        document.getElementById('modalOverlay').style.display = 'block';
        document.getElementById('modalSolicitud').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function cerrarModal() {
        document.getElementById('modalOverlay').style.display = 'none';
        document.getElementById('modalSolicitud').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('formSolicitud').reset();
    }

    function filterMotors() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const clearBtn = document.getElementById('clearBtn');
        const motorsContainer = document.getElementById('motorsContainer');
        const emptySearch = document.getElementById('emptySearch');
        const motorCards = document.querySelectorAll('.motor-card');
        
        // Mostrar/ocultar botón de limpiar
        clearBtn.style.display = searchValue ? 'block' : 'none';
        
        let visibleCount = 0;
        
        motorCards.forEach(card => {
            const motorId = card.getAttribute('data-motor-id').toLowerCase();
            if (motorId.includes(searchValue)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        if (visibleCount === 0 && searchValue) {
            motorsContainer.style.display = 'none';
            emptySearch.style.display = 'block';
        } else {
            motorsContainer.style.display = 'block';
            emptySearch.style.display = 'none';
        }
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        filterMotors();
    }

    // Prevenir cierre del modal al hacer click dentro
    document.getElementById('modalSolicitud').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Mostrar loading al enviar formulario
    document.getElementById('formSolicitud').addEventListener('submit', function() {
        document.querySelector('.modal-body').style.display = 'none';
        document.querySelector('.modal-footer').style.display = 'none';
        document.getElementById('loadingIndicator').style.display = 'block';
    });

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModal();
        }
    });
</script>
@endsection