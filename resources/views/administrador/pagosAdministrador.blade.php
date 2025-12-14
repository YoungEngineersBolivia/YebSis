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
            <p class="text-muted mb-0">Administra los pagos de los estudiantes</p>
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
                    <i class="fas fa-clock me-2"></i>Con Saldo
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
                $tieneSaldo = false;
                $todosCompletados = true;
                
                if($planes && $planes->count() > 0) {
                    foreach($planes as $plan) {
                        $totalPagado = $plan->pagos->sum('Monto_pago');
                        if($totalPagado < $plan->Monto_total) {
                            $tieneSaldo = true;
                            $todosCompletados = false;
                        }
                    }
                } else {
                    $todosCompletados = false;
                }
                
                $estadoClase = $todosCompletados ? 'completado' : ($tieneSaldo ? 'pendiente' : 'pendiente');
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
                            @if($tieneSaldo)
                                <span class="badge rounded-pill bg-warning text-dark border border-warning">
                                    Con Saldo
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
                                    $totalPagado = $plan->pagos->sum('Monto_pago');
                                    $restante = $plan->Monto_total - $totalPagado;
                                    $porcentaje = $plan->Monto_total > 0 ? ($totalPagado / $plan->Monto_total) * 100 : 0;
                                @endphp
                                
                                <div class="plan-card mb-3 p-4 bg-light rounded-3">
                                    <!-- Header del plan -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold">
                                                <i class="fas fa-file-invoice me-2 text-primary"></i>
                                                {{ $plan->programa->Nombre ?? 'Programa' }}
                                            </h6>
                                            <small class="text-muted">
                                                Monto Total: Bs. {{ number_format($plan->Monto_total, 2) }}
                                            </small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapsePlan-{{ $plan->Id_planes_pagos }}">
                                            <i class="fas fa-eye me-1"></i>Ver Pagos
                                        </button>
                                    </div>

                                    <!-- Barra de progreso -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Pagado</small>
                                            <small class="fw-bold text-success">{{ number_format($porcentaje, 1) }}%</small>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $porcentaje }}%" 
                                                 aria-valuenow="{{ $porcentaje }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Resumen financiero -->
                                    <div class="row g-2 mb-3 text-center">
                                        <div class="col-4">
                                            <div class="bg-white p-2 rounded">
                                                <small class="text-muted d-block">Pagado</small>
                                                <strong class="text-success">Bs. {{ number_format($totalPagado, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-white p-2 rounded">
                                                <small class="text-muted d-block">Restante</small>
                                                <strong class="text-danger">Bs. {{ number_format($restante, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-white p-2 rounded">
                                                <small class="text-muted d-block">Pagos</small>
                                                <strong class="text-primary">{{ $plan->pagos->count() }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lista de pagos (colapsable) -->
                                    <div class="collapse" id="collapsePlan-{{ $plan->Id_planes_pagos }}">
                                        @if($plan->pagos && $plan->pagos->count() > 0)
                                            <div class="pagos-list">
                                                <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i>Historial de Pagos</h6>
                                                @foreach($plan->pagos->sortByDesc('Fecha_pago') as $index => $pago)
                                                    <div class="pago-item p-3 mb-2 bg-white rounded border-start border-4 border-success">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-2">
                                                                <span class="badge bg-dark">Pago #{{ $plan->pagos->count() - $index }}</span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <strong class="text-success">Bs. {{ number_format($pago->Monto_pago, 2) }}</strong>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($pago->Fecha_pago)->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <small class="text-muted">{{ $pago->Descripcion }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                No se han registrado pagos aún
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Botón agregar pago -->
                                    @if($restante > 0)
                                        <button type="button" class="btn btn-success w-100 mt-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAgregarPago"
                                                data-plan-id="{{ $plan->Id_planes_pagos }}"
                                                data-monto-total="{{ $plan->Monto_total }}"
                                                data-monto-pagado="{{ $totalPagado }}"
                                                data-restante="{{ $restante }}"
                                                data-programa="{{ $plan->programa->Nombre ?? 'Programa' }}">
                                            <i class="fas fa-plus me-2"></i>Agregar Pago
                                        </button>
                                    @else
                                        <div class="alert alert-success mb-0 mt-3">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Plan completado</strong> - Todos los pagos realizados
                                        </div>
                                    @endif
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

<!-- Modal para agregar pago -->
<div class="modal fade" id="modalAgregarPago" tabindex="-1" aria-labelledby="modalAgregarPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form method="POST" action="{{ route('pagos.registrar') }}" id="formAgregarPago">
        @csrf
        <input type="hidden" name="plan_id" id="modal-plan-id">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title" id="modalAgregarPagoLabel">
                    <i class="fas fa-plus-circle me-2"></i>Agregar Pago
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Info del plan -->
                <div class="alert alert-info mb-4">
                    <h6 class="mb-2"><strong id="modal-programa-nombre"></strong></h6>
                    <div class="row">
                        <div class="col-6">
                            <small>Monto Total: <strong>Bs. <span id="modal-monto-total-display">0.00</span></strong></small>
                        </div>
                        <div class="col-6">
                            <small>Restante: <strong class="text-danger">Bs. <span id="modal-restante-display">0.00</span></strong></small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción del Pago</label>
                    <input type="text" class="form-control form-control-lg" name="descripcion" 
                           placeholder="Ej: Pago mensualidad Diciembre" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Monto (Bs.)</label>
                    <input type="number" step="0.01" class="form-control form-control-lg" 
                           name="monto_pago" id="modal-monto-input" 
                           placeholder="0.00" required min="0.01">
                    <small class="text-muted">Máximo: Bs. <span id="modal-max-monto">0.00</span></small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fecha de Pago</label>
                        <input type="date" class="form-control form-control-lg" 
                               name="fecha_pago" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Comprobante/Referencia</label>
                        <input type="text" class="form-control form-control-lg" 
                               name="comprobante" placeholder="Ej: TRANS-12345" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" class="btn btn-success btn-lg px-4">
                    <i class="fas fa-save me-2"></i>Registrar Pago
                </button>
            </div>
        </div>
    </form>
  </div>
</div>

<style>
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

.plan-card {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.pago-item {
    transition: all 0.2s ease;
}

.pago-item:hover {
    transform: translateX(5px);
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
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
    const csrfToken = "{{ csrf_token() }}";
</script>

<script src="{{ auto_asset('js/administrador/pagosAdministrador.js') }}"></script>

@endsection
