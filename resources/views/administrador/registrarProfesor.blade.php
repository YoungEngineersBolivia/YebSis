@extends('administrador.baseAdministrador')

@section('title', 'Registrar Profesor')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="bi bi-person-plus-fill me-2"></i>Registrar Profesor</h1>
            <p class="mb-0 text-muted">Añadir un nuevo profesor al sistema</p>
        </div>
        <a href="{{ route('profesores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm border-0 mb-4 role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="fw-bold text-primary mb-0">Información Personal y Profesional</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('administrador.registrarP') }}" method="POST">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="nombre" class="form-label fw-semibold">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" 
                                       value="{{ old('nombre') }}" required placeholder="Ingrese nombre">
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="apellido" class="form-label fw-semibold">Apellido</label>
                                <input type="text" id="apellido" name="apellido" class="form-control" 
                                       value="{{ old('apellido') }}" required placeholder="Ingrese apellido">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">Género</label>
                                <div class="d-flex gap-4 pt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" 
                                               {{ old('genero', 'M') == 'M' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="generoM">Masculino</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="genero" id="generoF" value="F"
                                               {{ old('genero') == 'F' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="generoF">Femenino</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="fecha_nacimiento" class="form-label fw-semibold">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" 
                                       value="{{ old('fecha_nacimiento') }}" required>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="celular" class="form-label fw-semibold">Teléfono / Celular</label>
                                <input type="tel" id="celular" name="celular" class="form-control" 
                                       value="{{ old('celular') }}" required placeholder="Ingrese teléfono">
                            </div>

                            <div class="col-md-4">
                                <label for="profesion" class="form-label fw-semibold">Profesión</label>
                                <input type="text" id="profesion" name="profesion" class="form-control" 
                                       value="{{ old('profesion') }}" required placeholder="Ej: Ingeniero">
                            </div>
                        </div>

                        <div class="row mb-4">
                             <div class="col-md-8 mb-3 mb-md-0">
                                <label for="direccion_domicilio" class="form-label fw-semibold">Dirección Domiciliaria</label>
                                <input type="text" id="direccion_domicilio" name="direccion_domicilio" class="form-control" 
                                       value="{{ old('direccion_domicilio') }}" required placeholder="Dirección completa">
                            </div>
                            <div class="col-md-4">
                                <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" id="correo" name="correo" class="form-control" 
                                       value="{{ old('correo') }}" required placeholder="ejemplo@correo.com">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                             <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Cancelar</a>
                             <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-person-check me-2"></i>Registrar Profesor
                             </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection