<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Programa;
use App\Models\Sucursal;
use App\Models\Profesor;
use App\Models\PlanesPago;
use App\Models\Cuota;
use App\Models\EstudianteTaller;
use App\Models\PagoTaller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InscripcionEstudianteController extends Controller
{
    public function mostrarFormulario()
    {
        try {
            $programas = Programa::where('Tipo', 'LIKE', '%programa%')
                                ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                                ->get();

            $talleres = Programa::where('Tipo', 'LIKE', '%taller%')
                               ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                               ->get();

            if ($programas->isEmpty() && $talleres->isEmpty()) {
                Log::info('No se encontraron programas por tipo, obteniendo todos los activos');
                $todosLosProgramas = Programa::select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                                            ->get();

                $programas = $todosLosProgramas;
                $talleres = collect(); 
            }

            $sucursales = Sucursal::select('Id_Sucursales', 'Nombre')->get();
            $profesores = Profesor::with(['persona' => function($query) {
                $query->select('Id_personas', 'Nombre', 'Apellido');
            }])->select('Id_profesores', 'Id_personas')->get();

            Log::info('Programas encontrados: ' . $programas->count());
            Log::info('Talleres encontrados: ' . $talleres->count());
            Log::info('Sucursales encontradas: ' . $sucursales->count());
            Log::info('Profesores encontrados: ' . $profesores->count());

            return view('administrador.inscripcionEstudiante', compact('programas', 'talleres', 'sucursales', 'profesores'));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de inscripción: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al cargar el formulario: ' . $e->getMessage()]);
        }
    }

    public function buscarPorCodigo(Request $request)
    {
        try {
            $codigo = $request->input('codigo');
            
            if (empty($codigo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe ingresar un código de estudiante'
                ]);
            }
            
            $estudiante = Estudiante::with(['persona:Id_personas,Nombre,Apellido', 'programa:Id_programas,Nombre'])
                                   ->where('Cod_estudiante', $codigo)
                                   ->first();

            if ($estudiante) {
                return response()->json([
                    'success' => true,
                    'estudiante' => [
                        'Id_estudiantes' => $estudiante->Id_estudiantes,
                        'codigo' => $estudiante->Cod_estudiante,
                        'nombre_completo' => ($estudiante->persona->Nombre ?? '') . ' ' . ($estudiante->persona->Apellido ?? ''),
                        'programa_actual' => $estudiante->programa->Nombre ?? 'Sin programa'
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al buscar estudiante por código: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    public function buscarPorNombre(Request $request)
    {
        try {
            $nombre = $request->input('nombre');
            
            if (empty($nombre)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe ingresar un nombre'
                ]);
            }
            
            $estudiantes = Estudiante::with(['persona:Id_personas,Nombre,Apellido', 'programa:Id_programas,Nombre'])
                                    ->whereHas('persona', function($query) use ($nombre) {
                                        $query->where('Nombre', 'LIKE', "%{$nombre}%")
                                              ->orWhere('Apellido', 'LIKE', "%{$nombre}%");
                                    })
                                    ->limit(10) 
                                    ->get();

            if ($estudiantes->count() > 0) {
                $resultados = $estudiantes->map(function($estudiante) {
                    return [
                        'Id_estudiantes' => $estudiante->Id_estudiantes,
                        'codigo' => $estudiante->Cod_estudiante,
                        'nombre_completo' => ($estudiante->persona->Nombre ?? '') . ' ' . ($estudiante->persona->Apellido ?? ''),
                        'programa_actual' => $estudiante->programa->Nombre ?? 'Sin programa'
                    ];
                });

                return response()->json([
                    'success' => true,
                    'estudiantes' => $resultados
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se encontraron estudiantes con ese nombre'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al buscar estudiante por nombre: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
    }

    public function obtenerPorTipo(Request $request)
    {
        try {
            $tipo = $request->input('tipo');
            Log::info("Buscando tipo: $tipo");
            
            if ($tipo === 'programa') {
                $items = Programa::where(function($query) {
                        $query->where('Tipo', 'LIKE', '%programa%')
                              ->orWhere('Tipo', 'LIKE', '%Programa%')
                              ->orWhere('Tipo', 'LIKE', '%PROGRAMA%')
                              ->orWhereNull('Tipo'); 
                    })
                    ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                    ->get();
                
            } elseif ($tipo === 'taller') {
                $items = Programa::where(function($query) {
                        $query->where('Tipo', 'LIKE', '%taller%')
                              ->orWhere('Tipo', 'LIKE', '%Taller%')
                              ->orWhere('Tipo', 'LIKE', '%TALLER%');
                    })
                    ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                    ->get();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo no válido. Debe ser "programa" o "taller"'
                ]);
            }

            if ($items->isEmpty()) {
                Log::warning("No se encontraron items para tipo: $tipo. Buscando todos los programas activos...");
                
                $items = Programa::select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                       ->get();
                       
                Log::info("Programas encontrados: " . $items->count());
            }

            Log::info("Items encontrados para $tipo:", $items->toArray());

            return response()->json([
                'success' => true,
                'items' => $items,
                'message' => $items->isEmpty() ? 'No se encontraron elementos disponibles' : null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener programas por tipo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function inscribir(Request $request)
    {
        // Validación básica
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'tipo_seleccion' => 'required|in:programa,taller',
            'programa_taller' => 'required|exists:programas,Id_programas',
            'sucursal' => 'required|exists:sucursales,Id_Sucursales'
        ]);

        DB::beginTransaction();
        
        try {
            $estudiante = Estudiante::findOrFail($request->Id_estudiantes);
            $programa = Programa::findOrFail($request->programa_taller);

            Log::info("Inscribiendo estudiante {$estudiante->Id_estudiantes} en {$request->tipo_seleccion}: {$programa->Nombre}");

            if ($request->tipo_seleccion === 'programa') {
                $result = $this->inscribirPrograma($request, $estudiante, $programa);
            } else {
                $result = $this->inscribirTaller($request, $estudiante, $programa);
            }

            DB::commit();
            return $result;

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al inscribir:', $e->errors());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al inscribir estudiante: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al inscribir: ' . $e->getMessage()])->withInput();
        }
    }

    private function inscribirPrograma($request, $estudiante, $programa)
    {
        $request->validate([
            'Nro_cuotas' => 'required|integer|min:1',
            'Monto_total' => 'required|numeric|min:0',
            'fecha_plan_pagos' => 'required|date',
            'Total_con_descuento' => 'required|numeric|min:0'
        ]);

        // Verificar si ya tiene un programa activo
        if ($estudiante->Id_programas) {
            $programaActual = Programa::find($estudiante->Id_programas);
            if ($programaActual && stripos($programaActual->Tipo, 'programa') !== false) {
                return back()->withErrors(['error' => 'El estudiante ya tiene un programa regular activo.']);
            }
        }

        // Actualizar datos del estudiante
        $estudiante->update([
            'Id_programas' => $programa->Id_programas,
            'Id_sucursales' => $request->sucursal,
            'Id_profesores' => $request->profesor ?: null,
            'Fecha_estado' => now()->format('Y-m-d')
        ]);

        // Crear plan de pagos
        $planPago = PlanesPago::create([
            'Monto_total' => $request->Total_con_descuento,
            'Nro_cuotas' => $request->Nro_cuotas,
            'fecha_plan_pagos' => $request->fecha_plan_pagos,
            'Estado_plan' => 'Activo',
            'Id_programas' => $programa->Id_programas,
            'Id_estudiantes' => $estudiante->Id_estudiantes,
        ]);

        // Crear cuotas
        if ($request->has('cuotas_programa') && is_array($request->cuotas_programa)) {
            foreach ($request->cuotas_programa as $cuotaData) {
                if (isset($cuotaData['Nro_de_cuota'], $cuotaData['Fecha_vencimiento'], $cuotaData['Monto_cuota'])) {
                    Cuota::create([
                        'Nro_de_cuota' => $cuotaData['Nro_de_cuota'],
                        'Fecha_vencimiento' => $cuotaData['Fecha_vencimiento'],
                        'Monto_cuota' => $cuotaData['Monto_cuota'],
                        'Estado_cuota' => 'Pendiente',
                        'Id_planes_pagos' => $planPago->Id_planes_pagos,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Estudiante inscrito exitosamente al programa: ' . $programa->Nombre);
    }

    private function inscribirTaller($request, $estudiante, $programa)
    {
        // CORREGIDO: Validación específica para talleres (sin Nro_cuotas)
        $request->validate([
            'monto_taller_descuento' => 'required|numeric|min:0',
            'fecha_pago_taller' => 'required|date',
            'metodo_pago_taller' => 'required|string',
            'estado_pago_taller' => 'required|in:pagado,pendiente',
            'descripcion_taller' => 'nullable|string'
        ]);

        // Verificar si ya está inscrito en este taller
        $yaInscrito = EstudianteTaller::where('Id_estudiantes', $estudiante->Id_estudiantes)
                                ->where('Id_programas', $programa->Id_programas)
                                ->exists();

        if ($yaInscrito) {
            return back()->withErrors(['error' => 'El estudiante ya está inscrito en este taller.']);
        }

        // Crear inscripción al taller
        $inscripcionTaller = EstudianteTaller::create([
            'Id_estudiantes' => $estudiante->Id_estudiantes,
            'Id_programas' => $programa->Id_programas,
            'Fecha_inscripcion' => now()->format('Y-m-d'),
        ]);

        // Crear pago del taller
        PagoTaller::create([
            'Descripcion' => $request->descripcion_taller ?: ('Pago ' . $programa->Nombre),
            'Monto_pago' => $request->monto_taller_descuento,
            'Fecha_pago' => $request->fecha_pago_taller,
            'Metodo_pago' => $request->metodo_pago_taller,
            'Id_estudiantes_talleres' => $inscripcionTaller->Id_estudiantes_talleres
        ]);

        return redirect()->back()->with('success', 'Estudiante inscrito exitosamente al taller: ' . $programa->Nombre);
    }

    // MÉTODO TEMPORAL PARA DEBUG - ELIMINAR DESPUÉS
    public function debugProgramas()
    {
        try {
            $programas = Programa::all();
            $tipos = Programa::distinct()->pluck('Tipo');
            
            Log::info('=== DEBUG PROGRAMAS COMPLETO ===');
            Log::info('Total programas en BD: ' . $programas->count());
            Log::info('Tipos encontrados: ' . $tipos->toJson());
            
            foreach ($programas as $programa) {
                Log::info("ID: {$programa->Id_programas}, Nombre: {$programa->Nombre}, Tipo: '{$programa->Tipo}'");
            }
            
            return response()->json([
                'total' => $programas->count(),
                'tipos' => $tipos,
                'programas' => $programas->map(function($p) {
                    return [
                        'id' => $p->Id_programas,
                        'nombre' => $p->Nombre,
                        'tipo' => $p->Tipo,
                        'costo' => $p->Costo
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en debug: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
