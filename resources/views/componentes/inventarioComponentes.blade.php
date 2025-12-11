@extends('administrador.baseAdministrador')

@section('title', 'Inventario de Componentes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-box-seam"></i> Inventario de Componentes / Motores</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoMotor">
            <i class="bi bi-plus-circle"></i> Nuevo Motor
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label><i class="bi bi-funnel"></i> Estado</label>
                    <select class="form-select" id="filtroEstado">
                        <option value="">Todos</option>
                        <option value="Disponible">Disponible</option>
                        <option value="En Reparacion">En Reparación</option>
                        <option value="Funcionando">Funcionando</option>
                        <option value="Descompuesto">Descompuesto</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label><i class="bi bi-geo-alt"></i> Ubicación</label>
                    <select class="form-select" id="filtroUbicacion">
                        <option value="">Todas</option>
                        <option value="Inventario">Inventario</option>
                        <option value="Con Tecnico">Con Técnico</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label><i class="bi bi-building"></i> Sucursal</label>
                    <select class="form-select" id="filtroSucursal">
                        <option value="">Todas</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->Id_sucursales }}">{{ $sucursal->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label><i class="bi bi-person-gear"></i> Técnico</label>
                    <input type="text" class="form-control" id="filtroTecnico" placeholder="Buscar técnico...">
                </div>
                <div class="col-md-3">
                    <label><i class="bi bi-search"></i> ID Motor</label>
                    <input type="text" class="form-control" id="buscarMotor" placeholder="Buscar ID...">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Motores -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tablaMotores">
                    <thead>
                        <tr>
                            <th><i class="bi bi-tag"></i> ID Motor</th>
                            <th><i class="bi bi-circle-fill"></i> Estado</th>
                            <th><i class="bi bi-pin-map"></i> Ubicación</th>
                            <th><i class="bi bi-building"></i> Sucursal</th>
                            <th><i class="bi bi-person-gear"></i> Técnico Actual</th>
                            <th><i class="bi bi-chat-left-text"></i> Observación</th>
                            <th><i class="bi bi-gear"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($motores as $motor)
                        <tr data-estado="{{ $motor->Estado }}" 
                            data-ubicacion="{{ $motor->Ubicacion_actual }}" 
                            data-sucursal="{{ $motor->Id_sucursales }}" 
                            data-tecnico="{{ $motor->tecnicoActual ? strtolower($motor->tecnicoActual->persona->Nombre . ' ' . $motor->tecnicoActual->persona->Apellido) : '' }}"
                            data-id="{{ strtolower($motor->Id_motor) }}">
                            <td><strong>{{ $motor->Id_motor }}</strong></td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'Disponible' => 'bg-success',
                                        'En Reparacion' => 'bg-warning text-dark',
                                        'Funcionando' => 'bg-primary',
                                        'Descompuesto' => 'bg-danger'
                                    ][$motor->Estado] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    <i class="bi bi-circle-fill"></i> {{ $motor->Estado }}
                                </span>
                            </td>
                            <td>
                                @if($motor->Ubicacion_actual == 'Inventario')
                                    <i class="bi bi-box-seam text-success"></i> Inventario
                                @else
                                    <i class="bi bi-person-workspace text-warning"></i> Con Técnico
                                @endif
                            </td>
                            <td>{{ $motor->sucursal->Nombre ?? 'N/A' }}</td>
                            <td>
                                @if($motor->tecnicoActual)
                                    <i class="bi bi-person-check"></i> {{ $motor->tecnicoActual->persona->Nombre }} {{ $motor->tecnicoActual->persona->Apellido }}
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($motor->Observacion, 50) ?? '---' }}</td>
                            <td>
                                <!--<button type="button" class="btn btn-sm btn-info" 
                                        onclick="verHistorial({{ $motor->Id_motores }})" 
                                        title="Ver Historial">
                                    <i class="bi bi-clock-history"></i>
                                </button>-->
                                <button type="button" class="btn btn-sm btn-warning" 
                                        onclick='editarMotor(@json($motor))' 
                                        title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @if(!$motor->asignacionActiva)
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="eliminarMotor({{ $motor->Id_motores }})" 
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Motor -->
<div class="modal fade" id="modalNuevoMotor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.componentes.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Registrar Nuevo Motor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-tag"></i> ID Motor *</label>
                        <input type="text" class="form-control" name="Id_motor" required placeholder="Ej: 001, 002...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-circle-fill"></i> Estado *</label>
                        <select class="form-select" name="Estado" required>
                            <option value="Disponible">Disponible</option>
                            <option value="Funcionando">Funcionando</option>
                            <option value="Descompuesto">Descompuesto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-building"></i> Sucursal</label>
                        <select class="form-select" name="Id_sucursales">
                            <option value="">Sin asignar</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->Id_sucursales }}">{{ $sucursal->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-chat-left-text"></i> Observación</label>
                        <textarea class="form-control" name="Observacion" rows="3" placeholder="Notas adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Motor -->
