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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @php
        $arrayProgramas = [];
        foreach($programas as $p) {
            $arrayProgramas[] = [
                'Id_programas' => $p->Id_programas,
                'Nombre' => $p->Nombre,
                'Costo' => $p->Costo
            ];
        }
    @endphp
    const programas = @json($arrayProgramas);

    const programaSelect = document.querySelector('select[name="programa"]');
    const precioInput = document.getElementById('Precio_programa');
    const matriculaInput = document.getElementById('Monto_matricula');
    const cuotasInput = document.getElementById('nro_cuotas');
    const montoTotalInput = document.getElementById('Monto_total');
    const descuentoInput = document.getElementById('Descuento_aplicado');
    const descuentoPorcentajeInput = document.getElementById('tutor_descuento');
    const totalConDescuentoInput = document.getElementById('Total_con_descuento');
    const tablaCuotas = document.getElementById('tabla-cuotas-auto').querySelector('tbody');
    const cuotasAutoHiddenInputs = document.getElementById('cuotas-auto-hidden-inputs');

    function getDescuentoPorcentaje() {
        let val = descuentoPorcentajeInput ? parseFloat(descuentoPorcentajeInput.value) : 0;
        return isNaN(val) ? 0 : val;
    }

    function actualizarPrecio() {
        const selectedId = programaSelect.value;
        const programa = programas.find(p => String(p.Id_programas) === String(selectedId));
        if (programa) {
            precioInput.value = programa.Costo;
        } else {
            precioInput.value = '';
        }
        actualizarMontoTotal();
    }

    function actualizarMontoTotal() {
        const matricula = parseFloat(matriculaInput.value) || 0;
        const precioPrograma = parseFloat(precioInput.value) || 0;
        const cuotas = parseInt(cuotasInput.value) || 0;
        // Monto total es matrícula + (precio del programa * cuotas) SIN descuento
        const total = Math.round(matricula + (precioPrograma * cuotas));

        montoTotalInput.value = total > 0 ? total : '';

        // Descuento: solo al precio del programa (por cuota * cuotas)
        const descuentoPorcentaje = getDescuentoPorcentaje();
        // Redondeo a la decena más próxima (por ejemplo, 82.32 -> 82.40)
        function roundToNearestTenCents(num) {
            return (Math.ceil(num * 100 / 10) * 10 / 100).toFixed(2);
        }
        const descuentoRaw = (precioPrograma * (descuentoPorcentaje / 100)) * cuotas;
        const descuento = cuotas > 0 && !isNaN(descuentoPorcentaje) ? roundToNearestTenCents(descuentoRaw) : '0.00';
        // Total con descuento aplicado
        const descuentoTotalRaw = total - descuentoRaw;
        const descuentoTotal = total > 0 && !isNaN(descuentoPorcentaje) ? roundToNearestTenCents(descuentoTotalRaw) : '0.00';

        descuentoInput.value = descuento;
        totalConDescuentoInput.value = descuentoTotal;
    }

    function addMonths(date, months) {
        // Suma meses y mantiene el día si es posible
        const d = new Date(date);
        const day = d.getDate();
        d.setMonth(d.getMonth() + months);
        // Si el mes siguiente no tiene ese día, ajusta al último día del mes
        if (d.getDate() < day) {
            d.setDate(0);
        }
        return d;
    }

    function pad(num) {
        return num < 10 ? '0' + num : num;
    }

    function formatDate(date) {
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
    }

    function generarCuotas() {
        const cuotas = parseInt(cuotasInput.value) || 0;
        const totalConDescuento = parseFloat(totalConDescuentoInput.value) || 0;
        const matricula = parseFloat(matriculaInput.value) || 0;
        const partesMatricula = parseInt(document.querySelector('select[name="Partes_matricula"]').value) || 1;
        const fechaBase = document.querySelector('input[name="fecha_plan_pagos"]').value || (new Date()).toISOString().slice(0,10);

        tablaCuotas.innerHTML = '';
        cuotasAutoHiddenInputs.innerHTML = '';
        if (cuotas > 0 && totalConDescuento > 0) {
            // Calcular cuánto de la matrícula va en cada cuota (solo en las primeras "partesMatricula" cuotas)
            let matriculaPorCuota = partesMatricula > 0 ? Math.floor((matricula / partesMatricula) * 100) / 100 : 0;
            let matriculaRestante = matricula - (matriculaPorCuota * partesMatricula);

            // El resto del total (sin matrícula) se distribuye entre todas las cuotas
            let restoTotal = totalConDescuento - matricula;
            let restoPorCuota = cuotas > 0 ? Math.floor((restoTotal / cuotas) * 100) / 100 : 0;
            let restoRestante = restoTotal - (restoPorCuota * cuotas);

            for (let i = 0; i < cuotas; i++) {
                // Sumar matrícula solo a las primeras "partesMatricula" cuotas
                let montoCuota = restoPorCuota;
                if (i < partesMatricula) {
                    montoCuota += matriculaPorCuota;
                    // Ajustar la última cuota de matrícula por redondeo
                    if (i === partesMatricula - 1) {
                        montoCuota += matriculaRestante;
                    }
                }
                // Ajustar la última cuota general por redondeo
                if (i === cuotas - 1) {
                    montoCuota += restoRestante;
                }
                const fechaVenc = addMonths(new Date(fechaBase), i);
                const fechaStr = formatDate(fechaVenc);
                const row = `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${fechaStr}</td>
                        <td>${montoCuota.toFixed(2)}</td>
                        <td>Pendiente</td>
                    </tr>
                `;
                tablaCuotas.insertAdjacentHTML('beforeend', row);

                // Agrega los inputs ocultos fuera de la tabla para que se envíen correctamente
                cuotasAutoHiddenInputs.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="cuotas_auto[${i}][Nro_de_cuota]" value="${i + 1}">
                    <input type="hidden" name="cuotas_auto[${i}][Fecha_vencimiento]" value="${fechaStr}">
                    <input type="hidden" name="cuotas_auto[${i}][Monto_cuota]" value="${montoCuota.toFixed(2)}">
                    <input type="hidden" name="cuotas_auto[${i}][Estado_cuota]" value="Pendiente">
                `);
            }
        }
    }

    function initEventListeners() {
        if (programaSelect) {
            programaSelect.addEventListener('change', actualizarPrecio);
        }
        if (matriculaInput) {
            matriculaInput.addEventListener('input', actualizarMontoTotal);
        }
        if (cuotasInput) {
            cuotasInput.addEventListener('input', function() {
                actualizarMontoTotal();
                generarCuotas();
            });
        }
        if (descuentoPorcentajeInput) {
            descuentoPorcentajeInput.addEventListener('input', actualizarMontoTotal);
        }
        if (totalConDescuentoInput) {
            totalConDescuentoInput.addEventListener('input', generarCuotas);
        }
        document.querySelector('input[name="fecha_plan_pagos"]').addEventListener('change', generarCuotas);
        document.querySelector('select[name="Partes_matricula"]').addEventListener('change', function() {
            actualizarMontoTotal();
            generarCuotas();
        });

        // También actualiza cuotas cuando cambian los valores que afectan el total
        [matriculaInput, precioInput, descuentoPorcentajeInput].forEach(function(input) {
            if (input) input.addEventListener('input', function() {
                actualizarMontoTotal();
                generarCuotas();
            });
        });
    }

    initEventListeners();

    // Inicializa valores al cargar
    actualizarPrecio();
    generarCuotas();
});
</script>
@endpush

