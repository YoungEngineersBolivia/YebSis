@extends('/administrador/baseAdministrador')

@section('title', 'Tutores')
@section('styles')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Tutores</h1>
        
    </div>

    <!-- Mostrar mensajes -->
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

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <form action="{{ route('tutores.index') }}" method="GET">
                    <input type="text" class="form-control" name="search" placeholder="Buscar Tutor" value="{{ request()->search }}">
                </form>
            </div>
        </div>
    </div>

    @if($tutores->isEmpty())
        <div class="alert alert-warning">No hay tutores registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold text-dark">Nombre <i class="fbi bi-arrow-down"></i></th>
                        <th class="fw-bold text-dark">Apellido <i class="fbi bi-arrow-down"></i></th>
                        <th class="fw-bold text-dark">Celular <i class="fbi bi-arrow-down"></i></th>
                        <th class="fw-bold text-dark">Dirección <i class="fbi bi-arrow-down"></i></th>
                        <th class="fw-bold text-dark">Correo <i class="fbi bi-arrow-down"></i></th>
                        <th class="fw-bold text-dark">Parentesco <i class="fbi bi-arrow-down"></i></th>
                        <th class="fw-bold text-dark">Acciones <i class="fbi bi-arrow-down"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tutores as $tutor)
                        <tr>
                            <td class="fw-normal">{{ $tutor->persona->Nombre }}</td>
                            <td class="text-muted">{{ $tutor->persona->Apellido }}</td>
                            <td class="text-muted">{{ $tutor->persona->Celular }}</td>
                            <td class="text-muted">{{ $tutor->persona->Direccion_domicilio }}</td>
                            <td class="text-muted">{{ $tutor->usuario->Correo }}</td>
                            <td class="text-muted">{{ $tutor->Parentesco }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" title="Ver detalles"
                                            onclick="verTutor({{ $tutor->Id_tutores }})">
                                        <i class="bi bi-person-fill"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" title="Editar"
                                            onclick="editarTutor({{ $tutor->Id_tutores }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                            onclick="eliminarTutor({{ $tutor->Id_tutores }})">
                                        <i class="bi bi-trash3-fill"></i>
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
            {{ $tutores->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Modal para ver información -->
    <div class="modal fade" id="modalVerTutor" tabindex="-1" aria-labelledby="modalVerTutorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Información del Tutor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="verNombre"></span></p>
                    <p><strong>Apellido:</strong> <span id="verApellido"></span></p>
                    <p><strong>Celular:</strong> <span id="verCelular"></span></p>
                    <p><strong>Dirección:</strong> <span id="verDireccion"></span></p>
                    <p><strong>Correo:</strong> <span id="verCorreo"></span></p>
                    <p><strong>Parentesco:</strong> <span id="verParentesco"></span></p>
                    <p><strong>Descuento:</strong> <span id="verDescuento"></span>%</p>
                    <p><strong>NIT:</strong> <span id="verNit"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para edición -->
    <div class="modal fade" id="modalEditarTutor" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" id="formEditarTutor">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Tutor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nombre</label>
                                <input type="text" name="nombre" id="editarNombre" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Apellido</label>
                                <input type="text" name="apellido" id="editarApellido" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Celular</label>
                                <input type="text" name="celular" id="editarCelular" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Dirección</label>
                                <input type="text" name="direccion_domicilio" id="editarDireccion" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Correo</label>
                                <input type="email" name="correo" id="editarCorreo" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Parentesco</label>
                                <input type="text" name="parentezco" id="editarParentesco" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Descuento (%)</label>
                                <input type="number" name="descuento" id="editarDescuento" class="form-control" step="0.01" min="0" max="100">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>NIT</label>
                                <input type="text" name="nit" id="editarNit" class="form-control">
                            </div>
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
<script>
    // Lógica de ver detalles, editar y eliminar tutor
    function verTutor(id) {
        fetch(`/administrador/tutores/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('verNombre').textContent = data.persona.Nombre;
                document.getElementById('verApellido').textContent = data.persona.Apellido;
                document.getElementById('verCelular').textContent = data.persona.Celular;
                document.getElementById('verDireccion').textContent = data.persona.Direccion_domicilio;
                document.getElementById('verCorreo').textContent = data.usuario.Correo;
                document.getElementById('verParentesco').textContent = data.Parentesco;
                document.getElementById('verDescuento').textContent = data.Descuento;
                document.getElementById('verNit').textContent = data.Nit;

                const modal = new bootstrap.Modal(document.getElementById('modalVerTutor'));
                modal.show();
            });
    }

    function editarTutor(id) {
        fetch(`/administrador/tutores/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editarNombre').value = data.persona.Nombre;
                document.getElementById('editarApellido').value = data.persona.Apellido;
                document.getElementById('editarCelular').value = data.persona.Celular;
                document.getElementById('editarDireccion').value = data.persona.Direccion_domicilio;
                document.getElementById('editarCorreo').value = data.usuario.Correo;
                document.getElementById('editarParentesco').value = data.Parentesco;
                document.getElementById('editarDescuento').value = data.Descuento;
                document.getElementById('editarNit').value = data.Nit;

                const form = document.getElementById('formEditarTutor');
                form.action = `/administrador/tutores/${id}`;
                const modal = new bootstrap.Modal(document.getElementById('modalEditarTutor'));
                modal.show();
            });
    }

    function eliminarTutor(id) {
        if (confirm("¿Estás seguro de eliminar este tutor?")) {
            fetch(`/administrador/tutores/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Error al eliminar tutor: " + (data.message || 'No se pudo eliminar.'));
                }
            });
        }
    }
</script>
@endsection