<div class="modal fade" id="modalEditarMotor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditarMotor" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Motor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-tag"></i> ID Motor *</label>
                        <input type="text" class="form-control" id="edit_Id_motor" name="Id_motor" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-circle-fill"></i> Estado *</label>
                        <select class="form-select" id="edit_Estado" name="Estado" required>
                            <option value="Disponible">Disponible</option>
                            <option value="En Reparacion">En Reparación</option>
                            <option value="Funcionando">Funcionando</option>
                            <option value="Descompuesto">Descompuesto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-building"></i> Sucursal</label>
                        <select class="form-select" id="edit_Id_sucursales" name="Id_sucursales">
                            <option value="">Sin asignar</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->Id_sucursales }}">{{ $sucursal->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-chat-left-text"></i> Observación</label>
                        <textarea class="form-control" id="edit_Observacion" name="Observacion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Función para editar motor
    function editarMotor(motor) {
        console.log('=== DEBUG EDITAR MOTOR ===');
        console.log('Motor:', motor);
        
        document.getElementById('edit_id').value = motor.Id_motores;
        document.getElementById('edit_Id_motor').value = motor.Id_motor;
        document.getElementById('edit_Estado').value = motor.Estado;
        document.getElementById('edit_Id_sucursales').value = motor.Id_sucursales || '';
        document.getElementById('edit_Observacion').value = motor.Observacion || '';
        
        // La URL correcta es /administrador/componentes/{id}/update
        const url = `/administrador/componentes/${motor.Id_motores}/update`;
        document.getElementById('formEditarMotor').action = url;
        
        console.log('URL generada:', url);
        console.log('=========================');
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditarMotor'));
        modal.show();
    }

    // Al enviar el formulario, mostrar info
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formEditarMotor');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('=== ENVIANDO FORMULARIO ===');
                console.log('Action URL:', this.action);
                console.log('Método:', this.method);
                console.log('===========================');
            });
        }
    });

    function eliminarMotor(id) {
        if (confirm('¿Está seguro de eliminar este motor? Esta acción no se puede deshacer.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/administrador/componentes/${id}/delete`;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function verHistorial(id) {
        window.location.href = `/administrador/componentes/${id}/historial`;
    }

    // Filtros de tabla
    document.getElementById('filtroEstado').addEventListener('change', filtrarTabla);
    document.getElementById('filtroUbicacion').addEventListener('change', filtrarTabla);
    document.getElementById('filtroSucursal').addEventListener('change', filtrarTabla);
    document.getElementById('filtroTecnico').addEventListener('input', filtrarTabla);
    document.getElementById('buscarMotor').addEventListener('input', filtrarTabla);

    function filtrarTabla() {
        const estado = document.getElementById('filtroEstado').value.toLowerCase();
        const ubicacion = document.getElementById('filtroUbicacion').value.toLowerCase();
        const sucursal = document.getElementById('filtroSucursal').value;
        const tecnico = document.getElementById('filtroTecnico').value.toLowerCase();
        const buscar = document.getElementById('buscarMotor').value.toLowerCase();

        const filas = document.querySelectorAll('#tablaMotores tbody tr');
        
        filas.forEach(function(fila) {
            const rowEstado = fila.getAttribute('data-estado').toLowerCase();
            const rowUbicacion = fila.getAttribute('data-ubicacion').toLowerCase();
            const rowSucursal = fila.getAttribute('data-sucursal');
            const rowTecnico = fila.getAttribute('data-tecnico');
            const rowId = fila.getAttribute('data-id');

            let mostrar = true;

            if (estado && !rowEstado.includes(estado)) mostrar = false;
            if (ubicacion && !rowUbicacion.includes(ubicacion)) mostrar = false;
            if (sucursal && rowSucursal !== sucursal) mostrar = false;
            if (tecnico && !rowTecnico.includes(tecnico)) mostrar = false;
            if (buscar && !rowId.includes(buscar)) mostrar = false;

            fila.style.display = mostrar ? '' : 'none';
        });
    }
</script>
@endsection