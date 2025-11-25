<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Estudiante;

class ProfesoresController extends Controller
{
    public function homeProfesor()
    {
        $usuario = Auth::user(); // obtiene el usuario autenticado
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

        // Consulta base de estudiantes asignados a este profesor
        $estudiantesQuery = Estudiante::with(['persona', 'programa', 'horario'])
            ->where('Estado', 'activo') // solo estudiantes activos
            ->where('Id_profesores', $profesorId) // asignados a este profesor
            ->whereHas('persona', function ($query) {
                $query->where('Id_roles', 4); // solo rol Estudiante
            });

        // Filtrado por tipo
        $titulo = '';
        if ($tipo === 'evaluar') {
            $titulo = 'Evaluar Estudiantes';
        } elseif ($tipo === 'asignados') {
            $titulo = 'Alumnos Asignados';
        } elseif ($tipo === 'recuperatoria') {
            $estudiantesQuery->where('clase_recuperatoria', true); // si existe ese campo
            $titulo = 'Clase Recuperatoria';
        } else {
            // Si el tipo no es válido, redirige al menú
            return redirect()->route('profesor.menu-alumnos');
        }

        // Obtenemos los estudiantes y los ordenamos por nombre
        $estudiantes = $estudiantesQuery->get()->sortBy(fn($e) => $e->persona->Nombre);

        return view('profesor.listadoAlumnos', compact('estudiantes', 'tipo', 'titulo'));
    }

    /**
     * Muestra el detalle de un estudiante
     */
    public function detalleEstudiante($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa', 'horario'])->findOrFail($id);
        return view('profesor.detalleEstudiante', compact('estudiante'));
    }

    /**
     * Muestra el formulario para editar un estudiante
     */
    public function editarEstudiante($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('profesor.editarEvaluacion', compact('estudiante'));
    }

    /**
     * Muestra el formulario de evaluación
     */
    public function evaluarEstudiante($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa', 'modelo'])
            ->findOrFail($id);
        return view('profesor.evaluarAlumno', compact('estudiante'));
    }

    /**
     * Guarda la evaluación del estudiante
     */
    public function guardarEvaluacion(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'participa' => 'required|in:si,no,en_proceso',
            'secuenciada' => 'required|in:si,no,en_proceso',
            'paciente' => 'required|in:si,no,en_proceso',
        ]);

        // Aquí guardarías la evaluación en la base de datos
        // Ejemplo:
        // Evaluacion::create([
        //     'estudiante_id' => $request->estudiante_id,
        //     'profesor_id' => auth()->id(),
        //     'participa_clases' => $request->participa,
        //     'acciones_secuenciadas' => $request->secuenciada,
        //     'es_paciente' => $request->paciente,
        //     'fecha_evaluacion' => now(),
        // ]);

        return redirect()
            ->route('profesor.detalle-estudiante', $request->estudiante_id)
            ->with('success', 'Evaluación guardada correctamente');
    }
}

