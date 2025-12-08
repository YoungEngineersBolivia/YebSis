@extends('administrador.baseAdministrador')

@section('title', 'Pagos')

@section('content')
<div class="container-fluid px-4 mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header con título y búsqueda -->
    <div class="row mb-4 align-items-center">
        <div class="col-lg-4">
            <h2 class="fw-bold text-primary mb-1">
                <i class="fas fa-money-bill-wave me-2"></i>Gestión de Pagos
            </h2>
            <p class="text-muted mb-0">Administra los planes de pago de los estudiantes</p>
        </div>
        <div class="col-lg-5">
            <div class="input-group input-group-lg shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="buscarEstudiante" class="form-control border-start-0 ps-0" 
                       placeholder="Buscar por nombre del tutor o estudiante..." autocomplete="off">
            </div>
        </div>
        <div class="col-lg-3 text-end">
            <span class="badge bg-primary fs-6 px-3 py-2" id="contador-resultados">
                {{ $estudiantes->count() }} estudiantes
            </span>
        </div>
    </div>

    <!-- Filtros por estado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group shadow-sm" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="todos" id="filter-todos">
                    <i class="fas fa-list me-2"></i>Todos
                </button>
                <button type="button" class="btn btn-outline-warning" data-filter="pendientes" id="filter-pendientes">
                    <i class="fas fa-clock me-2"></i>Pendientes
                </button>
                <button type="button" class="btn btn-outline-success" data-filter="completados" id="filter-completados">
                    <i class="fas fa-check-circle me-2"></i>Completados
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de estudiantes -->
    <div id="lista-estudiantes" class="row g-4">
        @foreach($estudiantes as $estudiante)
            @php
                $planes = $estudiante->planesPago;
                $tienePendientes = false;
                $todosCompletados = true;
                
                if($planes && $planes->count() > 0) {
                    foreach($planes as $plan) {
                        if($plan->cuotas && $plan->cuotas->count() > 0) {
                            foreach($plan->cuotas as $cuota) {
                                if($cuota->Estado_cuota !== 'Pagado') {
                                    $tienePendientes = true;
                                    $todosCompletados = false;
                                }
                            }
                        }
                    }
                }
                
                $estadoClase = $todosCompletados && !$tienePendientes ? 'completado' : 'pendiente';
            @endphp
            
            <div class="col-12 estudiante-card" data-estado="{{ $estadoClase }}" 
                 data-tutor="{{ $estudiante->tutor && $estudiante->tutor->persona ? strtolower($estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido) : '' }}"
                 data-estudiante="{{ strtolower(($estudiante->persona->Nombre ?? '') . ' ' . ($estudiante->persona->Apellido ?? '')) }}">
                <div class="card h-100 shadow hover-shadow transition-all border-0">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fw-bold text-dark">
                                    <i class="fas fa-user-tie text-primary me-2"></i>
                                    {{ $estudiante->tutor && $estudiante->tutor->persona
                                        ? $estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido
                                        : 'Sin tutor asignado' }}
                                </h5>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    {{ $estudiante->persona->Nombre ?? '' }}
                                    {{ $estudiante->persona->Apellido ?? '' }}
                                </p>
                            </div>
                            @if($tienePendientes)
                                <span class="badge rounded-pill bg-warning text-dark border border-warning">
                                    Pendiente
                                </span>
                            @else
                                <span class="badge rounded-pill bg-success border border-success">
                                    Completo
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        @if($planes && $planes->count() > 0)
                            @foreach($planes as $plan)
                                @php
                                    $cuotasPendientes = $plan->cuotas->where('Estado_cuota', '!=', 'Pagado');
                                    $totalPendiente = $cuotasPendientes->sum(function($cuota) {
                                        return $cuota->Monto_cuota - ($cuota->Monto_pagado ?? 0);
                                    });
                                @endphp
                                
                                <div class="plan-container mb-4">
                                    <div class="d-flex gap-2 mb-3">
                                        <button class="btn btn-outline-primary flex-grow-1" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapsePlan-{{ $plan->Id_planes_pagos }}" 
                                                aria-expanded="false">
                                            <i class="fas fa-eye me-2"></i>Ver Plan
                                        </button>
                                        
                                        @if($cuotasPendientes->count() > 0)
                                            <button type="button" class="btn btn-success px-4" 
                                                    onclick="pagarPlanCompleto({{ $plan->Id_planes_pagos }}, {{ $totalPendiente }})"
                                                    title="Pagar todas las cuotas pendientes">
                                                <i class="fas fa-check-double me-2"></i>Pago Completo
                                            </button>
                                        @endif
                                    </div>

                                    <div class="collapse" id="collapsePlan-{{ $plan->Id_planes_pagos }}">
                                        @if($plan->cuotas && $plan->cuotas->count() > 0)
                                            <div class="cuotas-container">
                                                @foreach($plan->cuotas as $cuota)
                                            <div class="cuota-card mb-3 p-3 bg-white shadow-sm rounded-3 border-start border-4 
                                                @if($cuota->Estado_cuota === 'Pagado') border-success
                                                @elseif($cuota->Estado_cuota === 'Parcial') border-warning
                                                @else border-danger
                                                @endif">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-3">
                                                                <h5 class="mb-1">
                                                                    <span class="badge bg-dark">Cuota #{{ $cuota->Nro_de_cuota }}</span>
                                                                </h5>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($cuota->Fecha_vencimiento)->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mb-1">
                                                                    <strong>Monto:</strong> Bs. {{ number_format($cuota->Monto_cuota, 2) }}
                                                                </div>
                                                                <div>
                                                                    <strong>Pagado:</strong> Bs. {{ number_format($cuota->Monto_pagado ?? 0, 2) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 text-center">
                                                                @if($cuota->Estado_cuota === 'Pagado')
                                                                    <span class="text-success fw-bold">
                                                                        <i class="fas fa-check-circle me-1"></i>Pagado
                                                                    </span>
                                                                @elseif($cuota->Estado_cuota === 'Parcial')
                                                                    <span class="text-warning fw-bold">
                                                                        <i class="fas fa-clock me-1"></i>Parcial
                                                                    </span>
                                                                @else
                                                                    <span class="text-danger fw-bold">
                                                                        <i class="fas fa-times-circle me-1"></i>Pendiente
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-3 text-end">
                                                                @if($cuota->Estado_cuota !== 'Pagado')
                                                                    <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalRegistrarPago"
                                                                        data-cuota-id="{{ $cuota->Id_cuotas }}"
                                                                        data-monto="{{ $cuota->Monto_cuota }}"
                                                                        data-plan-id="{{ $plan->Id_planes_pagos }}"
                                                                        title="Registrar pago parcial">
                                                                        Pagar
                                                                    </button>
                                                                @else
                                                                    <div class="text-success fs-4">
                                                                        <i class="fas fa-check-circle"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Este plan no tiene cuotas registradas.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No tiene plan de pago asignado.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Mensaje cuando no hay resultados -->
    <div id="no-resultados" class="text-center py-5" style="display: none;">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No se encontraron resultados</h5>
        <p class="text-muted">Intenta con otro término de búsqueda o cambia el filtro</p>
    </div>
</div>

<!-- Modal para registrar pago -->
<div class="modal fade" id="modalRegistrarPago" tabindex="-1" aria-labelledby="modalRegistrarPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form method="POST" action="{{ route('pagos.registrar') }}">
        @csrf
        <input type="hidden" name="cuota_id" id="modal-cuota-id">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="modalRegistrarPagoLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>Registrar Pago Parcial
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold fs-5">Descripción</label>
                    <input type="text" class="form-control form-control-lg" name="descripcion" id="modal-descripcion" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold fs-5">Comprobante</label>
                    <input type="text" class="form-control form-control-lg" name="comprobante" id="modal-comprobante" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold fs-5">Monto Pago (Bs.)</label>
                    <input type="number" step="0.01" class="form-control form-control-lg" name="monto_pago" id="modal-monto-pago" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold fs-5">Fecha Pago</label>
                    <input type="date" class="form-control form-control-lg" name="fecha_pago" id="modal-fecha-pago" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold fs-5">ID Planes Pagos</label>
                    <input type="number" class="form-control form-control-lg" name="id_planes_pagos" id="modal-id-planes-pagos" required readonly>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-save me-2"></i>Registrar Pago
                </button>
            </div>
        </div>
    </form>
  </div>
</div>

<style>
/* Estilos removidos - usando colores sólidos de Bootstrap */

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2) !important;
}

.transition-all {
    transition: all 0.3s ease;
}

.card-header {
    border-bottom: none;
}

.plan-container {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
}

.cuota-card {
    background: white;
    transition: all 0.3s ease;
    border: 2px solid #dee2e6 !important;
}

.cuota-card:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    transform: translateX(5px);
}

.bg-light-success {
    background-color: #d1e7dd !important;
    border-color: #198754 !important;
}

.bg-light-warning {
    background-color: #fff3cd !important;
    border-color: #ffc107 !important;
}

.bg-light-danger {
    background-color: #f8d7da !important;
    border-color: #dc3545 !important;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.collapse {
    transition: height 0.35s ease;
}
</style>

@endsection

@section('scripts')

<script id="estudiantes-data" type="application/json">
    {!! json_encode($estudiantes) !!}
</script>

<script>
    const estudiantes = JSON.parse(document.getElementById('estudiantes-data').textContent);
    const registrarPagoUrl = "{{ route('pagos.registrar') }}";
    const pagarPlanCompletoUrl = "{{ route('pagos.pagarPlanCompleto') }}";
    const csrfToken = "{{ csrf_token() }}";
</script>

<script src="{{ auto_asset('js/administrador/pagosAdministrador.js') }}"></script>

@endsection
