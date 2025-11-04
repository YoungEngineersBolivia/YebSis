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
                    <input id="searchInput" type="text" class="form-control" placeholder="Filtrar por nombre, apellido o correo" name="search" value="{{ request()->search }}">
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
@foreach ($programas as $programa)
<tr>
    <td class="fw-normal">{{ $programa->Nombre }}</td>
    <td class="text-muted">{{ $programa->Tipo ?? 'No especificado' }}</td>
    <td class="text-muted">{{ number_format($programa->Costo, 0) }} Bs</td>
    <td class="text-muted">{{ $programa->Rango_edad }}</td>
    <td class="text-muted">{{ $programa->Duracion }}</td>
    <td class="text-muted">{{ Str::limit($programa->Descripcion, 50) }}</td>
    <td>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-danger" 
                    onclick="eliminarPrograma({{ $programa->id }})" title="Eliminar">
                <i class="bi bi-trash3-fill"></i>
            </button>
            <button class="btn btn-sm btn-outline-warning" 
                    data-bs-toggle="modal" data-bs-target="#editarProgramaModal{{ $programa->id }}"
                    title="Editar">
                <i class="bi bi-pencil-square"></i>
            </button>
            <button class="btn btn-sm btn-outline-dark" 
                    data-bs-toggle="modal" data-bs-target="#verProgramaModal{{ $programa->id }}"
                    title="Ver detalles">
                <i class="bi bi-person-fill"></i>
            </button>
        </div>
    </td>
</tr>

<!-- Modal Editar -->
<div class="modal fade" id="editarProgramaModal{{ $programa->id }}" tabindex="-1" aria-labelledby="editarProgramaLabel{{ $programa->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarProgramaLabel{{ $programa->id }}">Editar Programa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form action="{{ route('programas.update', $programa->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ $programa->Nombre }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-control">
                    <option value="programa" {{ $programa->Tipo=='programa'?'selected':'' }}>Programa</option>
                    <option value="taller" {{ $programa->Tipo=='taller'?'selected':'' }}>Taller</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Costo</label>
                <input type="number" name="costo" class="form-control" value="{{ $programa->Costo }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rango de Edad</label>
                <input type="text" name="rango_edad" class="form-control" value="{{ $programa->Rango_edad }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Duración</label>
                <input type="text" name="duracion" class="form-control" value="{{ $programa->Duracion }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" required>{{ $programa->Descripcion }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input type="file" name="imagen" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Ver -->
<div class="modal fade" id="verProgramaModal{{ $programa->id }}" tabindex="-1" aria-labelledby="verProgramaLabel{{ $programa->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verProgramaLabel{{ $programa->id }}">Detalles del Programa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nombre:</strong> {{ $programa->Nombre }}</p>
        <p><strong>Tipo:</strong> {{ $programa->Tipo }}</p>
        <p><strong>Costo:</strong> {{ number_format($programa->Costo,0) }} Bs</p>
        <p><strong>Rango de Edad:</strong> {{ $programa->Rango_edad }}</p>
        <p><strong>Duración:</strong> {{ $programa->Duracion }}</p>
        <p><strong>Descripción:</strong> {{ $programa->Descripcion }}</p>
        @if($programa->Imagen)
            <p><strong>Imagen:</strong><br>
            <img src="{{ asset('storage/'.$programa->Imagen) }}" alt="Imagen Programa" class="img-fluid"></p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
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
<!--<script src="{{ auto_asset('js/administrador/profesoresAdministrador.js') }}"></script>-->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('#searchInput');
    const table = document.querySelector('#teachersTable');
    if (!input || !table) return;

    input.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase(); // todo el texto de la fila
            if (text.includes(query)) {
                row.style.display = ''; // mostrar fila
            } else {
                row.style.display = 'none'; // ocultar fila
            }
        });
    });
});
</script>


@endsection
