@extends('administrador/baseAdministrador')

@section('title', 'Inscripción de Estudiante a Nuevo Programa/Taller')

@section('content')
<div class="container mt-4">
    <h2>Inscribir Estudiante Existente</h2>

    {{-- AGREGAR META TAG PARA CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


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

    <form action="{{ route('inscripcionEstudiante.registrar') }}" method="POST" id="formInscripcion">
        @csrf

        {{-- ================= BUSCAR ESTUDIANTE ================= --}}
        <h4>Buscar Estudiante</h4>
        <div class="row">
            <div class="col-md-6">
                <label>Buscar por Nombre</label>
                <input type="text" id="nombre_buscar" class="form-control" placeholder="Ingrese nombre del estudiante">
                <button type="button" id="btn_buscar_nombre" class="btn btn-info mt-2">Buscar por Nombre</button>
            </div>
        </div>

        {{-- Lista de resultados de búsqueda --}}
        <div id="resultados_busqueda" class="mt-3" style="display: none;">
            <h5>Resultados de búsqueda:</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Programa Actual</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tabla_resultados">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================= DATOS DEL ESTUDIANTE SELECCIONADO ================= --}}
        <div id="datos_estudiante" style="display: none;">
            <hr>
            <h4>Datos del Estudiante Seleccionado</h4>
            <div class="row">
                <div class="col-md-4">
                    <label>Nombre Completo</label>
                    <input type="text" id="estudiante_nombre_completo" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label>Código</label>
                    <input type="text" id="estudiante_codigo" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label>Programa Actual</label>
                    <input type="text" id="estudiante_programa_actual" class="form-control" readonly>
                </div>
            </div>
            <input type="hidden" id="Id_estudiantes" name="Id_estudiantes">
        </div>

        {{-- ================= SELECCIÓN DE NUEVO PROGRAMA/TALLER ================= --}}
        <div id="seleccion_programa" style="display: none;">
            <hr>
            <h4>Seleccionar Nuevo Programa o Taller</h4>
            <div class="row">
                <div class="col-md-6">
                    <label>Tipo</label>
                    <select id="tipo_seleccion" name="tipo_seleccion" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <option value="programa">Nuevo Programa Regular</option>
                        <option value="taller">Taller de Temporada</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Programa/Taller</label>
                    <select id="programa_taller" name="programa_taller" class="form-control" required>
                        <option value="">Primero seleccione el tipo...</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Costo Original</label>
                    <input type="number" step="0.01" id="costo_programa" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label>Sucursal</label>
                    <select name="sucursal" id="sucursal" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->Id_Sucursales }}">{{ $s->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Profesor (Opcional)</label>
                    <select name="profesor" id="profesor" class="form-control">
                        <option value="">Seleccione...</option>
                        @foreach ($profesores as $p)
                            <option value="{{ $p->Id_profesores }}">
                                {{ $p->persona->Nombre }} {{ $p->persona->Apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ================= PLAN DE PAGOS (SOLO PARA PROGRAMAS) ================= --}}
        <div id="plan_pagos_programa" style="display: none;">
            <hr>
            <h4>Plan de Pagos - Programa</h4>
            <div class="row">
                <div class="col-md-4">
                    <label>Matrícula (Bs)</label>
                    <input type="number" step="0.01" name="Monto_matricula" id="Monto_matricula" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label>Nro de cuotas</label>
                    <input type="number" name="Nro_cuotas" id="nro_cuotas" class="form-control" min="1" required>
                </div>
                <div class="col-md-4">
                    <label>Monto total</label>
                    <input type="number" step="0.01" name="Monto_total" id="Monto_total" class="form-control" required readonly>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Descuento (%) - Estudiante Activo</label>
                    <input type="number" step="0.01" id="descuento_estudiante" class="form-control" value="15" min="0" max="100">
                </div>
                <div class="col-md-4">
                    <label>Total con descuento (Bs)</label>
                    <input type="number" step="0.01" id="Total_con_descuento" name="Total_con_descuento" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label>Fecha plan de pagos</label>
                    <input type="date" name="fecha_plan_pagos" id="fecha_plan_pagos" class="form-control" value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>

            {{-- Cuotas generadas --}}
            <h5 class="mt-3">Cuotas generadas automáticamente</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="tabla-cuotas-programa">
                    <thead>
                        <tr>
                            <th>Nro de cuota</th>
                            <th>Fecha vencimiento</th>
                            <th>Monto cuota</th>
                            <th>Estado cuota</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div id="cuotas-programa-hidden-inputs"></div>
        </div>

        {{-- ================= PAGO DIRECTO (SOLO PARA TALLERES) ================= --}}
        <div id="pago_taller" style="display: none;">
            <hr>
            <h4>Pago de Taller</h4>
            <div class="row">
                <div class="col-md-6">
                    <label>Descripción del Pago</label>
                    <input type="text" name="descripcion_taller" id="descripcion_taller" class="form-control" placeholder="Pago Taller...">
                </div>
                <div class="col-md-6">
                    <label>Monto Original del Taller (Bs)</label>
                    <input type="number" step="0.01" name="monto_taller" id="monto_taller" class="form-control" readonly>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Descuento Estudiante Activo (%)</label>
                    <input type="number" step="0.01" id="descuento_taller" class="form-control" value="20" min="0" max="100">
                </div>
                <div class="col-md-4">
                    <label>Monto con Descuento (Bs) *</label>
                    <input type="number" step="0.01" id="monto_taller_descuento" name="monto_taller_descuento" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Fecha de Pago *</label>
                    <input type="date" name="fecha_pago_taller" class="form-control" value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Método de Pago *</label>
                    <select name="metodo_pago_taller" class="form-control">
                        <option value="">Seleccione método de pago...</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="qr">QR</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Estado del Pago *</label>
                    <select name="estado_pago_taller" class="form-control">
                        <option value="">Seleccione estado...</option>
                        <option value="pagado">Pagado</option>
                        <option value="pendiente">Pendiente</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" id="btn_inscribir" class="btn btn-primary" style="display: none;">
                Inscribir Estudiante
            </button>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    window.programas = @json($programas ?? []);
    window.talleres = @json($talleres ?? []);
</script>
<script src="{{ asset('js/administrador/inscripcionEstudiante.js') }}"></script>

@endsection