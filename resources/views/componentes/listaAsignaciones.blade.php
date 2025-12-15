@extends('administrador.baseAdministrador')

@section('title', 'Motores Asignados')

@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Motores Asignados</h1>
            <p class="text-muted">Gestión de motores en mantenimiento</p>
        </div>
        <a href="{{ route('motores.asignar.create') }}" class="btn btn-primary">
            <i class="bi bi-tools me-2"></i>Nueva Asignación
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($asignaciones->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No hay motores asignados</h5>
                <p class="text-muted">Todos los motores están disponibles en el inventario</p>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID Motor</th>
                                <th>Técnico Asignado</th>
                                <th>Fecha Asignación</th>
                                <th>Días en Proceso</th>
                                <th>Estado</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaciones as $asignacion)
                                @php
                                    $diasEnProceso = (int) \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->diffInDays(now());
                                @endphp
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $asignacion->motor->Id_motor }}</strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-fill text-muted me-1"></i>
                                        {{ $asignacion->profesor->persona->Nombre ?? '' }} 
                                        {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $diasEnProceso > 7 ? 'bg-warning text-dark' : 'bg-info' }}">
                                            {{ $diasEnProceso }} {{ $diasEnProceso == 1 ? 'día' : 'días' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($asignacion->motor->Estado == 'Funcionando') bg-success
                                            @elseif($asignacion->motor->Estado == 'Descompuesto') bg-danger
                                            @else bg-warning text-dark
                                            @endif">
                                            {{ $asignacion->motor->Estado }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#entregaModal{{ $asignacion->Id_motores_asignados }}"
                                                title="Registrar Entrada">
                                            <i class="bi bi-box-arrow-in-down me-1"></i>Registrar Entrada
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal: Registrar Entrada -->
                                <div class="modal fade" id="entregaModal{{ $asignacion->Id_motores_asignados }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-box-arrow-in-down me-2"></i>Registrar Entrada de Motor
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('motores.registrar.entrada', $asignacion->Id_motores_asignados) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <!-- Información de la Asignación -->
                                                    <div class="card bg-light mb-4">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="bi bi-info-circle me-2"></i>Información de la Asignación
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Motor:</strong> 
                                                                    <span class="text-primary">{{ $asignacion->motor->Id_motor }}</span>
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Técnico:</strong> 
                                                                    {{ $asignacion->profesor->persona->Nombre ?? '' }} 
                                                                    {{ $asignacion->profesor->persona->Apellido_paterno ?? '' }}
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Fecha Asignación:</strong> 
                                                                    {{ \Carbon\Carbon::parse($asignacion->Fecha_asignacion)->format('d/m/Y') }}
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <strong>Días en Proceso:</strong> 
                                                                    <span class="badge {{ $diasEnProceso > 7 ? 'bg-warning text-dark' : 'bg-info' }}">
                                                                        {{ $diasEnProceso }} {{ $diasEnProceso == 1 ? 'día' : 'días' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @if($asignacion->Observacion_inicial)
                                                                <div class="mt-3">
                                                                    <strong>Observación Inicial:</strong>
                                                                    <p class="mb-0 mt-1">{{ $asignacion->Observacion_inicial }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Formulario de Entrada -->
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="fecha_entrega{{ $asignacion->Id_motores_asignados }}" class="form-label">
                                                                <i class="bi bi-calendar-event me-1"></i>Fecha de Entrega 
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="date" 
                                                                   class="form-control" 
                                                                   id="fecha_entrega{{ $asignacion->Id_motores_asignados }}" 
                                                                   name="fecha_entrega" 
                                                                   value="{{ date('Y-m-d') }}" 
                                                                   min="{{ $asignacion->Fecha_asignacion }}"
                                                                   max="{{ date('Y-m-d') }}"
                                                                   required>
                                                            <small class="text-muted">Debe ser posterior a la fecha de asignación</small>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label for="estado_final{{ $asignacion->Id_motores_asignados }}" class="form-label">
                                                                <i class="bi bi-gear-fill me-1"></i>Estado Final del Motor 
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <select class="form-select" 
                                                                    id="estado_final{{ $asignacion->Id_motores_asignados }}" 
                                                                    name="estado_final" required>
                                                                <option value="">Seleccione el estado</option>
                                                                <option value="Descompuesto">Descompuesto</option>
                                                                <option value="En Proceso">En Proceso</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label for="Id_sucursales{{ $asignacion->Id_motores_asignados }}" class="form-label">
                                                                <i class="bi bi-building me-1"></i>Sucursal de Entrada 
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <select class="form-select" 
                                                                    id="Id_sucursales{{ $asignacion->Id_motores_asignados }}" 
                                                                    name="Id_sucursales" required>
                                                                <option value="">Seleccione la sucursal</option>
                                                                @foreach($sucursales as $sucursal)
                                                                    <option value="{{ $sucursal->Id_Sucursales }}"
                                                                        {{ $asignacion->motor->Id_sucursales == $sucursal->Id_Sucursales ? 'selected' : '' }}>
                                                                        {{ $sucursal->Nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-12 mb-3">
                                                            <label for="observaciones_entrega{{ $asignacion->Id_motores_asignados }}" class="form-label">
                                                                <i class="bi bi-chat-left-text me-1"></i>Observaciones
                                                            </label>
                                                            <textarea class="form-control" 
                                                                      id="observaciones_entrega{{ $asignacion->Id_motores_asignados }}" 
                                                                      name="observaciones" 
                                                                      rows="4" 
                                                                      placeholder="Descripción de reparaciones realizadas, estado final, problemas encontrados, piezas reemplazadas, etc."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-circle me-2"></i>Confirmar Entrada
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection