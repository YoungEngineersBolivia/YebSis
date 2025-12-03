<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Programa;
use App\Models\Sucursal;
use App\Models\Rol;
use App\Models\Tutores;
use App\Models\Usuario;
use App\Models\Profesor;
use App\Mail\ClaveGeneradaAdmin;
use Illuminate\Support\Facades\Mail;
use App\Models\PlanesPago;
use App\Models\Pago;
use App\Models\Cuota;

class RegistroCombinadoController extends Controller
{
    // Mostrar formulario
    public function mostrarFormulario()
    {
        $programas = Programa::all();
        $sucursales = Sucursal::all();
        $profesores = Profesor::with('persona')
            ->whereHas('persona', function ($q) {$q->where('Id_roles', 2); }) ->get();

        $tutores = Tutores::with('persona', 'usuario', 'estudiantes.persona')->get()->map(function($t){
            return [
                'Id_tutores' => $t->Id_tutores,
                'Nombre' => $t->persona->Nombre,
                'Apellido' => $t->persona->Apellido,
                'Genero' => $t->persona->Genero,
                'Direccion_domicilio' => $t->persona->Direccion_domicilio,
                'Fecha_nacimiento' => $t->persona->Fecha_nacimiento,
                'Celular' => $t->persona->Celular,
                'Correo' => $t->usuario->Correo,
                'Parentesco' => $t->Parentesco,
                'Descuento' => $t->Descuento,
                'Nit' => $t->Nit,
                'Nombre_factura' => $t->Nombre_factura,
                'estudiantes' => $t->estudiantes->map(function($e) {
                    return [
                        'Id_estudiantes' => $e->Id_estudiantes,
                        'Cod_estudiante' => $e->Cod_estudiante,
                        'Nombre' => $e->persona->Nombre,
                        'Apellido' => $e->persona->Apellido,
                        'Genero' => $e->persona->Genero,
                        'Direccion_domicilio' => $e->persona->Direccion_domicilio,
                        'Fecha_nacimiento' => $e->persona->Fecha_nacimiento,
                        'Celular' => $e->persona->Celular,
                    ];
                })
            ];
        });

        return view('administrador.tutorEstudianteAdministrador', compact('programas', 'sucursales', 'profesores', 'tutores')); 
    }

