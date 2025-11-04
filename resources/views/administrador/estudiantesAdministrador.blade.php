@extends('administrador.baseAdministrador')

@section('title', 'Estudiantes')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ auto_asset('css/administrador/estudiantesAdministrador.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Lista de Estudiantes</h1>
        <a href="{{ route('registroCombinado.registrar') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Registrar Estudiante
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
    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('estudiantes.index') }}" method="GET">
                <label for="searchInput" class="form-label mb-1">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Filtrar por código, nombre o apellido" name="search" value="{{ request()->search }}">
                </div>
            </form>
        </div>
    </div>

    @if ($estudiantes->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="mb-2">No hay estudiantes registrados.</p>
                <a href="{{ route('registroCombinado.registrar') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus me-2"></i>Registrar el primero
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="studentsTable">
                <thead class="table-light">
                    <tr>
                        <th style="min-width:120px;">Código</th>
                        <th style="min-width:220px;">Nombre</th>
                        <th style="min-width:180px;">Programa</th>
                        <th style="min-width:160px;">Sucursal</th>
                        <th style="min-width:120px;">Estado</th>
                        <th style="min-width:140px;">Acciones</th>
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
                            <td class="fw-semibold">{{ $estudiante->Cod_estudiante }}</td>
                            <td>{{ $nombreCompletoEst ?: 'Sin datos' }}</td>
                            <td>{{ $programa }}</td>
                            <td>{{ $sucursal }}</td>
                            <td>
                                @if($esActivo)
                                    <span class="badge text-bg-success badge-status">Activo</span>
                                @elseif($estadoLower === 'inactivo')
                                    <span class="badge text-bg-secondary badge-status">Inactivo</span>
                                @else
                                    <span class="badge text-bg-light text-dark badge-status">{{ $estudiante->Estado }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
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
                                       class="btn btn-sm btn-outline-secondary" 
                                       title="Ver perfil">
                                        <i class="bi bi-person-fill"></i>
                                    </a>

                                    {{-- Botón Cambiar estado --}}
                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modales de edición --}}
        @foreach ($estudiantes as $estudiante)
        @php
            $persona = $estudiante->persona ?? null;
        @endphp
        <div class="modal fade" id="editarModal{{ $estudiante->Id_estudiantes }}" tabindex="-1" aria-labelledby="editarModalLabel{{ $estudiante->Id_estudiantes }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarModalLabel{{ $estudiante->Id_estudiantes }}">Editar Estudiante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <form action="{{ route('estudiantes.actualizar', $estudiante->Id_estudiantes) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body row g-3">

                            <!-- Código estudiante -->
                            <div class="col-md-6">
                                <label class="form-label" for="codigo{{ $estudiante->Id_estudiantes }}">Código</label>
                                <input type="text" id="codigo{{ $estudiante->Id_estudiantes }}" name="codigo_estudiante"
                                    class="form-control" value="{{ $estudiante->Cod_estudiante }}" required>
                            </div>

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label" for="nombre{{ $estudiante->Id_estudiantes }}">Nombre</label>
                                <input type="text" id="nombre{{ $estudiante->Id_estudiantes }}" name="nombre"
                                    class="form-control" value="{{ $persona->Nombre ?? '' }}" required>
                            </div>

                            <!-- Apellido -->
                            <div class="col-md-6">
                                <label class="form-label" for="apellido{{ $estudiante->Id_estudiantes }}">Apellido</label>
                                <input type="text" id="apellido{{ $estudiante->Id_estudiantes }}" name="apellido"
                                    class="form-control" value="{{ $persona->Apellido ?? '' }}" required>
                            </div>

                            <!-- Género -->
                            <div class="col-md-6">
                                <label class="form-label" for="genero{{ $estudiante->Id_estudiantes }}">Género</label>
                                <select class="form-select" name="genero" id="genero{{ $estudiante->Id_estudiantes }}" required>
                                    <option value="M" {{ $persona->Genero === 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ $persona->Genero === 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>

                            <!-- Fecha nacimiento -->
                            <div class="col-md-6">
                                <label class="form-label" for="fecha_nacimiento{{ $estudiante->Id_estudiantes }}">Fecha de nacimiento</label>
                                <input type="date" id="fecha_nacimiento{{ $estudiante->Id_estudiantes }}" name="fecha_nacimiento"
                                    class="form-control" value="{{ $persona->Fecha_nacimiento ?? '' }}" required>
                            </div>

                            <!-- Celular -->
                            <div class="col-md-6">
                                <label class="form-label" for="celular{{ $estudiante->Id_estudiantes }}">Celular</label>
                                <input type="text" id="celular{{ $estudiante->Id_estudiantes }}" name="celular"
                                    class="form-control" value="{{ $persona->Celular ?? '' }}" required>
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-12">
                                <label class="form-label" for="direccion{{ $estudiante->Id_estudiantes }}">Dirección</label>
                                <input type="text" id="direccion{{ $estudiante->Id_estudiantes }}" name="direccion_domicilio"
                                    class="form-control" value="{{ $persona->Direccion_domicilio ?? '' }}" required>
                            </div>

                            <!-- Programa -->
                            <div class="col-md-6">
                                <label class="form-label" for="programa{{ $estudiante->Id_estudiantes }}">Programa</label>
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
                                <label class="form-label" for="sucursal{{ $estudiante->Id_estudiantes }}">Sucursal</label>
                                <select class="form-select" id="sucursal{{ $estudiante->Id_estudiantes }}" name="sucursal" required>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->Id_Sucursales }}" 
                                            {{ $sucursal->Id_Sucursales == $estudiante->Id_sucursales ? 'selected' : '' }}>
                                            {{ $sucursal->Nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tutor -->
                            <div class="col-md-12">
                                <label class="form-label" for="tutor{{ $estudiante->Id_estudiantes }}">Tutor</label>
                                <select class="form-select" id="tutor{{ $estudiante->Id_estudiantes }}" name="tutor_estudiante" required>
                                    @foreach ($tutores as $tutor)
                                        <option value="{{ $tutor->Id_tutores }}"
                                            {{ $tutor->Id_tutores == $estudiante->Id_tutores ? 'selected' : '' }}>
                                            {{ $tutor->persona->Nombre ?? '' }} {{ $tutor->persona->Apellido ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div> <!-- modal-body -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    @endif

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $estudiantes->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filtro de tabla en vivo
    (function () {
        const input = document.querySelector('input[name="search"]');
        const table = document.getElementById('studentsTable');
        if (!input || !table) return;

        input.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    })();

    // Auto-cerrar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
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