@extends('administrador.baseAdministrador')

@section('title', 'Registrar')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="d-flex align-items-center gap-3">
    <h1 class="me-2">Registrar estudiante</h1>
</div>

<form action="/estudiantes/registrar" method="POST">
    @csrf

    <div class="d-flex align-items-end gap-3 mb-3">
        <div class="flex-grow-1">
            <label for="nombre" class="form-label">Nombre</label>
            <div class="input">
                <input type="text" id="nombre" name="nombre" class="form-control" 
                       value="{{ old('nombre') }}" required>
            </div>
        </div>
    
        <div class="flex-grow-1">
            <label for="apellido" class="form-label">Apellido</label>
            <div class="input">
                <input type="text" id="apellido" name="apellido" class="form-control" 
                       value="{{ old('apellido') }}" required>
            </div>
        </div>
    
        <div class="flex-grow-1">
            <label for="genero" class="form-label">Género</label>
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" 
                           {{ old('genero', 'M') == 'M' ? 'checked' : '' }}>
                    <label class="form-check-label" for="generoM">M</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="genero" id="generoF" value="F"
                           {{ old('genero') == 'F' ? 'checked' : '' }}>
                    <label class="form-check-label" for="generoF">F</label>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-end gap-3 mb-3">
        <div style="flex-basis: 220px;">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <div class="input-group">
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" 
                       value="{{ old('fecha_nacimiento') }}" required>
            </div>
        </div>
    
        <div style="flex-basis: 220px;">
            <label for="celular" class="form-label">Teléfono</label>
            <input type="tel" id="celular" name="celular" class="form-control" 
                   value="{{ old('celular') }}" required>
        </div>
    
        <div class="flex-grow-1">
            <label for="direccion_domicilio" class="form-label">Dirección</label>
            <input type="text" id="direccion_domicilio" name="direccion_domicilio" class="form-control" 
                   value="{{ old('direccion_domicilio') }}" required>
        </div>
    </div>

    <div class="d-flex align-items-end gap-3">

        <div style="width: 150px;">
            <label for="codigo_estudiante" class="form-label">Código</label>
            <input type="text" id="codigo_estudiante" name="codigo_estudiante" class="form-control" 
                value="{{ old('codigo_estudiante') }}" required>
        </div>

        <div style="width: 220px;">
            <label for="programa" class="form-label">Programa</label>
            <select id="programa" name="programa" class="form-select" required>
                <option value="" selected disabled>Seleccione un programa...</option>
                @foreach($programas as $programa)
                    <option value="{{ $programa->Id_programas }}">{{ $programa->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div style="width: 220px;">
            <label for="sucursal" class="form-label">Sucursal</label>
            <select id="sucursal" name="sucursal" class="form-select" required>
                <option value="" selected disabled>Seleccione una sucursal...</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->Id_Sucursales }}">{{ $sucursal->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div style="width: 280px;">
            <label for="tutor_estudiante" class="form-label">Tutor</label>
            <select id="tutor_estudiante" name="tutor_estudiante" class="form-select select2" required>
                <option value="" disabled selected>Seleccione un tutor...</option>
                @foreach($tutores as $tutor)
                    <option value="{{ $tutor->Id_tutores }}">
                        {{ $tutor->persona->Nombre }} {{ $tutor->persona->Apellido }} - {{ $tutor->Parentesco }}
                    </option>
                @endforeach
            </select>
        </div>

        </div>

        <div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>

    </div>

</form>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccione un tutor",
            allowClear: true
        });
    });
</script>

@endsection