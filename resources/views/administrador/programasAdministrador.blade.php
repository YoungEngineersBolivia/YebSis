@extends('/administrador/baseAdministrador')

@section('title', 'Programas')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Listado de Programas</h1>

        @if($programas->isEmpty())
            <div class="alert alert-warning">No hay programas registrados.</div>
        @else
            <div class="row">
                @foreach ($programas as $programa)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($programa->Foto)
                                <img src="{{ asset('storage/' . $programa->Foto) }}" class="card-img-top" alt="Foto del programa">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $programa->Nombre }}</h5>
                                <p class="card-text">{{ $programa->Descripcion }}</p>
                                <p class="card-text"><strong>Duración:</strong> {{ $programa->Duracion }}</p>
                                <p class="card-text"><strong>Edad:</strong> {{ $programa->Rango_edad }}</p>
                                <p class="card-text"><strong>Costo:</strong> Bs {{ number_format($programa->Costo, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
