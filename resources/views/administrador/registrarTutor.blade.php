@extends('administrador.baseAdministrador')

@section('title', 'Registrar')

@section('content')
<div class="d-flex align-items-center gap-3">
    <h1 class="me-2">Registrar tutor</h1>
</div>

<!-- Mostrar errores de validación -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Mostrar mensaje de éxito -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('administrador.registrarT') }}" method="POST">
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

    <div class="row g-3 align-items-end mb-3">
        <div class="col-md-2">
            <label for="parentezco" class="form-label">Parentesco</label>
            <input type="text" id="parentezco" name="parentezco" class="form-control" 
                value="{{ old('parentezco') }}" required>
        </div>

        <div class="col-md-2">
            <label for="descuento" class="form-label">Descuento</label>
            <div class="input-group">
                <input type="number" id="descuento" name="descuento" class="form-control" 
                    value="{{ old('descuento') }}" required placeholder="Ej: 10" min="0" max="100" step="0.01">
                <span class="input-group-text">%</span>
            </div>
        </div>

        <div class="col-md-4">
            <label for="nombre_factura" class="form-label">Nombre a Facturar</label>
            <input type="text" id="nombre_factura" name="nombre_factura" class="form-control" 
                value="{{ old('nombre_factura') }}" required>
        </div>

        <div class="col-md-4">
            <label for="numero_nit" class="form-label">Numero NIT</label>
            <input type="text" id="numero_nit" name="numero_nit" class="form-control" 
                value="{{ old('numero_nit') }}" required>
        </div>
    </div>

    <div class="d-flex align-items-end gap-3">
        <div style="width: 220px;">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" name="correo" class="form-control" 
                   value="{{ old('correo') }}" required>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </div>
</form>

@endsection