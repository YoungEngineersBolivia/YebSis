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
            <div class="table-responsive">
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
                                    <input type="text" class="form-control" id="comentario_{{ $clase->Id_clasePrueba }}" value="{{ $clase->Comentarios }}" placeholder="Nombre estudiante (opcional) / Observación">
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
        // Prevenir el cambio visual del radio button hasta confirmar
        const radio = document.querySelector(`input[name="asistencia_${id}"][onclick*="'${status}'"]`);
        // Deshacer el check visual temporalmente (necesitamos saber cuál estaba antes, pero asumimos pendiente si es click inicial)
        // Mejor enfoque: SweetAlert intercepta antes. Como es onclick, ya cambió.
        // Lo dejamos así y si cancela, revertimos? Es complicado revertir radio.
        // Aceptamos que marque visualmente, si cancela recargamos página o revertimos manual.
        
        const commentInput = document.getElementById(`comentario_${id}`);
        const comentario = commentInput.value.trim();
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
                    body: JSON.stringify({ comentarios: commentInput.value })
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado',
                            text: 'Asistencia registrada correctamente',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                });
            } else {
                // Si cancela, recargamos para revertir el radio button seleccionado visualmente
                location.reload(); 
            }
        });
    }

    function updateComment(id) {
        const comment = document.getElementById(`comentario_${id}`).value;
        fetch(`/profesor/clases-prueba/${id}/comentarios`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ comentarios: comment })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                toast.fire({
                    icon: 'success',
                    title: 'Comentario guardado'
                });
            }
        });
    }
</script>
@endsection
@endsection
