@extends('profesor.baseProfesor')

@section('title', 'Inventario de Componentes')

@section('content')
<style>
    .mobile-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 100vh;
    }

    .mobile-header {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    }

    .motor-card {
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .motor-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.75em;
        font-weight: 600;
    }

    .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
    }

    .alert-light {
        border-left: 3px solid #0d6efd;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .motor-card {
        animation: fadeInUp 0.5s ease;
    }

    @media (max-width: 576px) {
        .mobile-header h5 {
            font-size: 1.1rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }

        .btn-lg {
            font-size: 0.95rem;
            padding: 0.65rem 0.9rem;
        }
    }
</style>

<div class="mobile-container">
    <!-- Header -->
    <div class="mobile-header bg-primary text-white p-3 sticky-top">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">
                    <i class="bi bi-box-seam"></i> Inventario
                </h5>
                <small>{{ $profesor->persona->Nombre }}</small>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-primary">
                    {{ $motores->count() }} Motores
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="p-3 bg-light border-bottom">
        <div class="input-group mb-2">
            <span class="input-group-text bg-white">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" id="buscarMotor" 
                   placeholder="Buscar por ID del motor...">
        </div>
        <select class="form-select" id="filtroEstado">
            <option value="">📋 Todos los estados</option>
            <option value="Disponible">✅ Disponible</option>
            <option value="Descompuesto">❌ Descompuesto</option>
            <option value="Funcionando">✔️ Funcionando</option>
        </select>
    </div>

    <!-- Lista de Motores -->
    <div class="p-3">
        <div id="listaMotores">
            @forelse($motores as $motor)
            <div class="card mb-3 shadow-sm motor-card" 
                 data-id="{{ $motor->Id_motor }}" 
                 data-estado="{{ $motor->Estado }}">
                <div class="card-body p-3">
                    <!-- Cabecera del Motor -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="mb-1">
                                <i class="bi bi-gear-fill text-primary"></i>
                                <strong>{{ $motor->Id_motor }}</strong>
                            </h6>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-geo-alt-fill"></i>
                                {{ $motor->sucursal->Nombre ?? 'Sin sucursal' }}
                            </p>
                        </div>
                        <div>
                            @php
                                $badges = [
                                    'Disponible' => ['class' => 'bg-success', 'icon' => '✓'],
                                    'Descompuesto' => ['class' => 'bg-danger', 'icon' => '✗'],
                                    'Funcionando' => ['class' => 'bg-primary', 'icon' => '⚙'],
                                    'En Reparacion' => ['class' => 'bg-warning text-dark', 'icon' => '🔧']
                                ];
                                $badge = $badges[$motor->Estado] ?? ['class' => 'bg-secondary', 'icon' => '•'];
                            @endphp
                            <span class="badge {{ $badge['class'] }}">
                                {{ $badge['icon'] }} {{ $motor->Estado }}
                            </span>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($motor->Observacion)
                    <div class="alert alert-light py-2 mb-3">
                        <p class="small mb-0">
                            <i class="bi bi-chat-left-text text-muted"></i>
                            <strong>Observación:</strong><br>
                            <span class="text-muted">{{ $motor->Observacion }}</span>
                        </p>
                    </div>
                    @endif

                    <!-- Botón de Acción -->
                    <div class="d-grid">
                        <button type="button" 
                                class="btn btn-primary btn-lg btn-solicitar" 
                                data-motor-id="{{ $motor->Id_motores }}"
                                data-motor-display="{{ $motor->Id_motor }}"
                                data-motor-estado="{{ $motor->Estado }}">
                            <i class="bi bi-send-fill"></i>
                            Solicitar Salida
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay motores en inventario</h5>
                <p class="text-muted">No se encontraron motores disponibles</p>
            </div>
            @endforelse
        </div>

        <!-- Mensaje cuando no hay resultados de búsqueda -->
        <div id="noResults" class="text-center py-5 d-none">
            <i class="bi bi-search display-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No se encontraron resultados</h5>
            <p class="text-muted">Intenta con otro término de búsqueda</p>
        </div>
    </div>
</div>

