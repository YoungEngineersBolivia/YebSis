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
            // Debug: Veamos qué tipos de programas existen en la BD
            Log::info('Tipos de programas en BD:', Programa::distinct()->pluck('Tipo')->toArray());
            
            // Obtener TODOS los programas primero para debug
            $todosLosProgramas = Programa::where('Estado', true)
                               ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                               ->get();
            
            Log::info('Todos los programas:', $todosLosProgramas->toArray());
            
            // Intentar diferentes variaciones del campo Tipo
            $programas = Programa::where(function($query) {
                    $query->where('Tipo', 'Programa')
                          ->orWhere('Tipo', 'programa')
                          ->orWhere('Tipo', 'PROGRAMA');
                })
                ->where('Estado', true)
                ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                ->get();
            
            $talleres = Programa::where(function($query) {
                    $query->where('Tipo', 'Taller')
                          ->orWhere('Tipo', 'taller')
                          ->orWhere('Tipo', 'TALLER');
                })
                ->where('Estado', true)
                ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                ->get();

            // Si no encuentra nada con el campo Tipo, usar todos los programas
            if ($programas->isEmpty() && $talleres->isEmpty()) {
                Log::warning('No se encontraron programas con campo Tipo, usando todos los programas');
                $programas = $todosLosProgramas; // Usar todos como programas
                $talleres = collect(); // Colección vacía para talleres
            }
            
            $sucursales = Sucursal::select('Id_Sucursales', 'Nombre')->get();
            $profesores = Profesor::with(['persona' => function($query) {
                $query->select('Id_personas', 'Nombre', 'Apellido');
            }])->select('Id_profesores', 'Id_personas')->get();

            return view('administrador.inscripcionEstudiante', compact('programas', 'talleres', 'sucursales', 'profesores'));
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de inscripción: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al cargar el formulario: ' . $e->getMessage()]);
        }
    }

    // Buscar estudiante por código
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
                        'programa_actual' => $estudiante->programa->Nombre ?? 'Sin programa',
                        'estado' => $estudiante->Estado ?? 'Sin estado'
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

    // Buscar estudiante por nombre
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
                        'programa_actual' => $estudiante->programa->Nombre ?? 'Sin programa',
                        'estado' => $estudiante->Estado ?? 'Sin estado'
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

    // CORREGIDO: Obtener programas o talleres según el tipo
    public function obtenerPorTipo(Request $request)
    {
        try {
            $tipo = $request->input('tipo');
            Log::info("Buscando tipo: $tipo");
            
            if ($tipo === 'programa') {
                // Buscar con diferentes variaciones
                $items = Programa::where(function($query) {
                        $query->where('Tipo', 'Programa')
                              ->orWhere('Tipo', 'programa')
                              ->orWhere('Tipo', 'PROGRAMA');
                    })
                    ->where('Estado', true)
                    ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                    ->get();
                
                // Si no encuentra nada, intentar sin filtro de tipo
                if ($items->isEmpty()) {
                    Log::warning('No se encontraron programas con campo Tipo, buscando todos activos');
                    $items = Programa::where('Estado', true)
                           ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                           ->get();
                }
                
            } elseif ($tipo === 'taller') {
                $items = Programa::where(function($query) {
                        $query->where('Tipo', 'Taller')
                              ->orWhere('Tipo', 'taller')
                              ->orWhere('Tipo', 'TALLER');
                    })
                    ->where('Estado', true)
                    ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                    ->get();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo no válido'
                ]);
            }

            Log::info("Items encontrados para $tipo:", $items->toArray());

            return response()->json([
                'success' => true,
                'items' => $items
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener programas por tipo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    // Método para debug - eliminar después de solucionar
    public function debugProgramas()
    {
        try {
            $programas = Programa::all();
            
            Log::info('=== DEBUG PROGRAMAS ===');
            Log::info('Total programas: ' . $programas->count());
            
            foreach ($programas as $programa) {
                Log::info("ID: {$programa->Id_programas}, Nombre: {$programa->Nombre}, Tipo: '{$programa->Tipo}', Estado: {$programa->Estado}");
            }
            
            // Verificar estructura de la tabla
            $columns = DB::getSchemaBuilder()->getColumnListing('programas');
            Log::info('Columnas de la tabla programas:', $columns);
            
            return response()->json([
                'total' => $programas->count(),
                'programas' => $programas,
                'columnas' => $columns
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en debug: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // Inscribir estudiante
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

            if ($request->tipo_seleccion === 'programa') {
                // INSCRIPCIÓN A PROGRAMA REGULAR
                $result = $this->inscribirPrograma($request, $estudiante, $programa);
                
            } else {
                // INSCRIPCIÓN A TALLER
                $result = $this->inscribirTaller($request, $estudiante, $programa);
            }

            DB::commit();
            return $result;

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
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

        // Verificar si ya tiene un programa activo del mismo tipo
        if ($estudiante->Estado === 'Activo' && $estudiante->Id_programas) {
            $programaActual = Programa::find($estudiante->Id_programas);
            if ($programaActual && in_array($programaActual->Tipo, ['Programa', 'programa', 'PROGRAMA'])) {
                return back()->withErrors(['error' => 'El estudiante ya tiene un programa regular activo. Complete o cancele el programa actual antes de inscribir a uno nuevo.']);
            }
        }

        // Actualizar datos del estudiante
        $estudiante->update([
            'Id_programas' => $programa->Id_programas,
            'Id_sucursales' => $request->sucursal,
            'Id_profesores' => $request->profesor ?: null,
            'Estado' => 'Activo',
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

        return redirect()->back()->with('success', 'Estudiante inscrito exitosamente al programa: ' . $programa->Nombre . '. Se aplicó un descuento del 15% por ser estudiante activo.');
    }

    private function inscribirTaller($request, $estudiante, $programa)
    {
        $request->validate([
            'monto_taller_descuento' => 'required|numeric|min:0',
            'fecha_pago_taller' => 'required|date',
            'metodo_pago_taller' => 'required|string',
            'estado_pago_taller' => 'required|in:pagado,pendiente',
            'descripcion_taller' => 'nullable|string'
        ]);

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
            'Estado_inscripcion' => 'inscrito',
            'Observaciones' => 'Inscrito por estudiante activo con descuento del 20% aplicado'
        ]);

        // Crear pago del taller
        PagoTaller::create([
            'Descripcion' => $request->descripcion_taller ?: ('Pago ' . $programa->Nombre),
            'Monto_pago' => $request->monto_taller_descuento,
            'Fecha_pago' => $request->fecha_pago_taller,
            'Estado_pago' => $request->estado_pago_taller,
            'Metodo_pago' => $request->metodo_pago_taller,
            'Id_estudiantes_talleres' => $inscripcionTaller->Id_estudiantes_talleres
        ]);

        return redirect()->back()->with('success', 'Estudiante inscrito exitosamente al taller: ' . $programa->Nombre . '. Se aplicó un descuento del 20% por ser estudiante activo.');
    }
}