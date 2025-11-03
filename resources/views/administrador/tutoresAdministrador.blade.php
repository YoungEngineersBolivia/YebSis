@extends('/administrador/baseAdministrador')

@section('title', 'Detalles del Tutor')

@section('styles')
    <link href="{{ auto_asset ('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            min-width: 150px;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .student-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .plan-card {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #007bff;
        }
        .payment-status-badge {
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        .status-pagado {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-vencido {
            background-color: #f8d7da;
            color: #721c24;
        }
        .progress-summary {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .stat-box {
            text-align: center;
            padding: 10px;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">

    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="bi bi-person-badge"></i> Detalles del Tutor</h1>
        <div class="d-flex gap-2">
            <button type="button" 
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditarTutor">
                <i class="bi bi-pencil-square"></i> Editar Tutor
            </button>
            <a href="{{ route('tutores.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Información del Tutor -->
    <div class="info-card">
        <h3 class="mb-3"><i class="bi bi-person-circle text-primary"></i> Información Personal</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Nombre Completo:</span>
                    <span class="info-value">{{ $tutor->persona->Nombre }} {{ $tutor->persona->Apellido }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Celular:</span>
                    <span class="info-value">
                        <i class="bi bi-phone"></i> {{ $tutor->persona->Celular ?? 'No especificado' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value">
                        <i class="bi bi-geo-alt"></i> {{ $tutor->persona->Direccion_domicilio ?? 'No especificado' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Género:</span>
                    <span class="info-value">{{ $tutor->persona->Genero ?? 'No especificado' }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Correo:</span>
                    <span class="info-value">
                        <i class="bi bi-envelope"></i> {{ $tutor->usuario->Correo }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Parentesco:</span>
                    <span class="info-value">
                        <i class="bi bi-people"></i> {{ $tutor->Parentesco }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Descuento:</span>
                    <span class="info-value">
                        <span class="badge bg-success">{{ $tutor->Descuento ?? 0 }}%</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIT:</span>
                    <span class="info-value">{{ $tutor->Nit ?? 'No registrado' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Estudiantes y Planes de Pago -->
    <div class="mt-4">
        <h3 class="mb-3">
            <i class="bi bi-people-fill text-primary"></i> Estudiantes Asociados 
            <span class="badge bg-primary">{{ $tutor->estudiantes->count() }}</span>
        </h3>
        
        @if($tutor->estudiantes->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Este tutor no tiene estudiantes asociados.
            </div>
        @else
            @foreach($tutor->estudiantes as $estudiante)
                <div class="student-card">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="bi bi-mortarboard-fill text-primary"></i>
                                {{ $estudiante->persona->Nombre }} {{ $estudiante->persona->Apellido }}
                            </h4>
                            <div class="d-flex gap-3 flex-wrap">
                                <small class="text-muted">
                                    <i class="bi bi-hash"></i> <strong>Código:</strong> {{ $estudiante->Cod_estudiante }}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> <strong>Estado:</strong> 
                                    <span class="badge bg-{{ $estudiante->Estado == 'Activo' ? 'success' : 'secondary' }}">
                                        {{ $estudiante->Estado ?? 'No especificado' }}
                                    </span>
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-book"></i> <strong>Programa:</strong> {{ $estudiante->programa->Nombre ?? 'N/A' }}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-building"></i> <strong>Sucursal:</strong> {{ $estudiante->sucursal->Nombre ?? 'N/A' }}
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="stat-box">
                                <div class="stat-number text-primary">{{ $estudiante->planesPagos ? $estudiante->planesPagos->count() : 0 }}</div>
                                <div class="stat-label">Planes de Pago</div>
                            </div>
                        </div>
                    </div>

                    @if($estudiante->planPago->isEmpty())
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i> No hay planes de pago registrados para este estudiante.
                        </div>
                    @else
                        @foreach($estudiante->planPago as $index => $plan)
                            <div class="plan-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="bi bi-credit-card"></i> Plan de Pago #{{ $index + 1 }}
                                        </h5>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-check"></i> 
                                            Fecha: {{ \Carbon\Carbon::parse($plan->fecha_plan_pagos)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 mb-0 text-primary">
                                            Bs. {{ number_format($plan->Monto_total, 2) }}
                                        </div>
                                        <small class="text-muted">Monto Total</small>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <strong><i class="bi bi-book"></i> Programa:</strong> 
                                    {{ $plan->programa->Nombre ?? 'No especificado' }}
                                </div>
                                <div class="mb-3">
                                    <strong><i class="bi bi-info-circle"></i> Estado del Plan:</strong> 
                                    <span class="badge bg-{{ $plan->Estado_plan == 'Activo' ? 'success' : 'secondary' }}">
                                        {{ $plan->Estado_plan ?? 'No especificado' }}
                                    </span>
                                </div>

                                @if($plan->cuotas->isNotEmpty())
                                    @php
                                        $totalCuotas = $plan->cuotas->count();
                                        $cuotasPagadas = $plan->cuotas->where('Estado_cuota', 'Pagado')->count();
                                        $cuotasPendientes = $plan->cuotas->where('Estado_cuota', 'Pendiente')->count();
                                        $cuotasVencidas = $plan->cuotas->filter(function($cuota) {
                                            return $cuota->Estado_cuota === 'Pendiente' && 
                                                   \Carbon\Carbon::parse($cuota->Fecha_vencimiento)->isPast();
                                        })->count();
                                        $porcentajePagado = $totalCuotas > 0 ? round(($cuotasPagadas / $totalCuotas) * 100, 1) : 0;
                                        $montoPagado = $plan->cuotas->where('Estado_cuota', 'Pagado')->sum('Monto_pagado');
                                        $montoPendiente = $plan->Monto_total - $montoPagado;
                                    @endphp

                                    <div class="progress-summary">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number text-success">{{ $cuotasPagadas }}</div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-check-circle-fill"></i> Pagadas
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number text-warning">{{ $cuotasPendientes }}</div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-clock-fill"></i> Pendientes
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number text-danger">{{ $cuotasVencidas }}</div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-exclamation-circle-fill"></i> Vencidas
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-box">
                                                    <div class="stat-number">{{ $totalCuotas }}</div>
                                                    <div class="stat-label">
                                                        <i class="bi bi-list-ol"></i> Total
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span><strong>Progreso de Pagos</strong></span>
                                                <span><strong>{{ $porcentajePagado }}%</strong></span>
                                            </div>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $porcentajePagado }}%" 
                                                     aria-valuenow="{{ $porcentajePagado }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $cuotasPagadas }}/{{ $totalCuotas }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-2">
                                            <span>
                                                <strong>Pagado:</strong> 
                                                <span class="text-success">Bs. {{ number_format($montoPagado, 2) }}</span>
                                            </span>
                                            <span>
                                                <strong>Pendiente:</strong> 
                                                <span class="text-danger">Bs. {{ number_format($montoPendiente, 2) }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><i class="bi bi-hash"></i> N° Cuota</th>
                                                    <th><i class="bi bi-cash"></i> Monto Cuota</th>
                                                    <th><i class="bi bi-cash-coin"></i> Monto Pagado</th>
                                                    <th><i class="bi bi-calendar-event"></i> Fecha Vencimiento</th>
                                                    <th><i class="bi bi-info-circle"></i> Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($plan->cuotas as $cuota)
                                                    @php
                                                        $estaVencida = $cuota->Estado_cuota === 'Pendiente' && 
                                                                       \Carbon\Carbon::parse($cuota->Fecha_vencimiento)->isPast();
                                                        $estadoClass = $cuota->Estado_cuota === 'Pagado' ? 'status-pagado' : 
                                                                      ($estaVencida ? 'status-vencido' : 'status-pendiente');
                                                        $estadoTexto = $cuota->Estado_cuota === 'Pagado' ? 'Pagado' : 
                                                                      ($estaVencida ? 'Vencido' : 'Pendiente');
                                                        $fechaVencimiento = \Carbon\Carbon::parse($cuota->Fecha_vencimiento);
                                                        $diasRestantes = $fechaVencimiento->diffInDays(\Carbon\Carbon::now(), false);
                                                    @endphp
                                                    <tr class="{{ $estaVencida ? 'table-danger' : '' }}">
                                                        <td><strong>{{ $cuota->Nro_de_cuota }}</strong></td>
                                                        <td>Bs. {{ number_format($cuota->Monto_cuota, 2) }}</td>
                                                        <td>
                                                            @if($cuota->Monto_pagado)
                                                                <span class="text-success">
                                                                    Bs. {{ number_format($cuota->Monto_pagado, 2) }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">Bs. 0.00</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $fechaVencimiento->format('d/m/Y') }}
                                                            @if($cuota->Estado_cuota !== 'Pagado')
                                                                @if($estaVencida)
                                                                    <br><small class="text-danger">
                                                                        <i class="bi bi-exclamation-triangle"></i> 
                                                                        Vencido hace {{ abs($diasRestantes) }} días
                                                                    </small>
                                                                @else
                                                                    <br><small class="text-muted">
                                                                        <i class="bi bi-clock"></i> 
                                                                        Faltan {{ abs($diasRestantes) }} días
                                                                    </small>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="payment-status-badge {{ $estadoClass }}">
                                                                @if($cuota->Estado_cuota === 'Pagado')
                                                                    <i class="bi bi-check-circle"></i>
                                                                @elseif($estaVencida)
                                                                    <i class="bi bi-exclamation-circle"></i>
                                                                @else
                                                                    <i class="bi bi-clock"></i>
                                                                @endif
                                                                {{ $estadoTexto }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-exclamation-triangle"></i> No hay cuotas registradas para este plan de pagos.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    {{-- Modal Editar Tutor --}}
    <div class="modal fade" id="modalEditarTutor" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="{{ route('tutores.update', $tutor->Id_tutores) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarLabel">Editar Tutor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $tutor->persona->Nombre ?? '' }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control" value="{{ $tutor->persona->Apellido ?? '' }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Celular</label>
                                <input type="text" name="celular" class="form-control" value="{{ $tutor->persona->Celular ?? '' }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion_domicilio" class="form-control" value="{{ $tutor->persona->Direccion_domicilio ?? '' }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Correo</label>
                                <input type="email" name="correo" class="form-control" value="{{ $tutor->usuario->Correo ?? '' }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Parentesco</label>
                                <input type="text" name="parentezco" class="form-control" value="{{ $tutor->Parentesco ?? '' }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Descuento (%)</label>
                                <input type="number" name="descuento" class="form-control" step="0.01" min="0" max="100" value="{{ $tutor->Descuento ?? '' }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">NIT</label>
                                <input type="text" name="nit" class="form-control" value="{{ $tutor->Nit ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save2 me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
(function () {
    // Autocierre de alertas después de 5 segundos
    document.querySelectorAll('.alert').forEach(alertEl => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
            bsAlert.close();
        }, 5000);
    });

    console.log('Detalles del tutor cargados correctamente');
})();
</script>
@endsection