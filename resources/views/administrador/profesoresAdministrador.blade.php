@extends('administrador.baseAdministrador')

@section('title', 'Profesores')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ auto_asset('css/administrador/profesoresAdministrador.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-person-workspace me-2"></i>Lista de Profesores</h2>
        <a href="{{ route('administrador.formProfesor') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Registrar Profesor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Buscador --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form action="{{ route('profesores.index') }}" method="GET" class="w-100">
                <div class="row align-items-center">
                    <div class="col-12 col-sm-8 col-lg-6">
                        <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">Buscar Profesor</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input id="searchInput" type="text" class="form-control border-start-0 ps-0" placeholder="Filtrar por nombre, apellido o correo..." name="search" value="{{ request()->search }}" data-table-filter="teachersTable">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($profesores->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-person-slash text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-muted">No hay profesores registrados.</h5>
                <a href="{{ route('administrador.formProfesor') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-user-plus me-2"></i>Registrar el primero
                </a>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="teachersTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-3 py-3">Nombre</th>
                                <th class="py-3">Apellido</th>
                                <th class="py-3">Teléfono</th>
                                <th class="py-3">Profesión</th>
                                <th class="py-3">Correo</th>
                                <th class="py-3">Rol componentes</th>
                                <th class="pe-3 py-3 text-end" style="min-width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profesores as $profesor)
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $profesor->persona->Nombre ?? '' }}</td>
                                <td>{{ $profesor->persona->Apellido ?? '' }}</td>
                                <td>{{ $profesor->persona->Celular ?? '' }}</td>
                                <td>{{ $profesor->Profesion ?? '' }}</td>
                                <td>{{ $profesor->usuario->Correo ?? '' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $profesor->Rol_componentes ?? 'Ninguno' }}</span></td>
                                <td class="pe-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <!-- Ver -->
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#verProfesorModal{{ $profesor->Id_profesores }}" title="Ver detalles">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>

                                        <!-- Editar -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editarProfesorModal{{ $profesor->Id_profesores }}" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Eliminar -->
                                        <form action="{{ route('profesores.destroy', $profesor->Id_profesores) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este profesor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Ver Profesor -->
                            <div class="modal fade" id="verProfesorModal{{ $profesor->Id_profesores }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i>Detalles del Profesor</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Nombre</label>
                                                    <div class="fs-5">{{ $profesor->persona->Nombre ?? '' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Apellido</label>
                                                    <div class="fs-5">{{ $profesor->persona->Apellido ?? '' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Celular</label>
                                                    <div class="fs-5">{{ $profesor->persona->Celular ?? '' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Correo</label>
                                                    <div class="fs-5">{{ $profesor->usuario->Correo ?? '' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Profesión</label>
                                                    <div class="fs-5">{{ $profesor->Profesion ?? '' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold text-muted small">Rol Componentes</label>
                                                    <div class="fs-5">{{ $profesor->Rol_componentes ?? '' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Editar Profesor -->
                            <div class="modal fade" id="editarProfesorModal{{ $profesor->Id_profesores }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Profesor</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('profesores.update', $profesor->Id_profesores) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Nombre</label>
                                                        <input type="text" name="nombre" class="form-control" value="{{ $profesor->persona->Nombre ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Apellido</label>
                                                        <input type="text" name="apellido" class="form-control" value="{{ $profesor->persona->Apellido ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Celular</label>
                                                        <input type="text" name="celular" class="form-control" value="{{ $profesor->persona->Celular ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Correo electrónico</label>
                                                        <input type="email" name="correo" class="form-control" value="{{ $profesor->usuario->Correo ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Nueva contraseña (opcional)</label>
                                                        <input type="password" name="contrasenia" class="form-control" placeholder="••••••••">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Profesión</label>
                                                        <input type="text" name="profesion" class="form-control" value="{{ $profesor->Profesion ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Rol en Componentes</label>
                                                        <select name="rol_componentes" class="form-select">
                                                            <option value="Ninguno" {{ $profesor->Rol_componentes == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                                                            <option value="Tecnico" {{ $profesor->Rol_componentes == 'Tecnico' ? 'selected' : '' }}>Técnico</option>
                                                            <option value="Inventario" {{ $profesor->Rol_componentes == 'Inventario' ? 'selected' : '' }}>Inventario</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-center mt-4 mb-4">
        {{ $profesores->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
/* MIGRADO A baseAdministrador.blade.php */
/*
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('#searchInput');
    const table = document.querySelector('#teachersTable');
    if (!input || !table) return;

    input.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
});
*/
</script>
@endsection
