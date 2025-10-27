@extends('administrador.baseAdministrador')

@section('title', 'Horarios de Estudiantes')

@section('styles')
<link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
{{-- Si usas Bootstrap Icons en los botones (bi bi-*) asegúrate de cargar su CSS en el layout base --}}
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Horarios Asignados</h1>

        {{-- Botón que abre el modal --}}
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAsignar">
            <i class="fas fa-plus me-2"></i> Asignar profesor a estudiante
        </button>
    </div>

    {{-- Mensajes de éxito / error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
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
                        <th>Día 1</th>
                        <th>Horario 1</th>
                        <th>Día 2</th>
                        <th>Horario 2</th>
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

                            <td>{{ $horario->Dia_clase_uno ?? '—' }}</td>
                            <td>{{ $horario->Horario_clase_uno ?? '—' }}</td>
                            <td>{{ $horario->Dia_clase_dos ?? '—' }}</td>
                            <td>{{ $horario->Horario_clase_dos ?? '—' }}</td>

                            <td>
                                @php $pp = $horario->profesor?->persona; @endphp
                                {{ ($pp?->Nombre && $pp?->Apellido) ? ($pp->Nombre.' '.$pp->Apellido) : 'Sin profesor' }}
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    {{-- Abrir modal con el estudiante/horario preseleccionados --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-success"
                                            title="Asignar/Modificar Profesor"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAsignar"
                                            data-estudiante="{{ $horario->Id_estudiantes }}"
                                            data-horario="{{ $horario->Id_horarios }}"
                                            data-programa="{{ $horario->Id_programas }}"
                                            data-dia1="{{ $horario->Dia_clase_uno }}"
                                            data-hora1="{{ $horario->Horario_clase_uno }}"
                                            data-dia2="{{ $horario->Dia_clase_dos }}"
                                            data-hora2="{{ $horario->Horario_clase_dos }}"
                                            data-profesor="{{ $horario->Id_profesores }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

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

{{-- Modal: Asignar profesor a estudiante (con Programa y Días/Horas) --}}
<div class="modal fade" id="modalAsignar" tabindex="-1" aria-labelledby="modalAsignarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('horarios.asignar') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalAsignarLabel">Asignar profesor a estudiante</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          {{-- Hidden para actualizar un horario existente si se abre desde una fila --}}
          <input type="hidden" name="horario_id" id="horario_id">

          {{-- Estudiante --}}
          <div class="mb-3">
            <label for="Id_estudiantes" class="form-label">Estudiante</label>
            <select class="form-select" id="Id_estudiantes" name="Id_estudiantes" required>
              <option value="" disabled selected>Seleccione un estudiante</option>
              @foreach($estudiantes as $e)
                <option value="{{ $e->Id_estudiantes }}">
                  {{ $e->persona?->Nombre }} {{ $e->persona?->Apellido }} (ID: {{ $e->Id_estudiantes }})
                </option>
              @endforeach
            </select>
          </div>

          {{-- Profesor --}}
          <div class="mb-3">
            <label for="Id_profesores" class="form-label">Profesor</label>
            <select class="form-select" id="Id_profesores" name="Id_profesores" required>
              <option value="" disabled selected>Seleccione un profesor</option>
              @foreach($profesores as $p)
                <option value="{{ $p->Id_profesores }}">
                  {{ $p->persona?->Nombre }} {{ $p->persona?->Apellido }} (ID: {{ $p->Id_profesores }})
                </option>
              @endforeach
            </select>
          </div>

          {{-- Programa --}}
          <div class="mb-3">
            <label for="Id_programas" class="form-label">Programa</label>
            <select class="form-select" id="Id_programas" name="Id_programas" required>
              <option value="" disabled selected>Seleccione un programa</option>
              @foreach($programas as $prog)
                <option value="{{ $prog->Id_programas }}">{{ $prog->Nombre ?? ('Programa '.$prog->Id_programas) }}</option>
              @endforeach
            </select>
          </div>

          <hr>

          {{-- Día/Horario 1 --}}
          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label for="Dia_clase_uno" class="form-label">Día 1</label>
              <select class="form-select" id="Dia_clase_uno" name="Dia_clase_uno" required>
                <option value="" disabled selected>Seleccione</option>
                @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $dia)
                  <option value="{{ $dia }}">{{ $dia }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label for="Horario_clase_uno" class="form-label">Horario 1</label>
              <input type="time" class="form-control" id="Horario_clase_uno" name="Horario_clase_uno" required>
            </div>
          </div>

          {{-- Día/Horario 2 --}}
          <div class="row g-2">
            <div class="col-md-6">
              <label for="Dia_clase_dos" class="form-label">Día 2</label>
              <select class="form-select" id="Dia_clase_dos" name="Dia_clase_dos" required>
                <option value="" disabled selected>Seleccione</option>
                @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $dia)
                  <option value="{{ $dia }}">{{ $dia }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label for="Horario_clase_dos" class="form-label">Horario 2</label>
              <input type="time" class="form-control" id="Horario_clase_dos" name="Horario_clase_dos" required>
            </div>
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
<script src="{{ auto_asset('js/administrador/horariosAdministrador.js') }}"></script>
@endsection
