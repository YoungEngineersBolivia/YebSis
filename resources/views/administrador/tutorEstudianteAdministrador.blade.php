@extends('/administrador/baseAdministrador')

@section('title', 'Registro Estudiante y Tutor')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <label><b>Buscar Tutor</b></label>
        <input type="text" id="buscarTutor" class="form-control" placeholder="Escriba el nombre del tutor">
        <ul id="listaTutor" class="list-group position-absolute" style="z-index:1000;"></ul>
    </div>
</div>

{{-- Cargar todos los tutores en un input oculto como JSON --}}
<input type="hidden" id="tutoresJson" value='@json($tutores)'>

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
                <input type="hidden" name="tutor_id_existente" id="tutor_id_existente" value="">
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
                <label>Número de referencia</label>
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
        
      <div class="col-md-6">
    <label>Profesor</label>
    <select name="profesor" class="form-control" required>
        <option value="">Seleccione...</option>
        @foreach ($profesores as $prof)
            <option value="{{ $prof->Id_profesores }}"
                {{ old('profesor') == $prof->Id_profesores ? 'selected' : '' }}>
                {{ $prof->persona->Nombre }} {{ $prof->persona->Apellido }}
            </option>
        @endforeach
    </select>
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
    class RegistroCombinado {
    constructor(programas) {
        this.programas = programas;
        this.initElements();
        this.initEventListeners();
        this.init();
    }

    initElements() {
        this.programaSelect = document.querySelector('select[name="programa"]');
        this.precioInput = document.getElementById('Precio_programa');
        this.matriculaInput = document.getElementById('Monto_matricula');
        this.cuotasInput = document.getElementById('nro_cuotas');
        this.montoTotalInput = document.getElementById('Monto_total');
        this.descuentoInput = document.getElementById('Descuento_aplicado');
        this.descuentoPorcentajeInput = document.getElementById('tutor_descuento');
        this.totalConDescuentoInput = document.getElementById('Total_con_descuento');
        this.tablaCuotas = document.getElementById('tabla-cuotas-auto').querySelector('tbody');
        this.cuotasAutoHiddenInputs = document.getElementById('cuotas-auto-hidden-inputs');
        this.partesMatriculaSelect = document.querySelector('select[name="Partes_matricula"]');
        this.fechaPlanInput = document.querySelector('input[name="fecha_plan_pagos"]');
    }

    getDescuentoPorcentaje() {
        let val = this.descuentoPorcentajeInput ? parseFloat(this.descuentoPorcentajeInput.value) : 0;
        return isNaN(val) ? 0 : val;
    }

    actualizarPrecio() {
        const selectedId = this.programaSelect.value;
        const programa = this.programas.find(p => String(p.Id_programas) === String(selectedId));
        
        if (programa) {
            this.precioInput.value = programa.Costo;
        } else {
            this.precioInput.value = '';
        }
        this.actualizarMontoTotal();
    }

    actualizarMontoTotal() {
        const matricula = parseFloat(this.matriculaInput.value) || 0;
        const precioPrograma = parseFloat(this.precioInput.value) || 0;
        const cuotas = parseInt(this.cuotasInput.value) || 0;
        
        // Monto total es matrícula + (precio del programa * cuotas) SIN descuento
        const total = matricula + (precioPrograma * cuotas);
        this.montoTotalInput.value = total > 0 ? total.toFixed(2) : '';

        // Descuento: solo al precio del programa (por cuota * cuotas)
        const descuentoPorcentaje = this.getDescuentoPorcentaje();
        
        const descuentoRaw = (precioPrograma * (descuentoPorcentaje / 100)) * cuotas;
        const descuento = cuotas > 0 && descuentoPorcentaje > 0 ? descuentoRaw.toFixed(2) : '0.00';
        
        // Total con descuento aplicado
        const totalConDescuento = (total - descuentoRaw).toFixed(2);

        this.descuentoInput.value = descuento;
        this.totalConDescuentoInput.value = totalConDescuento;
        
        // Generar cuotas después de actualizar totales
        this.generarCuotas();
    }

    addMonths(date, months) {
        const d = new Date(date);
        const day = d.getDate();
        d.setMonth(d.getMonth() + months);
        if (d.getDate() < day) {
            d.setDate(0);
        }
        return d;
    }

    pad(num) {
        return num < 10 ? '0' + num : num;
    }

    formatDate(date) {
        return date.getFullYear() + '-' + this.pad(date.getMonth() + 1) + '-' + this.pad(date.getDate());
    }

    generarCuotas() {
        const cuotas = parseInt(this.cuotasInput.value) || 0;
        const totalConDescuento = parseFloat(this.totalConDescuentoInput.value) || 0;
        const matricula = parseFloat(this.matriculaInput.value) || 0;
        const partesMatricula = parseInt(this.partesMatriculaSelect.value) || 1;
        const fechaBase = this.fechaPlanInput.value || (new Date()).toISOString().slice(0,10);

        this.tablaCuotas.innerHTML = '';
        this.cuotasAutoHiddenInputs.innerHTML = '';
        
        if (cuotas > 0 && totalConDescuento > 0) {
            // Calcular cuánto de la matrícula va en cada cuota
            let matriculaPorCuota = partesMatricula > 0 ? (matricula / partesMatricula) : 0;
            
            // El resto del total (sin matrícula) se distribuye entre todas las cuotas
            let restoTotal = totalConDescuento - matricula;
            let restoPorCuota = cuotas > 0 ? (restoTotal / cuotas) : 0;

            for (let i = 0; i < cuotas; i++) {
                let montoCuota = restoPorCuota;
                
                // Agregar parte de matrícula solo a las primeras cuotas
                if (i < partesMatricula) {
                    montoCuota += matriculaPorCuota;
                }
                
                // Ajustar última cuota para compensar redondeos
                if (i === cuotas - 1) {
                    let sumaAcumulada = 0;
                    for (let j = 0; j < cuotas - 1; j++) {
                        let tempMonto = restoPorCuota;
                        if (j < partesMatricula) {
                            tempMonto += matriculaPorCuota;
                        }
                        sumaAcumulada += tempMonto;
                    }
                    montoCuota = totalConDescuento - sumaAcumulada;
                }
                
                const fechaVenc = this.addMonths(new Date(fechaBase), i);
                const fechaStr = this.formatDate(fechaVenc);
                
                const row = `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${fechaStr}</td>
                        <td>${montoCuota.toFixed(2)}</td>
                        <td>Pendiente</td>
                    </tr>
                `;
                this.tablaCuotas.insertAdjacentHTML('beforeend', row);

                this.cuotasAutoHiddenInputs.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="cuotas_auto[${i}][Nro_de_cuota]" value="${i + 1}">
                    <input type="hidden" name="cuotas_auto[${i}][Fecha_vencimiento]" value="${fechaStr}">
                    <input type="hidden" name="cuotas_auto[${i}][Monto_cuota]" value="${montoCuota.toFixed(2)}">
                    <input type="hidden" name="cuotas_auto[${i}][Estado_cuota]" value="Pendiente">
                `);
            }
        }
    }

    initEventListeners() {
        if (this.programaSelect) {
            this.programaSelect.addEventListener('change', () => this.actualizarPrecio());
        }
        
        if (this.matriculaInput) {
            this.matriculaInput.addEventListener('input', () => this.actualizarMontoTotal());
        }
        
        if (this.cuotasInput) {
            this.cuotasInput.addEventListener('input', () => this.actualizarMontoTotal());
        }
        
        if (this.descuentoPorcentajeInput) {
            this.descuentoPorcentajeInput.addEventListener('input', () => this.actualizarMontoTotal());
        }
        
        if (this.partesMatriculaSelect) {
            this.partesMatriculaSelect.addEventListener('change', () => this.actualizarMontoTotal());
        }
        
        if (this.fechaPlanInput) {
            this.fechaPlanInput.addEventListener('change', () => this.generarCuotas());
        }
    }

    init() {
        // Inicializa valores al cargar si hay un programa pre-seleccionado
        if (this.programaSelect && this.programaSelect.value) {
            this.actualizarPrecio();
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.getElementById('buscarTutor');
    const listaTutor = document.getElementById('listaTutor');
    const tutores = JSON.parse(document.getElementById('tutoresJson').value);

    inputBuscar.addEventListener('input', function() {
        const valor = this.value.toLowerCase();
        listaTutor.innerHTML = '';

        if (valor.length === 0) return; // si no hay texto, no mostrar nada

        const coincidencias = tutores.filter(t => 
            t.Nombre.toLowerCase().includes(valor) || t.Apellido.toLowerCase().includes(valor)
        );

        coincidencias.forEach(t => {
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'list-group-item-action');
            li.textContent = t.Nombre + ' ' + t.Apellido + ' - ' + t.Correo;
            li.style.cursor = 'pointer';

            li.addEventListener('click', () => {
                // Rellenar los campos del formulario
                document.querySelector('input[name="tutor_nombre"]').value = t.Nombre;
                document.querySelector('input[name="tutor_apellido"]').value = t.Apellido;
                document.querySelector('select[name="tutor_genero"]').value = t.Genero;
                document.querySelector('input[name="tutor_fecha_nacimiento"]').value = t.Fecha_nacimiento;
                document.querySelector('input[name="tutor_celular"]').value = t.Celular;
                document.querySelector('input[name="tutor_direccion"]').value = t.Direccion_domicilio;
                document.querySelector('input[name="tutor_email"]').value = t.Correo;
                document.querySelector('select[name="tutor_parentesco"]').value = t.Parentesco;
                document.querySelector('input[name="tutor_nit"]').value = t.Nit || '';
                document.querySelector('input[name="tutor_nombre_factura"]').value = t.Nombre_factura || '';
                document.querySelector('input[name="tutor_descuento"]').value = t.Descuento || '';

                // Guardar ID del tutor existente
                document.getElementById('tutor_id_existente').value = t.Id_tutores;

                // Ocultar la lista
                listaTutor.innerHTML = '';
                inputBuscar.value = t.Nombre + ' ' + t.Apellido;
            });


            listaTutor.appendChild(li);
        });
    });

    // Ocultar lista si se hace click fuera
    document.addEventListener('click', function(e) {
        if (!listaTutor.contains(e.target) && e.target !== inputBuscar) {
            listaTutor.innerHTML = '';
        }
    });
});


// Exportar para uso global
window.RegistroCombinado = RegistroCombinado;

</script>