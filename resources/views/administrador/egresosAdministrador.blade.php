@extends('administrador.baseAdministrador')

@section('title', 'Egresos')
@section('styles')
    <link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="mt-2 text-start">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2"></i>Egresos</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarEgreso">
                <i class="bi bi-plus-lg me-2"></i>Registrar Egreso
            </button>
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
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">Buscar Egreso</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Descripción, tipo..."
                                id="searchInput" data-table-filter="egresosTable">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($egresos->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-wallet2 text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">No hay egresos registrados.</h5>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0" id="egresosTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3 py-3">Tipo</th>
                                    <th class="py-3">Descripción</th>
                                    <th class="py-3">Fecha</th>
                                    <th class="py-3">Monto</th>
                                    <th class="pe-3 py-3 text-end" style="width: 150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($egresos as $egreso)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $egreso->Tipo }}</td>
                                        <td>{{ $egreso->Descripcion_egreso }}</td>
                                        <td>{{ \Carbon\Carbon::parse($egreso->Fecha_egreso)->format('d/m/Y') }}</td>
                                        <td class="fw-bold text-danger">Bs {{ number_format($egreso->Monto_egreso, 2) }}</td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <!-- Botón Editar -->
                                                <button class="btn btn-sm btn-outline-primary btn-editar" title="Editar"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditarEgreso"
                                                    data-id="{{ $egreso->Id_egreso }}" data-tipo="{{ $egreso->Tipo }}"
                                                    data-descripcion="{{ $egreso->Descripcion_egreso }}"
                                                    data-fecha="{{ $egreso->Fecha_egreso }}"
                                                    data-monto="{{ $egreso->Monto_egreso }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- Botón Eliminar -->
                                                <button class="btn btn-sm btn-outline-danger btn-eliminar" title="Eliminar"
                                                    data-bs-toggle="modal" data-bs-target="#modalEliminarEgreso"
                                                    data-id="{{ $egreso->Id_egreso }}"
                                                    data-descripcion="{{ $egreso->Descripcion_egreso }}">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Registrar Egreso -->
        <div class="modal fade" id="modalRegistrarEgreso" tabindex="-1" aria-labelledby="modalRegistrarEgresoLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('egresos.store') }}">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalRegistrarEgresoLabel"><i
                                    class="bi bi-plus-circle me-2"></i>Registrar Nuevo Egreso</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="tipo" class="form-label fw-bold">Tipo de Egreso</label>
                                <input type="text" class="form-control" id="tipo" name="Tipo" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="Descripcion_egreso" rows="3"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="fecha" class="form-label fw-bold">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="Fecha_egreso"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="monto" class="form-label fw-bold">Monto (Bs)</label>
                                <input type="number" step="0.01" class="form-control" id="monto" name="Monto_egreso" min="0"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Egreso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Editar Egreso -->
        <div class="modal fade" id="modalEditarEgreso" tabindex="-1" aria-labelledby="modalEditarEgresoLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="#" id="formEditarEgreso">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalEditarEgresoLabel"><i
                                    class="bi bi-pencil-square me-2"></i>Editar Egreso</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editTipo" class="form-label fw-bold">Tipo de Egreso</label>
                                <input type="text" class="form-control" id="editTipo" name="Tipo" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDescripcion" class="form-label fw-bold">Descripción</label>
                                <textarea class="form-control" id="editDescripcion" name="Descripcion_egreso" rows="3"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editFecha" class="form-label fw-bold">Fecha</label>
                                <input type="date" class="form-control" id="editFecha" name="Fecha_egreso" required>
                            </div>
                            <div class="mb-3">
                                <label for="editMonto" class="form-label fw-bold">Monto (Bs)</label>
                                <input type="number" step="0.01" class="form-control" id="editMonto" name="Monto_egreso"
                                    min="0" required>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Egreso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Eliminar Egreso -->
        <div class="modal fade" id="modalEliminarEgreso" tabindex="-1" aria-labelledby="modalEliminarEgresoLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="#" id="formEliminarEgreso">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="modalEliminarEgresoLabel"><i
                                    class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar Egreso</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center py-3">
                                <i class="bi bi-trash3 text-danger mb-3" style="font-size: 3rem;"></i>
                                <p>¿Estás seguro de que deseas eliminar este egreso?</p>
                                <p class="text-muted fw-bold"><span id="egresoEliminarDescripcion"></span></p>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                // BÚSQUEDA EN TIEMPO REAL - MIGRADO A baseAdministrador.blade.php
                /*
                $('#searchInput').on('keyup', function() {
                    const searchValue = $(this).val().toLowerCase();

                    $('tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
                    });
                });
                */

                // MODAL EDITAR - Llenar con datos del egreso
                $('.btn-editar').on('click', function () {
                    const id = $(this).data('id');
                    const tipo = $(this).data('tipo');
                    const descripcion = $(this).data('descripcion');
                    const fecha = $(this).data('fecha');
                    const monto = $(this).data('monto');

                    // Llenar campos del modal
                    $('#editTipo').val(tipo);
                    $('#editDescripcion').val(descripcion);
                    $('#editFecha').val(fecha);
                    $('#editMonto').val(monto);

                    // Actualizar action del formulario con el ID correcto
                    const actionUrl = "{{ route('egresos.update', ':id') }}".replace(':id', id);
                    $('#formEditarEgreso').attr('action', actionUrl);
                });

                // MODAL ELIMINAR - Configurar con ID del egreso
                $('.btn-eliminar').on('click', function () {
                    const id = $(this).data('id');
                    const descripcion = $(this).data('descripcion');

                    // Mostrar descripción del egreso a eliminar
                    $('#egresoEliminarDescripcion').text(descripcion);

                    // Actualizar action del formulario con el ID correcto
                    const actionUrl = "{{ route('egresos.destroy', ':id') }}".replace(':id', id);
                    $('#formEliminarEgreso').attr('action', actionUrl);
                });
            });
        </script>
    @endsection