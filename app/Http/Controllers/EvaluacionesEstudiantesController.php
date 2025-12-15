<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EvaluacionesEstudianteController extends Controller
{
    /**
     * Mostrar TODAS las evaluaciones del sistema con buscador
     */
    public function index(Request $request)
    {
        $query = Evaluacion::with([
            'pregunta',
            'respuesta',
            'modelo',
            'profesor.persona',
            'programa',
            'estudiante.persona'
        ]);

        // Búsqueda si existe el parámetro
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('estudiante.persona', function($q) use ($search) {
                $q->where('Nombre', 'like', "%{$search}%")
                  ->orWhere('Apellido', 'like', "%{$search}%");
            })
            ->orWhereHas('estudiante', function($q) use ($search) {
                $q->where('Cod_estudiante', 'like', "%{$search}%");
            })
            ->orWhereHas('programa', function($q) use ($search) {
                $q->where('Nombre', 'like', "%{$search}%");
            });
        }

        $evaluaciones = $query->orderBy('fecha_evaluacion', 'desc')
                             ->paginate(15);

        return view('administrador.evaluacionesEstudiante', compact('evaluaciones'));
    }

    /**
     * Mostrar las evaluaciones de UN estudiante específico
     */
    public function show($id)
    {
        // Obtener el estudiante con todas sus relaciones
        $estudiante = Estudiante::with([
            'persona',
            'programa',
            'sucursal',
            'tutor.persona'
        ])->findOrFail($id);

        // Obtener las evaluaciones del estudiante como colección separada
        $evaluaciones = Evaluacion::with([
            'pregunta',
            'respuesta',
            'modelo',
            'profesor.persona',
            'programa',
            'estudiante.persona' // Agregar esto por si acaso
        ])
        ->where('Id_estudiantes', $id)
        ->orderBy('fecha_evaluacion', 'desc')
        ->get();

        // IMPORTANTE: Pasar AMBAS variables a la vista
        return view('administrador.evaluacionesEstudiante', [
            'estudiante' => $estudiante,
            'evaluaciones' => $evaluaciones
        ]);
    }

    /**
     * Método alternativo (mantener para compatibilidad si ya lo usas en otras vistas)
     */
    public function evaluacionesPorEstudiante($estudianteId)
    {
        return $this->show($estudianteId);
    }

    /**
 * Obtener evaluaciones de un estudiante en formato JSON (para AJAX)
 */
public function getEvaluacionesJson($id)
{
    try {
        $evaluaciones = Evaluacion::with([
            'pregunta',
            'respuesta',
            'modelo',
            'profesor.persona',
            'programa'
        ])
        ->where('Id_estudiantes', $id)
        ->orderBy('fecha_evaluacion', 'desc')
        ->get()
        ->map(function($evaluacion) {
            return [
                'Id_evaluaciones' => $evaluacion->Id_evaluaciones,
                'Id_modelos' => $evaluacion->Id_modelos ?? null,
                'Nombre_modelo' => optional($evaluacion->modelo)->Nombre_modelo ?? 'Sin modelo',
                'Pregunta' => optional($evaluacion->pregunta)->Pregunta ?? 'N/A',
                'Respuesta' => optional($evaluacion->respuesta)->Respuesta ?? 'N/A',
                'fecha_evaluacion' => $evaluacion->fecha_evaluacion,
                'programa_nombre' => optional($evaluacion->programa)->Nombre ?? 'Sin programa',
                'profesor_nombre' => optional($evaluacion->profesor)->persona->Nombre ?? '',
                'profesor_apellido' => optional($evaluacion->profesor)->persona->Apellido ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'evaluaciones' => $evaluaciones
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en getEvaluacionesJson: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'error' => 'Error al obtener las evaluaciones: ' . $e->getMessage()
        ], 500);
    }
}
}