@extends('/administrador/baseAdministrador')

@section('title', 'Tutores')
@section('styles')
<link href="{{ asset('css/programasAdministrador.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Tutores</h1>
        <a href="{{ url('/administrador/registrarTutor') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Añadir Tutor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar por nombre" id="searchInput">
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
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Celular</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th>Parentesco</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tutores as $tutor)
                        <tr>
                            <td>{{ $tutor->persona->Nombre }}</td>
                            <td>{{ $tutor->persona->Apellido }}</td>
                            <td>{{ $tutor->persona->Celular }}</td>
                            <td>{{ $tutor->persona->Direccion_domicilio }}</td>
                            <td>{{ $tutor->usuario->Correo }}</td>
                            <td>{{ $tutor->Parentesco }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            onclick="verTutor({{ $tutor->Id_tutores }})" 
                                            title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="editarTutor({{ $tutor->Id_tutores }})" 
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="eliminarTutor({{ $tutor->Id_tutores }})" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $tutores->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Modal de edición (solo estructura, puedes adaptarlo) -->
    <div class="modal fade" id="modalEditarTutor" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formEditarTutor">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Tutor</h5>
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
                            <label>Celular</label>
                            <input type="text" name="celular" id="editarCelular" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Dirección</label>
                            <input type="text" name="direccion_domicilio" id="editarDireccion" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Correo</label>
                            <input type="email" name="correo" id="editarCorreo" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Parentesco</label>
                            <input type="text" name="parentezco" id="editarParentesco" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Descuento (%)</label>
                            <input type="number" name="descuento" id="editarDescuento" class="form-control" step="0.01" min="0" max="100">
                        </div>
                        <div class="mb-2">
                            <label>NIT</label>
                            <input type="text" name="nit" id="editarNit" class="form-control">
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
    // Buscar en tabla por nombre
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const filtro = this.value.toUpperCase();
        const filas = document.querySelectorAll('tbody tr');

        filas.forEach(fila => {
            const nombre = fila.cells[0].textContent.toUpperCase();
            fila.style.display = nombre.includes(filtro) ? '' : 'none';
        });
    });

    function verTutor(id) {
        fetch(`/admin/tutores/${id}`)
            .then(res => res.json())
            .then(data => {
                alert(`Nombre: ${data.Nombre} ${data.Apellido}
Celular: ${data.Celular}
Dirección: ${data.Direccion_domicilio}
Correo: ${data.Correo}
Parentesco: ${data.Parentesco}
Descuento: ${data.Descuento}%
NIT: ${data.Nit}`);
            });
    }

    function editarTutor(id) {
        fetch(`/admin/tutores/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editarNombre').value = data.Nombre;
                document.getElementById('editarApellido').value = data.Apellido;
                document.getElementById('editarCelular').value = data.Celular;
                document.getElementById('editarDireccion').value = data.Direccion_domicilio;
                document.getElementById('editarCorreo').value = data.Correo;
                document.getElementById('editarParentesco').value = data.Parentesco;
                document.getElementById('editarDescuento').value = data.Descuento;
                document.getElementById('editarNit').value = data.Nit;

                const form = document.getElementById('formEditarTutor');
                form.action = `/admin/tutores/${id}`;

                const modal = new bootstrap.Modal(document.getElementById('modalEditarTutor'));
                modal.show();
            });
    }

    function eliminarTutor(id) {
        if (confirm("¿Estás seguro de eliminar este tutor?")) {
            fetch(`/admin/tutores/${id}`, {
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
                    alert("Error al eliminar tutor: " + data.message);
                }
            });
        }
    }
</script>
@endsection
