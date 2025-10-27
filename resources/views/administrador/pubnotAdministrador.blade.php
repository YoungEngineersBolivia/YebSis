@extends('/administrador/baseAdministrador')
@section('title','Publicaciones y notificaciones')

@section('content')
<div class="container mt-4">
    <h1>Publicaciones y Notificaciones</h1>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- Formulario para crear publicación en la web --}}
    <div class="card mb-4">
        <div class="card-header">Crear nueva publicación en la página web</div>
        <div class="card-body">
            <form action="{{ route('publicaciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Título</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control" required></textarea>
                    @error('descripcion')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Archivo / Imagen (opcional)</label>
                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*,application/pdf">
                    @error('imagen')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Crear Publicación</button>
            </form>
        </div>
    </div>

    {{-- Formulario para enviar notificación a tutores --}}
    <div class="card mb-4">
        <div class="card-header">Enviar notificación a tutores</div>
        <div class="card-body">
            <form action="{{ route('notificaciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nombre_notif" class="form-label">Título</label>
                    <input type="text" name="nombre" id="nombre_notif" class="form-control" required>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="descripcion_notif" class="form-label">Mensaje</label>
                    <textarea name="descripcion" id="descripcion_notif" rows="3" class="form-control" required></textarea>
                    @error('descripcion')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="imagen_notif" class="form-label">Archivo / Imagen (opcional)</label>
                    <input type="file" name="imagen" id="imagen_notif" class="form-control" accept="image/*,application/pdf">
                    @error('imagen')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Buscar tutor</label>
                    <input type="text" id="buscador-tutor" class="form-control" placeholder="Buscar por nombre o apellido...">
                </div>
                <div class="mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="select-all-tutores">
                        <label class="form-check-label" for="select-all-tutores">Seleccionar todos los tutores</label>
                    </div>
                    <div id="lista-tutores" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                        @foreach($tutores as $tutor)
                            <div class="form-check tutor-item">
                                <input class="form-check-input tutor-checkbox" type="checkbox" name="tutores[]" value="{{ $tutor->Id_tutores }}" id="tutor-{{ $tutor->Id_tutores }}">
                                <label class="form-check-label" for="tutor-{{ $tutor->Id_tutores }}">
                                    {{ $tutor->persona->Nombre ?? '' }} {{ $tutor->persona->Apellido ?? '' }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-warning">Enviar Notificación</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscador-tutor');
            const listaTutores = document.getElementById('lista-tutores');
            const selectAll = document.getElementById('select-all-tutores');
            buscador.addEventListener('input', function() {
                const filtro = buscador.value.toLowerCase();
                listaTutores.querySelectorAll('.tutor-item').forEach(function(item) {
                    const label = item.textContent.toLowerCase();
                    item.style.display = label.includes(filtro) ? '' : 'none';
                });
            });
            selectAll.addEventListener('change', function() {
                const checked = selectAll.checked;
                listaTutores.querySelectorAll('.tutor-checkbox').forEach(function(cb) {
                    cb.checked = checked;
                });
            });
        });
    </script>

    {{-- Lista de publicaciones --}}
    <h3>Publicaciones</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Archivo</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($publicaciones as $pub)
                <tr>
                    <td>{{ $pub->Nombre }}</td>
                    <td>{{ $pub->Descripcion }}</td>
                    <td>
                        @if($pub->Imagen)
                            <a href="{{ auto_asset('storage/' . $pub->Imagen) }}" target="_blank">Ver archivo</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $pub->created_at->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('publicaciones.destroy', $pub->Id_publicaciones) }}" method="POST" onsubmit="return confirm('¿Eliminar publicación?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay publicaciones aún.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Lista de notificaciones para tutores --}}
    <h3>Notificaciones para Tutores</h3>
    <ul class="list-group mb-4">
        @forelse($notificaciones as $notif)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $notif->Nombre }}</strong> - {{ $notif->Descripcion }}
                </div>
                <small class="text-muted">{{ \Carbon\Carbon::parse($notif->Fecha)->format('d/m/Y') }}</small>
            </li>
        @empty
            <li class="list-group-item">No hay notificaciones.</li>
        @endforelse
    </ul>
</div>
@endsection
