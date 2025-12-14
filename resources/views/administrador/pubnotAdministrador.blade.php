@extends('/administrador/baseAdministrador')
@section('title','Publicaciones y notificaciones')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-megaphone me-2"></i>Publicaciones y Notificaciones</h2>
    </div>

    {{-- Mensajes de éxito --}}
    {{-- Mensajes de éxito (Manejado globalmente en baseAdministrador) --}}

    <!-- Toggle Selector -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="btn-group" role="group" aria-label="Tipo de acción">
                <input type="radio" class="btn-check" name="tipoAccion" id="optionPublicacion" value="publicacion" autocomplete="off" 
                    {{ old('form_type', 'publicacion') == 'publicacion' ? 'checked' : '' }}>
                <label class="btn btn-outline-primary" for="optionPublicacion"><i class="bi bi-globe me-2"></i>Gestión de Publicaciones</label>

                <input type="radio" class="btn-check" name="tipoAccion" id="optionNotificacion" value="notificacion" autocomplete="off"
                    {{ old('form_type') == 'notificacion' ? 'checked' : '' }}>
                <label class="btn btn-outline-warning" for="optionNotificacion"><i class="bi bi-envelope-paper me-2"></i>Gestión de Notificaciones</label>
            </div>
        </div>
    </div>

    {{-- SECCIÓN PUBLICACIONES --}}
    <div id="sectionPublicacion" class="row">
        {{-- Formulario Publicación --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 ps-3">
                    <h5 class="card-title fw-bold text-primary mb-0"><i class="bi bi-plus-circle me-2"></i>Nueva Publicación</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('publicaciones.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="form_type" value="publicacion">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">Título</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Título de la publicación" value="{{ old('nombre') }}">
                            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="form-control" required placeholder="Contenido de la publicación">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label fw-semibold">Archivo / Imagen</label>
                            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*,application/pdf">
                            @error('imagen') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Publicar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Lista Publicaciones --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 ps-3">
                    <h5 class="card-title fw-bold text-secondary mb-0"><i class="bi bi-list-ul me-2"></i>Publicaciones Web Activas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3 py-3">Título</th>
                                    <th class="py-3">Descripción</th>
                                    <th class="py-3">Archivo</th>
                                    <th class="py-3">Fecha</th>
                                    <th class="pe-3 py-3 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($publicaciones as $pub)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $pub->Nombre }}</td>
                                        <td>{{ Str::limit($pub->Descripcion, 50) }}</td>
                                        <td>
                                            @if($pub->Imagen)
                                                <a href="{{ auto_asset('storage/' . $pub->Imagen) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-file-earmark-arrow-down me-1"></i>Ver
                                                </a>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $pub->created_at->format('d/m/Y') }}</td>
                                        <td class="pe-3 text-end">
                                            <form action="{{ route('publicaciones.destroy', $pub->Id_publicaciones) }}" method="POST" onsubmit="return confirm('¿Eliminar publicación?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No hay publicaciones aún.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN NOTIFICACIONES --}}
    <div id="sectionNotificacion" class="row" style="display: none;">
        {{-- Formulario Notificación --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 ps-3">
                    <h5 class="card-title fw-bold text-warning mb-0"><i class="bi bi-broadcast me-2"></i>Nueva Notificación</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('notificaciones.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="form_type" value="notificacion">
                        <div class="mb-3">
                            <label for="nombre_notif" class="form-label fw-semibold">Asunto</label>
                            <input type="text" name="nombre" id="nombre_notif" class="form-control" required placeholder="Asunto de la notificación" value="{{ old('nombre') }}">
                            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_notif" class="form-label fw-semibold">Mensaje</label>
                            <textarea name="descripcion" id="descripcion_notif" rows="3" class="form-control" required placeholder="Mensaje para los tutores">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="imagen_notif" class="form-label fw-semibold">Archivo / Imagen</label>
                            <input type="file" name="imagen" id="imagen_notif" class="form-control" accept="image/*,application/pdf">
                            @error('imagen') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Destinatarios</label>
                            <div class="d-flex gap-2 mb-2">
                                <input type="radio" class="btn-check" name="filtroDestinatarios" id="filtroTodos" value="todos" 
                                    {{ old('filtroDestinatarios', 'todos') == 'todos' ? 'checked' : '' }}>
                                <label class="btn btn-sm btn-outline-secondary" for="filtroTodos">Todos</label>

                                <input type="radio" class="btn-check" name="filtroDestinatarios" id="filtroActivos" value="activos"
                                    {{ old('filtroDestinatarios') == 'activos' ? 'checked' : '' }}>
                                <label class="btn btn-sm btn-outline-success" for="filtroActivos">Solo Activos</label>
                            </div>

                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                <input type="text" id="buscador-tutor" class="form-control" placeholder="Buscar destinatario...">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all-tutores">
                                    <label class="form-check-label small" for="select-all-tutores">Seleccionar visibles</label>
                                </div>
                                <span class="badge bg-secondary" id="contador-seleccionados">0 seleccionados</span>
                            </div>

                            <div id="lista-tutores" class="border rounded p-2 bg-light" style="max-height: 200px; overflow-y: auto;">
                                @foreach($tutores as $tutor)
                                    @php
                                        $esActivo = $tutor->estudiantes->isNotEmpty();
                                        $isChecked = is_array(old('tutores')) && in_array($tutor->Id_tutores, old('tutores'));
                                    @endphp
                                    <div class="form-check tutor-item border-bottom py-1" data-activo="{{ $esActivo ? 'true' : 'false' }}">
                                        <input class="form-check-input tutor-checkbox" type="checkbox" name="tutores[]" value="{{ $tutor->Id_tutores }}" id="tutor-{{ $tutor->Id_tutores }}" {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex justify-content-between w-100 pe-1" for="tutor-{{ $tutor->Id_tutores }}" style="cursor: pointer;">
                                            <span class="text-truncate">{{ $tutor->persona->Nombre ?? '' }} {{ $tutor->persona->Apellido ?? '' }}</span>
                                            @if($esActivo)
                                                <i class="bi bi-circle-fill text-success" title="Activo" style="font-size: 0.5rem; margin-top: 6px;"></i>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('tutores') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning text-white"><i class="bi bi-send me-1"></i>Enviar Notificación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Historial Notificaciones --}}
        <div class="col-lg-7 mb-4">
             <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pt-3 ps-3">
                    <h5 class="card-title fw-bold text-secondary mb-0"><i class="bi bi-clock-history me-2"></i>Historial de Envíos</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($notificaciones as $notif)
                            <li class="list-group-item p-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <h6 class="mb-0 fw-bold text-dark">{{ $notif->Nombre }}</h6>
                                    <span class="badge bg-light text-muted border">{{ \Carbon\Carbon::parse($notif->Fecha)->format('d/m/Y') }}</span>
                                </div>
                                <p class="mb-0 text-muted small">{{ $notif->Descripcion }}</p>
                                @if($notif->Imagen)
                                    <div class="mt-2">
                                        <a href="{{ auto_asset('storage/' . $notif->Imagen) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="bi bi-paperclip"></i> Adjunto
                                        </a>
                                    </div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                No hay notificaciones recientes.
                            </li>
                        @endforelse
                    </ul>
                </div>
             </div>
        </div>
    </div>

    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- TOGGLE FORMULARIOS ---
        const radioPublicacion = document.getElementById('optionPublicacion');
        const radioNotificacion = document.getElementById('optionNotificacion');
        const sectionPublicacion = document.getElementById('sectionPublicacion');
        const sectionNotificacion = document.getElementById('sectionNotificacion');

        function toggleSections() {
            if (radioPublicacion.checked) {
                sectionPublicacion.style.display = 'flex'; // Use flex for row
                sectionNotificacion.style.display = 'none';
            } else {
                sectionPublicacion.style.display = 'none';
                sectionNotificacion.style.display = 'flex'; // Use flex for row
            }
        }

        radioPublicacion.addEventListener('change', toggleSections);
        radioNotificacion.addEventListener('change', toggleSections);
        toggleSections(); // Inicializar

        // --- LÓGICA DE NOTIFICACIONES ---
        const buscador = document.getElementById('buscador-tutor');
        const listaTutores = document.getElementById('lista-tutores');
        const selectAll = document.getElementById('select-all-tutores');
        const filtroTodos = document.getElementById('filtroTodos');
        const filtroActivos = document.getElementById('filtroActivos');
        const contadorSeleccionados = document.getElementById('contador-seleccionados');
        
        function filtrarTutores() {
            const textoBusqueda = buscador.value.toLowerCase();
            const soloActivos = filtroActivos.checked;
            let visibles = 0;

            listaTutores.querySelectorAll('.tutor-item').forEach(function(item) {
                const label = item.textContent.toLowerCase();
                const esActivo = item.getAttribute('data-activo') === 'true';
                
                // Condición 1: Coincide con búsqueda
                const matchBusqueda = label.includes(textoBusqueda);
                // Condición 2: Filtro de activos
                const matchActivo = !soloActivos || esActivo;

                if (matchBusqueda && matchActivo) {
                    item.style.display = 'flex'; // Use flex for tutor-item
                    visibles++;
                } else {
                    item.style.display = 'none';
                    // Si se oculta, deseleccionar (opcional, pero recomendable UX)
                    // item.querySelector('.tutor-checkbox').checked = false;
                }
            });
            actualizarContador();
        }

        function actualizarContador() {
            const total = listaTutores.querySelectorAll('.tutor-checkbox:checked').length;
            contadorSeleccionados.textContent = `${total} seleccionados`;
        }

        if(buscador) buscador.addEventListener('input', filtrarTutores);
        if(filtroTodos) filtroTodos.addEventListener('change', filtrarTutores);
        if(filtroActivos) filtroActivos.addEventListener('change', filtrarTutores);

        if(selectAll && listaTutores) {
            selectAll.addEventListener('change', function() {
                const checked = selectAll.checked;
                // Solo seleccionar los que están VISIBLES actualmente
                listaTutores.querySelectorAll('.tutor-item').forEach(function(item) {
                    if (item.style.display !== 'none') {
                        item.querySelector('.tutor-checkbox').checked = checked;
                    }
                });
                actualizarContador();
            });
        }

        // Actualizar contador al cambiar manualmnete
        listaTutores.querySelectorAll('.tutor-checkbox').forEach(cb => {
            cb.addEventListener('change', actualizarContador);
        });

    });
</script>
@endsection
