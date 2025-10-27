@extends('/administrador/baseAdministrador')

@section('title', 'Programas')

@section('styles')
    <link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Programas</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoProgramaModal">
            <i class="fas fa-plus me-2"></i>Añadir Programa
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
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

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar Programa" id="searchInput">
            </div>
        </div>
    </div>

    @if($programas->isEmpty())
        <div class="alert alert-warning">No hay programas registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold text-dark">Nombre</th>
                        <th class="fw-bold text-dark">Tipo</th> <!-- Nuevo campo de tipo -->
                        <th class="fw-bold text-dark">Costo</th>
                        <th class="fw-bold text-dark">Rango de Edad</th>
                        <th class="fw-bold text-dark">Duración</th>
                        <th class="fw-bold text-dark">Descripción</th>
                        <th class="fw-bold text-dark">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programas as $programa)
                        <tr>
                            <td class="fw-normal">{{ $programa->Nombre }}</td>
                            <td class="text-muted">{{ $programa->Tipo ?? 'No especificado' }}</td> <!-- Mostrar tipo -->
                            <td class="text-muted">{{ number_format($programa->Costo, 0) }} Bs</td>
                            <td class="text-muted">{{ $programa->Rango_edad }}</td>
                            <td class="text-muted">{{ $programa->Duracion }}</td>
                            <td class="text-muted">{{ Str::limit($programa->Descripcion, 50) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="eliminarPrograma({{ $programa->id }})"
                                            title="Eliminar">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="editarPrograma({{ $programa->Id_programas }})"
                                            title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-dark" 
                                            onclick="verPrograma({{ $programa->id }})"
                                            title="Ver detalles">
                                        <i class="bi bi-person-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $programas->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Modal para añadir programa -->
    <div class="modal fade" id="nuevoProgramaModal" tabindex="-1" aria-labelledby="nuevoProgramaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Programa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoPrograma" action="{{ route('programas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo" class="form-label">Tipo de Programa</label>
                                    <select class="form-control" id="tipo" name="tipo" required>
                                        <option value="programa">Programa</option>
                                        <option value="taller">Taller</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="costo" class="form-label">Costo (Bs)</label>
                                    <input type="number" class="form-control" id="costo" name="costo" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rango_edad" class="form-label">Rango de Edad</label>
                                    <input type="text" class="form-control" id="rango_edad" name="rango_edad" required>
                                </div>
                            </div>
                            <div class="mb-3">
                            <label for="duracion" class="form-label">Duracion</label>
                            <input type="text" class="form-control" id="duracion" name="duracion" required>
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Foto del Programa</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formNuevoPrograma" class="btn btn-primary">Guardar Programa</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="{{ auto_asset('js/administrador/programasAdministrador.js') }}"></script>
@endsection
