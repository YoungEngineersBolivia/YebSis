$(document).ready(function() {
    // DATOS DE PROGRAMAS Y TALLERES DESDE EL SERVIDOR
    const programas = window.programas || [];
    const talleres = window.talleres || [];
    
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
        
        // IMPORTANTE: Limpiar atributos required según el tipo
        if (tipo === 'programa') {
            // Para programas: requeridos los campos del plan de pagos
            $('#nro_cuotas').attr('required', true);
            $('#Monto_total').attr('required', true);
            $('#Total_con_descuento').attr('required', true);
            $('#fecha_plan_pagos').attr('required', true);
            
            // Para programas: NO requeridos los campos de taller
            $('#monto_taller_descuento').removeAttr('required');
            $('#fecha_pago_taller').removeAttr('required');
            $('#metodo_pago_taller').removeAttr('required');
            $('#estado_pago_taller').removeAttr('required');
            
            console.log('Cargando programas...');
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
            // Para talleres: requeridos los campos de pago directo
            $('#monto_taller_descuento').attr('required', true);
            $('#fecha_pago_taller').attr('required', true);
            $('#metodo_pago_taller').attr('required', true);
            $('#estado_pago_taller').attr('required', true);
            
            // Para talleres: NO requeridos los campos de programa
            $('#nro_cuotas').removeAttr('required');
            $('#Monto_total').removeAttr('required');
            $('#Total_con_descuento').removeAttr('required');
            $('#fecha_plan_pagos').removeAttr('required');
            
            console.log('Cargando talleres...');
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

    // ===== EVENTOS PARA RECALCULAR DESCUENTOS CUANDO CAMBIEN =====
    $('#descuento_estudiante').on('input change', function() {
        const tipo = $('#tipo_seleccion').val();
        if (tipo === 'programa') {
            calcularDescuentoPrograma();
        }
    });

    $('#descuento_taller').on('input change', function() {
        const tipo = $('#tipo_seleccion').val();
        if (tipo === 'taller') {
            calcularDescuentoTaller();
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
        
        // Validar que el descuento esté en rango válido
        if (descuento < 0) {
            $('#descuento_estudiante').val(0);
            return;
        }
        if (descuento > 100) {
            $('#descuento_estudiante').val(100);
            return;
        }
        
        const totalConDescuento = montoTotal - (montoTotal * descuento / 100);
        
        $('#Total_con_descuento').val(totalConDescuento.toFixed(2));
        generarCuotas();
    }

    // ===== CALCULAR DESCUENTO PARA TALLER =====
    function calcularDescuentoTaller() {
        const montoTaller = parseFloat($('#monto_taller').val()) || 0;
        const descuento = parseFloat($('#descuento_taller').val()) || 0;
        
        // Validar que el descuento esté en rango válido
        if (descuento < 0) {
            $('#descuento_taller').val(0);
            return;
        }
        if (descuento > 100) {
            $('#descuento_taller').val(100);
            return;
        }
        
        const montoConDescuento = montoTaller - (montoTaller * descuento / 100);
        
        $('#monto_taller_descuento').val(montoConDescuento.toFixed(2));
        
        // Actualizar descripción automáticamente
        const nombrePrograma = $('#programa_taller option:selected').text();
        if (nombrePrograma && nombrePrograma !== 'Seleccione...') {
            $('#descripcion_taller').val(`Pago ${nombrePrograma.split(' - ')[0]}`);
        }
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
        
        // Limpiar valores cuando se ocultan las secciones
        $('#plan_pagos_programa input').val('');
        $('#pago_taller input').val('');
        $('#tabla-cuotas-programa tbody').html('');
        $('#cuotas-programa-hidden-inputs').html('');
    }

    // ===== VALIDACIÓN DEL FORMULARIO ANTES DEL ENVÍO =====
    $('#formInscripcion').submit(function(e) {
        const tipo = $('#tipo_seleccion').val();
        
        // Validación específica según tipo
        if (tipo === 'programa') {
            const nroCuotas = $('#nro_cuotas').val();
            const totalConDescuento = $('#Total_con_descuento').val();
            
            if (!nroCuotas || nroCuotas < 1) {
                e.preventDefault();
                alert('El número de cuotas debe ser mayor a 0');
                $('#nro_cuotas').focus();
                return false;
            }
            
            if (!totalConDescuento || totalConDescuento <= 0) {
                e.preventDefault();
                alert('El total con descuento debe ser mayor a 0');
                return false;
            }
            
        } else if (tipo === 'taller') {
            const montoTallerDescuento = $('#monto_taller_descuento').val();
            const fechaPago = $('input[name="fecha_pago_taller"]').val();
            const metodoPago = $('select[name="metodo_pago_taller"]').val();
            const estadoPago = $('select[name="estado_pago_taller"]').val();
            
            if (!montoTallerDescuento || montoTallerDescuento <= 0) {
                e.preventDefault();
                alert('El monto del taller debe ser mayor a 0');
                $('#monto_taller_descuento').focus();
                return false;
            }
            
            if (!fechaPago) {
                e.preventDefault();
                alert('Debe seleccionar una fecha de pago');
                $('input[name="fecha_pago_taller"]').focus();
                return false;
            }
            
            if (!metodoPago) {
                e.preventDefault();
                alert('Debe seleccionar un método de pago');
                $('select[name="metodo_pago_taller"]').focus();
                return false;
            }
            
            if (!estadoPago) {
                e.preventDefault();
                alert('Debe seleccionar un estado de pago');
                $('select[name="estado_pago_taller"]').focus();
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