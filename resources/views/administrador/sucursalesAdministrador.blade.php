@extends('administrador.baseAdministrador')

@section('title', 'Sucursal')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Sucursales</h1>

    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarSucursal">
        Agregar Sucursal
    </button>

    <!-- Tabla de sucursales -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sucursales as $sucursal)
            <tr>
                <td>{{ $sucursal->Id_Sucursales }}</td>
                <td>{{ $sucursal->Nombre }}</td>
                <td>{{ $sucursal->Direccion }}</td>
                <td>
                    <!-- Aquí podrías agregar botones de editar o eliminar -->
                    <a href="#" class="btn btn-sm btn-warning">Editar</a>
                    <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAgregarSucursal" tabindex="-1" aria-labelledby="modalLabelSucursal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sucursales.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelSucursal">Agregar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" name="Direccion" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
