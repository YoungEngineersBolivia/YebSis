@extends('administrador.baseAdministrador')

@section('title', 'Pagos')

@section('content')
<div class="container mt-4">
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

    <h2>Pagos de Estudiantes</h2>

    <form method="GET" action="{{ route('pagos.index') }}">
        <div class="mb-3 position-relative">
            <input type="text" id="buscarEstudiante" name="nombre" placeholder="Buscar estudiante" value="{{ request('nombre') }}" autocomplete="off" class="form-control">
            <div id="sugerencias" class="list-group position-absolute w-100" style="z-index:1000;"></div>
        </div>
       
    </form>

    <hr>

    <div id="estudiante-info" style="display:none;"></div>

    <div id="lista-estudiantes">
        @foreach($estudiantes as $estudiante)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>
                        {{ $estudiante->persona->Nombre ?? '' }} {{ $estudiante->persona->Apellido ?? '' }}
                    </h5>
                    <p>
                        Tutor:
                        {{ $estudiante->tutor && $estudiante->tutor->persona ? $estudiante->tutor->persona->Nombre . ' ' . $estudiante->tutor->persona->Apellido : 'Sin padre asignado' }}
                    </p>
                    <h6>Cuotas:</h6>
                    <ul>
                        @if($estudiante->planPago && $estudiante->planPago->cuotas && count($estudiante->planPago->cuotas) > 0)
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                      
                                        <th>Nro_de_cuota</th>
                                        <th>Fecha_vencimiento</th>
                                        <th>Monto_cuota</th>
                                        <th>Monto_pagado</th>
                                        <th>Estado_cuota</th>
                                      
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estudiante->planPago->cuotas as $cuota)
                                        <tr>
                                    
                                            <td>{{ $cuota->Nro_de_cuota }}</td>
                                            <td>{{ $cuota->Fecha_vencimiento }}</td>
                                            <td>{{ $cuota->Monto_cuota }}</td>
                                            <td>{{ $cuota->Monto_pagado ?? 'NULL' }}</td>
                                            <td>{{ $cuota->Estado_cuota }}</td>
                                         
                                            <td>
                                                @if(!$cuota->pagado)
                                                    <button type="button" class="btn btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalRegistrarPago"
                                                        data-cuota-id="{{ $cuota->Id_cuotas }}"
                                                        data-monto="{{ $cuota->Monto_cuota }}"
                                                        data-plan-id="{{ $cuota->Id_planes_pagos }}">
                                                        <i class="fas fa-money-bill"></i> Registrar
                                                    </button>
                                                @else
                                                    <span class="badge bg-success">Pagado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($estudiante->planPago)
                            <li>
                                El estudiante tiene un plan de pago (ID: {{ $estudiante->planPago->id }}), pero no tiene cuotas registradas.
                            </li>
                        @else
                            <li>
                                El estudiante no tiene plan de pago asignado.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal para registrar pago -->
<div class="modal fade" id="modalRegistrarPago" tabindex="-1" aria-labelledby="modalRegistrarPagoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('pagos.registrar') }}">
        @csrf
        <input type="hidden" name="cuota_id" id="modal-cuota-id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarPagoLabel">Registrar Pago de Cuota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label for="modal-descripcion" class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" id="modal-descripcion" required>
                </div>
                <div class="mb-2">
                    <label for="modal-comprobante" class="form-label">Comprobante</label>
                    <input type="text" class="form-control" name="comprobante" id="modal-comprobante" required>
                </div>
                <div class="mb-2">
                    <label for="modal-monto-pago" class="form-label">Monto Pago</label>
                    <input type="number" step="0.01" class="form-control" name="monto_pago" id="modal-monto-pago" required>
                </div>
                <div class="mb-2">
                    <label for="modal-fecha-pago" class="form-label">Fecha Pago</label>
                    <input type="date" class="form-control" name="fecha_pago" id="modal-fecha-pago" required>
                </div>
                <div class="mb-2">
                    <label for="modal-id-planes-pagos" class="form-label">ID Planes Pagos</label>
                    <input type="number" class="form-control" name="id_planes_pagos" id="modal-id-planes-pagos" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Pago</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
// Usa values() para asegurar que el array sea plano y serializable correctamente
const estudiantes = @json($estudiantes);
const csrfToken = '{{ csrf_token() }}';
const registrarPagoUrl = '{{ route('pagos.registrar') }}';

const buscarInput = document.getElementById('buscarEstudiante');
const sugerenciasDiv = document.getElementById('sugerencias');
const estudianteInfoDiv = document.getElementById('estudiante-info');
const listaEstudiantesDiv = document.getElementById('lista-estudiantes');

