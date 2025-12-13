@extends('administrador.baseAdministrador')

@section('title', 'Tutores')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Tutores</h1>
    </div>

    {{-- Mensajes --}}
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
            <form id="formBuscador" action="{{ route('tutores.index') }}" method="GET" class="w-100">


                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                  <input type="text" id="searchTutor" class="form-control" name="search"
       placeholder="Buscar Tutor" value="{{ $search ?? '' }}">



                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
        </div>
    </div>

  @if($tutores->isEmpty())
    <div class="alert alert-warning">No hay tutores registrados.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Celular</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Parentesco</th>
                    <th style="min-width:140px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tutores as $tutor)
                        <tr>
                            <td>{{ $tutor->persona->Nombre ?? '—' }}</td>
                            <td>{{ $tutor->persona->Apellido ?? '—' }}</td>
                            <td>{{ $tutor->persona->Celular ?? '—' }}</td>
                            <td>{{ $tutor->persona->Direccion_domicilio ?? '—' }}</td>
                            <td>{{ $tutor->usuario->Correo ?? '—' }}</td>
                            <td>{{ $tutor->Parentesco ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    {{-- Botón Ver - Redirige a detalles --}}
                                    <a href="{{ route('tutores.detalles', $tutor->Id_tutores) }}" 
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Ver detalles">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    {{-- Botón Editar - Abre modal --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar{{ $tutor->Id_tutores }}">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>

                                
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Editar para cada tutor --}}
                        <div class="modal fade" id="modalEditar{{ $tutor->Id_tutores }}" tabindex="-1" aria-labelledby="modalEditarLabel{{ $tutor->Id_tutores }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <form action="{{ route('tutores.update', $tutor->Id_tutores) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalEditarLabel{{ $tutor->Id_tutores }}">Editar Tutor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" name="nombre" class="form-control" value="{{ $tutor->persona->Nombre ?? '' }}" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Apellido</label>
                                                    <input type="text" name="apellido" class="form-control" value="{{ $tutor->persona->Apellido ?? '' }}" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Celular</label>
                                                    <input type="text" name="celular" class="form-control" value="{{ $tutor->persona->Celular ?? '' }}">
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Dirección</label>
                                                    <input type="text" name="direccion_domicilio" class="form-control" value="{{ $tutor->persona->Direccion_domicilio ?? '' }}">
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Correo</label>
                                                    <input type="email" name="correo" class="form-control" value="{{ $tutor->usuario->Correo ?? '' }}" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Parentesco</label>
                                                    <input type="text" name="parentezco" class="form-control" value="{{ $tutor->Parentesco ?? '' }}" required>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">Descuento (%)</label>
                                                    <input type="number" name="descuento" class="form-control" step="0.01" min="0" max="100" value="{{ $tutor->Descuento ?? '' }}">
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <label class="form-label">NIT</label>
                                                    <input type="text" name="nit" class="form-control" value="{{ $tutor->Nit ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save me-1"></i>Guardar Cambios
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
      <div class="d-flex justify-content-center mt-4">
        {{ $tutores->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
let timer;
let lastValue = "{{ $search ?? '' }}";


window.onload = function() {
    const input = document.getElementById('searchTutor');
    if (input) {
        input.focus();
        input.setSelectionRange(input.value.length, input.value.length);
    }
};

document.getElementById('searchTutor').addEventListener('input', function () {
    const value = this.value;

  
    if (value === lastValue) return;

    clearTimeout(timer);

    
    timer = setTimeout(() => {
        lastValue = value;
        document.getElementById('formBuscador').submit();
    }, 900); 
});
</script>


@endsection