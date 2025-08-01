@extends('/administrador/baseAdministrador')

@section('title', 'Programas')
@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
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

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar Programa" id="searchInput">
            </div>
        </div>
    </div>

    @if($programas->isEmpty())
        <div class="alert alert-warning">No hay programas registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold text-dark">
                            Nombre 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold bi-arrow-down">
                            Costo 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark ">
                            Rango de Edad 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark ">
                            Duración 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark ">
                            Descripción 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark ">
                            Acciones 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programas as $programa)
                        <tr>
                            <td class="fw-normal">{{ $programa->Nombre }}</td>
                            <td class="text-muted">{{ number_format($programa->Costo, 0) }} Bs</td>
                            <td class="text-muted">{{ $programa->Rango_edad }}</td>
                            <td class="text-muted">{{ $programa->Duracion }}</td>
                            <td class="text-muted">{{ Str::limit($programa->Descripcion, 50) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="eliminarPrograma({{ $programa->id }})"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                   
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="editarPrograma({{ $programa->Id_programas }})"
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            onclick="verPrograma({{ $programa->id }})"
                                            title="Ver detalles">
                                        <i class="fas fa-user"></i>
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
            {{ $programas->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <div class="modal fade" id="nuevoProgramaModal" tabindex="-1" aria-labelledby="nuevoProgramaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Programa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                   <form id="formNuevoPrograma" action="{{ route('programas.store') }}" method="POST" enctype="multipart/form-data">-->
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="costo" class="form-label">Costo (Bs)</label>
                                    <input type="number" class="form-control" id="costo" name="costo" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rango_edad" class="form-label">Rango de Edad</label>
                                    <input type="text" class="form-control" id="rango_edad" name="rango_edad" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duracion" class="form-label">Duración</label>
                                    <input type="text" class="form-control" id="duracion" name="duracion" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Programa</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
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
    <!-- Modal para editar -->
        <div class="modal fade" id="modalPrograma" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Programa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalProgramaBody">
               
            </div>
            </div>
        </div>
        </div>

</div>


@endsection

@section('scripts')
<script src="{{asset('js/programasAdministrador.js')}}"></script>
<script>
// Función de búsqueda
document.getElementById('searchInput').addEventListener('keyup', function() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById('searchInput');
    filter = input.value.toUpperCase();
    table = document.querySelector('.table tbody');
    tr = table.getElementsByTagName('tr');

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td')[0]; // Buscar en la columna nombre
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
});

// Funciones para los botones de acción
function eliminarPrograma(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este programa?')) {
        fetch(`/admin/programas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el programa');
        });
    }
}

function editarPrograma(id) {
   
    fetch(`/admin/programas/${id}/edit`)
        .then(response => response.json())
        .then(data => {
           
            document.getElementById('nombre').value = data.Nombre;
            document.getElementById('costo').value = data.Costo;
            document.getElementById('rango_edad').value = data.Rango_edad;
            document.getElementById('duracion').value = data.Duracion;
            document.getElementById('descripcion').value = data.Descripcion;
            
            
            const form = document.getElementById('formNuevoPrograma');
            form.action = `/admin/programas/${id}`;
            
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            form.appendChild(methodField);
            
            
            document.querySelector('.modal-title').textContent = 'Editar Programa';
         
            const modal = new bootstrap.Modal(document.getElementById('nuevoProgramaModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del programa');
        });
}

function verPrograma(id) {

    fetch(`/admin/programas/${id}`)
        .then(response => response.json())
        .then(data => {
            alert(`Programa: ${data.Nombre}\nCosto: ${data.Costo} Bs\nRango de Edad: ${data.Rango_edad}\nDuración: ${data.Duracion}\nDescripción: ${data.Descripcion}`);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los detalles del programa');
        });
}


document.getElementById('nuevoProgramaModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('formNuevoPrograma');
    form.reset();
    form.action = "{{ route('programas.store') }}";
    const methodField = form.querySelector('input[name="_method"]');
    if (methodField) {
        methodField.remove();
    }

    document.querySelector('.modal-title').textContent = 'Nuevo Programa';
});
</script>
@endsection