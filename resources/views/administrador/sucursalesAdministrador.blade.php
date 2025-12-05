@extends('administrador.baseAdministrador')

@section('title', 'Sucursal')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Sucursales</h1>

    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarSucursal">
        Agregar Sucursal
    </button>

    <!-- Tabla de sucursales -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sucursales as $sucursal)
            <tr>
                <td>{{ $sucursal->Nombre }}</td>
                <td>{{ $sucursal->Direccion }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" 
                            title="Editar" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEditarSucursal"
                            data-id="{{ $sucursal->Id_sucursales }}"
                            data-nombre="{{ $sucursal->Nombre }}"
                            data-direccion="{{ $sucursal->Direccion }}">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" 
                            title="Eliminar"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEliminarSucursal"
                            data-id="{{ $sucursal->Id_sucursales }}"
                            data-nombre="{{ $sucursal->Nombre }}">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Agregar Sucursal -->
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

<!-- Modal Editar Sucursal -->
<div class="modal fade" id="modalEditarSucursal" tabindex="-1" aria-labelledby="modalLabelEditarSucursal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditarSucursal" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelEditarSucursal">Editar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="editNombre" name="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDireccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="editDireccion" name="Direccion" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Sucursal -->
<div class="modal fade" id="modalEliminarSucursal" tabindex="-1" aria-labelledby="modalLabelEliminarSucursal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEliminarSucursal" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelEliminarSucursal">Eliminar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar la sucursal <strong id="nombreSucursalEliminar"></strong>?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para el modal de editar
    document.addEventListener('DOMContentLoaded', function() {
        const modalEditarSucursal = document.getElementById('modalEditarSucursal');
        modalEditarSucursal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const direccion = button.getAttribute('data-direccion');
            
            const form = document.getElementById('formEditarSucursal');
            form.action = `/administrador/sucursalesAdministrador/${id}`;
            
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editDireccion').value = direccion;
        });

        // Script para el modal de eliminar
        const modalEliminarSucursal = document.getElementById('modalEliminarSucursal');
        modalEliminarSucursal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            
            const form = document.getElementById('formEliminarSucursal');
            form.action = `/administrador/sucursalesAdministrador/${id}`;
            
            document.getElementById('nombreSucursalEliminar').textContent = nombre;
        });
    });
</script>
@endsection
