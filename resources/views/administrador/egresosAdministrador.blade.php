@extends('administrador.baseAdministrador')

@section('title', 'Gestión de Egresos')

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-xl-5 col-lg-6 mb-3 mb-lg-0">
                <h1><i class="bi bi-cash-stack text-primary"></i> Gestión de Egresos</h1>
                <p class="text-muted">Administración y control de gastos del sistema</p>
            </div>
            <div class="col-xl-7 col-lg-6 d-flex flex-wrap justify-content-lg-end align-items-center gap-2">
                <form action="{{ route('egresos.index') }}" method="GET"
                    class="d-flex flex-wrap gap-2 me-lg-2 w-100 w-lg-auto mb-2 mb-lg-0">
                    <div class="d-flex gap-2 flex-grow-1">
                        <select name="mes" class="form-select form-select-sm" onchange="this.form.submit()"
                            style="min-width: 120px;">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $estadisticasTiempo['mes_num'] == $m ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($m)->monthName }}
                                </option>
                            @endfor
                        </select>
                        <input type="number" name="anio" class="form-control form-control-sm" style="width: 85px;"
                            min="2000" value="{{ $estadisticasTiempo['anio_actual'] }}" onchange="this.form.submit()">
                    </div>
                    <a href="{{ route('egresos.index') }}" class="btn btn-sm btn-outline-secondary"
                        title="Reiniciar filtros">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </form>

                <div class="d-flex flex-wrap gap-2 w-100 w-lg-auto justify-content-between justify-content-lg-end">
                    <div class="btn-group shadow-sm flex-grow-1 flex-lg-grow-0">
                        <a href="{{ route('egresos.formato') }}"
                            class="btn btn-sm btn-outline-success d-flex align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i> <span
                                class="d-none d-sm-inline">Formato</span>
                        </a>
                        <button type="button"
                            class="btn btn-sm btn-success d-flex align-items-center justify-content-center"
                            data-bs-toggle="modal" data-bs-target="#modalImportarEgresos">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i> <span
                                class="d-none d-sm-inline">Importar</span>
                        </button>
                    </div>

                    <button type="button"
                        class="btn btn-sm btn-primary shadow-sm flex-grow-1 flex-lg-grow-0 d-flex align-items-center justify-content-center"
                        data-bs-toggle="modal" data-bs-target="#modalRegistrarEgreso">
                        <i class="bi bi-plus-lg me-1"></i> <span class="">Nuevo Egreso</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Métricas Rápidas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1 text-uppercase small fw-bold">Egresos en
                                {{ $estadisticasTiempo['mes_nombre'] }}
                            </h6>
                            <h2 class="mb-0 fs-3 fw-bold">Bs. {{ number_format($totalMes, 2) }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-20 p-2 p-md-3 rounded-3">
                            <i class="bi bi-graph-down-arrow fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center p-2 p-md-3">
                    <div class="flex-grow-1">
                        <div class="input-group search-box border-0 shadow-none m-0">
                            <span class="input-group-text bg-transparent border-0"><i
                                    class="bi bi-search text-primary"></i></span>
                            <input type="text" class="form-control border-0 bg-transparent"
                                placeholder="Filtrar por descripción o tipo..." id="searchInput"
                                data-table-filter="egresosTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Detalles a Ancho Completo --}}
    <div class="card border-0 shadow-sm">
        <div
            class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 border-0">
            <h5 class="mb-0 fw-bold text-dark text-center text-sm-start">
                <i class="bi bi-list-task me-2 text-primary"></i>
                Detalles: {{ $estadisticasTiempo['mes_nombre'] }} {{ $estadisticasTiempo['anio_actual'] }}
            </h5>
            <span class="badge bg-primary rounded-pill">{{ count($egresos) }} registros</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="egresosTable">
                    <thead class="bg-light">
                        <tr class="small text-uppercase text-muted">
                            <th class="ps-3 ps-md-4">Concepto</th>
                            <th class="d-none d-md-table-cell">Fecha</th>
                            <th class="text-center">Monto</th>
                            <th class="pe-3 pe-md-4 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($egresos as $egreso)
                            <tr>
                                <td class="ps-3 ps-md-4">
                                    <div class="fw-bold text-dark text-wrap" style="max-width: 250px;">
                                        {{ $egreso->Descripcion_egreso }}</div>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1"
                                            style="font-size: 0.65rem;">
                                            {{ $egreso->Tipo }}
                                        </span>
                                        <span class="d-md-none text-muted small" style="font-size: 0.65rem;">
                                            <i
                                                class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($egreso->Fecha_egreso)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($egreso->Fecha_egreso)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-danger">Bs. {{ number_format($egreso->Monto_egreso, 2) }}</span>
                                </td>
                                <td class="pe-3 pe-md-4 text-end">
                                    <div class="btn-group btn-group-sm shadow-sm bg-white">
                                        <button class="btn btn-sm btn-white border-0 btn-editar" title="Editar"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarEgreso"
                                            data-id="{{ $egreso->Id_egreso }}" data-tipo="{{ $egreso->Tipo }}"
                                            data-descripcion="{{ $egreso->Descripcion_egreso }}"
                                            data-fecha="{{ $egreso->Fecha_egreso }}" data-monto="{{ $egreso->Monto_egreso }}">
                                            <i class="bi bi-pencil-fill text-primary"></i>
                                        </button>
                                        <button class="btn btn-sm btn-white border-0 btn-eliminar" title="Eliminar"
                                            data-bs-toggle="modal" data-bs-target="#modalEliminarEgreso"
                                            data-id="{{ $egreso->Id_egreso }}"
                                            data-descripcion="{{ $egreso->Descripcion_egreso }}">
                                            <i class="bi bi-trash3-fill text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Sin egresos en este mes.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Egreso -->
    <div class="modal fade" id="modalRegistrarEgreso" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('egresos.store') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Nuevo Egreso</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de Egreso</label>
                            <select name="Tipo" class="form-select" required>
                                <option value="">Seleccione un tipo...</option>
                                <option value="Sueldos">Sueldos</option>
                                <option value="Servicios">Servicios (Luz, Agua, Internet)</option>
                                <option value="Alquiler">Alquiler</option>
                                <option value="Materiales">Materiales / Insumos</option>
                                <option value="Marketing">Marketing / Publicidad</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea class="form-control" name="Descripcion_egreso" rows="3"
                                placeholder="Detalle del gasto..." required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Monto (Bs)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Bs</span>
                                    <input type="number" step="0.01" class="form-control" name="Monto_egreso" min="0"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Fecha</label>
                                <input type="date" class="form-control" name="Fecha_egreso" value="{{ date('Y-m-d') }}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3">
                        <button type="button" class="btn btn-link text-muted text-decoration-none"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Guardar Egreso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Egreso -->
    <div class="modal fade" id="modalEditarEgreso" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="#" id="formEditarEgreso">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-dark text-white border-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Egreso</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de Egreso</label>
                            <select name="Tipo" id="editTipo" class="form-select" required>
                                <option value="Sueldos">Sueldos</option>
                                <option value="Servicios">Servicios</option>
                                <option value="Alquiler">Alquiler</option>
                                <option value="Materiales">Materiales</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea class="form-control" id="editDescripcion" name="Descripcion_egreso" rows="3"
                                required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Monto (Bs)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Bs</span>
                                    <input type="number" step="0.01" class="form-control" id="editMonto" name="Monto_egreso"
                                        min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Fecha</label>
                                <input type="date" class="form-control" id="editFecha" name="Fecha_egreso" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3">
                        <button type="button" class="btn btn-link text-muted text-decoration-none"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Egreso -->
    <div class="modal fade" id="modalEliminarEgreso" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="#" id="formEliminarEgreso">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-4 text-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-trash3-fill fs-2"></i>
                        </div>
                        <h5 class="fw-bold">¿Eliminar egreso?</h5>
                        <p class="text-muted small">Esta acción no se puede deshacer. Se eliminará el registro de:</p>
                        <p class="fw-bold mb-4" id="egresoEliminarDescripcion"></p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                            <button type="button" class="btn btn-outline-secondary border-0"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Importar Egresos -->
    <div class="modal fade" id="modalImportarEgresos" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('egresos.importar') }}" enctype="multipart/form-data"
                    id="formImportarEgresos">
                    @csrf
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Carga Masiva de
                            Egresos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="egresoImportContent">
                            <div class="alert alert-info small border-0 shadow-sm mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Instrucciones:</strong>
                                <ol class="mb-0 mt-1">
                                    <li>Descarga el formato usando el botón <strong>"Formato"</strong>.</li>
                                    <li>Llena los datos en Excel (asegúrate de mantener las columnas).</li>
                                    <li>Guarda el archivo como CSV y súbelo aquí.</li>
                                </ol>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Seleccionar archivo CSV</label>
                                <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
                                <small class="text-muted">Solo archivos .csv (Excel delimitado por comas)</small>
                            </div>
                        </div>
                        <!-- Barra de Carga -->
                        <div id="egresoImportProgress" class="d-none text-center py-4">
                            <div class="spinner-border text-success mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <h5 class="fw-bold text-dark">Procesando información...</h5>
                            <p class="text-muted small">Esto puede tardar unos segundos, por favor no cierres la ventana.
                            </p>
                            <div class="progress mt-3" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3" id="egresoImportFooter">
                        <button type="button" class="btn btn-link text-muted text-decoration-none"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success px-4 shadow-sm">
                            <i class="bi bi-cloud-upload me-1"></i> Iniciar Carga
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // MODAL EDITAR - Llenar con datos del egreso
            const btnEditores = document.querySelectorAll('.btn-editar');
            btnEditores.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const tipo = this.dataset.tipo;
                    const descripcion = this.dataset.descripcion;
                    const fecha = this.dataset.fecha;
                    const monto = this.dataset.monto;

                    document.getElementById('editTipo').value = tipo;
                    document.getElementById('editDescripcion').value = descripcion;
                    document.getElementById('editFecha').value = fecha;
                    document.getElementById('editMonto').value = monto;

                    const form = document.getElementById('formEditarEgreso');
                    form.action = "{{ route('egresos.update', ':id') }}".replace(':id', id);
                });
            });

            // MODAL ELIMINAR - Configurar con ID del egreso
            const btnEliminadores = document.querySelectorAll('.btn-eliminar');
            btnEliminadores.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const descripcion = this.dataset.descripcion;

                    document.getElementById('egresoEliminarDescripcion').textContent = descripcion;

                    const form = document.getElementById('formEliminarEgreso');
                    form.action = "{{ route('egresos.destroy', ':id') }}".replace(':id', id);
                });
            });

            // Barra de carga para importación de egresos
            const formImportar = document.getElementById('formImportarEgresos');
            if (formImportar) {
                formImportar.addEventListener('submit', function () {
                    document.getElementById('egresoImportContent').classList.add('d-none');
                    document.getElementById('egresoImportFooter').classList.add('d-none');
                    document.getElementById('egresoImportProgress').classList.remove('d-none');
                    const modalEl = document.getElementById('modalImportarEgresos');
                    modalEl.querySelector('.btn-close').classList.add('d-none');
                });
            }
        });
    </script>
@endsection