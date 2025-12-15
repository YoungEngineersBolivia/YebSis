@extends('profesor.baseProfesor')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('profesor.home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="row mb-4">
        <div class="col">
            <h2 class="text-uppercase fw-bold text-dark">
                <i class="bi bi-chalkboard-teacher me-2"></i> Clases de Prueba Asignadas
            </h2>
            <p class="text-muted mb-0">Gestione la asistencia y observaciones de sus clases de prueba.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4">Estudiante</th>
                            <th class="py-3">Fecha y Hora</th>
                            <th class="py-3">Asistencia</th>
                            <th class="py-3">Comentarios</th>
                            <th class="py-3 text-end pe-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clases as $clase)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $clase->Nombre_Estudiante }}</div>
                                <small class="text-muted">Prospecto: {{ $clase->prospecto->Nombre }} {{ $clase->prospecto->Apellido }}</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($clase->Fecha_clase)->format('d/m/Y') }}</span>
                                    <span class="text-muted small">{{ \Carbon\Carbon::parse($clase->Hora_clase)->format('H:i') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="asistencia_{{ $clase->Id_clasePrueba }}" id="btnradio1_{{ $clase->Id_clasePrueba }}" autocomplete="off" {{ $clase->Asistencia === 'asistio' ? 'checked' : '' }} onclick="updateAttendance({{ $clase->Id_clasePrueba }}, 'asistio')">
                                    <label class="btn btn-outline-success btn-sm" for="btnradio1_{{ $clase->Id_clasePrueba }}">Asistió</label>

                                    <input type="radio" class="btn-check" name="asistencia_{{ $clase->Id_clasePrueba }}" id="btnradio2_{{ $clase->Id_clasePrueba }}" autocomplete="off" {{ $clase->Asistencia === 'no_asistio' ? 'checked' : '' }} onclick="updateAttendance({{ $clase->Id_clasePrueba }}, 'no_asistio')">
                                    <label class="btn btn-outline-danger btn-sm" for="btnradio2_{{ $clase->Id_clasePrueba }}">Falta</label>
                                </div>
                            </td>
                            <td style="width: 35%;">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="comentario_{{ $clase->Id_clasePrueba }}" value="{{ $clase->Comentarios }}" placeholder="Observación">
                                    <button class="btn btn-outline-primary" type="button" onclick="updateComment({{ $clase->Id_clasePrueba }})">
                                        <i class="bi bi-save"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                @if($clase->Asistencia === 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($clase->Asistencia === 'asistio')
                                    <span class="badge bg-success">Completada</span>
                                @else
                                    <span class="badge bg-danger">Inasistencia</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                                No tienes clases de prueba asignadas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="d-lg-none bg-light p-3">
                @forelse($clases as $clase)
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="card-title fw-bold mb-1">{{ $clase->Nombre_Estudiante }}</h5>
                                <p class="card-subtitle text-muted small">
                                    Prospecto: {{ $clase->prospecto->Nombre }} {{ $clase->prospecto->Apellido }}
                                </p>
                            </div>
                            @if($clase->Asistencia === 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($clase->Asistencia === 'asistio')
                                <span class="badge bg-success">Completada</span>
                            @else
                                <span class="badge bg-danger">Inasistencia</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ \Carbon\Carbon::parse($clase->Fecha_clase)->format('d/m/Y') }}
                            </span>
                            <span class="badge bg-light text-dark border ms-1">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($clase->Hora_clase)->format('H:i') }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Asistencia:</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="mobile_asistencia_{{ $clase->Id_clasePrueba }}" id="mobile_btnradio1_{{ $clase->Id_clasePrueba }}" autocomplete="off" {{ $clase->Asistencia === 'asistio' ? 'checked' : '' }} onclick="updateAttendance({{ $clase->Id_clasePrueba }}, 'asistio')">
                                <label class="btn btn-outline-success" for="mobile_btnradio1_{{ $clase->Id_clasePrueba }}">Asistió</label>

                                <input type="radio" class="btn-check" name="mobile_asistencia_{{ $clase->Id_clasePrueba }}" id="mobile_btnradio2_{{ $clase->Id_clasePrueba }}" autocomplete="off" {{ $clase->Asistencia === 'no_asistio' ? 'checked' : '' }} onclick="updateAttendance({{ $clase->Id_clasePrueba }}, 'no_asistio')">
                                <label class="btn btn-outline-danger" for="mobile_btnradio2_{{ $clase->Id_clasePrueba }}">Falta</label>
                            </div>
                        </div>

                        <div>
                            <label class="form-label small fw-bold text-muted">Comentarios:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="mobile_comentario_{{ $clase->Id_clasePrueba }}" value="{{ $clase->Comentarios }}" placeholder="Observación">
                                <button class="btn btn-primary" type="button" onclick="updateComment({{ $clase->Id_clasePrueba }}, true)">
                                    <i class="bi bi-save"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
                    No tienes clases de prueba asignadas.
                </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $clases->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateAttendance(id, status) {
        // Intentar obtener el input de comentario desktop y mobile
        const commentInputDesktop = document.getElementById(`comentario_${id}`);
        const commentInputMobile = document.getElementById(`mobile_comentario_${id}`);
        
        // Usar el valor del que tenga contenido o del que esté visible
        let comentario = '';
        if (commentInputMobile && commentInputMobile.offsetParent !== null) {
             comentario = commentInputMobile.value.trim();
        } else if (commentInputDesktop) {
             comentario = commentInputDesktop.value.trim();
        }

        const textoAccion = status === 'asistio' ? 'marcar como ASISTIÓ' : 'marcar como FALTA';

        let confirmOptions = {
            title: '¿Confirmar asistencia?',
            text: `Vas a ${textoAccion}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
        };

        if (comentario === '') {
            confirmOptions.title = '¡Atención!';
            confirmOptions.text = `Estás a punto de ${textoAccion} SIN COMENTARIOS. ¿Estás seguro? Es recomendable añadir una observación.`;
            confirmOptions.icon = 'warning';
            confirmOptions.confirmButtonColor = '#d33';
        }

        Swal.fire(confirmOptions).then((result) => {
            if (result.isConfirmed) {
                // Guardar comentario primero
                fetch(`/profesor/clases-prueba/${id}/comentarios`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ comentarios: comentario })
                })
                .then(() => {
                    return fetch(`/profesor/clases-prueba/${id}/asistencia`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ asistencia: status })
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Éxito!', 'Asistencia registrada correctamente.', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Hubo un error al registrar.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error de conexión.', 'error');
                });
            } else {
                // Si cancela, recargar para resetear radio buttons (opcional pero limpio)
                location.reload();
            }
        });
    }

    function updateComment(id, isMobile = false) {
        const inputId = isMobile ? `mobile_comentario_${id}` : `comentario_${id}`;
        const input = document.getElementById(inputId);
        const comentario = input.value.trim();

        if (comentario === '') {
            Swal.fire('Atención', 'El campo de comentarios está vacío.', 'warning');
            return;
        }

        fetch(`/profesor/clases-prueba/${id}/comentarios`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ comentarios: comentario })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: 'Comentario actualizado',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                Swal.fire('Error', 'No se pudo guardar el comentario.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de conexión.', 'error');
        });
    }
</script>
@endsection
@endsection
