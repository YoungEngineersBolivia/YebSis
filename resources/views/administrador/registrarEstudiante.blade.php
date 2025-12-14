@extends('administrador.baseAdministrador')

@section('title', 'Registrar Estudiante')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .form-control:focus, .form-select:focus, .select2-container--default .select2-selection--single:focus {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #dee2e6;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<div class="container-fluid mt-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Registrar Nuevo Estudiante</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ url('/estudiantes/registrar') }}" method="POST">
                @csrf

                <h4 class="mb-3 text-secondary border-bottom pb-2">Datos Personales</h4>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required placeholder="Ej: Maria">
                    </div>
                
                    <div class="col-md-4">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" value="{{ old('apellido') }}" required placeholder="Ej: Gonzales">
                    </div>
                
                    <div class="col-md-4">
                        <label for="genero" class="form-label">Género</label>
                        <div class="d-flex align-items-center gap-3 pt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" {{ old('genero') == 'M' ? 'checked' : '' }}>
                                <label class="form-check-label" for="generoM">Masculino</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="genero" id="generoF" value="F" {{ old('genero') == 'F' ? 'checked' : '' }}>
                                <label class="form-check-label" for="generoF">Femenino</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" required>
                    </div>
                
                    <div class="col-md-4">
                        <label for="celular" class="form-label">Teléfono / Celular</label>
                        <input type="tel" id="celular" name="celular" class="form-control" value="{{ old('celular') }}" required placeholder="Ej: 70012345">
                    </div>
                
                    <div class="col-md-4">
                        <label for="direccion_domicilio" class="form-label">Dirección</label>
                        <input type="text" id="direccion_domicilio" name="direccion_domicilio" class="form-control" value="{{ old('direccion_domicilio') }}" required placeholder="Ej: Av. Principal #123">
                    </div>
                </div>

                <hr class="my-4">
                <h4 class="mb-3 text-secondary border-bottom pb-2">Información Académica</h4>

                <div class="row mb-4">
                    <div class="col-md-2">
                        <label for="codigo_estudiante" class="form-label">Código</label>
                        <input type="text" id="codigo_estudiante" name="codigo_estudiante" class="form-control" value="{{ old('codigo_estudiante') }}" required placeholder="Ej: EST-001">
                    </div>

                    <div class="col-md-3">
                        <label for="programa" class="form-label">Programa</label>
                        <select id="programa" name="programa" class="form-select" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach($programas as $programa)
                                <option value="{{ $programa->Id_programas }}">{{ $programa->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="sucursal" class="form-label">Sucursal</label>
                        <select id="sucursal" name="sucursal" class="form-select" required>
                            <option value="" selected disabled>Seleccione...</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->Id_sucursales }}">{{ $sucursal->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="tutor_estudiante" class="form-label">Tutor</label>
                        <select id="tutor_estudiante" name="tutor_estudiante" class="form-select select2 w-100" required>
                            <option value="" disabled selected>Seleccione un tutor...</option>
                            @foreach($tutores as $tutor)
                                <option value="{{ $tutor->Id_tutores }}">
                                    {{ $tutor->persona->Nombre ?? '' }} {{ $tutor->persona->Apellido ?? '' }} ({{ $tutor->Parentesco ?? 'Tutor' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Registrar Estudiante
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Buscar y seleccionar tutor...",
            allowClear: true,
            width: '100%'
        });
    });
</script>

@endsection