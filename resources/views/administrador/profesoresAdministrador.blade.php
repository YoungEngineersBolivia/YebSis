@extends('administrador.baseAdministrador')

@section('title', 'Profesores')

@section('styles')
    @vite('resources/css/dashboard.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .table thead th { white-space: nowrap; }
        .badge-status { font-size: .85rem; }
        .search-hint { font-size: .9rem; color:#666; }
        .pagination { justify-content: center; }

        /* --- Tabla a tarjetas en móvil --- */
        @media (max-width: 576px) {
            .responsive-table thead {
                display: none;
            }
            .responsive-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #e9ecef;
                border-radius: .5rem;
                overflow: hidden;
                background: #fff;
            }
            .responsive-table tbody td {
                display: flex;
                justify-content: space-between;
                gap: .75rem;
                padding: .75rem 1rem;
                border-bottom: 1px solid #f1f3f5;
            }
            .responsive-table tbody td:last-child {
                border-bottom: 0;
            }
            .responsive-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #555;
                min-width: 44%;
                text-align: left;
            }
            /* Botones en una línea con wrap */
            .actions-mobile {
                display: flex;
                flex-wrap: wrap;
                gap: .5rem;
                width: 100%;
                justify-content: flex-start;
            }
        }

        /* Correcciones visuales */
        .truncate-mail {
            max-width: 260px;
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
        }
        @media (max-width: 576px) {
            .truncate-mail { max-width: 60%; }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">

    <!-- Toolbar superior -->
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Lista de Profesores</h1>
        <a href="{{ route('registroCombinado.registrar') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Registrar Profesor
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
                    @foreach ($profesores as $profesor)
                        @php
                            $persona   = $profesor->persona ?? null;
                            $usuario   = $profesor->usuario ?? null;
                            $nombre    = $persona->Nombre   ?? '—';
                            $apellido  = $persona->Apellido ?? '—';
                            $celular   = $persona->Celular  ?? '—';
                            $correo    = $usuario->Correo   ?? '—';
                            $profesion = $profesor->Profesion ?? '—';
                            $rolComp   = $profesor->Rol_componentes ?? '—';
                        @endphp
                        <tr>
                            <td data-label="Nombre" class="fw-semibold">{{ $nombre }}</td>
                            <td data-label="Apellido">{{ $apellido }}</td>
                            <td data-label="Teléfono">{{ $celular }}</td>
                            <td data-label="Profesión">{{ $profesion }}</td>
                            <td data-label="Correo">
                                <span class="truncate-mail" title="{{ $correo }}">{{ $correo }}</span>
                            </td>
                            <td data-label="Rol componentes">{{ $rolComp }}</td>
                            <td data-label="Acciones">
                                {{-- Desktop: botones inline / Móvil: siguen viéndose en bloque por CSS --}}
                                <div class="d-none d-sm-flex gap-2">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal{{ $profesor->Id_profesores }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('profesores.destroy', $profesor->Id_profesores) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este profesor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('profesores.show', $profesor->Id_profesores) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Ver detalle">
                                        <i class="bi bi-person-fill"></i>
                                    </a>
                                </div>

                                {{-- Mobile: acciones en fila con wrap --}}
                                <div class="actions-mobile d-sm-none">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarModal{{ $profesor->Id_profesores }}">
                                        <i class="bi bi-pencil-square me-1"></i>Editar
                                    </button>

                                    <a href="{{ route('profesores.show', $profesor->Id_profesores) }}"
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-person-fill me-1"></i>Ver
                                    </a>

                                    <form action="{{ route('profesores.destroy', $profesor->Id_profesores) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este profesor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash3-fill me-1"></i>Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal fade" id="editarModal{{ $profesor->Id_profesores }}" tabindex="-1" aria-labelledby="editarModalLabel{{ $profesor->Id_profesores }}" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="editarModalLabel{{ $profesor->Id_profesores }}">Editar Profesor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                              </div>
                              <form action="{{ route('profesores.update', $profesor->Id_profesores) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" name="nombre" class="form-control" value="{{ $persona->Nombre ?? '' }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Apellido</label>
                                            <input type="text" name="apellido" class="form-control" value="{{ $persona->Apellido ?? '' }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Género</label>
                                            <input type="text" name="genero" class="form-control" value="{{ $persona->Genero ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Celular</label>
                                            <input type="text" name="celular" class="form-control" value="{{ $persona->Celular ?? '' }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" name="direccion" class="form-control" value="{{ $persona->Direccion_domicilio ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Fecha de nacimiento</label>
                                            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $persona->Fecha_nacimiento ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Rol (Id_roles)</label>
                                            <input type="number" name="id_roles" class="form-control" value="{{ $persona->Id_roles ?? '' }}" required>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Correo</label>
                                            <input type="email" name="correo" class="form-control" value="{{ $usuario->Correo ?? '' }}" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
                                            <input type="password" name="contrasenia" class="form-control" autocomplete="new-password">
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Profesión</label>
                                            <input type="text" name="profesion" class="form-control" value="{{ $profesor->Profesion ?? '' }}">
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <label class="form-label">Rol componentes</label>
                                            <input type="text" name="rol_componentes" class="form-control" value="{{ $profesor->Rol_componentes ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save2 me-1"></i>Guardar cambios
                                  </button>
                                </div>
                              </form>
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
<script>
    // Filtro de tabla en vivo (cliente)
    (function () {
        const input = document.querySelector('input[name="search"]');
        const table = document.getElementById('teachersTable');
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
</script>
@endsection
