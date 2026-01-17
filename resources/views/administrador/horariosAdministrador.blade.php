@extends('administrador.baseAdministrador')

@section('title', 'Horarios de Estudiantes')

@section('styles')
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
@endsection

@section('content')
  <div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0 fw-bold"><i class="bi bi-calendar-week me-2"></i>Horarios Asignados</h2>

      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-lg me-2"></i>Asignar nuevo horario
      </button>
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
        <div id="table-container">
            @include('administrador.partials.horarios_table')
        </div>
    @endif
  </div>


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
            <div class="mb-3 position-relative">
              <label for="estudiante_search_crear" class="form-label">Estudiante</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="estudiante_search_crear"
                  placeholder="Escriba el nombre del estudiante..." autocomplete="off">
              </div>
              <input type="hidden" name="Id_estudiantes" id="Id_estudiantes_crear_hidden" required>
              <div id="results_crear"
                class="list-group search-results-container position-absolute w-100 shadow-sm d-none">
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
              <small class="form-text text-muted" id="profesor_help_crear"></small>
            </div>

            <div class="mb-3">
              <label for="programa_info_crear" class="form-label">Programa Inscrito</label>
              <input type="text" class="form-control" id="programa_info_crear" readonly
                placeholder="Seleccione un estudiante primero">
              <small class="form-text text-muted"></small>
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
                  </div>
                </div>
              </div>
              <small class="form-text text-muted"></small>
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
                <small class="form-text text-muted" id="profesor_help_editar"></small>
              </div>

              <div class="mb-3">
                <label for="programa_info_editar" class="form-label">Programa Inscrito</label>
                <input type="text" class="form-control" id="programa_info_editar" readonly>
                <small class="form-text text-muted"></small>
              </div>

              <div class="mb-3">
                <label for="Dia_editar" class="form-label">Día</label>
                <select class="form-select" id="Dia_editar" name="Dia" required>
                  @foreach(['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'] as $dia)
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
      </div>
    </div>
  </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        @php
          $datosEstudiantes = $estudiantes->map(function ($e) {
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
        const searchInputGlobal = document.getElementById('searchInput');
        const resultCountGlobal = document.getElementById('resultCount');
        const tableContainer = document.getElementById('table-container');

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
                const profesorNombre = dataObj.getAttribute('data-profesor-nombre') || 'Sin profesor';

                if (programaInput) {
                    programaInput.value = programaNombre;
                    if (programaNombre === 'Sin programa asignado') {
                        programaInput.classList.add('is-invalid');
                    } else {
                        programaInput.classList.remove('is-invalid');
                    }
                }

                if (profesorSelect) {
                    if (profesorId && profesorId !== 'null' && profesorId !== '') {
                        profesorSelect.value = profesorId;
                        profesorSelect.disabled = false;
                        profesorSelect.classList.remove('bg-light');
                        if (helpText) {
                            helpText.innerHTML = `<i class="bi bi-info-circle me-1"></i>Profesor actual: <strong>${profesorNombre}</strong> (puede cambiarlo)`;
                            helpText.classList.remove('text-muted', 'text-warning');
                            helpText.classList.add('text-info');
                        }
                    } else {
                        profesorSelect.value = '';
                        profesorSelect.disabled = false;
                        profesorSelect.classList.remove('bg-light');
                        if (helpText) {
                            helpText.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Este estudiante no tiene profesor asignado.';
                            helpText.classList.remove('text-success', 'text-info');
                            helpText.classList.add('text-warning');
                        }
                    }
                }
            }
        }

        // --- Modals logic ---
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
        const btnAgregar = document.getElementById('btn-agregar-horario');
        if (btnAgregar) {
            btnAgregar.addEventListener('click', function () {
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
                nuevoHorario.querySelector('.btn-eliminar-horario').addEventListener('click', function () {
                    nuevoHorario.remove();
                });
            });
        }

        const modalEditar = document.getElementById('modalEditar');
        if (modalEditar) {
            modalEditar.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const estudianteId = button.getAttribute('data-estudiante');
                const profesorId = button.getAttribute('data-profesor');
                const dia = button.getAttribute('data-dia');
                const hora = button.getAttribute('data-hora');

                const form = document.getElementById('formEditar');
                form.action = "{{ route('horarios.update', ':id') }}".replace(':id', id);

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

        // --- Table Search logic (AJAX) ---
        if (searchInputGlobal && tableContainer) {
            let searchTimeout;
            searchInputGlobal.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value;
                searchTimeout = setTimeout(() => {
                    fetchResults(query);
                }, 400);
            });

            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    fetchResults(searchInputGlobal.value);
                });
            }
        }

        function fetchResults(query = '', page = 1) {
            const url = new URL('{{ route('horarios.index') }}');
            url.searchParams.set('search', query);
            url.searchParams.set('page', page);

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                tableContainer.innerHTML = html;
                window.history.pushState(null, '', url.toString());
                updateResultCount(query);
            })
            .catch(error => console.error('Error:', error));
        }

        function updateResultCount(query) {
            if (resultCountGlobal) {
                resultCountGlobal.textContent = query ? `Buscando resultados para "${query}"...` : 'Mostrando todos los horarios';
            }
        }

        tableContainer.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                const urlObj = new URL(link.href);
                const pageNum = urlObj.searchParams.get('page') || 1;
                fetchResults(searchInputGlobal.value, pageNum);
            }
        });

        if (searchInputGlobal) {
            searchInputGlobal.focus();
            const totalRows = {{ $horarios->total() }};
            const currentSearch = "{{ $search }}";
            resultCountGlobal.textContent = currentSearch ? 
                `Mostrando ${totalRows} resultados para "${currentSearch}"` : 
                `Mostrando todos los ${totalRows} horarios`;
        }
      });
    </script>

@endsection