<!-- Modal Solicitar Salida -->
<div class="modal fade" id="modalSolicitarSalida" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('profesor.componentes.solicitar-salida') }}" method="POST" id="formSolicitud">
                @csrf
                <input type="hidden" name="Id_motores" id="modal_motor_id">
                
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">
                        <i class="bi bi-send-fill"></i>
                        Solicitar Salida de Motor
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-6">
                                <strong>Motor:</strong><br>
                                <span id="modal_motor_display" class="h5"></span>
                            </div>
                            <div class="col-6">
                                <strong>Estado:</strong><br>
                                <span id="modal_estado_display"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-pencil-square"></i>
                            Motivo de Salida *
                        </label>
                        <textarea class="form-control" 
                                  name="Motivo_salida" 
                                  id="motivoSalida"
                                  rows="5" 
                                  required
                                  placeholder="Describe detalladamente el motivo por el cual el motor necesita salir del inventario...

Ejemplo:
- Motor presenta fallas en el circuito principal
- Se detectó sobrecalentamiento
- Requiere mantenimiento preventivo"></textarea>
                        <div class="form-text">
                            <span id="charCount">0</span> / 500 caracteres
                            <span id="charWarning" class="text-danger d-none">⚠️ Mínimo 10 caracteres</span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnEnviar" disabled>
                        <i class="bi bi-check2-circle"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda y filtrado
        const buscarMotor = document.getElementById('buscarMotor');
        const filtroEstado = document.getElementById('filtroEstado');
        
        function filtrarMotores() {
            const buscar = buscarMotor.value.toLowerCase().trim();
            const estado = filtroEstado.value.toLowerCase();
            
            let visibleCount = 0;
            const motorCards = document.querySelectorAll('.motor-card');

            motorCards.forEach(function(card) {
                const motorId = card.getAttribute('data-id').toLowerCase();
                const motorEstado = card.getAttribute('data-estado').toLowerCase();

                let mostrar = true;

                if (buscar && !motorId.includes(buscar)) {
                    mostrar = false;
                }

                if (estado && motorEstado !== estado) {
                    mostrar = false;
                }

                if (mostrar) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Mostrar mensaje si no hay resultados
            const noResults = document.getElementById('noResults');
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }

        buscarMotor.addEventListener('input', filtrarMotores);
        filtroEstado.addEventListener('change', filtrarMotores);

        // Botones de solicitar salida
        const botonesSolicitar = document.querySelectorAll('.btn-solicitar');
        const modalElement = document.getElementById('modalSolicitarSalida');
        const modal = new bootstrap.Modal(modalElement);
        
        botonesSolicitar.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const motorId = this.getAttribute('data-motor-id');
                const motorDisplay = this.getAttribute('data-motor-display');
                const motorEstado = this.getAttribute('data-motor-estado');
                
                document.getElementById('modal_motor_id').value = motorId;
                document.getElementById('modal_motor_display').textContent = motorDisplay;
                
                // Badge con color según estado
                const badgeColors = {
                    'Disponible': 'success',
                    'Descompuesto': 'danger',
                    'Funcionando': 'primary',
                    'En Reparacion': 'warning text-dark'
                };
                const badgeClass = badgeColors[motorEstado] || 'secondary';
                
                document.getElementById('modal_estado_display').innerHTML = 
                    '<span class="badge bg-' + badgeClass + '">' + motorEstado + '</span>';
                
                modal.show();
                
                // Focus en el textarea después de un delay
                setTimeout(function() {
                    document.getElementById('motivoSalida').focus();
                }, 500);
            });
        });

        // Contador de caracteres
        const motivoSalida = document.getElementById('motivoSalida');
        const charCount = document.getElementById('charCount');
        const charWarning = document.getElementById('charWarning');
        const btnEnviar = document.getElementById('btnEnviar');
        
        motivoSalida.addEventListener('input', function() {
            const length = this.value.length;
            
            charCount.textContent = length;
            
            if (length < 10) {
                btnEnviar.disabled = true;
                charWarning.classList.remove('d-none');
            } else {
                btnEnviar.disabled = false;
                charWarning.classList.add('d-none');
            }

            // Límite máximo
            if (length > 500) {
                this.value = this.value.substring(0, 500);
                charCount.textContent = 500;
            }
        });

        // Resetear modal al cerrar
        modalElement.addEventListener('hidden.bs.modal', function () {
            document.getElementById('formSolicitud').reset();
            charCount.textContent = 0;
            charWarning.classList.add('d-none');
            btnEnviar.disabled = true;
        });
    });
</script>
@endsection