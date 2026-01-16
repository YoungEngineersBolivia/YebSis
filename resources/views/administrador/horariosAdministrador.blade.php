@extends('administrador.baseAdministrador')

@section('title', 'Horarios de Estudiantes')

@section('styles')
<<<<<<< HEAD
  <link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
=======
<link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .search-results-container {
        max-height: 280px;
        overflow-y: auto;
        z-index: 1070;
        border-radius: 0 0 0.5rem 0.5rem;
        background: white;
        border: 1px solid #dee2e6;
        top: 100%;
        left: 0;
    }
    .search-result-item {
        cursor: pointer;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.2s;
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-item:hover {
        background-color: #e9ecef;
        padding-left: 1.5rem;
    }
    .search-result-item .student-code {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .search-result-item .student-name {
        font-weight: 500;
        display: block;
    }
    .search-result-item .student-program {
        font-size: 0.8rem;
        color: #20c997;
    }
</style>
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
@endsection

@section('content')
  <div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0 fw-bold"><i class="bi bi-calendar-week me-2"></i>Horarios Asignados</h2>

<<<<<<< HEAD
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-lg me-2"></i>Asignar nuevo horario
      </button>
=======
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-plus-lg me-2"></i>Asignar nuevo horario
        </button>
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
    </div>

    <div class="card shadow-sm border-0 mb-3">
      <div class="card-body">
        <form action="{{ route('horarios.index') }}" method="GET" id="searchForm">
          <div class="row g-3">
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" name="search" value="{{ $search ?? '' }}"
                  placeholder="Buscar por nombre del estudiante..." autocomplete="off">
                @if($search)
                  <a href="{{ route('horarios.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i> Limpiar
                  </a>
                @endif
              </div>
              <small class="text-muted mt-2 d-block">
                <span id="resultCount"></span>
              </small>
            </div>
          </div>
        </form>
      </div>
    </div>

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

    @if($horarios->isEmpty())
      <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5">
          <i class="bi bi-calendar-x text-muted mb-3" style="font-size: 3rem;"></i>
          <h5 class="text-muted">No hay horarios asignados aún.</h5>
        </div>
      </div>
    @else
<<<<<<< HEAD
      <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
              <thead class="bg-primary text-white">
                <tr>
                  <th class="ps-3 py-3">Nombre</th>
                  <th class="py-3">Apellido</th>
                  <th class="py-3">Programa</th>
                  <th class="py-3">Día</th>
                  <th class="py-3">Hora</th>
                  <th class="py-3">Profesor Asignado</th>
                  <th class="pe-3 py-3 text-end">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($horarios as $horario)
                  <tr>
                    <td class="ps-3 fw-semibold">{{ $horario->estudiante?->persona?->Nombre ?? '—' }}</td>
                    <td>{{ $horario->estudiante?->persona?->Apellido ?? '—' }}</td>
                    <td>{{ $horario->programa?->Nombre ?? '—' }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $horario->Dia ?? '—' }}</span></td>
                    <td>{{ $horario->Hora ?? '—' }}</td>
                    <td>
                      @php $pp = $horario->profesor?->persona; @endphp
                      {{ ($pp?->Nombre && $pp?->Apellido) ? ($pp->Nombre . ' ' . $pp->Apellido) : 'Sin profesor' }}
                    </td>
                    <td class="pe-3 text-end">
                      <div class="d-flex justify-content-end gap-2">
                        {{-- Abrir modal de edición --}}
                        <button type="button" class="btn btn-sm btn-outline-primary" title="Editar horario"
                          data-bs-toggle="modal" data-bs-target="#modalEditar" data-id="{{ $horario->Id_horarios }}"
                          data-estudiante="{{ $horario->Id_estudiantes }}" data-profesor="{{ $horario->Id_profesores }}"
                          data-dia="{{ $horario->Dia }}" data-hora="{{ $horario->Hora }}">
                          <i class="bi bi-pencil-square"></i>
                        </button>

                        {{-- Eliminar --}}
                        <form action="{{ route('horarios.destroy', $horario->Id_horarios) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar este horario?');">
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
=======
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-3 py-3">Nombre</th>
                                <th class="py-3">Apellido</th>
                                <th class="py-3">Programa</th>
                                <th class="py-3">Día</th>
                                <th class="py-3">Hora</th>
                                <th class="py-3">Profesor Asignado</th>
                                <th class="pe-3 py-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($horarios as $horario)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $horario->estudiante?->persona?->Nombre ?? '—' }}</td>
                                    <td>{{ $horario->estudiante?->persona?->Apellido ?? '—' }}</td>
                                    <td>{{ $horario->programa?->Nombre ?? '—' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $horario->Dia ?? '—' }}</span></td>
                                    <td>{{ $horario->Hora ?? '—' }}</td>
                                    <td>
                                        @php $pp = $horario->profesor?->persona; @endphp
                                        {{ ($pp?->Nombre && $pp?->Apellido) ? ($pp->Nombre.' '.$pp->Apellido) : 'Sin profesor' }}
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary"
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
            </div>
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
        </div>
      </div>

<<<<<<< HEAD
      {{-- Paginación --}}
      <div class="d-flex justify-content-center mt-4 mb-4">
        {{ $horarios->appends(['search' => $search])->links('pagination::bootstrap-5') }}
      </div>
=======
        <div class="d-flex justify-content-center mt-4 mb-4">
            {{ $horarios->links('pagination::bootstrap-5') }}
        </div>
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
    @endif
  </div>

<<<<<<< HEAD
  {{-- Modal Crear Horario --}}
  <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ route('horarios.store') }}">
          @csrf
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalCrearLabel"><i class="bi bi-calendar-plus me-2"></i>Asignar nuevo horario
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div class="modal-body">
            {{-- Estudiante --}}
            <div class="mb-3">
              <label for="Id_estudiantes_crear" class="form-label">Estudiante</label>
              <select class="form-select" id="Id_estudiantes_crear" name="Id_estudiantes" required>
                <option value="" disabled selected>Seleccione un estudiante</option>
                @foreach($estudiantes as $e)
                  <option value="{{ $e->Id_estudiantes }}" data-profesor="{{ $e->Id_profesores }}"
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

            {{-- Horarios (Dinámicos) --}}
            <div class="mb-3">
              <label class="form-label fw-bold">Horarios de la semana</label>
              <div id="horarios-container">
                {{-- Primer horario (siempre visible) --}}
                <div class="horario-item border rounded p-3 mb-2 bg-light">
                  <div class="row g-2">
                    <div class="col-md-5">
                      <label class="form-label small">Día</label>
                      <select class="form-select" name="horarios[0][dia]" required>
                        <option value="" disabled selected>Seleccione</option>
                        @foreach(['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'] as $dia)
                          <option value="{{ $dia }}">{{ $dia }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-5">
                      <label class="form-label small">Hora</label>
                      <input type="time" class="form-control" name="horarios[0][hora]" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                      <button type="button" class="btn btn-success w-100" id="btn-agregar-horario">
                        <i class="bi bi-plus-lg"></i>
                      </button>
                    </div>
=======
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('horarios.store') }}">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalCrearLabel"><i class="bi bi-calendar-plus me-2"></i>Asignar nuevo horario</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3 position-relative">
            <label for="estudiante_search_crear" class="form-label">Estudiante</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="estudiante_search_crear" 
                       placeholder="Escriba el nombre del estudiante..." autocomplete="off">
            </div>
            <input type="hidden" name="Id_estudiantes" id="Id_estudiantes_crear_hidden" required>
            <div id="results_crear" class="list-group search-results-container position-absolute w-100 shadow-sm d-none">
            </div>
          </div>

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

          <div class="mb-3">
            <label for="programa_info_crear" class="form-label">Programa Inscrito</label>
            <input type="text" class="form-control" id="programa_info_crear" readonly 
                   placeholder="Seleccione un estudiante primero">
            <small class="form-text text-muted">El programa se obtiene automáticamente del estudiante</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Horarios de la semana</label>
            <div id="horarios-container">
              <div class="horario-item border rounded p-3 mb-2 bg-light">
                <div class="row g-2">
                  <div class="col-md-5">
                    <label class="form-label small">Día</label>
                    <select class="form-select" name="horarios[0][dia]" required>
                      <option value="" disabled selected>Seleccione</option>
                      @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $dia)
                        <option value="{{ $dia }}">{{ $dia }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-5">
                    <label class="form-label small">Hora</label>
                    <input type="time" class="form-control" name="horarios[0][hora]" required>
                  </div>
                  <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success w-100" id="btn-agregar-horario">
                      <i class="bi bi-plus-lg"></i>
                    </button>
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
                  </div>
                </div>
              </div>
              <small class="form-text text-muted">
                <i class="bi bi-info-circle me-1"></i>Agregue todos los horarios que el estudiante tendrá durante la
                semana
              </small>
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

<<<<<<< HEAD
  {{-- Modal Editar Horario --}}
  <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" id="formEditar">
          @csrf
          @method('PUT')
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalEditarLabel"><i class="bi bi-pencil-square me-2"></i>Editar horario</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div class="modal-body">
            {{-- Estudiante --}}
            <div class="mb-3">
              <label for="Id_estudiantes_editar" class="form-label">Estudiante</label>
              <select class="form-select" id="Id_estudiantes_editar" name="Id_estudiantes" required>
                @foreach($estudiantes as $e)
                  <option value="{{ $e->Id_estudiantes }}" data-profesor="{{ $e->Id_profesores }}"
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
                @foreach(['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'] as $dia)
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
=======
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formEditar">
        @csrf
        @method('PUT')
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalEditarLabel"><i class="bi bi-pencil-square me-2"></i>Editar horario</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3 position-relative">
            <label for="estudiante_search_editar" class="form-label">Estudiante</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="estudiante_search_editar" 
                       placeholder="Escriba el nombre del estudiante..." autocomplete="off">
            </div>
            <input type="hidden" name="Id_estudiantes" id="Id_estudiantes_editar_hidden" required>
            <div id="results_editar" class="list-group search-results-container position-absolute w-100 shadow-sm d-none">
            </div>
          </div>

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

          <div class="mb-3">
            <label for="programa_info_editar" class="form-label">Programa Inscrito</label>
            <input type="text" class="form-control" id="programa_info_editar" readonly>
            <small class="form-text text-muted">El programa se obtiene automáticamente del estudiante</small>
          </div>

          <div class="mb-3">
            <label for="Dia_editar" class="form-label">Día</label>
            <select class="form-select" id="Dia_editar" name="Dia" required>
              @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $dia)
                <option value="{{ $dia }}">{{ $dia }}</option>
              @endforeach
            </select>
          </div>

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
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
    </div>
  </div>