    // Registrar tutor y estudiante
    public function registrar(Request $request)
    {
        // Función helper para capitalizar nombres
        $capitalizarNombre = function($texto) {
            return ucwords(strtolower(trim($texto)));
        };
        // Validación dinámica del código de estudiante
        $estudianteIdExistente = $request->input('estudiante_id_existente');
        
        $validationRules = [
            'tutor_email' => 'required|email',
            'tutor_nombre' => 'required|string|max:255',
            'tutor_apellido' => 'required|string|max:255',
            'tutor_genero' => 'required|string',
            'tutor_fecha_nacimiento' => 'required|date|after:1900-01-01|before:2100-01-01',
            'tutor_celular' => 'required|string|max:255',
            'tutor_direccion' => 'required|string',
            'tutor_parentesco' => 'required|string|max:255',
            'tutor_descuento' => 'nullable|string|max:255',
            'tutor_nit' => 'nullable|string|max:255',
            'tutor_nombre_factura' => 'nullable|string|max:255',
            'estudiante_nombre' => 'required|string|max:255',
            'estudiante_apellido' => 'required|string|max:255',
            'estudiante_genero' => 'required|string',
            'estudiante_fecha_nacimiento' => 'required|date|after:1900-01-01|before:2100-01-01',
            'estudiante_celular' => 'required|string|max:255',
            'estudiante_direccion' => 'required|string',
            'programa' => 'required|exists:programas,Id_programas',
            'sucursal' => 'required|exists:sucursales,Id_sucursales',
            'profesor' => 'nullable|exists:profesores,Id_profesores',
        ];
        
        // Si es estudiante existente, no validar código (ya existe)
        // Si es nuevo, validar que sea único
        if (!$estudianteIdExistente) {
            $validationRules['codigo_estudiante'] = 'required|string|unique:estudiantes,Cod_estudiante';
        } else {
            $validationRules['codigo_estudiante'] = 'required|string';
        }
        
        $customMessages = [
            'tutor_email.required' => 'El correo del tutor es obligatorio.',
            'tutor_email.email' => 'El correo del tutor no tiene un formato válido.',
            'tutor_nombre.required' => 'El nombre del tutor es obligatorio.',
            'tutor_apellido.required' => 'El apellido del tutor es obligatorio.',
            'tutor_genero.required' => 'Debe seleccionar el género del tutor.',
            'tutor_fecha_nacimiento.required' => 'La fecha de nacimiento del tutor es obligatoria.',
            'tutor_celular.required' => 'El celular del tutor es obligatorio.',
            'tutor_direccion.required' => 'La dirección del tutor es obligatoria.',
            'tutor_parentesco.required' => 'El parentesco es obligatorio.',
            'estudiante_nombre.required' => 'El nombre del estudiante es obligatorio.',
            'estudiante_apellido.required' => 'El apellido del estudiante es obligatorio.',
            'estudiante_genero.required' => 'Debe seleccionar el género del estudiante.',
            'estudiante_fecha_nacimiento.required' => 'La fecha de nacimiento del estudiante es obligatoria.',
            'estudiante_celular.required' => 'El celular de referencia del estudiante es obligatorio.',
            'estudiante_direccion.required' => 'La dirección del estudiante es obligatoria.',
            'codigo_estudiante.required' => 'El código del estudiante es obligatorio.',
            'codigo_estudiante.unique' => 'Este código de estudiante ya está registrado. Intente con otro.',
            'programa.required' => 'Debe seleccionar un programa.',
            'sucursal.required' => 'Debe seleccionar una sucursal.',
        ];
        
        $request->validate($validationRules, $customMessages);

        try {
            // Roles
            $rolEstudiante = Rol::where('Nombre_rol', 'Estudiante')->first();
            $rolTutor = Rol::where('Nombre_rol', 'Tutor')->first();

            if (!$rolEstudiante || !$rolTutor) {
                return back()->withErrors(['error' => 'Roles no encontrados en el sistema.']);
            }

            // ---------------------------
            // Tutor existente o nuevo
            // ---------------------------
            $tutorIdExistente = $request->input('tutor_id_existente');

            if ($tutorIdExistente) {
                $tutor = Tutores::find($tutorIdExistente);
                $personaTutor = $tutor->persona;
            } else {
                // Validar correo tutor
                if (Usuario::where('Correo', $request->tutor_email)->exists()) {
                    return back()->withErrors(['tutor_email' => 'El correo del tutor ya está en uso.']);
                }

                // Crear persona y usuario tutor
                $personaTutor = Persona::create([
                    'Nombre' => $capitalizarNombre($request->tutor_nombre),
                    'Apellido' => $capitalizarNombre($request->tutor_apellido),
                    'Genero' => $request->tutor_genero,
                    'Direccion_domicilio' => $capitalizarNombre($request->tutor_direccion),
                    'Fecha_nacimiento' => $request->tutor_fecha_nacimiento,
                    'Fecha_registro' => now()->format('Y-m-d'),
                    'Celular' => $request->tutor_celular,
                    'Id_roles' => $rolTutor->Id_roles,
                ]);

                $contraseñaTemporal = Str::random(8);

                $usuarioTutor = Usuario::create([
                    'Correo' => $request->tutor_email,
                    'Contrasenia' => bcrypt($contraseñaTemporal),
                    'Id_personas' => $personaTutor->Id_personas,
                ]);

                Mail::to($request->tutor_email)->send(new ClaveGeneradaAdmin(
                    $request->tutor_nombre,
                    $request->tutor_email,
                    $contraseñaTemporal
                ));

                $tutor = Tutores::create([
                    'Descuento' => $request->tutor_descuento ?? '0',
                    'Parentesco' => $request->tutor_parentesco,
                    'Nit' => $request->tutor_nit ?? 'S/N',
                    'Nombre_factura' => $request->tutor_nombre_factura ?? ($capitalizarNombre($request->tutor_nombre) . ' ' . $capitalizarNombre($request->tutor_apellido)),
                    'Id_personas' => $personaTutor->Id_personas,
                    'Id_usuarios' => $usuarioTutor->Id_usuarios,
                ]);
            }

            // ---------------------------
            // Crear o usar estudiante existente
            // ---------------------------
            $estudianteIdExistente = $request->input('estudiante_id_existente');
            
            if ($estudianteIdExistente) {
                // Estudiante existente - solo actualizar programa y crear plan
                $estudiante = Estudiante::find($estudianteIdExistente);
                
                // Actualizar programa si es diferente
                if ($estudiante->Id_programas != $request->programa) {
                    $estudiante->Id_programas = $request->programa;
                    $estudiante->save();
                }
            } else {
                // Crear nuevo estudiante
                $personaEstudiante = Persona::create([
                    'Nombre' => $capitalizarNombre($request->estudiante_nombre),
                    'Apellido' => $capitalizarNombre($request->estudiante_apellido),
                    'Genero' => $request->estudiante_genero,
                    'Direccion_domicilio' => $capitalizarNombre($request->estudiante_direccion),
                    'Fecha_nacimiento' => $request->estudiante_fecha_nacimiento,
                    'Fecha_registro' => now()->format('Y-m-d'),
                    'Celular' => $request->estudiante_celular,
                    'Id_roles' => $rolEstudiante->Id_roles,
                ]);

                $estudiante = Estudiante::create([
                    'Cod_estudiante' => $request->codigo_estudiante,
                    'Estado' => 'Activo',
                    'Fecha_estado' => now()->format('Y-m-d'),
                    'Id_personas' => $personaEstudiante->Id_personas,
                    'Id_programas' => $request->programa,
                    'Id_sucursales' => $request->sucursal,
                    'Id_profesores' => $request->profesor,
                    'Id_tutores' => $tutor->Id_tutores,
                ]);
            }

            // ---------------------------
            // Plan de pago
            // ---------------------------
            $planPago = PlanesPago::create([
                'Monto_total' => $request->Monto_total,
                'Nro_cuotas' => $request->Nro_cuotas,
                'fecha_plan_pagos' => $request->fecha_plan_pagos,
                'Estado_plan' => $request->Estado_plan,
                'Id_programas' => $request->programa,
                'Id_estudiantes' => $estudiante->Id_estudiantes,
            ]);

            // ---------------------------
            // Pagos y cuotas
            // ---------------------------
            if ($request->filled('Descripcion') || $request->filled('Monto_pago') || $request->filled('Fecha_pago')) {
                Pago::create([
                    'Descripcion' => $request->Descripcion,
                    'Monto_pago' => $request->Monto_pago,
                    'Fecha_pago' => $request->Fecha_pago,
                    'Id_planes_pagos' => $planPago->Id_planes_pagos,
                ]);
            }

            if ($request->has('cuotas_auto') && is_array($request->cuotas_auto)) {
                foreach ($request->cuotas_auto as $cuota) {
                    Cuota::create([
                        'Nro_de_cuota' => $cuota['Nro_de_cuota'],
                        'Fecha_vencimiento' => $cuota['Fecha_vencimiento'],
                        'Monto_cuota' => $cuota['Monto_cuota'],
                        'Estado_cuota' => $cuota['Estado_cuota'],
                        'Id_planes_pagos' => $planPago->Id_planes_pagos,
                    ]);
                }
            }

            if ($request->filled('Nro_de_cuota') || $request->filled('Fecha_vencimiento') || $request->filled('Monto_cuota')) {
                Cuota::create([
                    'Nro_de_cuota' => $request->Nro_de_cuota,
                    'Fecha_vencimiento' => $request->Fecha_vencimiento,
                    'Monto_cuota' => $request->Monto_cuota,
                    'Estado_cuota' => $request->Estado_cuota,
                    'Id_planes_pagos' => $planPago->Id_planes_pagos,
                ]);
            }

            return redirect()->back()->with('success', 'Estudiante, tutor y plan de pagos registrados correctamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar: ' . $e->getMessage()])->withInput();
        }
    }
}
