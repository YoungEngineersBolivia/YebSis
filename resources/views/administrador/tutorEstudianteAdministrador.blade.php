@extends('/administrador/baseAdministrador')

@section('title', 'Registro Estudiante y Tutor')

@section('content')
<div class="container mt-4">
    <h2>Registro Combinado</h2>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('registroCombinado.registrar') }}" method="POST">
        @csrf

        {{-- ================= TUTOR ================= --}}
        <h4>Datos del Tutor</h4>
        <div class="row">
            <div class="col-md-6">
                <label>Nombre</label>
                <input type="text" name="tutor_nombre" class="form-control" value="{{ old('tutor_nombre') }}" required>
            </div>
            <div class="col-md-6">
                <label>Apellido</label>
                <input type="text" name="tutor_apellido" class="form-control" value="{{ old('tutor_apellido') }}" required>
            </div>
            <div class="col-md-4">
                <label>Género</label>
                <select name="tutor_genero" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <option value="M" {{ old('tutor_genero')=='M'?'selected':'' }}>Masculino</option>
                    <option value="F" {{ old('tutor_genero')=='F'?'selected':'' }}>Femenino</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="tutor_fecha_nacimiento" class="form-control" value="{{ old('tutor_fecha_nacimiento') }}" required>
            </div>
            <div class="col-md-4">
                <label>Celular</label>
                <input type="text" name="tutor_celular" class="form-control" value="{{ old('tutor_celular') }}" required>
            </div>
            <div class="col-md-12">
                <label>Dirección</label>
                <input type="text" name="tutor_direccion" class="form-control" value="{{ old('tutor_direccion') }}" required>
            </div>
            <div class="col-md-4">
                    <label>Parentesco</label>
                    <select name="tutor_parentesco" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="Padre" {{ old('tutor_parentesco') == 'Padre' ? 'selected' : '' }}>Padre</option>
                        <option value="Madre" {{ old('tutor_parentesco') == 'Madre' ? 'selected' : '' }}>Madre</option>
                        <option value="Hermano/a" {{ old('tutor_parentesco') == 'Hermano/a' ? 'selected' : '' }}>Hermano/a</option>
                        <option value="Tío/a" {{ old('tutor_parentesco') == 'Tío/a' ? 'selected' : '' }}>Tío/a</option>
                        <option value="Abuelo/a" {{ old('tutor_parentesco') == 'Abuelo/a' ? 'selected' : '' }}>Abuelo/a</option>
                        <option value="Otro" {{ old('tutor_parentesco') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
         
            <div class="col-md-4">
                <label>NIT</label>
                <input type="text" name="tutor_nit" class="form-control" value="{{ old('tutor_nit') }}">
            </div>
            <div class="col-md-6">
                <label>Nombre Factura</label>
                <input type="text" name="tutor_nombre_factura" class="form-control" value="{{ old('tutor_nombre_factura') }}">
            </div>

        <div class="row">
            <div class="col-md-6">
                <label>Correo</label>
                <input type="email" name="tutor_email" class="form-control" required>
            </div>

        </div>

        </div>

        <hr>
        {{-- ================= ESTUDIANTE ================= --}}
        <h4>Datos del Estudiante</h4>
        <div class="row">
            <div class="col-md-6">
                <label>Nombre</label>
                <input type="text" name="estudiante_nombre" class="form-control" value="{{ old('estudiante_nombre') }}" required>
            </div>
            <div class="col-md-6">
                <label>Apellido</label>
                <input type="text" name="estudiante_apellido" class="form-control" value="{{ old('estudiante_apellido') }}" required>
            </div>
            <div class="col-md-4">
                <label>Género</label>
                <select name="estudiante_genero" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <option value="M" {{ old('estudiante_genero')=='M'?'selected':'' }}>Masculino</option>
                    <option value="F" {{ old('estudiante_genero')=='F'?'selected':'' }}>Femenino</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="estudiante_fecha_nacimiento" class="form-control" value="{{ old('estudiante_fecha_nacimiento') }}" required>
            </div>
            <div class="col-md-4">
                <label>Celular</label>
                <input type="text" name="estudiante_celular" class="form-control" value="{{ old('estudiante_celular') }}" required>
            </div>
            <div class="col-md-12">
                <label>Dirección</label>
                <input type="text" name="estudiante_direccion" class="form-control" value="{{ old('estudiante_direccion') }}" required>
            </div>
            <div class="col-md-6">
                <label>Código de Estudiante</label>
                <input type="text" name="codigo_estudiante" class="form-control" value="{{ old('codigo_estudiante') }}" required>
            </div>
            <div class="col-md-6">
                <label>Programa</label>
                <select name="programa" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach ($programas as $p)
                        <option value="{{ $p->Id_programas }}" {{ old('programa')==$p->Id_programas?'selected':'' }}>
                            {{ $p->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        <div class="col-md-6">
            <label>Sucursal</label>
            <select name="sucursal" class="form-control" required>
                <option value="">Seleccione...</option>
                @forelse ($sucursales as $s)
                    <option value="{{ $s->Id_Sucursales }}" {{ old('sucursal') == $s->Id_Sucursales ? 'selected' : '' }}>
                        {{ $s->Nombre }}
                    </option>
                @empty
                    <option value="">No hay sucursales registradas</option>
                @endforelse
            </select>
        </div>
        
        </div>


        <hr>
        {{-- ================= PLAN DE PAGOS ================= --}}
        <h4>Plan de Pagos</h4>
        <div class="row">
            <div class="col-md-4">
                <label>Matrícula (Bs)</label>
                <input type="number" step="0.01" name="Monto_matricula" id="Monto_matricula" class="form-control" value="{{ old('Monto_matricula') }}">
            </div>
            <div class="col-md-4">
                <label>Matrícula en cuántas partes</label>
                <select name="Partes_matricula" class="form-control">
                    <option value="1" {{ old('Partes_matricula') == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('Partes_matricula') == '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ old('Partes_matricula') == '3' ? 'selected' : '' }}>3</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Precio del Programa (Bs)</label>
                <input type="number" step="0.01" id="Precio_programa" class="form-control" >
            </div>
            <div class="col-md-4">
                <label>Nro de cuotas</label>
                <input type="number" name="Nro_cuotas" id="nro_cuotas" class="form-control" value="{{ old('Nro_cuotas') }}" required>
            </div>
            <div class="col-md-4">
                <label>Monto total</label>
                <input type="number" step="0.01" name="Monto_total" id="Monto_total" class="form-control" value="{{ old('Monto_total') }}" required>
            </div>
            <div class="col-md-4">
                <label>Descuento (%)</label>
                <input type="number" step="0.01" name="tutor_descuento" id="tutor_descuento" class="form-control" value="{{ old('tutor_descuento') }}">
            </div>
            <div class="col-md-4">
                <label>Descuento aplicado (Bs)</label>
                <input type="number" step="0.01" id="Descuento_aplicado" class="form-control"
                    value=""
                    readonly>
            </div>
            <div class="col-md-4">
                <label>Total con descuento aplicado (Bs)</label>
                <input type="number" step="0.01" id="Total_con_descuento" class="form-control"
                    value=""
                    readonly>
            </div>
            <div class="col-md-4">
                <label>Fecha plan de pagos</label>
                <input type="date" name="fecha_plan_pagos" class="form-control" 
                    value="{{ old('fecha_plan_pagos', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="row mt-3">
            {{-- <div class="col-md-4">
                <label>Estado del plan</label>
                <input type="text" name="Estado_plan" class="form-control" value="{{ old('Estado_plan', 'Pendiente') }}" required>
            </div> --}}
            <input type="hidden" name="Estado_plan" value="Pendiente">
        </div>
        <input type="hidden" name="Id_programas" value="{{ old('programa') }}">
        <hr>
        {{-- ========== CUOTAS GENERADAS AUTOMÁTICAMENTE ========== --}}
        <h5>Cuotas generadas automáticamente</h5>
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla-cuotas-auto">
                <thead>
                    <tr>
                        <th>Nro de cuota</th>
                        <th>Fecha vencimiento</th>
                        <th>Monto cuota</th>
                        <th>Estado cuota</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Las filas se llenan por JS --}}
                </tbody>
            </table>
        </div>
        <!-- Agrega este div oculto para los inputs de cuotas generadas -->
        <div id="cuotas-auto-hidden-inputs"></div>
        <hr>

        <button type="submit" class="btn btn-primary mt-4">Registrar</button>
    </form>
</div>

@endsection

<script src="{{ asset('js/administrador/tutorEstudianteAdministrador.js') }}"></script>
<script>
    // Pasar los datos de PHP a JavaScript e inicializar
    document.addEventListener('DOMContentLoaded', function() {
        const programas = @json($programas->map(fn($p) => [
            'Id_programas' => $p->Id_programas,
            'Nombre' => $p->Nombre,
            'Costo' => $p->Costo
        ]));
        
        // Inicializar la clase
        new RegistroCombinado(programas);
    });
</script>

