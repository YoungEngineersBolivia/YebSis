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
            Log::info('=== INICIO mostrarFormulario ===');
            
            // Paso 1: Obtener programas
            Log::info('Paso 1: Obteniendo programas...');
            $programas = Programa::where(function($query) {
                    $query->where('Tipo', 'LIKE', '%programa%')
                          ->orWhereNull('Tipo');
                })
                ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                ->get();
            Log::info('Programas obtenidos: ' . $programas->count());

            // Paso 2: Obtener talleres
            Log::info('Paso 2: Obteniendo talleres...');
            $talleres = Programa::where('Tipo', 'LIKE', '%taller%')
                               ->select('Id_programas', 'Nombre', 'Costo', 'Tipo')
                               ->get();
            Log::info('Talleres obtenidos: ' . $talleres->count());

            // Paso 3: Si no hay clasificación por tipo
            if ($programas->isEmpty() && $talleres->isEmpty()) {
                Log::warning('No se encontraron programas clasificados por tipo, obteniendo todos');
                $programas = Programa::select('Id_programas', 'Nombre', 'Costo', 'Tipo')->get();
                $talleres = collect();
                Log::info('Programas totales: ' . $programas->count());
            }

            // Paso 4: Obtener sucursales
            Log::info('Paso 3: Obteniendo sucursales...');
            $sucursales = Sucursal::select('Id_Sucursales', 'Nombre')->get();
            Log::info('Sucursales obtenidas: ' . $sucursales->count());

            // Paso 5: Obtener profesores SIN relación primero
            Log::info('Paso 4: Obteniendo profesores...');
            $profesores = Profesor::select('Id_profesores', 'Id_personas')->get();
            Log::info('Profesores obtenidos: ' . $profesores->count());
            
            // Paso 6: Cargar relación persona manualmente
            Log::info('Paso 5: Cargando personas de profesores...');
            foreach ($profesores as $profesor) {
                $profesor->load('persona:Id_personas,Nombre,Apellido');
            }
            Log::info('Personas cargadas exitosamente');

            Log::info('=== FIN mostrarFormulario - TODO OK ===');

            return view('administrador.inscripcionEstudiante', compact('programas', 'talleres', 'sucursales', 'profesores'));
            
        } catch (\Exception $e) {
            Log::error('=== ERROR en mostrarFormulario ===');
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('Línea: ' . $e->getLine());
            Log::error('Archivo: ' . $e->getFile());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Mostrar error detallado en desarrollo
            if (config('app.debug')) {
                dd([
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            return back()->withErrors(['error' => 'Error al cargar el formulario. Revise los logs para más detalles.']);
        }
    }

    public function buscarPorCodigo(Request $request)
    {
        try {
            $codigo = trim($request->input('codigo'));
            
            if (empty($codigo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe ingresar un código de estudiante'
                ]);
            }
            
            // Buscar estudiante con sus relaciones
            $estudiante = Estudiante::where('Cod_estudiante', $codigo)->first();

            if (!$estudiante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado'
                ]);
            }

            // Obtener datos relacionados de forma segura
            $persona = Persona::find($estudiante->Id_personas);
            $programa = $estudiante->Id_programas ? Programa::find($estudiante->Id_programas) : null;

            return response()->json([
                'success' => true,
                'estudiante' => [
                    'Id_estudiantes' => $estudiante->Id_estudiantes,
                    'codigo' => $estudiante->Cod_estudiante,
                    'nombre_completo' => ($persona ? $persona->Nombre . ' ' . $persona->Apellido : 'Sin nombre'),
                    'programa_actual' => ($programa ? $programa->Nombre : 'Sin programa'),
                    'estado' => $estudiante->Estado ?? 'Activo'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al buscar estudiante por código: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function buscarPorNombre(Request $request)
    {
        try {
            $nombre = trim($request->input('nombre'));
            
            if (empty($nombre)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe ingresar un nombre'
                ]);
            }
            
            // Buscar estudiantes por nombre o apellido
            $estudiantes = Estudiante::whereHas('persona', function($query) use ($nombre) {
                    $query->where('Nombre', 'LIKE', "%{$nombre}%")
                          ->orWhere('Apellido', 'LIKE', "%{$nombre}%");
                })
                ->with(['persona:Id_personas,Nombre,Apellido'])
                ->limit(10)
                ->get();

            if ($estudiantes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron estudiantes con ese nombre'
                ]);
            }

            $resultados = $estudiantes->map(function($estudiante) {
                $programa = $estudiante->Id_programas ? Programa::find($estudiante->Id_programas) : null;
                
                return [
                    'Id_estudiantes' => $estudiante->Id_estudiantes,
                    'codigo' => $estudiante->Cod_estudiante,
                    'nombre_completo' => ($estudiante->persona ? $estudiante->persona->Nombre . ' ' . $estudiante->persona->Apellido : 'Sin nombre'),
                    'programa_actual' => ($programa ? $programa->Nombre : 'Sin programa'),
                    'estado' => $estudiante->Estado ?? 'Activo'
                ];
            });

            return response()->json([
                'success' => true,
                'estudiantes' => $resultados
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al buscar estudiante por nombre: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function inscribir(Request $request)
    {
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

            Log::info("Inscribiendo estudiante {$estudiante->Id_estudiantes} en {$request->tipo_seleccion}");

            if ($request->tipo_seleccion === 'programa') {
                $result = $this->inscribirPrograma($request, $estudiante, $programa);
            } else {
                $result = $this->inscribirTaller($request, $estudiante, $programa);
            }

            DB::commit();
            return $result;

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación:', $e->errors());
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al inscribir: ' . $e->getMessage());
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

        // Actualizar estudiante
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
                Cuota::create([
                    'Nro_de_cuota' => $cuotaData['Nro_de_cuota'],
                    'Fecha_vencimiento' => $cuotaData['Fecha_vencimiento'],
                    'Monto_cuota' => $cuotaData['Monto_cuota'],
                    'Estado_cuota' => 'Pendiente',
                    'Id_planes_pagos' => $planPago->Id_planes_pagos,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Estudiante inscrito exitosamente al programa: ' . $programa->Nombre);
    }

    private function inscribirTaller($request, $estudiante, $programa)
    {
        $request->validate([
            'monto_taller_descuento' => 'required|numeric|min:0',
            'fecha_pago_taller' => 'required|date',
            'metodo_pago_taller' => 'required|string',
            'estado_pago_taller' => 'required|in:pagado,pendiente'
        ]);

        // Verificar duplicado
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
            'Estado_inscripcion' => 'inscrito', // AGREGADO: Estado por defecto
            'Observaciones' => null
        ]);

        Log::info("Inscripción taller creada con ID: {$inscripcionTaller->Id_estudiantes_talleres}");

        // Crear pago del taller
        $pagoTaller = PagoTaller::create([
            'Descripcion' => $request->descripcion_taller ?: ('Pago ' . $programa->Nombre),
            'Monto_pago' => $request->monto_taller_descuento,
            'Fecha_pago' => $request->fecha_pago_taller,
            'Metodo_pago' => $request->metodo_pago_taller,
            'Estado_pago' => $request->estado_pago_taller, // AGREGADO: Estado del pago
            'Id_estudiantes_talleres' => $inscripcionTaller->Id_estudiantes_talleres
        ]);

        Log::info("Pago taller creado con ID: {$pagoTaller->Id_pagos_talleres}");

        return redirect()->back()->with('success', 'Estudiante inscrito exitosamente al taller: ' . $programa->Nombre);
    }
}