@extends('administrador.baseAdministrador')

@section('title', 'Estudiantes')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ auto_asset('css/administrador/estudiantesAdministrador.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="mt-2">

    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Lista de Estudiantes</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('estudiantes.exportarPDF') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i>Exportar PDF
            </a>
            <a href="{{ route('registroCombinado.registrar') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Registrar Estudiante
            </a>
        </div>
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
 
    {{-- Buscador y Filtros --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form action="{{ route('estudiantes.index') }}" method="GET" id="filtrosForm">
                <div class="row g-3">
                    {{-- Buscador --}}
                    <div class="col-md-4">
                        <label for="searchInput" class="form-label mb-1 fw-semibold text-muted">
                            <i class="fas fa-search me-1"></i>Buscar Estudiante
                        </label>
                        <input type="text" 
                               id="searchInput" 
                               class="form-control" 
                               placeholder="Código, nombre o apellido..." 
                               name="search" 
                               value="{{ request()->search }}">
                    </div>

                    {{-- Filtro por Estado --}}
                    <div class="col-md-2">
                        <label for="estadoFilter" class="form-label mb-1 fw-semibold text-muted">
                            <i class="fas fa-toggle-on me-1"></i>Estado
                        </label>
                        <select class="form-select" id="estadoFilter" name="estado">
                            <option value="">Todos</option>
                            <option value="Activo" {{ request()->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ request()->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    {{-- Filtro por Programa --}}
                    <div class="col-md-3">
                        <label for="programaFilter" class="form-label mb-1 fw-semibold text-muted">
                            <i class="fas fa-book me-1"></i>Programa
                        </label>
                        <select class="form-select" id="programaFilter" name="programa">
                            <option value="">Todos los programas</option>
                            @foreach($programas as $prog)
                                <option value="{{ $prog->Id_programas }}" 
                                        {{ request()->programa == $prog->Id_programas ? 'selected' : '' }}>
                                    {{ $prog->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro por Sucursal --}}
                    <div class="col-md-3">
                        <label for="sucursalFilter" class="form-label mb-1 fw-semibold text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>Sucursal
                        </label>
                        <select class="form-select" id="sucursalFilter" name="sucursal">
                            <option value="">Todas las sucursales</option>
                            @foreach($sucursales as $suc)
                                <option value="{{ $suc->Id_sucursales }}" 
                                        {{ request()->sucursal == $suc->Id_sucursales ? 'selected' : '' }}>
                                    {{ $suc->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="col-12">
                        <div class="d-flex gap-2 align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Aplicar Filtros
                            </button>
                            <a href="{{ route('estudiantes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Limpiar Filtros
                            </a>
                            @if(request()->hasAny(['search', 'estado', 'programa', 'sucursal']))
                                <span class="badge bg-info text-white px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>Filtros activos
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($estudiantes->isEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                </div>
                @if(request()->hasAny(['search', 'estado', 'programa', 'sucursal']))
                    <h5 class="text-muted">No se encontraron estudiantes con los filtros aplicados</h5>
                    <p class="text-muted mb-4">Intenta ajustar los criterios de búsqueda o limpia los filtros.</p>
                    <a href="{{ route('estudiantes.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Limpiar Filtros
                    </a>
                @else
                    <h5 class="text-muted">No hay estudiantes registrados</h5>
                    <p class="text-muted mb-4">Comienza registrando un nuevo estudiante en el sistema.</p>
                    <a href="{{ route('registroCombinado.registrar') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Registrar el primero
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="studentsTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="ps-3 py-3" style="min-width:120px;">Código</th>
                                <th class="py-3" style="min-width:220px;">Nombre</th>
                                <th class="py-3" style="min-width:180px;">Programa</th>
                                <th class="py-3" style="min-width:160px;">Sucursal</th>
                                <th class="py-3" style="min-width:120px;">Estado</th>
                                <th class="pe-3 py-3 text-end" style="min-width:140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estudiantes as $estudiante)
                                @php
                                    $personaEst = $estudiante->persona ?? null;
                                    $nombreCompletoEst = $personaEst ? trim(($personaEst->Nombre ?? '').' '.($personaEst->Apellido ?? '')) : null;

                                    $programa = $estudiante->programa->Nombre ?? 'Sin programa';
                                    $sucursal = $estudiante->sucursal->Nombre ?? 'Sin sucursal';

                                    $prof = $estudiante->profesor ?? null;
                                    $profPersona = $prof?->persona ?? null;
                                    $profesorNombre = $profPersona
                                        ? trim(($profPersona->Nombre ?? '').' '.($profPersona->Apellido ?? ''))
                                        : ($prof->Nombre ?? null);
                                    $profesorNombre = $profesorNombre ?: 'Sin profesor';
                                    
                                    $estadoLower = Str::lower($estudiante->Estado ?? '');
                                    $esActivo = $estadoLower === 'activo';
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-bold text-primary">{{ $estudiante->Cod_estudiante }}</td>
                                    <td class="fw-semibold">{{ $nombreCompletoEst ?: 'Sin datos' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $programa }}</span></td>
                                    <td>{{ $sucursal }}</td>
                                    <td>
                                        @if($esActivo)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Activo</span>
                                        @elseif($estadoLower === 'inactivo')
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Inactivo</span>
                                        @else
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $estudiante->Estado }}</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            {{-- Botón Editar --}}
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editarModal{{ $estudiante->Id_estudiantes }}"
                                                    title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Botón Ver perfil --}}
                                            <a href="{{ route('estudiantes.ver', $estudiante->Id_estudiantes ?? 0) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Ver perfil">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4 mb-4">
            {{ $estudiantes->links('pagination::bootstrap-5') }}
        </div>
    @endif

    {{-- Modales de edición --}}
    @foreach ($estudiantes as $estudiante)
    @php
        $persona = $estudiante->persona ?? null;
    @endphp
    <div class="modal fade" id="editarModal{{ $estudiante->Id_estudiantes }}" tabindex="-1" aria-labelledby="editarModalLabel{{ $estudiante->Id_estudiantes }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editarModalLabel{{ $estudiante->Id_estudiantes }}">
                        <i class="bi bi-pencil-square me-2"></i>Editar Estudiante
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="{{ route('estudiantes.actualizar', $estudiante->Id_estudiantes) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Código estudiante -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="codigo{{ $estudiante->Id_estudiantes }}">Código</label>
                                <input type="text" id="codigo{{ $estudiante->Id_estudiantes }}" name="codigo_estudiante"
                                    class="form-control" value="{{ $estudiante->Cod_estudiante }}" required>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="nombre{{ $estudiante->Id_estudiantes }}">Nombre</label>
                                <input type="text" id="nombre{{ $estudiante->Id_estudiantes }}" name="nombre"
                                    class="form-control" value="{{ $persona->Nombre ?? '' }}" required>
                            </div>

                            <!-- Apellido -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="apellido{{ $estudiante->Id_estudiantes }}">Apellido</label>
                                <input type="text" id="apellido{{ $estudiante->Id_estudiantes }}" name="apellido"
                                    class="form-control" value="{{ $persona->Apellido ?? '' }}" required>
                            </div>

                            <!-- Género -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="genero{{ $estudiante->Id_estudiantes }}">Género</label>
                                <select class="form-select" name="genero" id="genero{{ $estudiante->Id_estudiantes }}" required>
                                    <option value="M" {{ $persona->Genero === 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ $persona->Genero === 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>

                            <!-- Fecha nacimiento -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="fecha_nacimiento{{ $estudiante->Id_estudiantes }}">Fecha de nacimiento</label>
                                <input type="date" id="fecha_nacimiento{{ $estudiante->Id_estudiantes }}" name="fecha_nacimiento"
                                    class="form-control" value="{{ $persona->Fecha_nacimiento ?? '' }}" required>
                            </div>

                            <!-- Celular -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="celular{{ $estudiante->Id_estudiantes }}">Celular</label>
                                <input type="text" id="celular{{ $estudiante->Id_estudiantes }}" name="celular"
                                    class="form-control" value="{{ $persona->Celular ?? '' }}" required>
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold" for="direccion{{ $estudiante->Id_estudiantes }}">Dirección</label>
                                <input type="text" id="direccion{{ $estudiante->Id_estudiantes }}" name="direccion_domicilio"
                                    class="form-control" value="{{ $persona->Direccion_domicilio ?? '' }}" required>
                            </div>

                            <!-- Programa -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="programa{{ $estudiante->Id_estudiantes }}">Programa</label>
                                <select class="form-select" id="programa{{ $estudiante->Id_estudiantes }}" name="programa" required>
                                    @foreach ($programas as $programa)
                                        <option value="{{ $programa->Id_programas }}" 
                                            {{ $programa->Id_programas == $estudiante->Id_programas ? 'selected' : '' }}>
                                            {{ $programa->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sucursal -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="sucursal{{ $estudiante->Id_estudiantes }}">Sucursal</label>
                                <select class="form-select" id="sucursal{{ $estudiante->Id_estudiantes }}" name="sucursal" required>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->Id_sucursales }}" 
                                            {{ $sucursal->Id_sucursales == $estudiante->Id_sucursales ? 'selected' : '' }}>
                                            {{ $sucursal->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tutor -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold" for="tutor{{ $estudiante->Id_estudiantes }}">Tutor</label>
                                <select class="form-select" id="tutor{{ $estudiante->Id_estudiantes }}" name="tutor_estudiante" required>
                                    @foreach ($tutores as $tutor)
                                        <option value="{{ $tutor->Id_tutores }}"
                                            {{ $tutor->Id_tutores == $estudiante->Id_tutores ? 'selected' : '' }}>
                                            {{ $tutor->persona->Nombre ?? '' }} {{ $tutor->persona->Apellido ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div> <!-- row -->
                    </div> <!-- modal-body -->
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    // Auto-submit al cambiar los filtros (opcional - puedes comentar si prefieres usar el botón)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filtrosForm');
        const selects = form.querySelectorAll('select');
        
        // Descomentar las siguientes líneas si quieres que los filtros se apliquen automáticamente
        /*
        selects.forEach(select => {
            select.addEventListener('change', function() {
                form.submit();
            });
        });
        */

        // Auto-cerrar alertas después de 5 segundos
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endsection