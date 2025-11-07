@extends('/administrador/baseAdministrador')

@section('title', 'Programas')

@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    .modal-content { border-radius: 15px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
    .modal-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0; padding: 20px 25px; }
    .modal-title { font-weight: 700; font-size: 1.3rem; }
    .btn-close-white { filter: brightness(0) invert(1); }
    .form-label { font-weight: 600; color: #495057; margin-bottom: 8px; }
    .form-control, .form-select { border: 2px solid #e0e0e0; border-radius: 8px; padding: 10px 14px; transition: all 0.3s ease; }
    .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.15); }
    .modal-footer { border-top: 1px solid #dee2e6; padding: 15px 25px; }
    .img-preview { max-height: 200px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .badge-custom { padding: 8px 15px; border-radius: 8px; font-weight: 600; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; transform: scale(1.01); box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease; }
</style>
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
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($programas->isEmpty())
        <div class="alert alert-warning">No hay programas registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Costo</th>
                        <th>Rango de Edad</th>
                        <th>Duración</th>
                        <th>Descripción</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programas as $programa)
                    <tr>
                        <td>{{ $programa->Nombre }}</td>
                        <td>
                            @if($programa->Tipo === 'programa')
                                <span class="badge bg-primary">Programa</span>
                            @else
                                <span class="badge bg-info">Taller</span>
                            @endif
                        </td>
                        <td>{{ number_format($programa->Costo,2) }} Bs</td>
                        <td>{{ $programa->Rango_edad }}</td>
                        <td>{{ $programa->Duracion }}</td>
                        <td>{{ Str::limit($programa->Descripcion,50) }}</td>
                        <td class="text-center">
                            <!-- Ver detalles con modal -->
                            <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#verProgramaModal{{ $programa->Id_programas }}">
                                <i class="bi bi-eye-fill"></i>
                            </button>

                            <!-- Editar con modal -->
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editarProgramaModal{{ $programa->Id_programas }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <!-- Eliminar con formulario -->
                            <form action="{{ route('programas.destroy', $programa->Id_programas) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Desea eliminar este programa?')">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Ver Programa -->
                    <div class="modal fade" id="verProgramaModal{{ $programa->Id_programas }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalles de {{ $programa->Nombre }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @if($programa->Imagen)
                                        <img src="{{ asset('storage/'.$programa->Imagen) }}" class="img-preview mb-3" alt="Imagen del programa">
                                    @endif
                                    <p><strong>Tipo:</strong> {{ ucfirst($programa->Tipo) }}</p>
                                    <p><strong>Costo:</strong> Bs {{ number_format($programa->Costo,2) }}</p>
                                    <p><strong>Rango de Edad:</strong> {{ $programa->Rango_edad }}</p>
                                    <p><strong>Duración:</strong> {{ $programa->Duracion }}</p>
                                    <p><strong>Descripción:</strong> {{ $programa->Descripcion }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar Programa -->
                    <div class="modal fade" id="editarProgramaModal{{ $programa->Id_programas }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar {{ $programa->Nombre }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('programas.update', $programa->Id_programas) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" class="form-control" name="nombre" value="{{ $programa->Nombre }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tipo</label>
                                            <select class="form-select" name="tipo" required>
                                                <option value="programa" {{ $programa->Tipo=='programa'?'selected':'' }}>Programa</option>
                                                <option value="taller" {{ $programa->Tipo=='taller'?'selected':'' }}>Taller</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Costo</label>
                                            <input type="number" class="form-control" name="costo" step="0.01" value="{{ $programa->Costo }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rango de Edad</label>
                                            <input type="text" class="form-control" name="rango_edad" value="{{ $programa->Rango_edad }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Duración</label>
                                            <input type="text" class="form-control" name="duracion" value="{{ $programa->Duracion }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Descripción</label>
                                            <textarea class="form-control" name="descripcion" rows="3" required>{{ $programa->Descripcion }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Imagen</label>
                                            <input type="file" class="form-control" name="imagen">
                                            @if($programa->Imagen)
                                                <img src="{{ asset('storage/'.$programa->Imagen) }}" class="img-preview mt-2">
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar Programa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Modal Nuevo Programa --}}
    <div class="modal fade" id="nuevoProgramaModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Programa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('programas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="programa">Programa</option>
                                <option value="taller">Taller</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Costo</label>
                            <input type="number" class="form-control" name="costo" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rango de Edad</label>
                            <input type="text" class="form-control" name="rango_edad" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duración</label>
                            <input type="text" class="form-control" name="duracion" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="imagen">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Programa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
