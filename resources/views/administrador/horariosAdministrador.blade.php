@extends('administrador.baseAdministrador')

@section('title', 'Horarios de Estudiantes')

@section('styles')
<link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Horarios Asignados</h1>

        {{-- Botón que abre el modal de creación --}}
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="fas fa-plus me-2"></i> Asignar nuevo horario
        </button>
    </div>

    {{-- Mensajes de éxito / error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de horarios --}}
    @if($horarios->isEmpty())
        <div class="alert alert-warning">No hay horarios asignados aún.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Programa</th>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Profesor Asignado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horarios as $horario)
                        <tr>
                            <td>{{ $horario->estudiante?->persona?->Nombre ?? '—' }}</td>
                            <td>{{ $horario->estudiante?->persona?->Apellido ?? '—' }}</td>
                            <td>{{ $horario->programa?->Nombre ?? '—' }}</td>
                            <td>{{ $horario->Dia ?? '—' }}</td>
                            <td>{{ $horario->Hora ?? '—' }}</td>
                            <td>
                                @php $pp = $horario->profesor?->persona; @endphp
                                {{ ($pp?->Nombre && $pp?->Apellido) ? ($pp->Nombre.' '.$pp->Apellido) : 'Sin profesor' }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Abrir modal de edición --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-success btn-editar"
                                            title="Editar horario"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar"
                                            data-id="{{ $horario->Id_horarios }}"
                                            data-estudiante="{{ $horario->Id_estudiantes }}"
                                            data-profesor="{{ $horario->Id_profesores }}"
                                            data-dia="{{ $horario->Dia }}"
                                            data-hora="{{ $horario->Hora }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('horarios.destroy', $horario->Id_horarios) }}" method="POST" onsubmit="return confirm('¿Eliminar este horario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $horarios->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Modal Crear Horario --}}
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('horarios.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearLabel">Asignar nuevo horario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          {{-- Estudiante --}}
          <div class="mb-3">
            <label for="Id_estudiantes_crear" class="form-label">Estudiante</label>
            <select class="form-select" id="Id_estudiantes_crear" name="Id_estudiantes" required>
              <option value="" disabled selected>Seleccione un estudiante</option>
              @foreach($estudiantes as $e)
                <option value="{{ $e->Id_estudiantes }}" 
                        data-profesor="{{ $e->Id_profesores }}"
                        data-programa="{{ $e->Id_programas }}"
                        data-profesor-nombre="{{ $e->profesor ? $e->profesor->persona->Nombre . ' ' . $e->profesor->persona->Apellido : 'Sin profesor' }}"
                        data-programa-nombre="{{ $e->programa ? $e->programa->Nombre : 'Sin programa' }}">
                  {{ $e->persona?->Nombre }} {{ $e->persona?->Apellido }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Profesor --}}
          <div class="mb-3" id="div_profesor_crear">
            <label for="Id_profesores_crear" class="form-label">Profesor</label>
            <select class="form-select" id="Id_profesores_crear" name="Id_profesores" required>
              <option value="" disabled selected>Seleccione un estudiante primero</option>
              @foreach($profesores as $p)
                <option value="{{ $p->Id_profesores }}">
                  {{ $p->persona?->Nombre }} {{ $p->persona?->Apellido }}
                </option>
              @endforeach
            </select>
            <small class="form-text text-muted" id="profesor_help_crear">
              El profesor se asignará automáticamente si el estudiante ya tiene uno
            </small>
          </div>

          {{-- Programa (Solo lectura) --}}
          <div class="mb-3">
            <label for="programa_info_crear" class="form-label">Programa Inscrito</label>
            <input type="text" class="form-control" id="programa_info_crear" readonly 
                   placeholder="Seleccione un estudiante primero">
            <small class="form-text text-muted">El programa se obtiene automáticamente del estudiante</small>
          </div>

          {{-- Día --}}
          <div class="mb-3">
            <label for="Dia_crear" class="form-label">Día</label>
            <select class="form-select" id="Dia_crear" name="Dia" required>
              <option value="" disabled selected>Seleccione</option>
              @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $dia)
                <option value="{{ $dia }}">{{ $dia }}</option>
              @endforeach
            </select>
          </div>

          {{-- Hora --}}
          <div class="mb-3">
            <label for="Hora_crear" class="form-label">Hora</label>
            <input type="time" class="form-control" id="Hora_crear" name="Hora" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Editar Horario --}}
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formEditar">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar horario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          {{-- Estudiante --}}
          <div class="mb-3">
            <label for="Id_estudiantes_editar" class="form-label">Estudiante</label>
            <select class="form-select" id="Id_estudiantes_editar" name="Id_estudiantes" required>
              @foreach($estudiantes as $e)
                <option value="{{ $e->Id_estudiantes }}"
                        data-profesor="{{ $e->Id_profesores }}"
                        data-programa="{{ $e->Id_programas }}"
                        data-profesor-nombre="{{ $e->profesor ? $e->profesor->persona->Nombre . ' ' . $e->profesor->persona->Apellido : 'Sin profesor' }}"
                        data-programa-nombre="{{ $e->programa ? $e->programa->Nombre : 'Sin programa' }}">
                  {{ $e->persona?->Nombre }} {{ $e->persona?->Apellido }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Profesor --}}
          <div class="mb-3" id="div_profesor_editar">
            <label for="Id_profesores_editar" class="form-label">Profesor</label>
            <select class="form-select" id="Id_profesores_editar" name="Id_profesores" required>
              @foreach($profesores as $p)
                <option value="{{ $p->Id_profesores }}">
                  {{ $p->persona?->Nombre }} {{ $p->persona?->Apellido }}
                </option>
              @endforeach
            </select>
            <small class="form-text text-muted" id="profesor_help_editar">
              El profesor se asignará automáticamente si el estudiante ya tiene uno
            </small>
          </div>

          {{-- Programa (Solo lectura) --}}
          <div class="mb-3">
            <label for="programa_info_editar" class="form-label">Programa Inscrito</label>
            <input type="text" class="form-control" id="programa_info_editar" readonly>
            <small class="form-text text-muted">El programa se obtiene automáticamente del estudiante</small>
          </div>

          {{-- Día --}}
          <div class="mb-3">
            <label for="Dia_editar" class="form-label">Día</label>
            <select class="form-select" id="Dia_editar" name="Dia" required>
              @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $dia)
                <option value="{{ $dia }}">{{ $dia }}</option>
              @endforeach
            </select>
          </div>

          {{-- Hora --}}
          <div class="mb-3">
            <label for="Hora_editar" class="form-label">Hora</label>
            <input type="time" class="form-control" id="Hora_editar" name="Hora" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Script para mostrar información automática del estudiante --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Función para actualizar información del estudiante
    function actualizarInfoEstudiante(selectElement, profesorSelect, programaInput, helpText) {
        const option = selectElement.selectedOptions[0];
        if (option && option.value) {
            const profesorId = option.getAttribute('data-profesor');
            const programaNombre = option.getAttribute('data-programa-nombre') || 'Sin programa asignado';
            const profesorNombre = option.getAttribute('data-profesor-nombre') || '';
            
            // Actualizar programa
            programaInput.value = programaNombre;

            // Validar programa
            if (programaNombre === 'Sin programa asignado') {
                programaInput.classList.add('is-invalid');
            } else {
                programaInput.classList.remove('is-invalid');
            }

            // Manejar selector de profesor
            if (profesorId && profesorId !== 'null' && profesorId !== '') {
                // El estudiante YA tiene profesor asignado
                profesorSelect.value = profesorId;
                profesorSelect.disabled = true;
                profesorSelect.classList.add('bg-light');
                helpText.textContent = `Profesor ya asignado: ${profesorNombre}`;
                helpText.classList.remove('text-muted');
                helpText.classList.add('text-success');
            } else {
                // El estudiante NO tiene profesor, permitir selección
                profesorSelect.value = '';
                profesorSelect.disabled = false;
                profesorSelect.classList.remove('bg-light');
                helpText.textContent = 'Seleccione un profesor para asignar al estudiante';
                helpText.classList.remove('text-success');
                helpText.classList.add('text-warning');
            }
        } else {
            programaInput.value = '';
            profesorSelect.value = '';
            profesorSelect.disabled = true;
            helpText.textContent = 'Seleccione un estudiante primero';
            helpText.classList.remove('text-success', 'text-warning');
            helpText.classList.add('text-muted');
        }
    }

    // Modal de creación
    const estudianteCrear = document.getElementById('Id_estudiantes_crear');
    const profesorCrear = document.getElementById('Id_profesores_crear');
    const programaCrear = document.getElementById('programa_info_crear');
    const helpCrear = document.getElementById('profesor_help_crear');

    if (estudianteCrear && profesorCrear && programaCrear) {
        estudianteCrear.addEventListener('change', function () {
            actualizarInfoEstudiante(this, profesorCrear, programaCrear, helpCrear);
        });
    }

    // Modal de edición
    const modalEditar = document.getElementById('modalEditar');
    const estudianteEditar = document.getElementById('Id_estudiantes_editar');
    const profesorEditar = document.getElementById('Id_profesores_editar');
    const programaEditar = document.getElementById('programa_info_editar');
    const helpEditar = document.getElementById('profesor_help_editar');

    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const estudiante = button.getAttribute('data-estudiante');
            const profesor = button.getAttribute('data-profesor');
            const dia = button.getAttribute('data-dia');
            const hora = button.getAttribute('data-hora');

            const form = document.getElementById('formEditar');
            form.action = "{{ route('horarios.update', ':id') }}".replace(':id', id);

            document.getElementById('Id_estudiantes_editar').value = estudiante;
            document.getElementById('Id_profesores_editar').value = profesor;
            document.getElementById('Dia_editar').value = dia;
            document.getElementById('Hora_editar').value = hora;

            // Actualizar información del estudiante seleccionado
            actualizarInfoEstudiante(estudianteEditar, profesorEditar, programaEditar, helpEditar);
        });

        // Actualizar cuando cambie el estudiante en edición
        if (estudianteEditar && profesorEditar && programaEditar) {
            estudianteEditar.addEventListener('change', function () {
                actualizarInfoEstudiante(this, profesorEditar, programaEditar, helpEditar);
            });
        }
    }
});
</script>

@endsection