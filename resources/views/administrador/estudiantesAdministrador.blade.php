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
                        <th style="min-width:220px;">Profesor</th>
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
                            <td>{{ $profesorNombre }}</td>
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

                                    {{-- Botón Eliminar --}}
                                    <form action="{{ route('estudiantes.eliminar', $estudiante->Id_estudiantes ?? 0) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Está seguro de eliminar este estudiante?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>

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
            <div class="modal fade" id="editarModal{{ $estudiante->Id_estudiantes }}" tabindex="-1" aria-labelledby="editarModalLabel{{ $estudiante->Id_estudiantes }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarModalLabel{{ $estudiante->Id_estudiantes }}">Editar Estudiante</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('estudiantes.actualizar', $estudiante->Id_estudiantes) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="codigo{{ $estudiante->Id_estudiantes }}" class="form-label">Código</label>
                                    <input type="text" class="form-control" id="codigo{{ $estudiante->Id_estudiantes }}" name="Cod_estudiante" value="{{ $estudiante->Cod_estudiante }}" required>
                                </div>
                                {{-- Agrega más campos según necesites --}}
                            </div>
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