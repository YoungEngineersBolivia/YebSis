@extends('/administrador/baseAdministrador')

@section('title', 'Usuarios')
@section('styles')
<link href="{{ auto_asset ('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Usuarios</h1>
    </div>

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

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('usuarios.index') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Buscar por nombre" value="{{ request()->search }}">
                </div>
            </form>
        </div>
    </div>

    @if($usuarios->isEmpty())
        <div class="alert alert-warning">No hay usuarios registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->persona->Nombre ?? '' }}</td>
                            <td>{{ $usuario->persona->Apellido ?? '' }}</td>
                            <td>{{ $usuario->Correo }}</td>
                            <td>{{ $usuario->persona->rol->Nombre_rol ?? '' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editarUsuario({{ $usuario->Id_usuarios }})" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarUsuario({{ $usuario->Id_usuarios }})" title="Eliminar">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Modal editar usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formEditarUsuario">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="editarNombre" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Apellido</label>
                            <input type="text" name="apellido" id="editarApellido" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Correo</label>
                            <input type="email" name="correo" id="editarCorreo" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Rol</label>
                            <input type="text" name="rol" id="editarRol" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ auto_asset('js/administrador/usuariosAdministrador.js') }}"></script>
@endsection
