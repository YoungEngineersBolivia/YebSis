@extends('administrador.baseAdministrador')

@section('title', 'Registrar Tutor')

@section('content')
<div class="container-fluid mt-4">
    
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-person-plus-fill me-2"></i>Registrar Tutor</h1>
            <p class="mb-0 text-muted">Añadir un nuevo tutor al sistema</p>
        </div>
        <a href="{{ route('tutoresAdministrador') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mostrar mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm border-0 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                     <h5 class="fw-bold text-primary mb-0">Información del Tutor</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('administrador.registrarT') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-secondary text-uppercase small mb-3">Datos Personales</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="nombre" class="form-label fw-semibold">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required placeholder="Ej: Juan">
                            </div>
                        
                            <div class="col-md-4">
                                <label for="apellido" class="form-label fw-semibold">Apellido</label>
                                <input type="text" id="apellido" name="apellido" class="form-control" value="{{ old('apellido') }}" required placeholder="Ej: Perez">
                            </div>
                        
                            <div class="col-md-4">
                                <label for="genero" class="form-label fw-semibold">Género</label>
                                <div class="d-flex align-items-center gap-3 pt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" {{ old('genero', 'M') == 'M' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="generoM">Masculino</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="generoF" value="F" {{ old('genero') == 'F' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="generoF">Femenino</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" required>
                            </div>
                        
                            <div class="col-md-4">
                                <label for="celular" class="form-label fw-semibold">Teléfono / Celular</label>
                                <input type="tel" id="celular" name="celular" class="form-control" value="{{ old('celular') }}" required placeholder="Ej: 70012345">
                            </div>
                        
                            <div class="col-md-4">
                                <label for="direccion_domicilio" class="form-label fw-semibold">Dirección</label>
                                <input type="text" id="direccion_domicilio" name="direccion_domicilio" class="form-control" value="{{ old('direccion_domicilio') }}" required placeholder="Ej: Av. Principal #123">
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3">Datos de Facturación y Contacto</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="parentezco" class="form-label fw-semibold">Parentesco</label>
                                <input type="text" id="parentezco" name="parentezco" class="form-control" value="{{ old('parentezco') }}" required placeholder="Ej: Padre, Madre, Tío">
                            </div>

                            <div class="col-md-6">
                                <label for="descuento" class="form-label fw-semibold">Descuento (%)</label>
                                <div class="input-group">
                                    <input type="number" id="descuento" name="descuento" class="form-control" value="{{ old('descuento') }}" required placeholder="Ej: 10" min="0" max="100" step="0.01">
                                    <span class="input-group-text bg-light text-secondary">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre_factura" class="form-label fw-semibold">Nombre a Facturar</label>
                                <input type="text" id="nombre_factura" name="nombre_factura" class="form-control" value="{{ old('nombre_factura') }}" required placeholder="Nombre para la factura">
                            </div>

                            <div class="col-md-6">
                                <label for="numero_nit" class="form-label fw-semibold">Número NIT/CI</label>
                                <input type="text" id="numero_nit" name="numero_nit" class="form-control" value="{{ old('numero_nit') }}" required placeholder="NIT o CI para factura">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" id="correo" name="correo" class="form-control" value="{{ old('correo') }}" required placeholder="ejemplo@email.com">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('tutoresAdministrador') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Registrar Tutor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection