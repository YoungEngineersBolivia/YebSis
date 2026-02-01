<div id="lista-estudiantes" class="row g-4">
    @foreach($estudiantes as $estudiante)
        @php
            $planes = $estudiante->planesPago;
            $tieneSaldo = false;
            $todosCompletados = true;

            if ($planes && $planes->count() > 0) {
                foreach ($planes as $plan) {
                    $totalPagado = $plan->pagos->sum('Monto_pago');
                    if ($totalPagado < $plan->Monto_total) {
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
            <div class="card h-100 shadow-sm border-0 hover-card" style="transition: all 0.3s ease;">
                <div class="card-header bg-gradient border-bottom pt-4 pb-3"
                    style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 me-2"
                                    style="font-size: 0.85rem;">
                                    <i class="fas fa-user-tie me-1"></i>Tutor
                                </span>
                                <span class="fw-bold text-dark" style="font-size: 1.05rem;">
                                    {{ $estudiante->tutor && $estudiante->tutor->persona
            ? $estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido
            : 'Sin tutor asignado' }}
                                </span>
                            </div>
                            <div>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 me-2"
                                    style="font-size: 0.85rem;">
                                    <i class="fas fa-graduation-cap me-1"></i>Estudiante
                                </span>
                                <span class="text-dark" style="font-size: 1rem;">
                                    {{ $estudiante->persona->Nombre ?? '' }}
                                    {{ $estudiante->persona->Apellido ?? '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4" style="max-height: 400px; overflow-y: auto;">
                    @if($planes && $planes->count() > 0)
                        @foreach($planes as $plan)
                            @php
                                $totalPagado = $plan->pagos->sum('Monto_pago');
                                $restante = $plan->Monto_total - $totalPagado;
                                $porcentaje = $plan->Monto_total > 0 ? ($totalPagado / $plan->Monto_total) * 100 : 0;
                                $ultimoPago = $plan->pagos->sortByDesc('Fecha_pago')->first();
                            @endphp

                            <div class="plan-card mb-3 p-3 bg-white rounded-3 border shadow-sm" style="transition: all 0.2s ease;">
                                <!-- Header del plan -->
                                <div class="mb-2">
                                    <h6 class="mb-1 fw-bold" style="font-size: 1.05rem;">
                                        <i class="fas fa-file-invoice me-2 text-primary"></i>
                                        {{ $plan->programa->Nombre ?? 'Programa' }}
                                    </h6>
                                </div>

                                <!-- Resumen financiero simplificado -->
                                <div class="row g-2 mb-2">
                                    <div class="col-12">
                                        <div class="p-3 rounded-3 border-0 shadow-sm" style="background: #f0f7ff;">
                                            <div class="text-center">
                                                <small class="text-muted d-block fw-semibold mb-1"
                                                    style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">MONTO
                                                    TOTAL PAGADO</small>
                                                <h5 class="text-primary mb-2 fw-bold" style="font-size: 1.4rem;">Bs.
                                                    {{ number_format($totalPagado, 2) }}
                                                </h5>

                                                @if($ultimoPago)
                                                    <div class="d-flex justify-content-center align-items-center gap-1 mt-1">
                                                        <span
                                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace"
                                                            style="font-size: 0.75rem;">
                                                            Último Pago: Bs. {{ number_format($ultimoPago->Monto_pago, 2) }}
                                                        </span>
                                                        <small class="text-muted" style="font-size: 0.75rem;">
                                                            ({{ \Carbon\Carbon::parse($ultimoPago->Fecha_pago)->format('d/m/Y') }})
                                                        </small>
                                                    </div>
                                                @else
                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Sin pagos registrados
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón para ver desglose de pagos -->
                                <button class="btn btn-outline-secondary btn-sm w-100 mb-2 hover-button" type="button"
                                    data-bs-toggle="modal" data-bs-target="#modalDesglose-{{ $plan->Id_planes_pagos }}"
                                    style="transition: all 0.3s ease; padding: 0.4rem;">
                                    <i class="fas fa-list-ul me-1" style="font-size: 0.85rem;"></i>
                                    <small>Ver Desglose</small>
                                </button>

                                <!-- Botón agregar pago -->
                                <button type="button" class="btn btn-primary w-100 btn-sm shadow-sm hover-button"
                                    data-bs-toggle="modal" data-bs-target="#modalAgregarPago"
                                    data-plan-id="{{ $plan->Id_planes_pagos }}" data-monto-total="{{ $plan->Monto_total }}"
                                    data-monto-pagado="{{ $totalPagado }}" data-restante="{{ $restante }}"
                                    data-programa="{{ $plan->programa->Nombre ?? 'Programa' }}"
                                    style="transition: all 0.3s ease; padding: 0.6rem;">
                                    <i class="fas fa-plus-circle me-2"></i>Agregar Pago
                                </button>
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

@if($estudiantes->isEmpty())
    <div id="no-resultados" class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No se encontraron resultados</h5>
        <p class="text-muted">Intenta con otros términos de búsqueda.</p>
    </div>
@endif

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4" id="pagination-links">
    {{ $estudiantes->links('pagination::bootstrap-5') }}
</div>

<!-- Modales fuera de las tarjetas para evitar conflictos de posicionamiento (Centrado) -->
@foreach($estudiantes as $estudiante)
    @php
        $planes = $estudiante->planesPago;
    @endphp
    @if($planes && $planes->count() > 0)
        @foreach($planes as $plan)
            @php
                $totalPagado = $plan->pagos->sum('Monto_pago');
            @endphp
            <div class="modal fade" id="modalDesglose-{{ $plan->Id_planes_pagos }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content text-start">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">
                                <i class="fas fa-history me-2"></i>
                                Historial de Pagos
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    {{ ($estudiante->persona->Nombre ?? '') . ' ' . ($estudiante->persona->Apellido ?? '') }}
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-book me-1"></i>
                                    {{ $plan->programa->Nombre ?? 'Programa' }}
                                </small>
                            </div>
                            <hr>

                            @if($plan->pagos && $plan->pagos->count() > 0)
                                @php
                                    $pagosOrdenadosRecientes = $plan->pagos->sortByDesc('Fecha_pago');
                                    $totalPagos = $plan->pagos->count();
                                @endphp
                                <div class="history-list">
                                    @foreach($pagosOrdenadosRecientes as $index => $pago)
                                        @php
                                            // Calculamos el número de pago (del más antiguo al más nuevo)
                                            $nroPago = $totalPagos - $loop->index;
                                        @endphp
                                        <div class="p-3 mb-2 rounded-3 bg-white border shadow-sm">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="badge bg-dark text-white me-2">#{{ $nroPago }}</span>
                                                    <span class="badge bg-primary-subtle text-primary fw-bold" style="font-size: 0.9rem;">
                                                        Bs. {{ number_format($pago->Monto_pago, 2) }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($pago->Fecha_pago)->format('d/m/Y') }}
                                                </small>
                                            </div>
                                            @php
                                                $comprobante = $pago->Comprobante ?? $pago->comprobante;
                                            @endphp
                                            @if($comprobante)
                                                <div class="mt-2 py-1 px-2 bg-light rounded text-muted font-monospace"
                                                    style="font-size: 0.75rem;">
                                                    <i class="fas fa-receipt me-1"></i>
                                                    {{ $comprobante }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 p-3 rounded-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-muted">TOTAL ACUMULADO</span>
                                        <h4 class="mb-0 text-primary fw-bold">Bs. {{ number_format($totalPagado, 2) }}</h4>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info border-0 shadow-sm">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No hay pagos registrados para este plan
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endforeach

<!-- Input oculto para pasar el total a JS -->
<input type="hidden" id="total-count-hidden" value="{{ $estudiantes->total() }}">