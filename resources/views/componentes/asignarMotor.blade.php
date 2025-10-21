@extends('administrador.baseAdministrador')

@section('title', 'Asignar Motor a Técnico')

@section('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Asignar Motor a Técnico</h1>
        <a href="{{ route('motores.asignaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Ver Asignaciones
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Registro de Salida de Motor</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('motores.asignar.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_asignacion" class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_asignacion') is-invalid @enderror" 
                               id="fecha_asignacion" name="fecha_asignacion" value="{{ old('fecha_asignacion', date('Y-m-d')) }}" required>
                        @error('fecha_asignacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="Id_motores" class="form-label">ID Motor <span class="text-danger">*</span></label>
                        <select class="form-select @error('Id_motores') is-invalid @enderror" 
                                id="Id_motores" name="Id_motores" required>
                            <option value="">Seleccione un motor</option>
                            @foreach($motoresDisponibles as $motor)
                                <option value="{{ $motor->Id_motores }}" {{ old('Id_motores') == $motor->Id_motores ? 'selected' : '' }}>
                                    {{ $motor->Id_motor }} - {{ $motor->Estado }} ({{ $motor->sucursal->Nombre ?? 'Sin sucursal' }})
                                </option>
                            @endforeach
                        </select>
                        @error('Id_motores')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estado_motor" class="form-label">Estado del Motor <span class="text-danger">*</span></label>
                        <select class="form-select @error('estado_motor') is-invalid @enderror" 
                                id="estado_motor" name="estado_motor" required>
                            <option value="">Seleccione estado</option>
                            <option value="Funcionando" {{ old('estado_motor') == 'Funcionando' ? 'selected' : '' }}>Funcionando</option>
                            <option value="Descompuesto" {{ old('estado_motor') == 'Descompuesto' ? 'selected' : '' }}>Descompuesto</option>
                            <option value="En Proceso" {{ old('estado_motor') == 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                        </select>
                        @error('estado_motor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="Id_profesores" class="form-label">Técnico Asignado <span class="text-danger">*</span></label>
                        <select class="form-select @error('Id_profesores') is-invalid @enderror" 
                                id="Id_profesores" name="Id_profesores" required>
                            <option value="">Seleccione un técnico</option>
                            @foreach($tecnicos as $tecnico)
                                <option value="{{ $tecnico->Id_profesores }}" {{ old('Id_profesores') == $tecnico->Id_profesores ? 'selected' : '' }}>
                                    {{ $tecnico->persona->Nombre }} {{ $tecnico->persona->Apellido_paterno }}
                                </option>
                            @endforeach
                        </select>
                        @error('Id_profesores')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observacion_inicial" class="form-label">Observaciones</label>
                    <textarea class="form-control @error('observacion_inicial') is-invalid @enderror" 
                              id="observacion_inicial" name="observacion_inicial" rows="4" 
                              placeholder="Describa el estado del motor, problemas detectados, etc.">{{ old('observacion_inicial') }}</textarea>
                    @error('observacion_inicial')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('motores.asignaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Asignar Motor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection