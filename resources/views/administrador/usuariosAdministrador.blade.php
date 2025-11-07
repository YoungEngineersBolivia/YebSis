@extends('administrador.baseAdministrador')

@section('title', 'Usuarios')
@section('styles')
<link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <table class="table table-hover align-middle">
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
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->persona->Nombre ?? '' }}</td>
                            <td>{{ $usuario->persona->Apellido ?? '' }}</td>
                            <td>{{ $usuario->Correo }}</td>
                            <td>{{ $usuario->persona->rol->Nombre_rol ?? '' }}</td>
                            <td class="d-flex gap-2">
                                <!-- Botón que abre modal -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal{{ $usuario->Id_usuarios }}">
                                    Editar
                                </button>

                                <!-- Formulario Eliminar -->
                                <form action="{{ route('usuarios.destroy', $usuario->Id_usuarios) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal de edición -->
                        <div class="modal fade" id="editarUsuarioModal{{ $usuario->Id_usuarios }}" tabindex="-1" aria-labelledby="editarUsuarioLabel{{ $usuario->Id_usuarios }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('usuarios.update', $usuario->Id_usuarios) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editarUsuarioLabel{{ $usuario->Id_usuarios }}">Editar Usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" class="form-control" value="{{ $usuario->persona->Nombre }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Apellido</label>
                                                <input type="text" name="apellido" class="form-control" value="{{ $usuario->persona->Apellido }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Correo</label>
                                                <input type="email" name="correo" class="form-control" value="{{ $usuario->Correo }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Rol</label>
                                                <input type="text" class="form-control" value="{{ $usuario->persona->rol->Nombre_rol ?? '' }}" disabled>
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

                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
