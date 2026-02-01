@extends('administrador.baseAdministrador')

@section('title', 'Pagos')

@section('styles')
    <link rel="stylesheet" href="{{ auto_asset('css/administrador/pagosAdministrador.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header Principal -->
        <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1><i class="fas fa-money-bill-wave me-2"></i>Gestión de Pagos</h1>
                <p>Administra los pagos de los estudiantes</p>
            </div>
            <div class="d-flex align-items-center gap-3 flex-grow-1 justify-content-end" style="max-width: 600px;">
                <div class="input-group search-box flex-grow-1">
                    <span class="input-group-text border-end-0 bg-transparent">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="buscarEstudiante" class="form-control border-start-0 ps-0"
                        placeholder="Buscar por tutor o estudiante..." autocomplete="off">
                </div>
                <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm" id="contador-resultados">
                    {{ $estudiantes->total() }}
                </span>
            </div>
        </div>

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
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('pagos.registrar') }}" id="formAgregarPago">
                @csrf
                <input type="hidden" name="plan_id" id="modal-plan-id">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="modalAgregarPagoLabel">Registrar Pago</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="alert alert-primary mb-4">
                            <h6 class="mb-1 fw-bold" id="modal-programa-nombre">Programa</h6>
                            <div class="d-flex justify-content-between mt-2">
                                <small>Total: Bs. <span id="modal-monto-total-display">0.00</span></small>
                                <small>Restante: Bs. <span id="modal-restante-display">0.00</span></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="descripcion" placeholder="Ej: Mensualidad Enero"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Monto (Bs.)</label>
                            <input type="number" step="0.01" class="form-control" name="monto_pago" id="modal-monto-input"
                                placeholder="0.00" required min="0.01">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" class="form-control" name="fecha_pago" required
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Referencia</label>
                                <input type="text" class="form-control" name="comprobante" placeholder="Opcional">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Pago</button>
                    </div>
                </div>
            </form>
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