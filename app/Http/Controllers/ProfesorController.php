<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Profesor;

class ProfesorController extends Controller
{
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

        $estudiantesQuery = Estudiante::with(['persona', 'programa', 'horarios'])
            ->where('Estado', 'activo')
            ->where('Id_profesores', $profesorId)
            ->whereHas('persona', function ($query) {
                $query->where('Id_roles', 4);
            });

        $titulo = '';

        if ($tipo === 'evaluar') {
            $titulo = 'Evaluar Estudiantes';
        } elseif ($tipo === 'asignados') {
            $titulo = 'Alumnos Asignados';
        } elseif ($tipo === 'recuperatoria') {
            $estudiantesQuery->where('clase_recuperatoria', true);
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
        $estudiante = Estudiante::with(['persona', 'programa', 'horarios'])->findOrFail($id);

        return view('profesor.detalleEstudiante', compact('estudiante'));
    }

    public function editarEstudiante($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('profesor.editarEvaluacion', compact('estudiante'));
    }

    public function evaluarEstudiante($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa', 'modelo'])
            ->findOrFail($id);

        return view('profesor.evaluarAlumno', compact('estudiante'));
    }

    public function guardarEvaluacion(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'participa' => 'required|in:si,no,en_proceso',
            'secuenciada' => 'required|in:si,no,en_proceso',
            'paciente' => 'required|in:si,no,en_proceso',
        ]);

        return redirect()
            ->route('profesor.detalle-estudiante', $request->estudiante_id)
            ->with('success', 'Evaluación guardada correctamente');
    }
}
