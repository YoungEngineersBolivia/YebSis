@extends('administrador.baseAdministrador')

@section('title', 'Pagos')

@section('content')
<div class="container-fluid mt-4">

    <!-- Header Principal -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1><i class="fas fa-money-bill-wave me-2"></i>Gestión de Pagos</h1>
            <p>Administra los pagos de los estudiantes</p>
        </div>
        <div class="d-flex align-items-center gap-3 flex-grow-1 justify-content-end" style="max-width: 600px;">
            <div class="input-group search-box flex-grow-1">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="buscarEstudiante" class="form-control" 
                       placeholder="Buscar por tutor o estudiante..." autocomplete="off">
            </div>
            <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm" id="contador-resultados">
                {{ $estudiantes->count() }}
            </span>
        </div>
    </div>

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
            
            <div class="col-lg-6 col-md-12 estudiante-card" data-estado="{{ $estadoClase }}" 
                 data-tutor="{{ $estudiante->tutor && $estudiante->tutor->persona ? strtolower($estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido) : '' }}"
                 data-estudiante="{{ strtolower(($estudiante->persona->Nombre ?? '') . ' ' . ($estudiante->persona->Apellido ?? '')) }}">
                <div class="card h-100">
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
                                
                                <div class="plan-card mb-3 p-4 bg-light rounded-3 border">
                                    <!-- Header del plan -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold">
                                                <i class="fas fa-file-invoice me-2 text-primary"></i>
                                                {{ $plan->programa->Nombre ?? 'Programa' }}
                                            </h6>
                                            <small class="text-muted">
                                                Total: Bs. {{ number_format($plan->Monto_total, 2) }}
                                            </small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapsePlan-{{ $plan->Id_planes_pagos }}">
                                            <i class="fas fa-eye me-1"></i>
                                        </button>
                                    </div>

                                    <!-- Resumen financiero -->
                                    <div class="row g-2 mb-3 text-center">
                                        <div class="col-6">
                                            <div class="bg-white p-2 rounded shadow-sm border">
                                                <small class="text-muted d-block" style="font-size: 0.75rem;">Pagado</small>
                                                <strong class="text-success small">Bs. {{ number_format($totalPagado, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-white p-2 rounded shadow-sm border">
                                                <small class="text-muted d-block" style="font-size: 0.75rem;">Restante</small>
                                                <strong class="text-danger small">Bs. {{ number_format($restante, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lista de pagos (colapsable) -->
                                    <div class="collapse" id="collapsePlan-{{ $plan->Id_planes_pagos }}">
                                        @if($plan->pagos && $plan->pagos->count() > 0)
                                            <div class="pagos-list my-3">
                                                <h6 class="fw-bold mb-2 text-secondary small"><i class="fas fa-history me-1"></i>Historial</h6>
                                                @foreach($plan->pagos->sortByDesc('Fecha_pago') as $index => $pago)
                                                    <div class="p-2 mb-2 bg-white rounded border border-start-0 border-end-0 border-top-0 border-bottom">
                                                        <div class="d-flex justify-content-between">
                                                            <small class="text-success fw-bold">Bs. {{ number_format($pago->Monto_pago, 2) }}</small>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($pago->Fecha_pago)->format('d/m/Y') }}</small>
                                                        </div>
                                                        <small class="text-muted d-block text-truncate">{{ $pago->Descripcion }}</small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info py-2 px-3 small mb-3">
                                                No hay pagos registr.
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Botón agregar pago -->
                                    <button type="button" class="btn btn-primary w-100 btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAgregarPago"
                                            data-plan-id="{{ $plan->Id_planes_pagos }}"
                                            data-monto-total="{{ $plan->Monto_total }}"
                                            data-monto-pagado="{{ $totalPagado }}"
                                            data-restante="{{ $restante }}"
                                            data-programa="{{ $plan->programa->Nombre ?? 'Programa' }}">
                                        <i class="fas fa-plus me-1"></i>Agregar Pago
                                    </button>

                                    @if($restante <= 0)
                                        <div class="mt-2 text-center text-success small fw-bold">
                                            <i class="fas fa-check-circle me-1"></i> Completado
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-warning py-2 mb-0">
                                <small><i class="fas fa-exclamation-triangle me-1"></i> Sin plan asignado</small>
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
    </div>
</div>

<!-- Modal para agregar pago -->
<div class="modal fade" id="modalAgregarPago" tabindex="-1" aria-labelledby="modalAgregarPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('pagos.registrar') }}" id="formAgregarPago">
        @csrf
        <input type="hidden" name="plan_id" id="modal-plan-id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="modalAgregarPagoLabel">Registrar Pago</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Info del plan -->
                <div class="alert alert-primary mb-4">
                    <h6 class="mb-1 fw-bold" id="modal-programa-nombre">Programa</h6>
                    <small>Monto Total: Bs. <span id="modal-monto-total-display">0.00</span></small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" 
                           placeholder="Ej: Mensualidad Enero" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Monto (Bs.)</label>
                    <input type="number" step="0.01" class="form-control" 
                           name="monto_pago" id="modal-monto-input" 
                           placeholder="0.00" required min="0.01">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control" 
                               name="fecha_pago" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" 
                               name="comprobante" placeholder="Opcional">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Pago</button>
            </div>
        </div>
    </form>
  </div>
</div>

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
