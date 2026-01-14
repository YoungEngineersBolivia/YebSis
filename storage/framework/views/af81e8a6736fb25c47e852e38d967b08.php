<?php $__env->startSection('title', 'Horarios de Estudiantes'); ?>

<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(auto_asset('css/style.css')); ?>" rel="stylesheet">
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-calendar-week me-2"></i>Horarios Asignados</h2>

        
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-plus-lg me-2"></i>Asignar nuevo horario
        </button>
    </div>

    
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" 
                               class="form-control" 
                               id="searchInput"
                               placeholder="Buscar por nombre del estudiante..." 
                               autocomplete="off">
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <span id="resultCount"></span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <?php if($horarios->isEmpty()): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x text-muted mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-muted">No hay horarios asignados aún.</h5>
            </div>
        </div>
    <?php else: ?>
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
                            <?php $__currentLoopData = $horarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $horario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-3 fw-semibold"><?php echo e($horario->estudiante?->persona?->Nombre ?? '—'); ?></td>
                                    <td><?php echo e($horario->estudiante?->persona?->Apellido ?? '—'); ?></td>
                                    <td><?php echo e($horario->programa?->Nombre ?? '—'); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo e($horario->Dia ?? '—'); ?></span></td>
                                    <td><?php echo e($horario->Hora ?? '—'); ?></td>
                                    <td>
                                        <?php $pp = $horario->profesor?->persona; ?>
                                        <?php echo e(($pp?->Nombre && $pp?->Apellido) ? ($pp->Nombre.' '.$pp->Apellido) : 'Sin profesor'); ?>

                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Editar horario"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditar"
                                                    data-id="<?php echo e($horario->Id_horarios); ?>"
                                                    data-estudiante="<?php echo e($horario->Id_estudiantes); ?>"
                                                    data-profesor="<?php echo e($horario->Id_profesores); ?>"
                                                    data-dia="<?php echo e($horario->Dia); ?>"
                                                    data-hora="<?php echo e($horario->Hora); ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            
                                            <form action="<?php echo e(route('horarios.destroy', $horario->Id_horarios)); ?>" method="POST" onsubmit="return confirm('¿Eliminar este horario?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="d-flex justify-content-center mt-4 mb-4">
            <?php echo e($horarios->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="<?php echo e(route('horarios.store')); ?>">
        <?php echo csrf_field(); ?>
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
              <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->Id_profesores); ?>">
                  <?php echo e($p->persona?->Nombre); ?> <?php echo e($p->persona?->Apellido); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                      <?php $__currentLoopData = ['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dia); ?>"><?php echo e($dia); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <small class="form-text text-muted">
              <i class="bi bi-info-circle me-1"></i>Agregue todos los horarios que el estudiante tendrá durante la semana
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


<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formEditar">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
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
              <?php $__currentLoopData = $profesores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->Id_profesores); ?>">
                  <?php echo e($p->persona?->Nombre); ?> <?php echo e($p->persona?->Apellido); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
              <?php $__currentLoopData = ['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($dia); ?>"><?php echo e($dia); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    // ========== DATOS DE ESTUDIANTES ==========
    <?php
        $datosEstudiantes = $estudiantes->map(function($e) {
            return [
                'id' => $e->Id_estudiantes,
                'nombre' => $e->persona ? ($e->persona->Nombre . ' ' . $e->persona->Apellido) : 'Sin nombre',
                'profesor_id' => $e->Id_profesores,
                'profesor_nombre' => $e->profesor ? ($e->profesor->persona->Nombre . ' ' . $e->profesor->persona->Apellido) : 'Sin profesor',
                'programa_id' => $e->Id_programas,
                'programa_nombre' => $e->programa ? $e->programa->Nombre : 'Sin programa',
                'codigo' => $e->Cod_estudiante
            ];
        });
    ?>
    const estudiantesData = <?php echo json_encode($datosEstudiantes, 15, 512) ?>;

    // ========== FUNCIONES DEL BUSCADOR DINÁMICO ==========
    function setupDynamicSearch(inputId, resultsId, hiddenId, profesorSelect, programaInput, helpText) {
        const searchInput = document.getElementById(inputId);
        const resultsContainer = document.getElementById(resultsId);
        const hiddenInput = document.getElementById(hiddenId);

        if (!searchInput || !resultsContainer) return;

        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            resultsContainer.innerHTML = '';
            
            if (term.length < 1) {
                resultsContainer.classList.add('d-none');
                return;
            }

            const filtered = estudiantesData.filter(e => 
                e.nombre.toLowerCase().includes(term) || (e.codigo && e.codigo.toLowerCase().includes(term))
            );

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
                        
                        // Fabricar un objeto similar a selectedOptions[0] para reusar actualizarInfoEstudiante
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

    // ========== GESTIÓN DE INFORMACIÓN DEL ESTUDIANTE ==========
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

    // ========== GESTIÓN DE HORARIOS DINÁMICOS ==========
    let contadorHorarios = 1; 

    document.getElementById('btn-agregar-horario').addEventListener('click', function() {
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

        nuevoHorario.querySelector('.btn-eliminar-horario').addEventListener('click', function() {
            nuevoHorario.remove();
        });
    });

    // ========== MODAL DE EDICIÓN ==========
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
            form.action = "<?php echo e(route('horarios.update', ':id')); ?>".replace(':id', id);

            const estudianteIdHidden = document.getElementById('Id_estudiantes_editar_hidden');
            const estudianteSearchInput = document.getElementById('estudiante_search_editar');
            
            estudianteIdHidden.value = estudianteId;
            document.getElementById('Id_profesores_editar').value = profesorId;
            document.getElementById('Dia_editar').value = dia;
            document.getElementById('Hora_editar').value = hora;

            // Encontrar el nombre del estudiante para el input de búsqueda
            const est = estudiantesData.find(e => e.id == estudianteId);
            if (est) {
                estudianteSearchInput.value = est.nombre;
                
                // Actualizar info adicional
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

// ========== BÚSQUEDA DINÁMICA ==========
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');
    const resultCount = document.getElementById('resultCount');
    const totalRows = tableRows.length;

    if (searchInput && tableRows.length > 0) {
        // Función para filtrar la tabla
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            tableRows.forEach(row => {
                // Obtener el nombre y apellido del estudiante (primera y segunda columna)
                const nombreCell = row.cells[0];
                const apellidoCell = row.cells[1];
                
                if (nombreCell && apellidoCell) {
                    const nombre = nombreCell.textContent.toLowerCase();
                    const apellido = apellidoCell.textContent.toLowerCase();
                    const nombreCompleto = nombre + ' ' + apellido;

                    // Mostrar/ocultar fila según coincidencia
                    if (nombreCompleto.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Actualizar contador de resultados
            if (searchTerm === '') {
                resultCount.textContent = `Mostrando todos los ${totalRows} horarios`;
            } else {
                resultCount.textContent = `Mostrando ${visibleCount} de ${totalRows} horarios`;
                if (visibleCount === 0) {
                    resultCount.innerHTML = `<i class="bi bi-exclamation-circle text-warning"></i> No se encontraron resultados para "${searchTerm}"`;
                }
            }
        }

        // Ejecutar búsqueda mientras el usuario escribe
        searchInput.addEventListener('input', filterTable);
        
        // Mostrar contador inicial
        resultCount.textContent = `Mostrando todos los ${totalRows} horarios`;
    }
});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('administrador.baseAdministrador', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/administrador/horariosAdministrador.blade.php ENDPATH**/ ?>