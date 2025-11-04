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
                                            data-programa="{{ $horario->Id_programas }}"
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
                <option value="{{ $e->Id_estudiantes }}">
                  {{ $e->persona?->Nombre }} {{ $e->persona?->Apellido }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Profesor --}}
          <div class="mb-3">
            <label for="Id_profesores_crear" class="form-label">Profesor</label>
            <select class="form-select" id="Id_profesores_crear" name="Id_profesores" required>
              <option value="" disabled selected>Seleccione un profesor</option>
              @foreach($profesores as $p)
                <option value="{{ $p->Id_profesores }}">
                  {{ $p->persona?->Nombre }} {{ $p->persona?->Apellido }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Programa --}}
          <div class="mb-3">
            <label for="Id_programas_crear" class="form-label">Programa</label>
            <select class="form-select" id="Id_programas_crear" name="Id_programas" required>
              <option value="" disabled selected>Seleccione un programa</option>
              @foreach($programas as $prog)
                <option value="{{ $prog->Id_programas }}">{{ $prog->Nombre }}</option>
              @endforeach
            </select>
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
                <option value="{{ $e->Id_estudiantes }}">
                  {{ $e->persona?->Nombre }} {{ $e->persona?->Apellido }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Profesor --}}
          <div class="mb-3">
            <label for="Id_profesores_editar" class="form-label">Profesor</label>
            <select class="form-select" id="Id_profesores_editar" name="Id_profesores" required>
              @foreach($profesores as $p)
                <option value="{{ $p->Id_profesores }}">
                  {{ $p->persona?->Nombre }} {{ $p->persona?->Apellido }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Programa --}}
          <div class="mb-3">
            <label for="Id_programas_editar" class="form-label">Programa</label>
            <select class="form-select" id="Id_programas_editar" name="Id_programas" required>
              @foreach($programas as $prog)
                <option value="{{ $prog->Id_programas }}">{{ $prog->Nombre }}</option>
              @endforeach
            </select>
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

{{-- Script para pasar datos al modal de edición --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEditar = document.getElementById('modalEditar');

    modalEditar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const estudiante = button.getAttribute('data-estudiante');
        const profesor = button.getAttribute('data-profesor');
        const programa = button.getAttribute('data-programa');
        const dia = button.getAttribute('data-dia');
        const hora = button.getAttribute('data-hora');

        // Form action update
        const form = document.getElementById('formEditar');
        form.action = "{{ route('horarios.update', ':id') }}".replace(':id', id);

        // Set values
        document.getElementById('Id_estudiantes_editar').value = estudiante;
        document.getElementById('Id_profesores_editar').value = profesor;
        document.getElementById('Id_programas_editar').value = programa;
        document.getElementById('Dia_editar').value = dia;
        document.getElementById('Hora_editar').value = hora;
    });
});
</script>

@endsection
