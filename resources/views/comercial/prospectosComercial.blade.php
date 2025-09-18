@extends('administrador.baseAdministrador')

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

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        flex-shrink: 0;
    }

    .estado-select {
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        border-radius: 6px;
    }

    .estado-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        background-color: white;
    }

    /* Estilos específicos para cada estado */
    .estado-nuevo {
        background-color: #d1edcc !important;
        color: #0f5132 !important;
        border-color: #28a745 !important;
    }

    .estado-contactado {
        background-color: #cfe2ff !important;
        color: #084298 !important;
        border-color: #007bff !important;
    }

    .estado-clase.de.prueba {
        background-color: #fff3cd !important;
        color: #664d03 !important;
        border-color: #ffc107 !important;
    }

    /* Estilos para badges */
    .estado-badge {
        font-size: 13px;
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }

    .clase-badge {
        font-size: 12px;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 15px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .badge {
        font-size: 12px;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .modal-content {
        border-radius: 12px;
    }

    .text-gray-800 {
        color: #2d3748 !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .filtro-fecha-box {
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border-radius: 16px;
        background: #fff;
        min-width: 420px;
    }
    .filtro-fecha-box .btn-light {
        box-shadow: none;
        border: none;
        background: #f8f9fa;
    }
    .filtro-fecha-box .btn-light:active, .filtro-fecha-box .btn-light:focus {
        background: #e2e6ea;
    }
    .filtro-fecha-box #filtroFechaTexto {
        min-width: 180px;
        text-align: center;
        font-size: 1rem;
        letter-spacing: 0.5px;
    }

    .filtro-fecha-box input[type="date"] {
        min-width: 120px;
        max-width: 160px;
        margin-left: 0 !important;
    }
    .filtro-fecha-box .form-select-sm {
        min-width: 120px;
    }
</style>

<script>
function abrirModalClasePrueba(id, nombre) {
    // Usar Bootstrap 5 modal
    const modal = new bootstrap.Modal(document.getElementById('modalClasePrueba'));
    modal.show();
    
    document.getElementById('modalIdProspectos').value = id;
    document.getElementById('modalNombreProspecto').value = nombre;
    document.getElementById('modalNombreEstudiante').value = nombre;
    document.querySelector('textarea[name="Comentarios"]').value = 'Clase de prueba asignada a: ' + nombre;
}

// Función para ver detalles de la clase de prueba
function verClasePrueba(nombre, fecha, hora, comentarios) {
    document.getElementById('verNombreEstudiante').textContent = nombre;
    document.getElementById('verFechaClase').textContent = fecha;
    document.getElementById('verHoraClase').textContent = hora;
    document.getElementById('verComentariosClase').textContent = comentarios;
    const modal = new bootstrap.Modal(document.getElementById('modalVerClasePrueba'));
    modal.show();
}

// Función para actualizar automáticamente el estado cuando se asigna una clase
function actualizarEstadoAClaseAsignada(prospectoId) {
    fetch(`/prospectos/${prospectoId}/updateEstado`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            'Estado_prospecto': 'clase asignada'
        })
    });
}

// Cambiar rango de fechas
function cambiarRango(direccion) {
    const inputDesde = document.getElementById('inputDesde');
    const inputHasta = document.getElementById('inputHasta');
    let desde = inputDesde.value ? new Date(inputDesde.value) : null;
    let hasta = inputHasta.value ? new Date(inputHasta.value) : null;

    // Si no hay fechas, usar hoy y hoy+6 días
    if (!desde || !hasta) {
        const hoy = new Date();
        desde = new Date(hoy);
        hasta = new Date(hoy);
        hasta.setDate(hasta.getDate() + 6);
    }

    // Calcular la diferencia de días
    const diff = Math.round((hasta - desde) / (1000*60*60*24)) || 6;
    let nuevoDesde, nuevoHasta;
    if (direccion === 'prev') {
        nuevoDesde = new Date(desde);
        nuevoDesde.setDate(nuevoDesde.getDate() - (diff+1));
        nuevoHasta = new Date(hasta);
        nuevoHasta.setDate(nuevoHasta.getDate() - (diff+1));
    } else {
        nuevoDesde = new Date(desde);
        nuevoDesde.setDate(nuevoDesde.getDate() + (diff+1));
        nuevoHasta = new Date(hasta);
        nuevoHasta.setDate(nuevoHasta.getDate() + (diff+1));
    }
    // Formatear YYYY-MM-DD
    function formatDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
    }
    inputDesde.value = formatDate(nuevoDesde);
    inputHasta.value = formatDate(nuevoHasta);
    document.getElementById('filtroFechasForm').submit();
}

// Aplicar colores según el estado al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.estado-select');
    selects.forEach(function(select) {
        const estado = select.value.replace(/\s+/g, '.');
        select.classList.remove('estado-nuevo', 'estado-contactado', 'estado-clase.de.prueba');
        select.classList.add('estado-' + estado);
        
        // Cambiar colores al seleccionar
        select.addEventListener('change', function() {
            const nuevoEstado = this.value.replace(/\s+/g, '.');
            this.classList.remove('estado-nuevo', 'estado-contactado', 'estado-clase.de.prueba');
            this.classList.add('estado-' + nuevoEstado);
        });
    });
});
</script>
@endsection