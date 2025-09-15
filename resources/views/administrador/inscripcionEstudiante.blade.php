@extends('administrador/baseAdministrador')

@section('title', 'Inscripción de Estudiante a Nuevo Programa/Taller')

@section('content')
<div class="container mt-4">
    <h2>Inscribir Estudiante Existente</h2>

    {{-- AGREGAR META TAG PARA CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- DEBUG INFO TEMPORAL - REMOVER DESPUÉS --}}
    <div style="background: #f0f0f0; padding: 10px; margin: 20px 0; border: 1px solid #ccc;">
        <h5>DEBUG INFO:</h5>
        <p><strong>Programas:</strong> {{ isset($programas) ? count($programas) : 'No definido' }}</p>
        <p><strong>Talleres:</strong> {{ isset($talleres) ? count($talleres) : 'No definido' }}</p>
        
        @if(isset($programas) && count($programas) > 0)
            <p><strong>Primer programa:</strong> {{ $programas->first()->Nombre ?? 'Sin nombre' }}</p>
        @endif
        
        @if(isset($talleres) && count($talleres) > 0)
            <p><strong>Primer taller:</strong> {{ $talleres->first()->Nombre ?? 'Sin nombre' }}</p>
        @endif
    </div>

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
                <label>Código de Estudiante</label>
                <input type="text" id="codigo_estudiante_buscar" class="form-control" placeholder="Ingrese código de estudiante">
                <button type="button" id="btn_buscar" class="btn btn-info mt-2">Buscar</button>
            </div>
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
                    <input type="number" step="0.01" id="descuento_estudiante" class="form-control" value="15" readonly>
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
                    <input type="number" step="0.01" id="descuento_taller" class="form-control" value="20" readonly>
                </div>
                <div class="col-md-4">
                    <label>Monto con Descuento (Bs)</label>
                    <input type="number" step="0.01" id="monto_taller_descuento" name="monto_taller_descuento" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label>Fecha de Pago</label>
                    <input type="date" name="fecha_pago_taller" class="form-control" value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>Método de Pago</label>
                    <select name="metodo_pago_taller" class="form-control">
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="qr">QR</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Estado del Pago</label>
                    <select name="estado_pago_taller" class="form-control">
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
$(document).ready(function() {
    // DATOS DE PROGRAMAS Y TALLERES DESDE EL SERVIDOR
    const programas = @json($programas ?? []);
    const talleres = @json($talleres ?? []);
    
    // DEBUG: Mostrar en consola lo que llega del servidor
    console.log('=== DEBUG DATOS ===');
    console.log('Programas cargados:', programas);
    console.log('Talleres cargados:', talleres);
    console.log('Total programas:', programas.length);
    console.log('Total talleres:', talleres.length);
    
    // CONFIGURACIÓN CSRF PARA AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
        }
    });

    // ===== BÚSQUEDA POR CÓDIGO =====
    $('#btn_buscar').click(function() {
        const codigo = $('#codigo_estudiante_buscar').val().trim();
        if (!codigo) {
            alert('Ingrese un código de estudiante');
            return;
        }

        $.ajax({
            url: '{{ route("inscripcionEstudiante.buscarCodigo") }}',
            method: 'POST',
            data: { codigo: codigo },
            success: function(response) {
                console.log('Respuesta búsqueda por código:', response);
                if (response.success) {
                    mostrarEstudianteSeleccionado(response.estudiante);
                    $('#resultados_busqueda').hide();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error búsqueda por código:', error);
                alert('Error al buscar estudiante: ' + error);
            }
        });
    });

    // ===== BÚSQUEDA POR NOMBRE =====
    $('#btn_buscar_nombre').click(function() {
        const nombre = $('#nombre_buscar').val().trim();
        if (!nombre) {
            alert('Ingrese un nombre');
            return;
        }

        $.ajax({
            url: '{{ route("inscripcionEstudiante.buscarNombre") }}',
            method: 'POST',
            data: { nombre: nombre },
            success: function(response) {
                console.log('Respuesta búsqueda por nombre:', response);
                if (response.success) {
                    mostrarResultadosBusqueda(response.estudiantes);
                } else {
                    alert(response.message);
                    $('#resultados_busqueda').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error búsqueda por nombre:', error);
                alert('Error al buscar estudiantes: ' + error);
            }
        });
    });

    // ===== MOSTRAR RESULTADOS DE BÚSQUEDA MÚLTIPLES =====
    function mostrarResultadosBusqueda(estudiantes) {
        let html = '';
        estudiantes.forEach(function(est) {
            html += `
                <tr>
                    <td>${est.codigo}</td>
                    <td>${est.nombre_completo}</td>
                    <td>${est.programa_actual}</td>
                    <td>${est.estado}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success seleccionar-estudiante" 
                                data-id="${est.Id_estudiantes}"
                                data-codigo="${est.codigo}"
                                data-nombre="${est.nombre_completo}"
                                data-programa="${est.programa_actual}">
                            Seleccionar
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#tabla_resultados').html(html);
        $('#resultados_busqueda').show();
        $('#datos_estudiante').hide();
        $('#seleccion_programa').hide();
    }

    // ===== SELECCIONAR ESTUDIANTE DE LA TABLA =====
    $(document).on('click', '.seleccionar-estudiante', function() {
        const estudiante = {
            Id_estudiantes: $(this).data('id'),
            codigo: $(this).data('codigo'),
            nombre_completo: $(this).data('nombre'),
            programa_actual: $(this).data('programa')
        };
        
        mostrarEstudianteSeleccionado(estudiante);
        $('#resultados_busqueda').hide();
    });

    // ===== MOSTRAR DATOS DEL ESTUDIANTE SELECCIONADO =====
    function mostrarEstudianteSeleccionado(estudiante) {
        $('#Id_estudiantes').val(estudiante.Id_estudiantes);
        $('#estudiante_codigo').val(estudiante.codigo);
        $('#estudiante_nombre_completo').val(estudiante.nombre_completo);
        $('#estudiante_programa_actual').val(estudiante.programa_actual);
        
        $('#datos_estudiante').show();
        $('#seleccion_programa').show();
        resetearFormulario();
    }

    // ===== CAMBIO DE TIPO (PROGRAMA/TALLER) - CORREGIDO =====
    $('#tipo_seleccion').change(function() {
        const tipo = $(this).val();
        console.log('Tipo seleccionado:', tipo);
        
        // Limpiar select de programa/taller
        $('#programa_taller').html('<option value="">Seleccione...</option>');
        $('#costo_programa').val('');
        
        if (tipo === 'programa') {
            console.log('Cargando programas...');
            console.log('Programas disponibles:', programas);
            
            if (programas && programas.length > 0) {
                let options = '<option value="">Seleccione un programa...</option>';
                programas.forEach(function(programa) {
                    options += `<option value="${programa.Id_programas}" data-costo="${programa.Costo}">${programa.Nombre} - $${programa.Costo}</option>`;
                });
                $('#programa_taller').html(options);
            } else {
                $('#programa_taller').html('<option value="">No hay programas disponibles</option>');
            }
            
        } else if (tipo === 'taller') {
            console.log('Cargando talleres...');
            console.log('Talleres disponibles:', talleres);
            
            if (talleres && talleres.length > 0) {
                let options = '<option value="">Seleccione un taller...</option>';
                talleres.forEach(function(taller) {
                    options += `<option value="${taller.Id_programas}" data-costo="${taller.Costo}">${taller.Nombre} - $${taller.Costo}</option>`;
                });
                $('#programa_taller').html(options);
            } else {
                $('#programa_taller').html('<option value="">No hay talleres disponibles</option>');
            }
        }
        
        ocultarSeccionesFormulario();
    });

    // ===== SELECCIÓN DE PROGRAMA/TALLER ESPECÍFICO =====
    $('#programa_taller').change(function() {
        const costo = $(this).find(':selected').data('costo') || 0;
        const tipo = $('#tipo_seleccion').val();
        
        console.log('Programa/Taller seleccionado, costo:', costo, 'tipo:', tipo);
        
        $('#costo_programa').val(costo);
        
        if (tipo === 'programa') {
            $('#Monto_total').val(costo);
            calcularDescuentoPrograma();
            $('#plan_pagos_programa').show();
            $('#pago_taller').hide();
        } else if (tipo === 'taller') {
            $('#monto_taller').val(costo);
            calcularDescuentoTaller();
            $('#pago_taller').show();
            $('#plan_pagos_programa').hide();
        }
        
        if (costo > 0) {
            $('#btn_inscribir').show();
        }
    });

    // ===== CALCULAR CUOTAS CUANDO CAMBIE EL NÚMERO O LA FECHA =====
    $('#nro_cuotas, #fecha_plan_pagos').on('input change', function() {
        generarCuotas();
    });

    // ===== CALCULAR DESCUENTO PARA PROGRAMA =====
    function calcularDescuentoPrograma() {
        const montoTotal = parseFloat($('#Monto_total').val()) || 0;
        const descuento = parseFloat($('#descuento_estudiante').val()) || 0;
        const totalConDescuento = montoTotal - (montoTotal * descuento / 100);
        
        $('#Total_con_descuento').val(totalConDescuento.toFixed(2));
        generarCuotas();
    }

    // ===== CALCULAR DESCUENTO PARA TALLER =====
    function calcularDescuentoTaller() {
        const montoTaller = parseFloat($('#monto_taller').val()) || 0;
        const descuento = parseFloat($('#descuento_taller').val()) || 0;
        const montoConDescuento = montoTaller - (montoTaller * descuento / 100);
        
        $('#monto_taller_descuento').val(montoConDescuento.toFixed(2));
        
        // Actualizar descripción automáticamente
        const nombrePrograma = $('#programa_taller option:selected').text();
        $('#descripcion_taller').val(`Pago ${nombrePrograma.split(' - ')[0]}`);
    }

    // ===== GENERAR CUOTAS AUTOMÁTICAMENTE =====
    function generarCuotas() {
        const nroCuotas = parseInt($('#nro_cuotas').val()) || 0;
        const totalConDescuento = parseFloat($('#Total_con_descuento').val()) || 0;
        const fechaPlan = $('#fecha_plan_pagos').val();
        
        if (nroCuotas > 0 && totalConDescuento > 0 && fechaPlan) {
            const montoCuota = (totalConDescuento / nroCuotas).toFixed(2);
            let html = '';
            let htmlInputs = '';
            
            for (let i = 1; i <= nroCuotas; i++) {
                const fechaVencimiento = new Date(fechaPlan);
                fechaVencimiento.setMonth(fechaVencimiento.getMonth() + i);
                const fechaFormateada = fechaVencimiento.toISOString().split('T')[0];
                
                html += `
                    <tr>
                        <td>${i}</td>
                        <td>${fechaFormateada}</td>
                        <td>${montoCuota}</td>
                        <td>Pendiente</td>
                    </tr>
                `;
                
                htmlInputs += `
                    <input type="hidden" name="cuotas_programa[${i-1}][Nro_de_cuota]" value="${i}">
                    <input type="hidden" name="cuotas_programa[${i-1}][Fecha_vencimiento]" value="${fechaFormateada}">
                    <input type="hidden" name="cuotas_programa[${i-1}][Monto_cuota]" value="${montoCuota}">
                `;
            }
            
            $('#tabla-cuotas-programa tbody').html(html);
            $('#cuotas-programa-hidden-inputs').html(htmlInputs);
        }
    }

    // ===== FUNCIONES AUXILIARES =====
    function resetearFormulario() {
        $('#tipo_seleccion').val('');
        $('#programa_taller').html('<option value="">Primero seleccione el tipo...</option>');
        $('#costo_programa').val('');
        ocultarSeccionesFormulario();
        $('#btn_inscribir').hide();
    }

    function ocultarSeccionesFormulario() {
        $('#plan_pagos_programa').hide();
        $('#pago_taller').hide();
        $('#btn_inscribir').hide();
    }

    // ===== VALIDACIÓN DEL FORMULARIO ANTES DEL ENVÍO =====
    $('#formInscripcion').submit(function(e) {
        const tipo = $('#tipo_seleccion').val();
        
        if (tipo === 'programa') {
            const nroCuotas = $('#nro_cuotas').val();
            if (!nroCuotas || nroCuotas < 1) {
                e.preventDefault();
                alert('El número de cuotas debe ser mayor a 0');
                return false;
            }
        } else if (tipo === 'taller') {
            const montoTaller = $('#monto_taller').val();
            if (!montoTaller || montoTaller <= 0) {
                e.preventDefault();
                alert('El monto del taller debe ser mayor a 0');
                return false;
            }
        }
        
        return true;
    });

    // ===== EVENTO ENTER EN CAMPOS DE BÚSQUEDA =====
    $('#codigo_estudiante_buscar').keypress(function(e) {
        if (e.which === 13) { // Enter
            e.preventDefault();
            $('#btn_buscar').click();
        }
    });

    $('#nombre_buscar').keypress(function(e) {
        if (e.which === 13) { // Enter
            e.preventDefault();
            $('#btn_buscar_nombre').click();
        }
    });
});
</script>

@endsection