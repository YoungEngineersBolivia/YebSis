@extends('administrador.baseAdministrador')

@section('title', 'Profesores')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ auto_asset('css/administrador/profesoresAdministrador.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">

    <!-- Toolbar superior -->
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Lista de Profesores</h1>
        <a href="{{ route('administrador.formProfesor') }}" class="btn btn-primary">
            Registrar Profesor
        </a>
    </div>

    {{-- Mensajes de sesión --}}
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

    {{-- Errores de validación --}}
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
    <div class="row mb-3 g-2">
        <div class="col-12 col-sm-8 col-lg-6">
            <form action="{{ route('profesores.index') }}" method="GET" class="w-100">
                <label for="searchInput" class="form-label mb-1">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input id="searchInput" type="text" class="form-control" 
                           placeholder="Filtrar por nombre, apellido o correo"
                           name="search" value="{{ request()->search }}">
                </div>
            </form>
        </div>
    </div>

    @if ($profesores->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="mb-2">No hay profesores registrados.</p>
                <a href="{{ route('registroCombinado.registrar') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus me-2"></i>Registrar el primero
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle responsive-table" id="teachersTable">
                <thead class="table-light">
                    <tr>
                        <th style="min-width:150px;">Nombre</th>
                        <th style="min-width:150px;">Apellido</th>
                        <th style="min-width:140px;">Teléfono</th>
                        <th style="min-width:160px;">Profesión</th>
                        <th style="min-width:220px;">Correo</th>
                        <th style="min-width:180px;">Rol componentes</th>
                        <th style="min-width:140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($profesores as $profesor)
                    <tr>
                        <td>{{ $profesor->persona->Nombre ?? 'Sin nombre' }}</td>
                        <td>{{ $profesor->persona->Apellido ?? 'Sin apellido' }}</td>
                        <td>{{ $profesor->persona->Celular ?? 'No registrado' }}</td>
                        <td>{{ $profesor->Profesion ?? 'No especificado' }}</td>
                        <td>{{ $profesor->usuario->Correo ?? 'Sin correo' }}</td>
                        <td>{{ $profesor->Rol_componentes ?? 'Ninguno' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Ver -->
                                <a href="{{ route('profesores.show', $profesor->Id_profesores) }}" class="btn btn-sm btn-outline-dark" title="Ver detalles">
                                    <i class="bi bi-person-fill"></i>
                                </a>

                                <!-- Editar -->
                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editarProfesorModal{{ $profesor->Id_profesores }}"
                                        title="Editar">
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

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarProfesorModal{{ $profesor->Id_profesores }}" tabindex="-1" aria-labelledby="editarProfesorLabel{{ $profesor->Id_profesores }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header bg-warning bg-opacity-25">
                            <h5 class="modal-title" id="editarProfesorLabel{{ $profesor->Id_profesores }}">Editar Profesor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <form action="{{ route('profesores.update', $profesor->Id_profesores) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $profesor->persona->Nombre ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control" value="{{ $profesor->persona->Apellido ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">Celular</label>
                                <input type="text" name="celular" class="form-control" value="{{ $profesor->persona->Celular ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="correo" class="form-control" value="{{ $profesor->usuario->Correo ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">Nueva contraseña (opcional)</label>
                                <input type="password" name="contrasenia" class="form-control" placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">Profesión</label>
                                <input type="text" name="profesion" class="form-control" value="{{ $profesor->Profesion ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                <label class="form-label">Rol en Componentes</label>
                                <select name="rol_componentes" class="form-select">
                                    <option value="Ninguno" {{ $profesor->Rol_componentes == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                                    <option value="Tecnico" {{ $profesor->Rol_componentes == 'Tecnico' ? 'selected' : '' }}>Técnico</option>
                                    <option value="Inventario" {{ $profesor->Rol_componentes == 'Inventario' ? 'selected' : '' }}>Inventario</option>
                                </select>
                                </div>
                            </div>
                            </div>
                            <div class="modal-footer">
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
    @endif

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $profesores->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection
