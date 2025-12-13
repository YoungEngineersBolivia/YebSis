<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutores;
use App\Models\Estudiante;
use App\Models\Citas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TutorHomeController extends Controller
{
    /**
     * Mostrar el dashboard del tutor
     */
    public function index()
    {
        try {
            // Obtener el usuario autenticado
            $usuario = Auth::user();
            
            // Buscar el tutor relacionado con el usuario
            $tutor = Tutores::with([
                'persona',
                'usuario'
            ])
            ->where('Id_usuarios', $usuario->Id_usuarios)
            ->first();

            // Validar que existe el tutor
            if (!$tutor) {
                return redirect()->route('login')
                    ->with('error', 'No se encontró información del tutor. Por favor, contacte al administrador.');
            }

            // Obtener todos los estudiantes del tutor con sus relaciones
            $estudiantes = Estudiante::with([
                'persona',
                'programa',
                'sucursal',
                'profesor.persona'
            ])
            ->where('Id_tutores', $tutor->Id_tutores)
            ->orderByRaw("CASE WHEN LOWER(TRIM(Estado)) = 'activo' THEN 0 ELSE 1 END") // Activos primero
            ->orderBy('created_at', 'desc')
            ->get();

            // DEBUG: Verificar los estados de los estudiantes
            Log::info('Estados de estudiantes cargados:');
            foreach ($estudiantes as $estudiante) {
                Log::info("Estudiante {$estudiante->Id_estudiantes}: Estado = '{$estudiante->Estado}' (tipo: " . gettype($estudiante->Estado) . ")");
                
                // Normalizar el estado si es necesario
                if ($estudiante->Estado) {
                    $estudiante->Estado = strtolower(trim($estudiante->Estado));
                }
            }

            // Contar estudiantes por estado
            $estadisticas = [
                'total' => $estudiantes->count(),
                'activos' => $estudiantes->filter(function($e) {
                    return strtolower(trim($e->Estado ?? '')) === 'activo';
                })->count(),
                'inactivos' => $estudiantes->filter(function($e) {
                    return strtolower(trim($e->Estado ?? '')) !== 'activo';
                })->count()
            ];

            Log::info('Estadísticas:', $estadisticas);

            return view('tutor.homeTutor', compact('tutor', 'estudiantes', 'estadisticas'));
            
        } catch (\Exception $e) {
            Log::error('Error en TutorHomeController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Ocurrió un error al cargar la información. Por favor, intente nuevamente.');
        }
    }

    /**
     * Ver detalles de un estudiante específico
     */
    public function verEstudiante($id)
    {
        try {
            $usuario = Auth::user();
            $tutor = Tutores::where('Id_usuarios', $usuario->Id_usuarios)->first();

            if (!$tutor) {
                return response()->json(['error' => 'Tutor no encontrado'], 404);
            }

            // Verificar que el estudiante pertenece al tutor
            $estudiante = Estudiante::with([
                'persona',
                'programa',
                'sucursal',
                'profesor.persona',
                'planesPagos.cuotas',
                'horarios'
            ])
            ->where('Id_estudiantes', $id)
            ->where('Id_tutores', $tutor->Id_tutores)
            ->first();

            if (!$estudiante) {
                return response()->json(['error' => 'Estudiante no encontrado'], 404);
            }

            return response()->json([
                'success' => true,
                'estudiante' => $estudiante
            ]);

        } catch (\Exception $e) {
            Log::error('Error en verEstudiante: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar los detalles'], 500);
        }
    }

    /**
     * Agendar una cita
     */
    public function agendarCita(Request $request)
    {
        try {
            // Validar los datos
            $validated = $request->validate([
                'estudiante_id' => 'required|exists:estudiantes,Id_estudiantes',
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => 'required|date_format:H:i',
                'motivo' => 'nullable|string|max:500'
            ], [
                'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior',
                'estudiante_id.exists' => 'El estudiante no existe',
                'hora.date_format' => 'El formato de hora no es válido'
            ]);

            $usuario = Auth::user();
            $tutor = Tutores::where('Id_usuarios', $usuario->Id_usuarios)->first();

            if (!$tutor) {
                return response()->json(['error' => 'Tutor no encontrado'], 404);
            }

            // Verificar que el estudiante pertenece al tutor
            $estudiante = Estudiante::where('Id_estudiantes', $validated['estudiante_id'])
                ->where('Id_tutores', $tutor->Id_tutores)
                ->first();

            if (!$estudiante) {
                return response()->json(['error' => 'No tiene permiso para agendar cita con este estudiante'], 403);
            }

            // Verificar que no haya otra cita en la misma fecha y hora
            $citaExistente = Citas::where('Id_tutores', $tutor->Id_tutores)
                ->where('Fecha', $validated['fecha'])
                ->where('Hora', $validated['hora'])
                ->first();

            if ($citaExistente) {
                return response()->json([
                    'error' => 'Ya tiene una cita agendada en esta fecha y hora'
                ], 422);
            }

            // Crear la cita
            $cita = Citas::create([
                'Fecha' => $validated['fecha'],
                'Hora' => $validated['hora'],
                'Id_tutores' => $tutor->Id_tutores,
                'Id_estudiantes' => $validated['estudiante_id'],
                'motivo' => $validated['motivo'] ?? null,
                'estado' => 'pendiente',
                'Id_evaluaciones' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cita agendada exitosamente',
                'cita' => $cita
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Datos inválidos',
                'errores' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al agendar cita: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al agendar la cita. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Ver evaluaciones de un estudiante
     */
    public function verEvaluaciones($id)
    {
        try {
            $usuario = Auth::user();
            $tutor = Tutores::where('Id_usuarios', $usuario->Id_usuarios)->first();

            if (!$tutor) {
                return response()->json(['error' => 'Tutor no encontrado'], 404);
            }

            // Verificar que el estudiante pertenece al tutor
            $estudiante = Estudiante::where('Id_estudiantes', $id)
                ->where('Id_tutores', $tutor->Id_tutores)
                ->first();

            if (!$estudiante) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            // Obtener evaluaciones del estudiante
            $evaluaciones = DB::table('evaluaciones')
                ->join('modelos', 'evaluaciones.Id_modelos', '=', 'modelos.Id_modelos')
                ->join('preguntas', 'evaluaciones.Id_preguntas', '=', 'preguntas.Id_preguntas')
                ->join('respuestas', 'evaluaciones.Id_respuestas', '=', 'respuestas.Id_respuestas')
                ->join('profesores', 'evaluaciones.Id_profesores', '=', 'profesores.Id_profesores')
                ->join('personas', 'profesores.Id_personas', '=', 'personas.Id_personas')
                ->where('evaluaciones.Id_estudiantes', $id)
                ->select(
                    'evaluaciones.*',
                    'modelos.Nombre_modelo',
                    'preguntas.Pregunta',
                    'respuestas.Respuesta',
                    'personas.Nombre as profesor_nombre',
                    'personas.Apellido as profesor_apellido'
                )
                ->orderBy('evaluaciones.fecha_evaluacion', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'evaluaciones' => $evaluaciones,
                'estudiante' => $estudiante
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener evaluaciones: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar las evaluaciones'], 500);
        }
    }
}