<<<<<<< HEAD
  {{-- Script para mostrar información automática del estudiante --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // ========== GESTIÓN DE HORARIOS DINÁMICOS ==========
      let contadorHorarios = 1; // Comienza en 1 porque ya existe el horario [0]

      // Botón para agregar nuevo horario
      document.getElementById('btn-agregar-horario').addEventListener('click', function () {
=======
<script>
document.addEventListener('DOMContentLoaded', function () {
    @php
        $datosEstudiantes = $estudiantes->map(function($e) {
            return [
                'id' => $e->Id_estudiantes,
                'nombre' => $e->persona ? ($e->persona->Nombre . ' ' . $e->persona->Apellido) : 'Sin nombre',
                'profesor_id' => $e->Id_profesores,
                'profesor_nombre' => $e->profesor ? ($e->profesor->persona->Nombre . ' ' . $e->profesor->persona->Apellido) : 'Sin profesor',
                'programa_id' => $e->Id_programas,
                'programa_nombre' => $e->programa ? $e->programa->Nombre : 'Sin programa',
                'codigo' => $e->Cod_estudiante,
                'tiene_horario' => $e->horarios_count > 0
            ];
        });
    @endphp
    const estudiantesData = @json($datosEstudiantes);

    function setupDynamicSearch(inputId, resultsId, hiddenId, profesorSelect, programaInput, helpText) {
        const searchInput = document.getElementById(inputId);
        const resultsContainer = document.getElementById(resultsId);
        const hiddenInput = document.getElementById(hiddenId);

        if (!searchInput || !resultsContainer) return;

        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            const currentSelectedId = hiddenInput.value;
            resultsContainer.innerHTML = '';
            
            if (term.length < 1) {
                resultsContainer.classList.add('d-none');
                return;
            }

            const filtered = estudiantesData.filter(e => {
                const matchesTerm = e.nombre.toLowerCase().includes(term) || (e.codigo && e.codigo.toLowerCase().includes(term));
                const isAvailable = !e.tiene_horario || e.id == currentSelectedId;
                return matchesTerm && isAvailable;
            });

            if (filtered.length > 0) {
                filtered.forEach(e => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item search-result-item';
                    item.innerHTML = `
                        <span class="student-name">${e.nombre}</span>
                        <div class="d-flex justify-content-between">
                            <span class="student-code">Código: ${e.codigo || '—'}</span>
                            <span class="student-program">${e.programa_nombre}</span>
                        </div>
                    `;
                    item.addEventListener('click', function() {
                        searchInput.value = e.nombre;
                        hiddenInput.value = e.id;
                        resultsContainer.classList.add('d-none');
                        
                        const fakeOption = {
                            value: e.id,
                            getAttribute: (attr) => {
                                if (attr === 'data-profesor') return e.profesor_id;
                                if (attr === 'data-programa-nombre') return e.programa_nombre;
                                if (attr === 'data-profesor-nombre') return e.profesor_nombre;
                                return null;
                            }
                        };
                        
                        actualizarInfoEstudianteDesdeData(fakeOption, profesorSelect, programaInput, helpText);
                    });
                    resultsContainer.appendChild(item);
                });
                resultsContainer.classList.remove('d-none');
            } else {
                resultsContainer.innerHTML = '<div class="list-group-item text-muted">No se encontraron estudiantes</div>';
                resultsContainer.classList.remove('d-none');
            }
        });

        // Cerrar resultados al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('d-none');
            }
        });
    }

    function actualizarInfoEstudianteDesdeData(dataObj, profesorSelect, programaInput, helpText) {
        if (dataObj && dataObj.value) {
            const profesorId = dataObj.getAttribute('data-profesor');
            const programaNombre = dataObj.getAttribute('data-programa-nombre') || 'Sin programa asignado';
            const profesorNombre = dataObj.getAttribute('data-profesor-nombre') || '';
            
            programaInput.value = programaNombre;
            if (programaNombre === 'Sin programa asignado') {
                programaInput.classList.add('is-invalid');
            } else {
                programaInput.classList.remove('is-invalid');
            }

            if (profesorId && profesorId !== 'null' && profesorId !== '') {
                profesorSelect.value = profesorId;
                profesorSelect.disabled = false;
                profesorSelect.classList.remove('bg-light');
                helpText.innerHTML = `<i class="bi bi-info-circle me-1"></i>Profesor actual: <strong>${profesorNombre}</strong> (puede cambiarlo)`;
                helpText.classList.remove('text-muted', 'text-warning');
                helpText.classList.add('text-info');
            } else {
                profesorSelect.value = '';
                profesorSelect.disabled = false;
                profesorSelect.classList.remove('bg-light');
                helpText.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Este estudiante no tiene profesor asignado.';
                helpText.classList.remove('text-success', 'text-info');
                helpText.classList.add('text-warning');
            }
        }
    }

    // Inicializar buscadores
    setupDynamicSearch(
        'estudiante_search_crear', 
        'results_crear', 
        'Id_estudiantes_crear_hidden', 
        document.getElementById('Id_profesores_crear'), 
        document.getElementById('programa_info_crear'), 
        document.getElementById('profesor_help_crear')
    );

    setupDynamicSearch(
        'estudiante_search_editar', 
        'results_editar', 
        'Id_estudiantes_editar_hidden', 
        document.getElementById('Id_profesores_editar'), 
        document.getElementById('programa_info_editar'), 
        document.getElementById('profesor_help_editar')
    );

    let contadorHorarios = 1; 

    document.getElementById('btn-agregar-horario').addEventListener('click', function() {
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
        const container = document.getElementById('horarios-container');
        const nuevoHorario = document.createElement('div');
        nuevoHorario.className = 'horario-item border rounded p-3 mb-2 bg-light';
        nuevoHorario.innerHTML = `
              <div class="row g-2">
                  <div class="col-md-5">
                      <label class="form-label small">Día</label>
                      <select class="form-select" name="horarios[${contadorHorarios}][dia]" required>
                          <option value="" disabled selected>Seleccione</option>
                          <option value="LUNES">LUNES</option>
                          <option value="MARTES">MARTES</option>
                          <option value="MIERCOLES">MIERCOLES</option>
                          <option value="JUEVES">JUEVES</option>
                          <option value="VIERNES">VIERNES</option>
                          <option value="SABADO">SABADO</option>
                      </select>
                  </div>
                  <div class="col-md-5">
                      <label class="form-label small">Hora</label>
                      <input type="time" class="form-control" name="horarios[${contadorHorarios}][hora]" required>
                  </div>
                  <div class="col-md-2 d-flex align-items-end">
                      <button type="button" class="btn btn-danger w-100 btn-eliminar-horario">
                          <i class="bi bi-trash3"></i>
                      </button>
                  </div>
              </div>
          `;
        container.appendChild(nuevoHorario);
        contadorHorarios++;

<<<<<<< HEAD
        // Agregar event listener al botón de eliminación
        nuevoHorario.querySelector('.btn-eliminar-horario').addEventListener('click', function () {
          nuevoHorario.remove();
=======
        nuevoHorario.querySelector('.btn-eliminar-horario').addEventListener('click', function() {
            nuevoHorario.remove();
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
        });
      });

<<<<<<< HEAD
      // ========== GESTIÓN DE INFORMACIÓN DEL ESTUDIANTE ==========
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
            // El estudiante YA tiene profesor asignado - preseleccionarlo pero permitir cambio
            profesorSelect.value = profesorId;
            profesorSelect.disabled = false; // PERMITIR cambio
            profesorSelect.classList.remove('bg-light');
            helpText.innerHTML = `<i class="bi bi-info-circle me-1"></i>Profesor actual: <strong>${profesorNombre}</strong> (puede cambiarlo si lo desea)`;
            helpText.classList.remove('text-muted', 'text-warning');
            helpText.classList.add('text-info');
          } else {
            // El estudiante NO tiene profesor, permitir selección
            profesorSelect.value = '';
            profesorSelect.disabled = false;
            profesorSelect.classList.remove('bg-light');
            helpText.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Este estudiante no tiene profesor asignado. Por favor, seleccione uno.';
            helpText.classList.remove('text-success', 'text-info');
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
=======
    const modalEditar = document.getElementById('modalEditar');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const estudianteId = button.getAttribute('data-estudiante');
            const profesorId = button.getAttribute('data-profesor');
            const dia = button.getAttribute('data-dia');
            const hora = button.getAttribute('data-hora');
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667

          const form = document.getElementById('formEditar');
          form.action = "{{ route('horarios.update', ':id') }}".replace(':id', id);

<<<<<<< HEAD
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

    // ========== BÚSQUEDA DINÁMICA ==========
    document.addEventListener('DOMContentLoaded', function () {
      const searchInput = document.getElementById('searchInput');
      const searchForm = document.getElementById('searchForm');
      const resultCount = document.getElementById('resultCount');

      // El filtrado por JavaScript se elimina para favorecer la búsqueda en el servidor
      // pero mantenemos el contador de resultados basado en lo que el servidor devolvió

      if (searchInput && resultCount) {
        let timeout = null;

        searchInput.addEventListener('input', function () {
          clearTimeout(timeout);
          // Debounce de 500ms antes de enviar el formulario
          timeout = setTimeout(() => {
            searchForm.submit();
          }, 600);
        });

        // Posicionar el cursor al final del texto después de recargar
        const val = searchInput.value;
        searchInput.value = '';
        searchInput.value = val;
        searchInput.focus();

        const totalRows = {{ $horarios->total() }};
        const isSearching = "{{ $search }}" !== "";

        if (isSearching) {
          resultCount.textContent = `Mostrando ${totalRows} resultados para "{{ $search }}"`;
        } else {
          resultCount.textContent = `Mostrando todos los ${totalRows} horarios`;
=======
            const estudianteIdHidden = document.getElementById('Id_estudiantes_editar_hidden');
            const estudianteSearchInput = document.getElementById('estudiante_search_editar');
            
            estudianteIdHidden.value = estudianteId;
            document.getElementById('Id_profesores_editar').value = profesorId;
            document.getElementById('Dia_editar').value = dia;
            document.getElementById('Hora_editar').value = hora;

            const est = estudiantesData.find(e => e.id == estudianteId);
            if (est) {
                estudianteSearchInput.value = est.nombre;
                
                const fakeOption = {
                    value: est.id,
                    getAttribute: (attr) => {
                        if (attr === 'data-profesor') return est.profesor_id;
                        if (attr === 'data-programa-nombre') return est.programa_nombre;
                        if (attr === 'data-profesor-nombre') return est.profesor_nombre;
                        return null;
                    }
                };
                actualizarInfoEstudianteDesdeData(fakeOption, document.getElementById('Id_profesores_editar'), document.getElementById('programa_info_editar'), document.getElementById('profesor_help_editar'));
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');
    const resultCount = document.getElementById('resultCount');
    const totalRows = tableRows.length;

    if (searchInput && tableRows.length > 0) {
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const nombreCell = row.cells[0];
                const apellidoCell = row.cells[1];
                
                if (nombreCell && apellidoCell) {
                    const nombre = nombreCell.textContent.toLowerCase();
                    const apellido = apellidoCell.textContent.toLowerCase();
                    const nombreCompleto = nombre + ' ' + apellido;

                    if (nombreCompleto.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            if (searchTerm === '') {
                resultCount.textContent = `Mostrando todos los ${totalRows} horarios`;
            } else {
                resultCount.textContent = `Mostrando ${visibleCount} de ${totalRows} horarios`;
                if (visibleCount === 0) {
                    resultCount.innerHTML = `<i class="bi bi-exclamation-circle text-warning"></i> No se encontraron resultados para "${searchTerm}"`;
                }
            }
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667
        }
      }
    });

<<<<<<< HEAD
  </script>
=======
        searchInput.addEventListener('input', filterTable);
        
        resultCount.textContent = `Mostrando todos los ${totalRows} horarios`;
    }
});

</script>
>>>>>>> 913521654f2f4dcc68754f8cd310808e009fa667

@endsection