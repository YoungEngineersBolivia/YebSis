@extends('administrador.baseAdministrador')

@section('title', 'Historial de Egresos')

@section('content')
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1><i class="bi bi-calendar3 text-primary"></i> Egresos Totales por Mes</h1>
            <p class="text-muted mb-0">Resumen histórico de gastos mensuales</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <form action="{{ route('egresos.mensuales') }}" method="GET" class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted small fw-bold text-uppercase mb-0">Año:</label>
                    <input type="number" name="anio" class="form-control form-control-sm shadow-sm" style="width: 100px;"
                        min="2000" value="{{ $anioSeleccionado }}" onchange="this.form.submit()">
                </div>
                <!-- Buscador de Tabla -->
                <div class="input-group search-box border-0 shadow-sm" style="max-width: 250px;">
                    <span class="input-group-text"><i class="bi bi-search py-0"></i></span>
                    <input type="text" class="form-control form-control-sm" placeholder="Buscar mes..."
                        data-table-filter="tablaEgresosMensuales">
                </div>
                <a href="{{ route('egresos.mensuales') }}" class="btn btn-sm btn-outline-secondary shadow-sm"
                    title="Limpiar filtro">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </form>

            <div class="btn-group shadow-sm">
                <a href="{{ route('egresos.formato') }}" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-arrow-down"></i> Formato
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportarEgresos">
                    <i class="bi bi-file-earmark-arrow-up"></i> Importar
                </button>
            </div>

            <a href="{{ route('egresos.index') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Gestionar Egresos
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" id="tablaEgresosMensuales">
                    <thead>
                        <tr>
                            <th class="ps-4">Periodo (Mes/Año)</th>
                            <th class="text-center">Cant. Egresos</th>
                            <th class="text-center">Total Gastado</th>
                            <th class="pe-4 text-end">Último Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($egresosMensuales as $egreso)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2 me-3">
                                            <i class="bi bi-calendar-minus fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-capitalize">{{ $egreso->nombre_mes }}</span>
                                            <span class="text-muted ms-1">{{ $egreso->anio }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('egresos.index', ['mes' => $egreso->mes, 'anio' => $egreso->anio]) }}"
                                        class="badge bg-info text-white rounded-pill px-3 text-decoration-none hover-shadow"
                                        title="Ver detalles de estos egresos">
                                        {{ $egreso->cantidad_egresos }} registros <i class="bi bi-eye ms-1"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-danger fs-5">
                                        Bs. {{ number_format($egreso->total, 2, '.', ',') }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <small class="text-muted">
                                        <i class="bi bi-clock-history me-1"></i>
                                        {{ \Carbon\Carbon::parse($egreso->ultima_fecha)->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                                    No se encontraron registros de egresos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($egresosMensuales->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="ps-4 fw-bold text-end">Total Histórico:</td>
                                <td class="text-center">
                                    <span class="fw-bold text-danger fs-4">
                                        Bs. {{ number_format($egresosMensuales->sum('total'), 2, '.', ',') }}
                                    </span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Importar Egresos -->
    <div class="modal fade" id="modalImportarEgresos" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="{{ route('egresos.importar') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Carga Masiva de
                            Egresos</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="importContent">
                            <div class="alert alert-info small border-0 shadow-sm mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Instrucciones:</strong>
                                <ol class="mb-0 mt-1">
                                    <li>Descarga el formato usando el botón <strong>"Formato"</strong>.</li>
                                    <li>Llena los datos en Excel (asegúrate de mantener las columnas).</li>
                                    <li>Guarda el archivo y súbelo aquí.</li>
                                </ol>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Seleccionar archivo CSV</label>
                                <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
                                <small class="text-muted">Solo archivos .csv (Excel delimitado por comas)</small>
                            </div>
                        </div>

                        <!-- Barra de Carga (Oculta por defecto) -->
                        <div id="importProgress" class="d-none text-center py-4">
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
                    <div class="modal-footer bg-light border-0 p-3" id="importFooter">
                        <button type="button" class="btn btn-link text-muted text-decoration-none"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success px-4 shadow-sm" id="btnIniciarCarga">
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
        document.querySelector('#modalImportarEgresos form').addEventListener('submit', function () {
            // Ocultar contenido y mostrar barra de carga
            document.getElementById('importContent').classList.add('d-none');
            document.getElementById('importFooter').classList.add('d-none');
            document.getElementById('importProgress').classList.remove('d-none');

            // Bloquear el cierre del modal
            const modalElement = document.getElementById('modalImportarEgresos');
            const modal = bootstrap.Modal.getInstance(modalElement);
            modalElement.querySelector('.btn-close').classList.add('d-none');
        });
    </script>
@endsection