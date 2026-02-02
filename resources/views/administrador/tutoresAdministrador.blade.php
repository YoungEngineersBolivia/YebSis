@extends('administrador.baseAdministrador')

@section('title', 'Tutores')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="mt-2 text-start">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Lista de Tutores</h2>
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Buscador --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <form id="formBuscador" action="{{ route('tutores.index') }}" method="GET" class="w-100">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label for="searchTutor" class="form-label mb-1 fw-semibold text-muted">Buscar Tutor</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-search text-muted"></i></span>
                                <input type="text" id="searchTutor" class="form-control border-start-0 ps-0" name="search"
                                    placeholder="Buscar por nombre, apellido o celular..." value="{{ $search ?? '' }}"
                                    data-table-filter="tutorsTable">
                                <button type="submit" class="btn btn-primary ms-2 rounded">Buscar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($tutores->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-person-slash text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">No hay tutores registrados</h5>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0" id="tutorsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3 py-3">Nombre</th>
                                    <th class="py-3">Apellido</th>
                                    <th class="py-3">Celular</th>
                                    <th class="py-3">Dirección</th>
                                    <th class="py-3">Correo</th>
                                    <th class="py-3">Parentesco</th>
                                    <th class="pe-3 py-3 text-end" style="min-width:140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tutores as $tutor)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $tutor->persona->Nombre ?? '—' }}</td>
                                        <td>{{ $tutor->persona->Apellido ?? '—' }}</td>
                                        <td>{{ $tutor->persona->Celular ?? '—' }}</td>
                                        <td>{{ $tutor->persona->Direccion_domicilio ?? '—' }}</td>
                                        <td>{{ $tutor->usuario->Correo ?? '—' }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $tutor->Parentesco ?? '—' }}</span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                {{-- Botón Ver - Redirige a detalles --}}
                                                <a href="{{ route('tutores.detalles', $tutor->Id_tutores) }}"
                                                    class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                {{-- Botón Editar - Abre modal --}}
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditar{{ $tutor->Id_tutores }}" title="Editar">
                                                    <i class="fa fa-pencil-alt"></i>
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

            @foreach ($tutores as $tutor)
                {{-- Modal Editar para cada tutor --}}
                <div class="modal fade" id="modalEditar{{ $tutor->Id_tutores }}" tabindex="-1"
                    aria-labelledby="modalEditarLabel{{ $tutor->Id_tutores }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <form action="{{ route('tutores.update', $tutor->Id_tutores) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalEditarLabel{{ $tutor->Id_tutores }}">
                                        <i class="bi bi-pencil-square me-2"></i>Editar Tutor
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Nombre</label>
                                            <input type="text" name="nombre" class="form-control"
                                                value="{{ $tutor->persona->Nombre ?? '' }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Apellido</label>
                                            <input type="text" name="apellido" class="form-control"
                                                value="{{ $tutor->persona->Apellido ?? '' }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Celular</label>
                                            <input type="text" name="celular" class="form-control"
                                                value="{{ $tutor->persona->Celular ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Dirección</label>
                                            <input type="text" name="direccion_domicilio" class="form-control"
                                                value="{{ $tutor->persona->Direccion_domicilio ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Correo</label>
                                            <input type="email" name="correo" class="form-control"
                                                value="{{ $tutor->usuario->Correo ?? '' }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Parentesco</label>
                                            <input type="text" name="parentezco" class="form-control"
                                                value="{{ $tutor->Parentesco ?? '' }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">Descuento (%)</label>
                                            <input type="number" name="descuento" class="form-control" step="0.01" min="0" max="100"
                                                value="{{ $tutor->Descuento ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label fw-bold">NIT</label>
                                            <input type="text" name="nit" class="form-control" value="{{ $tutor->Nit ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save me-1"></i>Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-4 mb-4">
                {{ $tutores->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script>
        let timer;
        let lastValue = "{{ $search ?? '' }}";


        window.onload = function () {
            const input = document.getElementById('searchTutor');
            if (input) {
                input.focus();
                input.setSelectionRange(input.value.length, input.value.length);
            }
        };

        /* MIGRADO A baseAdministrador.blade.php - Se eliminó auto-submit para usar filtro cliente */
        /*
        document.getElementById('searchTutor').addEventListener('input', function () {
            const value = this.value;


            if (value === lastValue) return;

            clearTimeout(timer);


            timer = setTimeout(() => {
                lastValue = value;
                document.getElementById('formBuscador').submit();
            }, 900); 
        });
        */
    </script>


@endsection