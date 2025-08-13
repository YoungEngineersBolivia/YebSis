@extends('/administrador/baseAdministrador')
@section('title','Publicaciones y notificaciones')

@section('content')
<div class="container mt-4">
    <h1>Publicaciones y Notificaciones</h1>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulario para crear publicación --}}
    <div class="card mb-4">
        <div class="card-header">Crear nueva publicación</div>
        <div class="card-body">
            <form action="{{ route('.store') }}" method="POST" enctype="multipart/form-data">
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
                            <a href="{{ asset('storage/' . $pub->Imagen) }}" target="_blank">Ver archivo</a>
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
