@extends('/administrador/baseAdministrador')

@section('title', 'Programas')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Listado de Programas</h1>

  <!-- Botón que abre el modal -->
<!-- Botón que abre el modal -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#nuevoProgramaModal">
    Registrar nuevo programa
</button>


    @if($programas->isEmpty())
        <div class="alert alert-warning">No hay programas registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Duración</th>
                        <th>Rango de Edad</th>
                        <th>Costo (Bs)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programas as $programa)
                        <tr>
                            <td>
                                @if($programa->Foto)
                                    <img src="{{ asset('storage/' . $programa->Foto) }}" alt="Foto" style="width: 100px; height: auto;">
                                @else
                                    Sin foto
                                @endif
                            </td>
                            <td>{{ $programa->Nombre }}</td>
                            <td>{{ $programa->Descripcion }}</td>
                            <td>{{ $programa->Duracion }}</td>
                            <td>{{ $programa->Rango_edad }}</td>
                            <td>{{ number_format($programa->Costo, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $programas->links() }}
            </div>
        </div>
        <!-- Modal -->
            <div class="modal fade" id="nuevoProgramaModal" tabindex="-1" aria-labelledby="nuevoProgramaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="modalContent">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Programa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">Cargando...</div>
                </div>
                </div>
            </div>
            </div>

    @endif
</div>
@endsection
