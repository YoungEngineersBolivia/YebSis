@extends('/administrador/baseAdministrador')

@section('title', 'Programas')

@section('styles')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Programas</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoProgramaModal">
            <i class="fas fa-plus me-2"></i>Añadir Programa
        </button>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
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

    {{-- Buscador --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar Programa" id="searchInput">
            </div>
        </div>
    </div>

    @if($programas->isEmpty())
        <div class="alert alert-warning">
            <i class="bi bi-info-circle me-2"></i>No hay programas registrados.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover" id="tablaProgramas">
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
                            <td>{{ number_format($programa->Costo, 2) }} Bs</td>
                            <td>{{ $programa->Rango_edad }}</td>
                            <td>{{ $programa->Duracion }}</td>
                            <td>{{ Str::limit($programa->Descripcion, 50) }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Ver --}}
                                    <a href="{{ route('programas.show', $programa->Id_programas) }}" class="btn btn-sm btn-outline-dark" title="Ver detalles">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    {{-- Editar --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#editarProgramaModal{{ $programa->Id_programas }}" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('programas.destroy', $programa->Id_programas) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este programa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal fade" id="editarProgramaModal{{ $programa->Id_programas }}" tabindex="-1"
                            aria-labelledby="editarProgramaLabel{{ $programa->Id_programas }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning bg-opacity-25">
                                        <h5 class="modal-title" id="editarProgramaLabel{{ $programa->Id_programas }}">
                                            Editar Programa
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <form action="{{ route('programas.update', $programa->Id_programas) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" name="nombre" class="form-control" value="{{ $programa->Nombre }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Tipo</label>
                                                    <select name="tipo" class="form-control" required>
                                                        <option value="programa" {{ $programa->Tipo==='programa' ? 'selected' : '' }}>Programa</option>
                                                        <option value="taller" {{ $programa->Tipo==='taller' ? 'selected' : '' }}>Taller</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Costo (Bs)</label>
                                                    <input type="number" step="0.01" name="costo" class="form-control" value="{{ $programa->Costo }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Rango de Edad</label>
                                                    <input type="text" name="rango_edad" class="form-control" value="{{ $programa->Rango_edad }}" required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Duración</label>
                                                <input type="text" name="duracion" class="form-control" value="{{ $programa->Duracion }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Descripción</label>
                                                <textarea name="descripcion" class="form-control" rows="3" required>{{ $programa->Descripcion }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Imagen</label>
                                                <input type="file" name="imagen" class="form-control" accept="image/*">
                                                <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                                                <div class="mt-2">
                                                    @if($programa->Imagen)
                                                        <p class="text-muted mb-1">Imagen actual:</p>
                                                        <img src="{{ asset('storage/'.$programa->Imagen) }}" class="img-thumbnail rounded" style="max-width: 180px;">
                                                    @else
                                                        <p class="text-muted">Sin imagen</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Actualizar Programa</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $programas->links('pagination::bootstrap-5') }}
        </div>
    @endif

    {{-- Modal Añadir --}}
    <div class="modal fade" id="nuevoProgramaModal" tabindex="-1" aria-labelledby="nuevoProgramaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-opacity-25">
                    <h5 class="modal-title">Nuevo Programa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoPrograma" action="{{ route('programas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="tipo" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="programa">Programa</option>
                                    <option value="taller">Taller</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Costo (Bs)</label>
                                <input type="number" step="0.01" name="costo" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rango de Edad</label>
                                <input type="text" name="rango_edad" class="form-control" placeholder="Ej: 6-12 años" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Duración</label>
                            <input type="text" name="duracion" class="form-control" placeholder="Ej: 3 meses" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                            <small class="text-muted">Formatos: JPG, PNG, GIF (máx. 2MB)</small>
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
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('searchInput');
    const table = document.querySelector('#tablaProgramas tbody');
    if (input && table) {
        input.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            table.querySelectorAll('tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Cierre automático de alertas
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endsection
