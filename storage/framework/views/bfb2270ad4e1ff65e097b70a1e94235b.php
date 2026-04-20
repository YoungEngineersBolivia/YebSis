<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <a href="<?php echo e(route('profesor.home')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </div>
                <h2 class="text-uppercase fw-bold text-dark">
                    <i class="bi bi-calendar-check me-2"></i> Registro de Asistencia
                </h2>
                <p class="text-muted mb-0">Profesor: <?php echo e($profesor->persona->Nombre); ?> <?php echo e($profesor->persona->Apellido); ?></p>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> <strong>Error:</strong> Por favor revise el formulario.
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?php echo e(route('profesor.asistencia.store')); ?>" method="POST" id="asistenciaForm"
                    onsubmit="confirmarEnvio(event)">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="profesor_id" value="<?php echo e($profesor->Id_profesores); ?>">

                    <div class="row mb-4 align-items-end">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label for="fecha" class="form-label fw-bold">Fecha de Clase</label>
                            <input type="date" name="fecha" id="fecha" class="form-control"
                                value="<?php echo e(request('fecha', date('Y-m-d'))); ?>" onchange="loadAttendance()" required>
                        </div>
                        <div class="col-md-5 mb-3 mb-md-0 position-relative">
                            <label for="search_estudiante" class="form-label fw-bold">Añadir Estudiante</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" id="search_estudiante" class="form-control border-start-0"
                                    placeholder="Buscar por nombre o código...">
                            </div>
                            <div id="search_results" class="list-group position-absolute w-100 shadow-sm d-none"
                                style="z-index: 1000; max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-md-2 text-md-end">
                            <button type="submit" id="btn-submit-asistencia" class="btn btn-primary w-100">
                                <i class="bi bi-save me-2"></i> <span id="btn-text">Guardar</span>
                            </button>
                        </div>
                    </div>

                    <div id="no-students-msg" class="text-center py-5 <?php echo e($estudiantes->isEmpty() ? '' : 'd-none'); ?>">
                        <i class="bi bi-people display-4 text-muted mb-3 d-block"></i>
                        <p class="text-muted lead">Usa el buscador para añadir a los alumnos que asistieron hoy.</p>
                    </div>

                    
                    <div class="d-none d-lg-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle <?php echo e($estudiantes->isEmpty() ? 'd-none' : ''); ?>"
                                id="attendance-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%">Estudiante</th>
                                        <th style="width: 15%">Programa</th>
                                        <th style="width: 35%">Estado</th>
                                        <th style="width: 25%">Detalles / Observación</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="attendance-tbody">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="d-lg-none" id="attendance-cards">
                        
                    </div>
                </form>
            </div>
        </div>
    </div>

    <template id="student-row-template">
        <tr data-student-id="@ID@">
            <td>
                <div class="fw-bold">@NOMBRE@</div>
                <small class="text-muted">@CODIGO@</small>
                <input type="hidden" name="programa_id[@ID@]" value="@PROGRAMA_ID@">
            </td>
            <td><span class="badge bg-secondary">@PROGRAMA@</span></td>
            <td>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="asistencia[@ID@]" id="asistio_@ID@" value="Asistio"
                        onchange="updateRowStyle(@ID@, 'Asistio')">
                    <label class="btn btn-outline-success btn-sm" for="asistio_@ID@">A</label>

                    <input type="radio" class="btn-check" name="asistencia[@ID@]" id="falta_@ID@" value="Falta"
                        onchange="updateRowStyle(@ID@, 'Falta')">
                    <label class="btn btn-outline-danger btn-sm" for="falta_@ID@">F</label>

                    <input type="radio" class="btn-check" name="asistencia[@ID@]" id="licencia_@ID@" value="Licencia"
                        onchange="updateRowStyle(@ID@, 'Licencia')">
                    <label class="btn btn-outline-warning btn-sm" for="licencia_@ID@">L</label>

                    <input type="radio" class="btn-check" name="asistencia[@ID@]" id="repro_@ID@" value="Reprogramado"
                        onchange="updateRowStyle(@ID@, 'Reprogramado')">
                    <label class="btn btn-outline-info btn-sm" for="repro_@ID@">R</label>
                </div>
            </td>
            <td>
                <input type="text" name="observacion[@ID@]" class="form-control form-control-sm mb-1" placeholder="Obs...">
                <div id="repro_div_@ID@" style="display: none;">
                    <input type="date" name="fecha_reprogramada[@ID@]" class="form-control form-control-sm border-info"
                        placeholder="Nueva Fecha">
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeStudent(@ID@)"><i
                        class="bi bi-trash"></i></button>
            </td>
        </tr>
    </template>

    <template id="student-card-template">
        <div class="card mb-3 shadow-sm border-start border-4" data-student-id="@ID@" id="card_@ID@">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="mb-0 fw-bold">@NOMBRE@</h6>
                        <small class="text-muted">@CODIGO@</small>
                    </div>
                    <button type="button" class="btn btn-sm text-danger" onclick="removeStudent(@ID@)"><i
                            class="bi bi-trash"></i></button>
                </div>

                <div class="btn-group w-100 mb-3" role="group">
                    <input type="radio" class="btn-check" name="asistencia_mob[@ID@]" id="asistio_mob_@ID@" value="Asistio"
                        onchange="syncFromMobile(@ID@, 'Asistio')">
                    <label class="btn btn-outline-success btn-sm" for="asistio_mob_@ID@">Asistió</label>

                    <input type="radio" class="btn-check" name="asistencia_mob[@ID@]" id="falta_mob_@ID@" value="Falta"
                        onchange="syncFromMobile(@ID@, 'Falta')">
                    <label class="btn btn-outline-danger btn-sm" for="falta_mob_@ID@">Falta</label>

                    <input type="radio" class="btn-check" name="asistencia_mob[@ID@]" id="licencia_mob_@ID@"
                        value="Licencia" onchange="syncFromMobile(@ID@, 'Licencia')">
                    <label class="btn btn-outline-warning btn-sm" for="licencia_mob_@ID@">Licencia</label>

                    <input type="radio" class="btn-check" name="asistencia_mob[@ID@]" id="repro_mob_@ID@"
                        value="Reprogramado" onchange="syncFromMobile(@ID@, 'Reprogramado')">
                    <label class="btn btn-outline-info btn-sm" for="repro_mob_@ID@">Repro.</label>
                </div>

                <input type="text" name="observacion_mob[@ID@]" id="obs_mob_@ID@" class="form-control form-control-sm mb-1"
                    placeholder="Observación..." oninput="syncTextToDesktop(@ID@, 'observacion', this.value)">
                <div id="repro_div_mob_@ID@" style="display: none;">
                    <input type="date" name="fecha_repro_mob[@ID@]" id="fecha_repro_mob_@ID@"
                        class="form-control form-control-sm border-info"
                        onchange="syncTextToDesktop(@ID@, 'fecha_reprogramada', this.value)">
                </div>
            </div>
        </div>
    </template>

    <script>
        const searchInput = document.getElementById('search_estudiante');
        const searchResults = document.getElementById('search_results');
        const attendanceTbody = document.getElementById('attendance-tbody');
        const attendanceCards = document.getElementById('attendance-cards');
        const noStudentsMsg = document.getElementById('no-students-msg');
        const attendanceTable = document.getElementById('attendance-table');
        const selectedStudents = new Set();

        let searchTimeout;

        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            const query = searchInput.value.trim();

            if (query.length < 1) {
                searchResults.classList.add('d-none');
                return;
            }

            // Buscador más rápido: bajamos de 300ms a 100ms
            searchTimeout = setTimeout(() => {
                fetch(`<?php echo e(route('profesor.asistencia.buscarEstudiantes')); ?>?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(est => {
                                const item = document.createElement('button');
                                item.type = 'button';
                                item.className = 'list-group-item list-group-item-action py-2';
                                item.innerHTML = `<div class="d-flex flex-column">
                                                                    <span class="fw-bold text-truncate" style="font-size: 0.9em">${est.nombre}</span>
                                                                    <small class="text-muted" style="font-size: 0.8em">${est.codigo}</small>
                                                                  </div>`;
                                item.onclick = () => addStudent(est);
                                searchResults.appendChild(item);
                            });
                            searchResults.classList.remove('d-none');
                        } else {
                            searchResults.classList.add('d-none');
                        }
                    });
            }, 100);
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('d-none');
            }
        });

        function addStudent(est, existingData = null) {
            if (selectedStudents.has(est.id)) {
                if (!existingData) alert('Este estudiante ya está en la lista.');
                return;
            }

            selectedStudents.add(est.id);
            searchResults.classList.add('d-none');
            searchInput.value = '';
            noStudentsMsg.classList.add('d-none');
            attendanceTable.classList.remove('d-none');

            // Añadir a tabla (Desktop)
            const rowTemplate = document.getElementById('student-row-template').innerHTML;
            const newRowHtml = rowTemplate
                .replace(/@ID@/g, est.id)
                .replace(/@NOMBRE@/g, est.nombre)
                .replace(/@CODIGO@/g, est.codigo)
                .replace(/@PROGRAMA@/g, est.programa)
                .replace(/@PROGRAMA_ID@/g, est.programa_id);

            const tr = document.createElement('tr');
            tr.innerHTML = newRowHtml;
            tr.dataset.studentId = est.id;
            attendanceTbody.appendChild(tr);

            // Añadir a cards (Mobile)
            const cardTemplate = document.getElementById('student-card-template').innerHTML;
            const newCardHtml = cardTemplate
                .replace(/@ID@/g, est.id)
                .replace(/@NOMBRE@/g, est.nombre)
                .replace(/@CODIGO@/g, est.codigo)
                .replace(/@PROGRAMA@/g, est.programa);

            const div = document.createElement('div');
            div.innerHTML = newCardHtml;
            div.dataset.studentId = est.id;
            attendanceCards.appendChild(div);

            // Si hay datos previos, cargarlos
            if (existingData) {
                const radio = document.querySelector(`tr[data-student-id="${est.id}"] input[name="asistencia[${est.id}]"][value="${existingData.estado}"]`);
                if (radio) {
                    radio.checked = true;
                    updateRowStyle(est.id, existingData.estado);
                }

                const obsInput = document.querySelector(`tr[data-student-id="${est.id}"] input[name="observacion[${est.id}]"]`);
                if (obsInput) {
                    obsInput.value = existingData.observacion || '';
                    syncTextToDesktop(est.id, 'observacion', obsInput.value);
                }

                if (existingData.estado === 'Reprogramado') {
                    const reproInput = document.querySelector(`tr[data-student-id="${est.id}"] input[name="fecha_reprogramada[${est.id}]"]`);
                    if (reproInput) {
                        reproInput.value = existingData.fecha_reprogramada || '';
                        syncTextToDesktop(est.id, 'fecha_reprogramada', reproInput.value);
                    }
                }
            }
        }

        function loadAttendance() {
            const fechaVal = document.getElementById('fecha').value;
            const btnSubmit = document.getElementById('btn-submit-asistencia');
            const btnText = document.getElementById('btn-text');
            if (!fechaVal) return;

            fetch(`<?php echo e(route('profesor.asistencia.cargar')); ?>?fecha=${fechaVal}`)
                .then(res => res.json())
                .then(data => {
                    // Limpiar lista actual
                    selectedStudents.clear();
                    attendanceTbody.innerHTML = '';
                    attendanceCards.innerHTML = '';
                    noStudentsMsg.classList.remove('d-none');
                    attendanceTable.classList.add('d-none');

                    if (data.length > 0) {
                        // Cambiar botón a naranja para edición
                        btnSubmit.style.backgroundColor = '#f39c12';
                        btnSubmit.style.borderColor = '#e67e22';
                        btnText.innerText = 'Editar Asistencia';

                        data.forEach(asist => {
                            const estData = {
                                id: asist.id,
                                nombre: asist.nombre,
                                codigo: asist.codigo,
                                programa: asist.programa,
                                programa_id: asist.programa_id
                            };
                            addStudent(estData, asist);
                        });
                    } else {
                        // Restaurar botón a azul original para nuevo registro
                        btnSubmit.style.backgroundColor = '';
                        btnSubmit.style.borderColor = '';
                        btnText.innerText = 'Guardar';
                    }
                });
        }

        function confirmarEnvio(e) {
            e.preventDefault(); // Detenemos el envío automático

            const btnText = document.getElementById('btn-text').innerText;
            const esEdicion = btnText.includes('Editar');

            Swal.fire({
                title: esEdicion ? '¿Deseas actualizar la asistencia?' : '¿Deseas guardar la asistencia?',
                text: esEdicion ? 'Se sobreescribirán los registros de esta fecha.' : 'Se registrará la asistencia para los alumnos seleccionados.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: esEdicion ? '#f39c12' : '#0d6efd',
                cancelButtonColor: '#dc3545',
                confirmButtonText: esEdicion ? '<i class="bi bi-pencil-square me-2"></i>Sí, Editar' : '<i class="bi bi-check-lg me-2"></i>Sí, Guardar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-lg px-4',
                    cancelButton: 'btn btn-lg px-4 me-2'
                },
                buttonsStyling: false,
                background: '#ffffff',
                color: '#212529',
                backdrop: `rgba(0,0,123,0.1)`
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('asistenciaForm').submit();
                }
            });

            return false;
        }

        // Cargar asistencia inicial al entrar (hoy)
        document.addEventListener('DOMContentLoaded', () => {
            loadAttendance();

            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Registrado!',
                    text: "<?php echo e(session('success')); ?>",
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?>
            });
    </script>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function removeStudent(id) {
            selectedStudents.delete(id);
            document.querySelector(`tr[data-student-id="${id}"]`)?.remove();
            document.querySelector(`div[data-student-id="${id}"]`)?.remove();

            if (selectedStudents.size === 0) {
                noStudentsMsg.classList.remove('d-none');
                attendanceTable.classList.add('d-none');
            }
        }

        function updateRowStyle(id, value) {
            // Sync Desktop -> Mobile
            const mobRadio = document.querySelector(`div[data-student-id="${id}"] input[name="asistencia_mob[${id}]"][value="${value}"]`);
            if (mobRadio) mobRadio.checked = true;

            toggleRepro(id, value === 'Reprogramado');
        }

        function syncFromMobile(id, value) {
            // Sync Mobile -> Desktop
            const deskRadio = document.querySelector(`tr[data-student-id="${id}"] input[name="asistencia[${id}]"][value="${value}"]`);
            if (deskRadio) deskRadio.checked = true;

            toggleRepro(id, value === 'Reprogramado');
        }

        function toggleRepro(id, show) {
            const div = document.getElementById(`repro_div_${id}`);
            const divMob = document.getElementById(`repro_div_mob_${id}`);
            if (div) div.style.display = show ? 'block' : 'none';
            if (divMob) divMob.style.display = show ? 'block' : 'none';
        }

        function syncTextToDesktop(id, field, value) {
            const deskInput = document.querySelector(`tr[data-student-id="${id}"] input[name="${field}[${id}]"]`);
            if (deskInput) deskInput.value = value;
        }
    </script>

    <style>
        /* Estilos para que el BOTÓN seleccionado tenga color sólido */
        .btn-check:checked+label.btn-outline-success {
            background-color: #198754 !important;
            color: white !important;
        }

        .btn-check:checked+label.btn-outline-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }

        .btn-check:checked+label.btn-outline-warning {
            background-color: #ffc107 !important;
            color: black !important;
        }

        .btn-check:checked+label.btn-outline-info {
            background-color: #0dcaf0 !important;
            color: white !important;
        }

        /* Quitamos hover/active que confunde con el estado seleccionado */
        .btn-outline-success:hover,
        .btn-outline-danger:hover,
        .btn-outline-warning:hover,
        .btn-outline-info:hover {
            background-color: transparent !important;
            color: inherit !important;
        }

        /* Asegurar que el label no sea transparente */
        .btn-outline-success,
        .btn-outline-danger,
        .btn-outline-warning,
        .btn-outline-info {
            background-color: #f8f9fa;
        }

        /* Eliminamos el resaltado de fondo de las filas y bordes de tarjetas */
        .table-success,
        .table-danger,
        .table-warning,
        .table-info {
            background-color: transparent !important;
        }

        .card[id^="card_"] {
            border-left-width: 4px !important;
            border-color: #dee2e6 !important;
        }

        /* ── Pantallas pequeñas (≤480px) ── */
        @media (max-width: 480px) {
            h2.text-uppercase { font-size: 1.1rem; }
            .card-body.p-4 { padding: 1rem !important; }
        }

        /* ── Pantallas muy pequeñas (≤350px) ── */
        @media (max-width: 350px) {
            .container-fluid { padding-left: 8px !important; padding-right: 8px !important; }
            .card-body, .card-body.p-4 { padding: 0.6rem !important; }
            h2.text-uppercase { font-size: 0.95rem !important; }
            .form-label { font-size: 0.78rem; margin-bottom: 2px; }
            .form-control, input[type="date"], .form-select { font-size: 0.8rem; padding: 0.3rem 0.45rem; }
            .input-group-text { padding: 0.3rem 0.45rem; font-size: 0.8rem; }
            .btn-outline-secondary { font-size: 0.78rem; padding: 0.25rem 0.5rem; }
            .alert { font-size: 0.78rem; padding: 0.5rem 0.6rem; }
            #search_results .list-group-item { padding: 0.4rem 0.5rem !important; font-size: 0.8rem; }
            /* Cards de alumnos */
            #attendance-cards .card-body { padding: 0.55rem !important; }
            #attendance-cards h6 { font-size: 0.82rem; }
            #attendance-cards small { font-size: 0.68rem; }
            #attendance-cards .form-control-sm { font-size: 0.75rem; padding: 0.25rem 0.4rem; }
            /* Botones de estado: 2 columnas en lugar de 4 seguidos */
            #attendance-cards .btn-group {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 3px;
            }
            #attendance-cards .btn-group .btn {
                border-radius: 6px !important;
                font-size: 0.72rem !important;
                padding: 0.28rem 0.2rem !important;
                border-width: 1px !important;
            }
            /* Botón guardar */
            #btn-submit-asistencia { font-size: 0.8rem; padding: 0.35rem 0.4rem; }
        }

        /* ── Pantallas mínimas (<320px) ── */
        @media (max-width: 319px) {
            .container-fluid { padding-left: 6px !important; padding-right: 6px !important; }
            .card-body, .card-body.p-4 { padding: 0.45rem !important; }
            h2.text-uppercase { font-size: 0.82rem !important; }
            p.text-muted.mb-0 { font-size: 0.72rem; }
            .form-label { font-size: 0.72rem; }
            .form-control, input[type="date"] { font-size: 0.74rem; padding: 0.25rem 0.35rem; }
            .input-group-text { font-size: 0.74rem; padding: 0.25rem 0.35rem; }
            .d-flex.align-items-center.gap-3 { gap: 0.35rem !important; }
            /* Cards de alumnos */
            #attendance-cards .card-body { padding: 0.4rem !important; }
            #attendance-cards h6 { font-size: 0.76rem; }
            #attendance-cards small { font-size: 0.63rem; }
            #attendance-cards .badge { font-size: 0.6rem; padding: 0.2em 0.4em; }
            #attendance-cards .btn-group .btn { font-size: 0.65rem !important; padding: 0.22rem 0.1rem !important; }
            #attendance-cards .form-control-sm { font-size: 0.7rem; }
            /* Mensaje vacío */
            #no-students-msg .lead { font-size: 0.82rem; }
            #no-students-msg .display-4 { font-size: 2rem; }
            /* Botón guardar */
            #btn-submit-asistencia { font-size: 0.74rem; padding: 0.28rem 0.35rem; }
            #btn-submit-asistencia .bi { display: none; }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('profesor.baseProfesor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DANTE\Desktop\YebSis\resources\views/profesor/asistenciaProfesor.blade.php ENDPATH**/ ?>