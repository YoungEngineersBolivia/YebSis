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
                        <th class="fw-bold text-dark">Nombre</th>
                        <th class="fw-bold text-dark">Tipo</th>
                        <th class="fw-bold text-dark">Costo</th>
                        <th class="fw-bold text-dark">Rango de Edad</th>
                        <th class="fw-bold text-dark">Duración</th>
                        <th class="fw-bold text-dark">Descripción</th>
                        <th class="fw-bold text-dark text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programas as $programa)
                        <tr>
                            <td class="fw-normal">{{ $programa->Nombre }}</td>
                            <td>
                                @if($programa->Tipo === 'programa')
                                    <span class="badge bg-primary">Programa</span>
                                @else
                                    <span class="badge bg-info">Taller</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ number_format($programa->Costo, 2) }} Bs</td>
                            <td class="text-muted">{{ $programa->Rango_edad }}</td>
                            <td class="text-muted">{{ $programa->Duracion }}</td>
                            <td class="text-muted">{{ Str::limit($programa->Descripcion, 50) }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('programas.show', $programa->Id_programas) }}" 
                                       class="btn btn-sm btn-outline-dark" 
                                       title="Ver detalles">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            onclick="editarPrograma({{ $programa->Id_programas }})"
                                            title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="eliminarPrograma({{ $programa->Id_programas }})"
                                            title="Eliminar">
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
            {{ $programas->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <!-- Modal para añadir programa -->
    <div class="modal fade" id="nuevoProgramaModal" tabindex="-1" aria-labelledby="nuevoProgramaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Programa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevoPrograma" action="{{ route('programas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required value="{{ old('nombre') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo" class="form-label">Tipo de Programa</label>
                                    <select class="form-control" id="tipo" name="tipo" required>
                                        <option value="">Seleccione...</option>
                                        <option value="programa" {{ old('tipo') === 'programa' ? 'selected' : '' }}>Programa</option>
                                        <option value="taller" {{ old('tipo') === 'taller' ? 'selected' : '' }}>Taller</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="costo" class="form-label">Costo (Bs)</label>
                                    <input type="number" class="form-control" id="costo" name="costo" step="0.01" required value="{{ old('costo') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rango_edad" class="form-label">Rango de Edad</label>
                                    <input type="text" class="form-control" id="rango_edad" name="rango_edad" placeholder="Ej: 6-12 años" required value="{{ old('rango_edad') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="duracion" class="form-label">Duración</label>
                            <input type="text" class="form-control" id="duracion" name="duracion" placeholder="Ej: 3 meses" required value="{{ old('duracion') }}">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Foto del Programa</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                            <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
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

    <!-- Modal para editar programa -->
    <div class="modal fade" id="editarProgramaModal" tabindex="-1" aria-labelledby="editarProgramaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Programa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPrograma" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_programa_id" name="programa_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_nombre" class="form-label">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_tipo" class="form-label">Tipo de Programa</label>
                                    <select class="form-control" id="edit_tipo" name="tipo" required>
                                        <option value="programa">Programa</option>
                                        <option value="taller">Taller</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_costo" class="form-label">Costo (Bs)</label>
                                    <input type="number" class="form-control" id="edit_costo" name="costo" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_rango_edad" class="form-label">Rango de Edad</label>
                                    <input type="text" class="form-control" id="edit_rango_edad" name="rango_edad" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_duracion" class="form-label">Duración</label>
                            <input type="text" class="form-control" id="edit_duracion" name="duracion" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_imagen" class="form-label">Nueva Foto del Programa</label>
                            <input type="file" class="form-control" id="edit_imagen" name="imagen" accept="image/*">
                            <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                            <div id="imagenActual" class="mt-2"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formEditarPrograma" class="btn btn-primary">Actualizar Programa</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
// IMPORTANTE: Verificar que las funciones estén disponibles globalmente
console.log('Script cargado correctamente');

// Búsqueda en tiempo real
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#tablaProgramas tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
}

// Función para editar programa (GLOBAL)
window.editarPrograma = function(id) {
    console.log('Editando programa ID:', id);
    
    fetch(`/programas/${id}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data);
        
        if (data.success) {
            const programa = data.programa;
            
            // Llenar el formulario con los datos
            document.getElementById('edit_programa_id').value = programa.Id_programas;
            document.getElementById('edit_nombre').value = programa.Nombre || '';
            document.getElementById('edit_tipo').value = programa.Tipo || 'programa';
            document.getElementById('edit_costo').value = programa.Costo || '';
            document.getElementById('edit_rango_edad').value = programa.Rango_edad || '';
            document.getElementById('edit_duracion').value = programa.Duracion || '';
            document.getElementById('edit_descripcion').value = programa.Descripcion || '';
            
            // Actualizar la acción del formulario
            document.getElementById('formEditarPrograma').action = `/programas/${programa.Id_programas}`;
            
            // Mostrar imagen actual si existe
            const imagenActual = document.getElementById('imagenActual');
            if (programa.Imagen) {
                imagenActual.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Imagen actual:</strong><br>
                        <img src="/storage/${programa.Imagen}" alt="${programa.Nombre}" style="max-width: 200px; max-height: 150px;" class="img-thumbnail mt-2">
                    </div>
                `;
            } else {
                imagenActual.innerHTML = '<div class="alert alert-warning">No hay imagen actual</div>';
            }
            
            // Mostrar el modal
            const modalElement = document.getElementById('editarProgramaModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else {
            alert('Error al cargar los datos del programa');
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        alert('Error al cargar los datos del programa: ' + error.message);
    });
}

// Función para eliminar programa (GLOBAL)
window.eliminarPrograma = function(id) {
    console.log('Eliminando programa ID:', id);
    
    if (confirm('¿Está seguro que desea eliminar este programa? Esta acción no se puede deshacer.')) {
        // Buscar el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!csrfToken) {
            alert('Error: No se encontró el token CSRF. Asegúrate de tener <meta name="csrf-token"> en tu layout.');
            return;
        }
        
        fetch(`/programas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta:', data);
            
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Error al eliminar el programa');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            alert('Error al eliminar el programa: ' + error.message);
        });
    }
}

// Auto-cerrar alertas después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    console.log('Funciones globales disponibles:', {
        editarPrograma: typeof window.editarPrograma,
        eliminarPrograma: typeof window.eliminarPrograma
    });
});
</script>
@endsection