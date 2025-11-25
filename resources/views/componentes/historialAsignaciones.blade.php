@extends('administrador.baseAdministrador')

@section('title', 'Historial de Asignaciones')

@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection

@section('content')
<div class="container-fluid mt-4">
    @if($asignaciones->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No hay historial de asignaciones</h5>
                <p class="text-muted">Los motores completados aparecerán aquí</p>
            </div>
        </div>
    @else
        {{-- Tarjeta principal con búsqueda y tabla --}}
        <div class="card shadow-sm border-0">
            {{-- Búsqueda --}}
            <div class="card-header bg-white border-0">
                <label class="form-label fw-semibold mb-2">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           class="form-control border-start-0"
                           id="searchInput"
                           placeholder="Filtrar por ID de motor, técnico, sucursal...">
                </div>
            </div>

            {{-- Tabla --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 py-3">ID Motor</th>
                                <th class="border-0 py-3">Técnico</th>
                                <th class="border-0 py-3">Sucursal</th>
                                <th class="border-0 py-3">Fecha Asignación</th>
                                <th class="border-0 py-3">Fecha Entrega</th>
                                <th class="border-0 py-3">Duración</th>
                                <th class="border-0 py-3">Estado Final</th>
                                <th class="border-0 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaciones as $asignacion)
                                @php
                                    $duracion = \Carbon\Carbon::parse($asignacion->Fecha_asignacion)
                                        ->diffInDays(\Carbon\Carbon::parse($asignacion->Fecha_entrega));
                                @endphp
                                <tr>
                                    <td class="py-3">
                                        <strong class="text-primary">{{ $asignacion->motor->Id_motor }}</strong>
                                    </td>
                                    <td class="py-3">
                                        {{ $asignacion->profesor->persona->Nombre ?? '' }}
                                        {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                                    </td>
                                    <td class="py-3">
                                        {{ $asignacion->motor->sucursal->Nombre ?? 'N/A' }}
                                    </td>
                                    <td class="py-3">
                                        {{ \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3">
                                        {{ \Carbon\Carbon::parse($asignacion->Fecha_entrega)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3">
                                        <span class="badge rounded-pill {{ $duracion > 7 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                            {{ $duracion }} {{ $duracion == 1 ? 'día' : 'días' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge rounded-pill
                                            @if($asignacion->motor->Estado == 'Funcionando') bg-success
                                            @elseif($asignacion->motor->Estado == 'Descompuesto') bg-danger
                                            @else bg-warning text-dark
                                            @endif">
                                            {{ $asignacion->motor->Estado }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detalleModal{{ $asignacion->Id_motores_asignados }}"
                                                title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modales de detalles --}}
        @foreach($asignaciones as $asignacion)
            @php
                $duracion = \Carbon\Carbon::parse($asignacion->Fecha_asignacion)
                    ->diffInDays(\Carbon\Carbon::parse($asignacion->Fecha_entrega));
            @endphp
            
            <div class="modal fade" id="detalleModal{{ $asignacion->Id_motores_asignados }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-info-circle me-2"></i>
                                Detalles de Asignación #{{ $asignacion->Id_motores_asignados }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            {{-- Tabla de información principal --}}
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-bold" style="width: 20%;">Motor</td>
                                        <td style="width: 30%;">{{ $asignacion->motor->Id_motor }}</td>
                                        <td class="bg-light fw-bold" style="width: 20%;">Sucursal</td>
                                        <td style="width: 30%;">{{ $asignacion->motor->sucursal->Nombre ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Técnico</td>
                                        <td colspan="3">
                                            {{ $asignacion->profesor->persona->Nombre ?? '' }}
                                            {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                                            {{ $asignacion->profesor->persona->Apellido_materno ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Fecha Asignación</td>
                                        <td>{{ \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y') }}</td>
                                        <td class="bg-light fw-bold">Fecha Entrega</td>
                                        <td>{{ \Carbon\Carbon::parse($asignacion->Fecha_entrega)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Duración</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $duracion > 7 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                                {{ $duracion }} {{ $duracion == 1 ? 'día' : 'días' }}
                                            </span>
                                        </td>
                                        <td class="bg-light fw-bold">Estado Final</td>
                                        <td>
                                            <span class="badge rounded-pill
                                                @if($asignacion->motor->Estado == 'Funcionando') bg-success
                                                @elseif($asignacion->motor->Estado == 'Descompuesto') bg-danger
                                                @else bg-warning text-dark
                                                @endif">
                                                {{ $asignacion->motor->Estado }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Observación Inicial</td>
                                        <td colspan="3">{{ $asignacion->Observacion_inicial ?? 'Sin observaciones' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-bold">Observaciones Finales</td>
                                        <td colspan="3">{{ $asignacion->motor->Observacion ?? 'Sin observaciones finales' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- Sección de Reportes de Mantenimiento --}}
                            @if($asignacion->reportes && $asignacion->reportes->count() > 0)
                            <div class="p-4 bg-light">
                                <h6 class="mb-3">
                                    <i class="bi bi-clipboard-check me-2"></i>
                                    Reportes de Mantenimiento ({{ $asignacion->reportes->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0 bg-white">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 25%;">Fecha</th>
                                                <th style="width: 25%;">Estado</th>
                                                <th style="width: 50%;">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($asignacion->reportes as $reporte)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($reporte->Fecha_reporte)->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge rounded-pill
                                                        @if($reporte->Estado_final == 'Funcionando') bg-success
                                                        @elseif($reporte->Estado_final == 'Descompuesto') bg-danger
                                                        @else bg-warning text-dark
                                                        @endif">
                                                        {{ $reporte->Estado_final }}
                                                    </span>
                                                </td>
                                                <td>{{ $reporte->Observaciones }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="p-4 bg-light text-center text-muted">
                                <i class="bi bi-clipboard-x" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">No hay reportes de mantenimiento registrados</p>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection