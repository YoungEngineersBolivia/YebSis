@extends('administrador.baseAdministrador')
@section('styles')
    <link href="{{ asset('css/comercial/prospectosComercial.css') }}" rel="stylesheet">

@endsection
@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-users me-2 text-primary"></i>
                    Lista de Prospectos
                </h2>
                <div class="text-muted">
                    Total: {{ count($prospectos) }} prospectos
                </div>
            </div>
            <!-- Filtros de fecha bonitos -->
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-center">
                    <form method="GET" class="d-flex align-items-center gap-2 filtro-fecha-box p-2" id="filtroFechasForm" style="background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); min-width: 420px;">
                        <input type="date" name="desde" id="inputDesde" class="form-control form-control-sm" style="max-width: 160px; margin-left: 0;" value="{{ request('desde') }}">
                        <span class="mx-1">—</span>
                        <input type="date" name="hasta" id="inputHasta" class="form-control form-control-sm" style="max-width: 160px; margin-left: 0;" value="{{ request('hasta') }}">
                        <button type="submit" class="btn btn-primary btn-sm ms-2">Filtrar</button>
                        <select name="filtro_rapido" class="form-select form-select-sm ms-2" style="max-width:180px;" onchange="this.form.submit()">
                            <option value="">Rápido...</option>
                            <option value="ultimos7" @if(request('filtro_rapido')=='ultimos7') selected @endif>Últimos 7 días</option>
                            <option value="ayer" @if(request('filtro_rapido')=='ayer') selected @endif>Ayer</option>
                            <option value="mespasado" @if(request('filtro_rapido')=='mespasado') selected @endif>Mes pasado</option>
                            <option value="ultimos3meses" @if(request('filtro_rapido')=='ultimos3meses') selected @endif>Últimos 3 meses</option>
                            <option value="esteano" @if(request('filtro_rapido')=='esteano') selected @endif>Este año</option>
                            <option value="anopasado" @if(request('filtro_rapido')=='anopasado') selected @endif>Año pasado</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 fw-semibold text-dark py-3">
                                        <i class="fas fa-user me-1"></i>Nombre Completo
                                    </th>
                                    <th class="border-0 fw-semibold text-dark py-3">
                                        <i class="fas fa-phone me-1"></i>Celular
                                    </th>
                                    <th class="border-0 fw-semibold text-dark py-3">
                                        <i class="fas fa-calendar-alt me-1"></i>Fecha de Registro
                                    </th>
                                    <th class="border-0 fw-semibold text-dark py-3">
                                        <i class="fas fa-flag me-1"></i>Estado
                                    </th>
                                    <th class="border-0 fw-semibold text-dark py-3">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>Clase de Prueba
                                    </th>
                                    <th class="border-0 fw-semibold text-dark py-3">
                                        <i class="fas fa-cog me-1"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prospectos as $prospecto)
                                    @php
                                        $clase = isset($clasesPrueba) ? $clasesPrueba->firstWhere('Id_prospectos', $prospecto->Id_prospectos) : null;
                                    @endphp
                                    <tr class="border-bottom">    
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    {{ strtoupper(substr($prospecto->Nombre, 0, 1)) }}{{ strtoupper(substr($prospecto->Apellido, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ $prospecto->Nombre }} {{ $prospecto->Apellido }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-muted">
                                                <i class="fas fa-mobile-alt me-1"></i>
                                                {{ $prospecto->Celular }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            {{ $prospecto->created_at ? $prospecto->created_at->format('d-m-Y') : '' }}
                                        </td>
                                        <td class="py-3">
                                            <form method="POST" action="{{ route('prospectos.updateEstado', $prospecto->Id_prospectos) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="Estado_prospecto" class="form-select form-select-sm estado-select" onchange="this.form.submit()">
                                                    <option value="nuevo" @if($prospecto->Estado_prospecto=='nuevo') selected @endif>Nuevo</option>
                                                    <option value="contactado" @if($prospecto->Estado_prospecto=='contactado') selected @endif>Contactado</option>
                                                    <option value="clase de prueba" @if($prospecto->Estado_prospecto=='clase de prueba') selected @endif>Clase de Prueba</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $tieneClase = $clase !== null;
                                            @endphp
                                            @if($tieneClase)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Asignada
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times me-1"></i>Sin asignar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm"
                                                    onclick="abrirModalClasePrueba('{{ $prospecto->Id_prospectos }}', '{{ $prospecto->Nombre }} {{ $prospecto->Apellido }}')">
                                                <i class="fas fa-plus me-1"></i>
                                                Agregar Clase
                                            </button>
                                            @if($clase)
                                            <button type="button" class="btn btn-info btn-sm ms-1" onclick="verClasePrueba('{{ $clase->Nombre_Estudiante }}', '{{ $clase->Fecha_clase }}', '{{ $clase->Hora_clase }}', `{{ $clase->Comentarios }}`)">
                                                <i class="fas fa-eye me-1"></i>Ver
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
    </div>
</div>

<!-- Modal para agregar clase de prueba -->
<div class="modal fade" id="modalClasePrueba" tabindex="-1" aria-labelledby="modalClasePruebaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalClasePruebaLabel">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Agregar Clase de Prueba
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('claseprueba.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i>
                                Nombre del estudiante
                            </label>
                            <input type="text" 
                                   name="Nombre_Estudiante" 
                                   id="modalNombreEstudiante" 
                                   class="form-control"
                                   value="Ejemplo Estudiante" 
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-primary"></i>
                                Fecha de clase
                            </label>
                            <input type="date" 
                                   name="Fecha_clase" 
                                   class="form-control"
                                   value="2025-09-01" 
                                   required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clock me-1 text-primary"></i>
                                Hora de clase
                            </label>
                            <input type="time" 
                                   name="Hora_clase" 
                                   class="form-control"
                                   value="15:00" 
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-graduate me-1 text-primary"></i>
                                Prospecto asignado
                            </label>
                            <input type="text" 
                                   id="modalNombreProspecto" 
                                   class="form-control"
                                   readonly 
                                   style="background-color: #f8f9fa;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-comment me-1 text-primary"></i>
                            Comentarios
                        </label>
                        <textarea name="Comentarios" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Comentarios adicionales (opcional)"></textarea>
                    </div>
                    <input type="hidden" name="Id_prospectos" id="modalIdProspectos" value="">
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Guardar Clase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver clase de prueba -->
<div class="modal fade" id="modalVerClasePrueba" tabindex="-1" aria-labelledby="modalVerClasePruebaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalVerClasePruebaLabel">
                    <i class="fas fa-eye me-2"></i>
                    Detalle Clase de Prueba
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-2"><strong>Estudiante:</strong> <span id="verNombreEstudiante"></span></div>
                <div class="mb-2"><strong>Fecha:</strong> <span id="verFechaClase"></span></div>
                <div class="mb-2"><strong>Hora:</strong> <span id="verHoraClase"></span></div>
                <div class="mb-2"><strong>Comentarios:</strong> <span id="verComentariosClase"></span></div>
            </div>
        </div>
    </div>
</div>


<script src="{{ auto_asset('js/comercial/prospectosComercial.js') }}"></script>
@endsection