@extends('administrador.baseAdministrador')

@section('title', 'Pagos')

@section('styles')
    <link rel="stylesheet" href="{{ auto_asset('css/administrador/pagosAdministrador.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
    <div class="mt-2 text-start">

        <!-- Header Principal -->
        <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1><i class="fas fa-money-bill-wave me-2"></i>Gestión de Pagos</h1>
                <p>Administra los pagos de los estudiantes</p>
            </div>
            <div class="d-flex align-items-center gap-3 flex-grow-1 justify-content-end" style="max-width: 800px;">
                <!-- Buscador AJAX -->
                <div class="input-group search-box border-0 shadow-sm flex-grow-1" style="max-width: 400px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="buscarEstudiante" class="form-control"
                        placeholder="Buscar por estudiante o tutor..." value="{{ request('search') }}">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small fw-bold text-uppercase">Resultados:</span>
                    <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm" id="contador-resultados">
                        {{ $estudiantes->total() }}
                    </span>
                </div>
            </div>
        </div>

        @if($mesSeleccionado || $anioSeleccionado)
            <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="bi bi-funnel-fill me-2 fs-5"></i>
                <div>
                    Viendo pagos de:
                    <strong class="text-capitalize">
                        {{ \Carbon\Carbon::create()->month((int) ($mesSeleccionado ?? 1))->monthName }} {{ $anioSeleccionado }}
                    </strong>
                </div>
                <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-info ms-auto text-dark fw-bold">
                    <i class="bi bi-x-circle me-1"></i> Quitar Filtros
                </a>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Lista de estudiantes -->
        <!-- Lista de estudiantes con contenedor para AJAX -->
        <div id="contenedor-estudiantes">
            @include('administrador.partials.pagos_lista')
        </div>

    </div>

    <!-- Modal para agregar pago -->
    <div class="modal fade" id="modalAgregarPago" tabindex="-1" aria-labelledby="modalAgregarPagoLabel" aria-hidden="true">
        {{-- ... contenido ya existente ... --}}
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('pagos.registrar') }}" id="formAgregarPago">
                @csrf
                <input type="hidden" name="plan_id" id="modal-plan-id">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="modalAgregarPagoLabel"><i
                                class="fas fa-plus-circle me-2"></i>Registrar Pago</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="alert alert-primary mb-4 border-0 shadow-sm">
                            <h6 class="mb-1 fw-bold" id="modal-programa-nombre">Programa</h6>
                            <div class="d-flex justify-content-between mt-2">
                                <small>Total: Bs. <span id="modal-monto-total-display">0.00</span></small>
                                <small>Restante: Bs. <span id="modal-restante-display">0.00</span></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <input type="text" class="form-control" name="descripcion" placeholder="Ej: Mensualidad Enero"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Monto (Bs.)</label>
                            <input type="number" step="0.01" class="form-control" name="monto_pago" id="modal-monto-input"
                                placeholder="0.00" required min="0.01">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Fecha</label>
                                <input type="date" class="form-control" name="fecha_pago" required
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Referencia/Comprobante</label>
                                <input type="text" class="form-control" name="comprobante" placeholder="Opcional">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Guardar Pago</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para EDITAR pago -->
    <div class="modal fade" id="modalEditarPago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="#" id="formEditarPago">
                @csrf
                @method('PUT')
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Pago</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <input type="text" class="form-control" name="descripcion" id="edit-descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Monto (Bs.)</label>
                            <input type="number" step="0.01" class="form-control" name="monto_pago" id="edit-monto" required
                                min="0.01">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Fecha</label>
                                <input type="date" class="form-control" name="fecha_pago" id="edit-fecha" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Referencia</label>
                                <input type="text" class="form-control" name="comprobante" id="edit-comprobante">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Actualizar Pago</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para ELIMINAR pago -->
    <div class="modal fade" id="modalEliminarPago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <form method="POST" action="#" id="formEliminarPago">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-4 text-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-inline-block mb-3">
                            <i class="bi bi-trash3-fill fs-2"></i>
                        </div>
                        <h5 class="fw-bold">¿Eliminar pago?</h5>
                        <p class="text-muted small">Esta acción no se puede deshacer. Se eliminará el registro de:</p>
                        <p class="fw-bold mb-4" id="pago-eliminar-desc"></p>
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


@endsection

@section('scripts')
    <script id="estudiantes-data" type="application/json">
                                                                    {!! json_encode($estudiantes) !!}
                                                                </script>

    <script>
        const estudiantes = JSON.parse(document.getElementById('estudiantes-data').textContent);
        const registrarPagoUrl = "{{ route('pagos.registrar') }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>

    <script src="{{ auto_asset('js/administrador/pagosAdministrador.js') }}"></script>
@endsection