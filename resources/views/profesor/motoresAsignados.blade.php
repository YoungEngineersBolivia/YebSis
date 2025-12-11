@extends('profesor.baseProfesor')

@section('title', 'Mis Reparaciones')

@section('content')
<div class="mobile-container">
    <!-- Header -->
    <div class="mobile-header bg-warning text-dark p-3 sticky-top">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">
                    <i class="bi bi-tools"></i> Mis Reparaciones
                </h5>
                <small>{{ $profesor->persona->Nombre }} {{ $profesor->persona->Apellido }}</small>
            </div>
            <div class="text-end">
                <span class="badge bg-dark">
                    {{ $asignaciones->count() }} Activa{{ $asignaciones->count() != 1 ? 's' : '' }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Lista de Motores Asignados -->
    <div class="p-3">
        @forelse($asignaciones as $asignacion)
        <div class="card mb-3 shadow-sm asignacion-card">
            <div class="card-body p-3">
                <!-- Header del Motor -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">
                            <i class="bi bi-gear-fill text-warning"></i>
                            <strong>{{ $asignacion->motor->Id_motor }}</strong>
                        </h6>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-calendar3"></i>
                            Asignado: {{ $asignacion->Fecha_salida->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $asignacion->motor->sucursal->Nombre ?? 'Sin sucursal' }}
                        </p>
                    </div>
                    <div>
                        @php
                            $ultimoReporte = $asignacion->reportesProgreso->first();
                            $estadoActual = $ultimoReporte ? $ultimoReporte->Estado_actual : 'Sin Iniciar';
                            $badges = [
                                'Sin Iniciar' => ['class' => 'bg-secondary', 'icon' => '⏸'],
                                'En Diagnostico' => ['class' => 'bg-info', 'icon' => '🔍'],
                                'En Reparacion' => ['class' => 'bg-warning text-dark', 'icon' => '🔧'],
                                'Reparado' => ['class' => 'bg-success', 'icon' => '✓'],
                                'Irreparable' => ['class' => 'bg-danger', 'icon' => '✗']
                            ];
                            $badge = $badges[$estadoActual] ?? ['class' => 'bg-secondary', 'icon' => '•'];
                        @endphp
                        <span class="badge {{ $badge['class'] }}">
                            {{ $badge['icon'] }} {{ $estadoActual }}
                        </span>
                    </div>
                </div>

                <!-- Información Inicial -->
                <div class="alert alert-light border-start border-warning border-4 mb-3">
                    <p class="small mb-1">
                        <strong><i class="bi bi-info-circle-fill text-warning"></i> Estado al Salir:</strong> 
                        <span class="badge bg-secondary">{{ $asignacion->Estado_motor_salida }}</span>
                    </p>
                    <p class="small mb-0">
                        <strong><i class="bi bi-chat-left-text-fill text-warning"></i> Motivo:</strong><br>
                        <span class="text-muted">{{ $asignacion->Motivo_salida }}</span>
                    </p>
                </div>

                <!-- Último Reporte -->
                @if($ultimoReporte)
                <div class="alert alert-info border-start border-info border-4 mb-3">
                    <p class="small mb-1">
                        <strong><i class="bi bi-clipboard-check-fill"></i> Último Reporte:</strong>
                    </p>
                    <p class="small mb-2">{{ $ultimoReporte->Descripcion_trabajo }}</p>
                    <small class="text-muted">
                        <i class="bi bi-clock-fill"></i>
                        {{ $ultimoReporte->Fecha_reporte->format('d/m/Y H:i') }}
                    </small>
                    @if($ultimoReporte->Observaciones)
                    <p class="small mb-0 mt-2">
                        <em class="text-muted">{{ $ultimoReporte->Observaciones }}</em>
                    </p>
                    @endif
                </div>
                @else
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <strong>Sin reportes aún.</strong> Actualiza el estado del motor.
                </div>
                @endif

                <!-- Días transcurridos -->
                <div class="text-center mb-3">
                    @php
                        $diasTranscurridos = $asignacion->Fecha_salida->diffInDays(now());
                    @endphp
                    <small class="badge bg-light text-dark">
                        <i class="bi bi-calendar-week"></i>
                        {{ $diasTranscurridos }} día{{ $diasTranscurridos != 1 ? 's' : '' }} en reparación
                    </small>
                </div>

                <!-- Botones de Acción -->
                <div class="d-grid gap-2">
                    <button type="button" 
                            class="btn btn-info" 
                            onclick="actualizarEstado({{ $asignacion->Id_asignacion }}, '{{ $asignacion->motor->Id_motor }}')">
                        <i class="bi bi-pencil-square"></i>
                        Actualizar Estado
                    </button>
                    
                    @if($ultimoReporte && $ultimoReporte->Estado_actual == 'Reparado')
                    <button type="button" 
                            class="btn btn-success" 
                            onclick="entregarMotor({{ $asignacion->Id_asignacion }}, '{{ $asignacion->motor->Id_motor }}')">
                        <i class="bi bi-check-circle-fill"></i>
                        Entregar Motor Reparado
                    </button>
                    @endif

                    @if($asignacion->reportesProgreso->count() > 0)
                    <button type="button" 
                            class="btn btn-outline-secondary" 
                            onclick="verHistorialReportes({{ $asignacion->Id_asignacion }})">
                        <i class="bi bi-clock-history"></i>
                        Ver Historial ({{ $asignacion->reportesProgreso->count() }})
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-check2-circle display-1 text-success"></i>
            <h5 class="mt-3">¡Todo al día!</h5>
            <p class="text-muted">No tienes reparaciones activas<br>Los motores asignados aparecerán aquí</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Actualizar Estado -->
<div class="modal fade" id="modalActualizarEstado" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <form action="{{ route('profesor.componentes.actualizar-estado') }}" method="POST" id="formActualizar">
                @csrf
                <input type="hidden" name="Id_asignacion" id="estado_asignacion_id">
                
                <div class="modal-header bg-info text-white">
                    <h6 class="modal-title">
                        <i class="bi bi-pencil-square"></i> Actualizar Estado
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Motor:</strong> <span id="estado_motor_display" class="h6"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado Actual *</label>
                        <select class="form-select form-select-lg" name="Estado_actual" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="En Diagnostico">🔍 En Diagnóstico</option>
                            <option value="En Reparacion">🔧 En Reparación</option>
                            <option value="Reparado">✅ Reparado</option>
                            <option value="Irreparable">❌ Irreparable</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción del Trabajo *</label>
                        <textarea class="form-control" 
                                  name="Descripcion_trabajo" 
                                  rows="6" 
                                  required
                                  placeholder="Describe detalladamente el trabajo realizado...

Ejemplo:
• Se inició el diagnóstico del motor
• Se identificó el problema en el circuito X
• Se reemplazó la pieza Y
• Se realizaron pruebas de funcionamiento"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea class="form-control" 
                                  name="Observaciones" 
                                  rows="3"
                                  placeholder="Comentarios adicionales, materiales usados, recomendaciones..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-save-fill"></i> Guardar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Entregar Motor -->
<div class="modal fade" id="modalEntregarMotor" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <form action="{{ route('profesor.componentes.entregar-motor') }}" method="POST" id="formEntregar">
                @csrf
                <input type="hidden" name="Id_asignacion" id="entregar_asignacion_id">
                
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title">
                        <i class="bi bi-check-circle-fill"></i> Entregar Motor
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>Motor:</strong> <span id="entregar_motor_display" class="h6"></span><br>
                        <small>El motor será devuelto al inventario</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado Final del Motor *</label>
                        <select class="form-select form-select-lg" name="Estado_final" required>
                            <option value="">Seleccionar...</option>
                            <option value="Disponible">✅ Disponible (Reparado y listo)</option>
                            <option value="Funcionando">✔️ Funcionando (Sin problemas)</option>
                            <option value="Descompuesto">❌ Descompuesto (Irreparable)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Resumen Completo del Trabajo *</label>
                        <textarea class="form-control" 
                                  name="Trabajo_realizado" 
                                  rows="8" 
                                  required
                                  placeholder="Escribe un resumen completo y detallado de todo el trabajo realizado...

Ejemplo:
1. DIAGNÓSTICO:
   • Se identificó falla en el circuito principal
   
2. REPARACIÓN:
   • Se reemplazaron componentes X, Y y Z
   • Se limpiaron los contactos
   • Se aplicó soldadura nueva
   
3. PRUEBAS:
   • Se realizaron 3 pruebas de funcionamiento
   • Resultados: EXITOSOS
   
4. CONCLUSIÓN:
   • Motor funcionando correctamente
   • Listo para uso"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones / Recomendaciones</label>
                        <textarea class="form-control" 
                                  name="Observaciones" 
                                  rows="3"
                                  placeholder="Recomendaciones de uso, mantenimiento futuro, precauciones..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2"></i> Confirmar Entrega
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Historial -->
<div class="modal fade" id="modalHistorial" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h6 class="modal-title">
                    <i class="bi bi-clock-history"></i> Historial de Reportes
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="historialContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .mobile-container {
        background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
        min-height: 100vh;
    }

    .mobile-header {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    }

    .asignacion-card {
        border-left: 4px solid #ffc107;
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .asignacion-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.75em;
        font-weight: 600;
    }

    .btn {
        font-weight: 600;
        border-radius: 8px;
        padding: 0.65rem 1rem;
    }

    /* Animación */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .asignacion-card {
        animation: slideInUp 0.5s ease;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .mobile-header h5 {
            font-size: 1.1rem;
        }
        
        .btn {
            font-size: 0.9rem;
            padding: 0.6rem 0.85rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    const asignaciones = @json($asignaciones);

    // Función para actualizar estado
    function actualizarEstado(asignacionId, motorId) {
        document.getElementById('estado_asignacion_id').value = asignacionId;
        document.getElementById('estado_motor_display').textContent = motorId;
        
        const modal = new bootstrap.Modal(document.getElementById('modalActualizarEstado'));
        modal.show();
        
        setTimeout(() => {
            document.querySelector('select[name="Estado_actual"]').focus();
        }, 500);
    }

    // Función para entregar motor
    function entregarMotor(asignacionId, motorId) {
        document.getElementById('entregar_asignacion_id').value = asignacionId;
        document.getElementById('entregar_motor_display').textContent = motorId;
        
        const modal = new bootstrap.Modal(document.getElementById('modalEntregarMotor'));
        modal.show();
        
        setTimeout(() => {
            document.querySelector('select[name="Estado_final"]').focus();
        }, 500);
    }

    // Función para ver historial
    function verHistorialReportes(asignacionId) {
        const asignacion = asignaciones.find(a => a.Id_asignacion == asignacionId);
        
        if (!asignacion || !asignacion.reportes_progreso || asignacion.reportes_progreso.length === 0) {
            document.getElementById('historialContainer').innerHTML = `
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i>
                    No hay reportes para este motor.
                </div>
            `;
            const modal = new bootstrap.Modal(document.getElementById('modalHistorial'));
            modal.show();
            return;
        }

        let html = '<div class="timeline">';
        
        asignacion.reportes_progreso.forEach((reporte, index) => {
            const badges = {
                'En Diagnostico': {class: 'bg-info', icon: '🔍'},
                'En Reparacion': {class: 'bg-warning text-dark', icon: '🔧'},
                'Reparado': {class: 'bg-success', icon: '✅'},
                'Irreparable': {class: 'bg-danger', icon: '❌'}
            };
            const badge = badges[reporte.Estado_actual] || {class: 'bg-secondary', icon: '•'};

            const fecha = new Date(reporte.Fecha_reporte);
            const fechaFormateada = fecha.toLocaleString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            html += `
                <div class="card mb-3 ${index === 0 ? 'border-primary border-2' : ''}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge ${badge.class}">
                                ${badge.icon} ${reporte.Estado_actual}
                            </span>
                            ${index === 0 ? '<span class="badge bg-primary">Más Reciente</span>' : ''}
                        </div>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-clock-fill"></i>
                            ${fechaFormateada}
                        </p>
                        <p class="small mb-1"><strong>Trabajo Realizado:</strong></p>
                        <p class="small mb-0" style="white-space: pre-wrap;">${reporte.Descripcion_trabajo}</p>
                        ${reporte.Observaciones ? `
                            <hr class="my-2">
                            <p class="small mb-1"><strong>Observaciones:</strong></p>
                            <p class="small text-muted mb-0" style="white-space: pre-wrap;">${reporte.Observaciones}</p>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        html += '</div>';
        document.getElementById('historialContainer').innerHTML = html;
        
        const modal = new bootstrap.Modal(document.getElementById('modalHistorial'));
        modal.show();
    }

    // Validación de formularios al enviar
    document.addEventListener('DOMContentLoaded', function() {
        const formActualizar = document.getElementById('formActualizar');
        const formEntregar = document.getElementById('formEntregar');

        if (formActualizar) {
            formActualizar.addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
            });
        }

        if (formEntregar) {
            formEntregar.addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
            });
        }

        // Resetear modales al cerrar
        const modales = document.querySelectorAll('.modal');
        modales.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = false;
                        const originalText = btn.getAttribute('data-original-text');
                        if (originalText) {
                            btn.innerHTML = originalText;
                        }
                    }
                }
            });
        });

        // Guardar texto original de los botones
        document.querySelectorAll('button[type="submit"]').forEach(btn => {
            btn.setAttribute('data-original-text', btn.innerHTML);
        });
    });
</script>
@endsection