buscarInput.addEventListener('input', function() {
    const val = this.value.trim().toLowerCase();
    sugerenciasDiv.innerHTML = '';
    estudianteInfoDiv.style.display = 'none';
    listaEstudiantesDiv.style.display = '';

    if (!val) return;

    const filtrados = estudiantes.filter(est => {
        const nombreCompleto = `${est.persona.Nombre} ${est.persona.Apellido}`.toLowerCase();
        return nombreCompleto.includes(val);
    });

    if (filtrados.length > 0) {
        filtrados.forEach(est => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action';
            btn.textContent = `${est.persona.Nombre} ${est.persona.Apellido}`;
            btn.onclick = () => {
                mostrarEstudiante(est);
                buscarInput.value = btn.textContent;
                sugerenciasDiv.innerHTML = '';
            };
            sugerenciasDiv.appendChild(btn);
        });
    } else {
        const noData = document.createElement('div');
        noData.className = 'list-group-item text-muted';
        noData.textContent = 'Sin coincidencias';
        sugerenciasDiv.appendChild(noData);
    }
});

function mostrarEstudiante(est) {
    console.log(est); // Depuración
    let cuotas = [];
    if (est.plan_pago && est.plan_pago.cuotas) {
        cuotas = est.plan_pago.cuotas;
    }
    let html = `
        <div class="card mb-3">
            <div class="card-body">
                <h5>${est.persona.Nombre ?? ''} ${est.persona.Apellido ?? ''}</h5>
                <p>
                    Padre: ${est.tutor && est.tutor.persona ? est.tutor.persona.Nombre + ' ' + est.tutor.persona.Apellido : 'Sin padre asignado'}
                </p>
                <h6>Cuotas:</h6>
    `;
    if (est.plan_pago) {
        if (cuotas.length > 0) {
            html += `
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Nro_de_cuota</th>
                            <th>Fecha_vencimiento</th>
                            <th>Monto_cuota</th>
                            <th>Monto_pagado</th>
                            <th>Estado_cuota</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            cuotas.forEach(cuota => {
                html += `
                    <tr>
                        <td>${cuota.Nro_de_cuota}</td>
                        <td>${cuota.Fecha_vencimiento}</td>
                        <td>${cuota.Monto_cuota}</td>
                        <td>${cuota.Monto_pagado ?? 'NULL'}</td>
                        <td>${cuota.Estado_cuota}</td>
                        <td>
                            ${!cuota.pagado ? `
                                <button type="button" 
                                    class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalRegistrarPago"
                                    data-cuota-id="${cuota.Id_cuotas}"
                                    data-monto="${cuota.Monto_cuota}"
                                    data-plan-id="${cuota.Id_planes_pagos}">
                                    Registrar Pago
                                </button>
                            ` : 'Pagado'}
                        </td>
                    </tr>
                `;
            });
            html += `
                    </tbody>
                </table>
            `;
        } else {
            html += '<ul><li>El estudiante tiene un plan de pago (ID: ' + est.plan_pago.id + '), pero no tiene cuotas registradas.</li></ul>';
        }
    } else {
        html += '<ul><li>El estudiante no tiene plan de pago asignado.</li></ul>';
    }
    html += `
            </div>
        </div>
    `;
    estudianteInfoDiv.innerHTML = html;
    estudianteInfoDiv.style.display = '';
    listaEstudiantesDiv.style.display = 'none';
}

buscarInput.addEventListener('blur', () => {
    setTimeout(() => sugerenciasDiv.innerHTML = '', 150);
});

// Modal: pasar el id de la cuota al input oculto
const modalRegistrarPago = document.getElementById('modalRegistrarPago');
modalRegistrarPago.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    
    // Debug: Mostrar el botón completo
    console.log('Botón:', button);
    console.log('Atributos:', {
        cuotaId: button.getAttribute('data-cuota-id'),
        monto: button.getAttribute('data-monto'),
        planId: button.getAttribute('data-plan-id')
    });
    
    // Obtener datos del botón
    const cuotaId = button.getAttribute('data-cuota-id');
    const monto = button.getAttribute('data-monto');
    const planId = button.getAttribute('data-plan-id');
    
    // Verificar que tenemos los datos
    if (!cuotaId || !monto || !planId) {
        console.error('Faltan datos de la cuota:', { cuotaId, monto, planId });
        return;
    }

    // Llenar el formulario
    document.getElementById('modal-cuota-id').value = cuotaId;
    document.getElementById('modal-monto-pago').value = monto;
    document.getElementById('modal-id-planes-pagos').value = planId;
    document.getElementById('modal-fecha-pago').value = new Date().toISOString().split('T')[0];
    document.getElementById('modal-descripcion').value = `Pago de cuota #${cuotaId}`;
    document.getElementById('modal-comprobante').value = `COMP-${new Date().getTime()}-${cuotaId}`;
});
</script>
@endsection

