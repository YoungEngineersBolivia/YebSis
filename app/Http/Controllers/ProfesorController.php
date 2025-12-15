<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\ClasePrueba;

class ProfesorController extends Controller
{
    // =====================================================
    //   DASHBOARD PROFESOR
    // =====================================================
    public function home()
    {
        $profesor = auth()->user()->profesor;
        $clasesPrueba = collect();

        if ($profesor) {
            $clasesPrueba = ClasePrueba::with('prospecto')
                ->where('Asistencia', 'pendiente')
                ->orderBy('Fecha_clase', 'asc')
                ->orderBy('Hora_clase', 'asc')
                ->take(3)
                ->get();
        }

        return view('profesor.homeProfesor', compact('profesor', 'clasesPrueba'));
    }

    // =====================================================
    //   CRUD PROFESOR
    // =====================================================

    public function index()
    {
        $profesores = Profesor::with('persona')->paginate(10);
        return view('administrador.profesoresAdministrador', compact('profesores'));
    }

    public function create()
    {
        return view('profesor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required',
        ]);

        Profesor::create([
            'Nombre' => $request->Nombre,
        ]);

        return redirect()->route('profesor.index')
            ->with('success', 'Profesor registrado correctamente');
    }



    public function edit($id)
    {
        $profesor = Profesor::findOrFail($id);
        return view('profesor.edit', compact('profesor'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required',
        'apellido' => 'required',
        'correo' => 'required|email',
    ]);

    $profesor = Profesor::with(['persona', 'usuario'])->findOrFail($id);

    // Actualizar persona
    $profesor->persona->Nombre = $request->nombre;
    $profesor->persona->Apellido = $request->apellido;
    $profesor->persona->Celular = $request->celular;
    $profesor->persona->save();

    // Actualizar usuario
    $profesor->usuario->Correo = $request->correo;
    if ($request->contrasenia) {
        $profesor->usuario->Contrasenia = bcrypt($request->contrasenia);
    }
    $profesor->usuario->save();

    // Actualizar campos propios de Profesor
    $profesor->Profesion = $request->profesion;
    $profesor->Rol_componentes = $request->rol_componentes;
    $profesor->save();

    return redirect()->route('profesores.index')
        ->with('success', 'Profesor actualizado correctamente');
}


    public function destroy($id)
    {
        $profesor = Profesor::findOrFail($id);
        $profesor->delete();

        return redirect()->route('profesores.index')
            ->with('success', 'Profesor eliminado correctamente');
    }

    // =====================================================
    //   FUNCIONES PERSONALIZADAS PARA PROFESOR
    // =====================================================

    public function homeProfesor()
    {
        $usuario = Auth::user();
        return view('profesor.homeProfesor', compact('usuario'));
    }

    public function menuAlumnosProfesor()
    {
        $usuario = Auth::user();
        return view('profesor.menuAlumnosProfesor', compact('usuario'));
    }

    public function listadoAlumnos($tipo)
    {
        $usuario = auth()->user();
        $profesor = $usuario->profesor;
        $profesorId = $profesor?->Id_profesores;

        if (!$profesorId) {
            return redirect()->route('profesor.menu-alumnos')
                ->with('error', 'No se encontró el perfil de profesor asociado.');
        }

        // Obtener IDs únicos de estudiantes que tienen horarios con este profesor
        $estudiantesIds = \App\Models\Horario::where('Id_profesores', $profesorId)
            ->distinct()
            ->pluck('Id_estudiantes')
            ->toArray();

        // Construir query base con estudiantes que tienen horarios con este profesor
        $estudiantesQuery = Estudiante::with(['persona', 'programa', 'horarios'])
            ->where('Estado', 'activo')
            ->whereIn('Id_estudiantes', $estudiantesIds)
            ->whereHas('persona', function ($query) {
                $query->where('Id_roles', 4);
            });

        $titulo = '';

        if ($tipo === 'asistencia') {
            $titulo = 'Registrar Asistencia';
        } elseif ($tipo === 'asignados') {
            $titulo = 'Alumnos Asignados';
        } elseif ($tipo === 'recuperatoria') {
            $estudiantesQuery->whereHas('asistencias', function($q) use ($profesorId) {
                $q->where('Estado', 'Reprogramado');
            });
            $titulo = 'Alumnos con Clases Reprogramadas';
            $titulo = 'Clase Recuperatoria';
        } else {
            return redirect()->route('profesor.menu-alumnos');
        }

        $estudiantes = $estudiantesQuery->get()
            ->sortBy(fn($e) => $e->persona->Nombre);

        return view('profesor.listadoAlumnos', compact('estudiantes', 'tipo', 'titulo'));
    }

    public function detalleEstudiante($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa.modelos', 'horarios'])->findOrFail($id);

        // Verificar si el estudiante ya fue evaluado
        $yaEvaluado = \App\Models\Evaluacion::where('Id_estudiantes', $estudiante->Id_estudiantes)
            ->exists();

        return view('profesor.detalleEstudiante', compact('estudiante', 'yaEvaluado'));
    }

    public function editarEstudiante($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('profesor.editarEvaluacion', compact('estudiante'));
    }

    public function evaluarEstudiante($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa.modelos'])
            ->findOrFail($id);

        // Obtener preguntas del programa
        $preguntas = \App\Models\Pregunta::porPrograma($estudiante->Id_programas)
            ->ordenadas()
            ->get();

        // Obtener opciones de respuesta (Sí, No, En proceso)
        $respuestas = \App\Models\Respuesta::all();

        // Obtener modelos del programa
        $modelos = $estudiante->programa->modelos;

        // Cargar evaluaciones existentes (para editar)
        $evaluacionesExistentes = \App\Models\Evaluacion::where('Id_estudiantes', $estudiante->Id_estudiantes)
            ->with(['pregunta', 'respuesta', 'modelo'])
            ->get()
            ->keyBy('Id_preguntas'); // Indexar por pregunta para fácil acceso

        // Modelo seleccionado previamente (si existe evaluación)
        $modeloSeleccionado = $evaluacionesExistentes->first()->Id_modelos ?? null;

        return view('profesor.evaluarAlumno', compact('estudiante', 'preguntas', 'respuestas', 'modelos', 'evaluacionesExistentes', 'modeloSeleccionado'));
    }

    public function guardarEvaluacion(Request $request)
    {
        // Validar datos básicos
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,Id_estudiantes',
            'modelo_id' => 'required|exists:modelos,Id_modelos',
            'respuestas' => 'required|array|min:1',
            'respuestas.*' => 'required|exists:respuestas,Id_respuestas',
        ], [
            'estudiante_id.required' => 'El estudiante es obligatorio',
            'modelo_id.required' => 'Debes seleccionar un modelo',
            'respuestas.required' => 'Debes responder al menos una pregunta',
            'respuestas.*.required' => 'Todas las preguntas deben ser respondidas',
        ]);

        try {
            \DB::beginTransaction();

            $estudiante = \App\Models\Estudiante::findOrFail($request->estudiante_id);
            $profesorId = auth()->user()->persona->profesor->Id_profesores;

            // Verificar si ya existen evaluaciones para este estudiante
            $evaluacionesExistentes = \App\Models\Evaluacion::where('Id_estudiantes', $estudiante->Id_estudiantes)->get();

            if ($evaluacionesExistentes->count() > 0) {
                // MODO EDICIÓN: Actualizar evaluaciones existentes
                foreach ($request->respuestas as $preguntaId => $respuestaId) {
                    \App\Models\Evaluacion::updateOrCreate(
                        [
                            'Id_estudiantes' => $estudiante->Id_estudiantes,
                            'Id_preguntas' => $preguntaId,
                        ],
                        [
                            'fecha_evaluacion' => now(),
                            'Id_respuestas' => $respuestaId,
                            'Id_modelos' => $request->modelo_id,
                            'Id_profesores' => $profesorId,
                            'Id_programas' => $estudiante->Id_programas,
                        ]
                    );
                }
                $mensaje = 'Evaluación actualizada correctamente';
            } else {
                // MODO CREACIÓN: Crear nuevas evaluaciones
                foreach ($request->respuestas as $preguntaId => $respuestaId) {
                    \App\Models\Evaluacion::create([
                        'fecha_evaluacion' => now(),
                        'Id_estudiantes' => $estudiante->Id_estudiantes,
                        'Id_preguntas' => $preguntaId,
                        'Id_respuestas' => $respuestaId,
                        'Id_modelos' => $request->modelo_id,
                        'Id_profesores' => $profesorId,
                        'Id_programas' => $estudiante->Id_programas,
                    ]);
                }
                $mensaje = 'Evaluación guardada correctamente';
            }

            \DB::commit();

            return redirect()
                ->route('profesor.detalle-estudiante', $request->estudiante_id)
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()
                ->with('error', 'Error al guardar la evaluación: ' . $e->getMessage())
                ->withInput();
        }
    }
}
