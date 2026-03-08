@extends('administrador.baseAdministrador')

@section('title', 'Planes de Pago - ' . ($estudiante->persona->Nombre ?? 'Estudiante'))

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ auto_asset('css/administrador/planesPagoEstudiante.css') }}">
@endsection

@section('content')
    <div class="container-fluid mt-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('estudiantes.ver', $estudiante->Id_estudiantes) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a detalles
            </a>
        </div>

        @php
            $persona = $estudiante->persona ?? null;
            $nombreCompleto = $persona ? trim(($persona->Nombre ?? '') . ' ' . ($persona->Apellido ?? '')) : 'Sin nombre';
        @endphp

        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-cash-coin"></i> Planes de Pago
                    </h2>
                    <p class="mb-0 opacity-75">
                        {{ $nombreCompleto }} - {{ $estudiante->Cod_estudiante }}
                    </p>
                </div>
                <button class="btn btn-light" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>

        @if($planesPago->isEmpty())
            <div class="card">
                <div class="card-body empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>No hay planes de pago registrados</h4>
                    <p class="text-muted mb-3">Este estudiante aún no tiene planes de pago asignados.</p>
                    <a href="{{ route('planesPago.crear', $estudiante->Id_estudiantes) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Crear Plan de Pago
                    </a>
                </div>
            </div>
        @else
            {{-- Estadísticas Generales --}}
            @php
                $totalPlanes = $planesPago->count();
                $planesActivos = $planesPago->where('Estado_plan', 'Activo')->count();
                $planesCompletados = $planesPago->where('Estado_plan', 'Completado')->count();
                $montoTotal = $planesPago->sum('Monto_total');

                $totalCuotas = 0;
                $cuotasPagadas = 0;
                $montoPagado = 0;

                foreach ($planesPago as $plan) {
                    $totalCuotas += $plan->cuotas->count();
                    $cuotasPagadas += $plan->cuotas->where('Estado_cuota', 'Pagado')->count();
                    $montoPagado += $plan->cuotas->sum('Monto_pagado');
                }

                $porcentajePago = $montoTotal > 0 ? ($montoPagado / $montoTotal) * 100 : 0;
            @endphp

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stats-box">
                        <div class="stats-value">{{ $totalPlanes }}</div>
                        <div class="stats-label">Planes Totales</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-box">
                        <div class="stats-value text-success">{{ $planesActivos }}</div>
                        <div class="stats-label">Planes Activos</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-box">
                        <div class="stats-value text-info">Bs. {{ number_format($montoPagado, 2) }}</div>
                        <div class="stats-label">Total Pagado</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-box">
                        <div class="stats-value text-warning">{{ number_format($porcentajePago, 1) }}%</div>
                        <div class="stats-label">Progreso</div>
                    </div>
                </div>
            </div>

            {{-- Lista de Planes --}}
            @foreach($planesPago as $plan)
                @php
                    $cuotas = $plan->cuotas ?? collect();
                    $totalCuotasPlan = $cuotas->count();
                    $cuotasPagadasPlan = $cuotas->where('Estado_cuota', 'Pagado')->count();
                    $progresoPlan = $totalCuotasPlan > 0 ? ($cuotasPagadasPlan / $totalCuotasPlan) * 100 : 0;

                    $montoPagadoPlan = $cuotas->sum('Monto_pagado');
                    $montoRestante = $plan->Monto_total - $montoPagadoPlan;

                    $estadoClass = match (Str::lower($plan->Estado_plan ?? '')) {
                        'activo' => 'status-activo',
                        'completado' => 'status-completado',
                        'pendiente' => 'status-pendiente',
                        'vencido' => 'status-vencido',
                        default => 'status-pendiente'
                    };
                @endphp

                <div class="plan-card">
                    <div class="plan-header">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-1">
                                    <i class="bi bi-file-text"></i>
                                    Plan de Pago - {{ $plan->programa->Nombre ?? 'Programa' }}
                                </h5>
                                <small class="text-muted">
                                    Fecha: {{ \Carbon\Carbon::parse($plan->fecha_plan_pagos)->format('d/m/Y') }}
                                </small>
                            </div>
                            <span class="plan-status {{ $estadoClass }}">
                                {{ $plan->Estado_plan ?? 'Pendiente' }}
                            </span>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <strong>Monto Total:</strong><br>
                                <span class="text-primary fs-5">Bs. {{ number_format($plan->Monto_total, 2) }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Pagado:</strong><br>
                                <span class="text-success fs-5">Bs. {{ number_format($montoPagadoPlan, 2) }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Restante:</strong><br>
                                <span class="text-danger fs-5">Bs. {{ number_format($montoRestante, 2) }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Cuotas:</strong><br>
                                <span class="fs-5">{{ $cuotasPagadasPlan }} / {{ $totalCuotasPlan }}</span>
                            </div>
                        </div>

                        {{-- Barra de progreso --}}
                        <div class="progress-container">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Progreso del plan</small>
                                <small><strong>{{ number_format($progresoPlan, 1) }}%</strong></small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progresoPlan }}%;"
                                    aria-valuenow="{{ $progresoPlan }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="bi bi-list-check"></i> Detalle de Cuotas
                        </h5>

                        @if($cuotas->isEmpty())
                            <div class="alert alert-light border text-muted small py-2">
                                <i class="bi bi-info-circle me-1"></i> No hay cuotas mensuales proyectadas para este plan.
                            </div>
                        @else
                            @foreach($cuotas as $cuota)
                                @php
                                    $fechaVencimiento = \Carbon\Carbon::parse($cuota->Fecha_vencimiento);
                                    $estadoCuota = Str::lower($cuota->Estado_cuota ?? '');
                                    $estaVencida = $fechaVencimiento->isPast() && $estadoCuota !== 'pagado';

                                    $cuotaClass = match ($estadoCuota) {
                                        'pagado' => 'cuota-pagada',
                                        default => $estaVencida ? 'cuota-vencida' : 'cuota-pendiente'
                                    };

                                    $iconoCuota = match ($estadoCuota) {
                                        'pagado' => 'bi-check-circle-fill text-success',
                                        default => $estaVencida ? 'bi-exclamation-circle-fill text-danger' : 'bi-clock-fill text-warning'
                                    };
                                @endphp

                                <div class="cuota-item {{ $cuotaClass }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-1 text-center">
                                            <i class="bi {{ $iconoCuota }} fs-4"></i>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Cuota #{{ $cuota->Nro_de_cuota }}</strong>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Vencimiento:</small><br>
                                            <strong>{{ $fechaVencimiento->format('d/m/Y') }}</strong>
                                            @if($estaVencida && $estadoCuota !== 'pagado')
                                                <br><small class="text-danger">
                                                    <i class="bi bi-exclamation-triangle"></i> Vencida
                                                </small>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Monto:</small><br>
                                            <strong>Bs. {{ number_format($cuota->Monto_cuota, 2) }}</strong>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Pagado:</small><br>
                                            <strong class="text-success">Bs. {{ number_format($cuota->Monto_pagado ?? 0, 2) }}</strong>
                                        </div>
                                        <div class="col-md-2 text-end pe-3">
                                            <span
                                                class="badge {{ $estadoCuota === 'pagado' ? 'bg-success' : ($estaVencida ? 'bg-danger' : 'bg-warning') }}">
                                                {{ ucfirst($cuota->Estado_cuota ?? 'Pendiente') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Nueva Sección: Historial de Transacciones Reales (Caja) --}}
                        <div class="mt-4 border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="bi bi-receipt-cutoff me-2 text-primary"></i>Historial de Transacciones (Caja)
                                </h5>
                                <span class="badge bg-light text-dark border">{{ $plan->pagos->count() }} transacciones</span>
                            </div>

                            @if($plan->pagos && $plan->pagos->isNotEmpty())
                                @php
                                    $pagosOrdenados = $plan->pagos->sortByDesc('Id_pagos');
                                    $totalTransacciones = $plan->pagos->count();
                                @endphp
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle" style="font-size: 0.9rem;">
                                        <thead class="bg-light text-muted small text-uppercase">
                                            <tr>
                                                <th class="ps-3" style="width: 60px;">#</th>
                                                <th>Concepto / Detalle</th>
                                                <th class="text-center">Comprobante</th>
                                                <th class="text-center">Fecha</th>
                                                <th class="pe-3 text-end">Monto Real</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pagosOrdenados as $pago)
                                                @php
                                                    $nroPago = $totalTransacciones - $loop->index - 1;
                                                @endphp
                                                <tr>
                                                    <td class="ps-3">
                                                        <span class="badge {{ $nroPago == 0 ? 'bg-dark' : 'bg-secondary' }} rounded-pill">
                                                            #{{ $nroPago }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $pago->Descripcion }}</div>
                                                        @if($nroPago == 0)
                                                            <span class="text-primary small" style="font-size: 0.75rem;">
                                                                <i class="bi bi-info-circle"></i> Registro Inicial
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border font-monospace">
                                                            {{ $pago->Comprobante ?: 'S/N' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-muted">
                                                        {{ \Carbon\Carbon::parse($pago->Fecha_pago)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="pe-3 text-end fw-bold text-primary">
                                                        Bs. {{ number_format($pago->Monto_pago, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-muted small ps-3 py-3 bg-light rounded">
                                    <i class="bi bi-info-circle me-1 text-primary"></i>
                                    No se han registrado transacciones físicas de ingreso para este plan aún.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-cerrar alertas
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endsection