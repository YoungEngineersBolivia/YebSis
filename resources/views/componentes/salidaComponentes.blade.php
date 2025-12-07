@extends('administrador.baseAdministrador')

@section('title', 'Salida de Componentes')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Salida de Componentes</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Solicitudes Pendientes de Asignación -->
    @if($solicitudesPendientes->count() > 0)
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5><i class="fas fa-exclamation-triangle"></i> Solicitudes Pendientes de Asignación</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha Solicitud</th>
                            <th>ID Motor</th>
                            <th>Estado Actual</th>
                            <th>Motivo</th>
                            <th>Solicitado Por</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudesPendientes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->Fecha_movimiento->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $solicitud->motor->Id_motor }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $solicitud->Estado_salida }}</span></td>
                            <td>{{ Str::limit($solicitud->Motivo_salida, 40) }}</td>
                            <td>
                                @if($solicitud->profesor)
                                    {{ $solicitud->profesor->persona->Nombre }} {{ $solicitud->profesor->persona->Apellido }}
                                @else
                                    {{ $solicitud->Nombre_tecnico }}
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" 
                                        onclick="asignarTecnico({{ $solicitud->Id_movimientos }}, {{ $solicitud->motor->Id_motores }})">
                                    <i class="fas fa-user-plus"></i> Asignar Técnico
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Registrar Nueva Salida -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-sign-out-alt"></i> Registrar Salida de Motor</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.componentes.registrar-salida') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Motor *</label>
                        <select class="form-select" name="Id_motores" id="selectMotor" required>
                            <option value="">Seleccionar...</option>
                            @foreach($motores as $motor)
                                <option value="{{ $motor->Id_motores }}" 
                                        data-estado="{{ $motor->Estado }}" 
                                        data-sucursal="{{ $motor->sucursal->Nombre ?? 'N/A' }}">
                                    {{ $motor->Id_motor }} - {{ $motor->Estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Técnico Asignado *</label>
                        <select class="form-select" name="Id_profesores" required>
                            <option value="">Seleccionar...</option>
                            @foreach($tecnicos as $tecnico)
                                <option value="{{ $tecnico->Id_profesores }}">
                                    {{ $tecnico->persona->Nombre }} {{ $tecnico->persona->Apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Estado Salida *</label>
                        <select class="form-select" name="Estado_salida" id="estadoSalida" required>
                            <option value="Descompuesto">Descompuesto</option>
                            <option value="En Reparacion">En Reparación</option>
                            <option value="Disponible">Disponible</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Motivo de Salida *</label>
                        <input type="text" class="form-control" name="Motivo_salida" 
                               placeholder="Ej: Reparación de circuito" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea class="form-control" name="Observaciones" rows="2" 
                                  placeholder="Detalles adicionales..."></textarea>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i> Registrar Salida
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Motores Disponibles para Salida -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Motores Disponibles en Inventario</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Motor</th>
                            <th>Estado</th>
                            <th>Sucursal</th>
                            <th>Observación</th>
                            <th>Acción Rápida</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($motores as $motor)
                        <tr>
                            <td><strong>{{ $motor->Id_motor }}</strong></td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'Disponible' => 'bg-success',
                                        'Descompuesto' => 'bg-danger',
                                        'En Reparacion' => 'bg-warning'
                                    ][$motor->Estado] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $motor->Estado }}</span>
                            </td>
                            <td>{{ $motor->sucursal->Nombre ?? 'N/A' }}</td>
                            <td>{{ Str::limit($motor->Observacion, 50) ?? '---' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="llenarFormulario({{ $motor->Id_motores }}, '{{ $motor->Estado }}')">
                                    <i class="fas fa-arrow-up"></i> Seleccionar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No hay motores disponibles para salida
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asignar Técnico a Solicitud -->
<div class="modal fade" id="modalAsignarTecnico" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.componentes.registrar-salida') }}" method="POST">
                @csrf
                <input type="hidden" name="Id_motores" id="modal_motor_id">
                <input type="hidden" name="Motivo_salida" id="modal_motivo">
                <input type="hidden" name="Estado_salida" id="modal_estado">
                
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Técnico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Motor: <strong id="modal_motor_display"></strong><br>
                        Motivo: <span id="modal_motivo_display"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Técnico *</label>
                        <select class="form-select" name="Id_profesores" required>
                            <option value="">Seleccionar...</option>
                            @foreach($tecnicos as $tecnico)
                                <option value="{{ $tecnico->Id_profesores }}">
                                    {{ $tecnico->persona->Nombre }} {{ $tecnico->persona->Apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones Adicionales</label>
                        <textarea class="form-control" name="Observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asignar y Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const motores = @json($motores);
    const solicitudes = @json($solicitudesPendientes);

    // Llenar formulario automáticamente
    function llenarFormulario(motorId, estado) {
        $('#selectMotor').val(motorId);
        $('#estadoSalida').val(estado == 'Disponible' ? 'Descompuesto' : estado);
        $('html, body').animate({ scrollTop: 0 }, 300);
        $('#selectMotor').focus();
    }

    // Asignar técnico a solicitud pendiente
    function asignarTecnico(movimientoId, motorId) {
        const solicitud = solicitudes.find(s => s.Id_movimientos == movimientoId);
        if (!solicitud) return;

        $('#modal_motor_id').val(motorId);
        $('#modal_motivo').val(solicitud.Motivo_salida);
        $('#modal_estado').val(solicitud.Estado_salida);
        $('#modal_motor_display').text(solicitud.motor.Id_motor);
        $('#modal_motivo_display').text(solicitud.Motivo_salida);
        
        $('#modalAsignarTecnico').modal('show');
    }

    // Auto-completar estado según motor seleccionado
    $('#selectMotor').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const estado = selectedOption.data('estado');
        if (estado) {
            $('#estadoSalida').val(estado);
        }
    });
</script>
@endsection