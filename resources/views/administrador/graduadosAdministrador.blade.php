@extends('administrador.baseAdministrador')

@section('title', 'Graduados')

@section('styles')
<link href="{{ auto_asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Estudiantes Graduados</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoProgramaModal">
            <i class="fas fa-plus me-2"></i>Añadir Estudiantes
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar Programa" id="searchInput">
            </div>
        </div>
    </div>

    @if($graduados->isEmpty())
        <div class="alert alert-warning">No hay graduados registrados.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th class="fw-bold text-dark">
                            Nombre Estudiante
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Apellido Estudiante
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark">
                            Programa
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold bi-arrow-down">
                            Nombre Profesor
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark ">
                            Apellido Profesor 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark ">
                            Fecha Graduacion
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        <th class="fw-bold text-dark ">
                            Acciones 
                            <i class="fbi bi-arrow-down"></i>
                        </th>
                        
                    </tr>
                </thead>
                <tbody>

                    @foreach($graduados as $graduado)
                        <tr>
                            <td>{{ $graduado->estudiante->persona->Nombre ?? 'N/A' }}</td>
                            <td>{{ $graduado->estudiante->persona->Apellido ?? 'N/A' }}</td>
                            <td>{{ $graduado->programa->Nombre ?? 'N/A' }}</td>
                            <td>{{ $graduado->profesor->persona->Nombre ?? 'N/A' }}</td>
                            <td>{{ $graduado->profesor->persona->Apellido ?? 'N/A' }}</td>
                            <td>{{ $graduado->Fecha_graduado ? \Carbon\Carbon::parse($graduado->Fecha_graduado)->format('d/m/Y') : 'Sin fecha' }}</td>
                             <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                   
                                    <button class="btn btn-sm btn-outline-primary" 
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" 
                                            title="Ver detalles">
                                        <i class="fas fa-user"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
