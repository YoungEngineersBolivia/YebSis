<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Programa;
use App\Models\Sucursal;
use App\Models\Tutores;
use App\Models\Rol;
use App\Models\Persona;

class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda desde el request
        $search = $request->input('search');  

        // Realizar la consulta para obtener los estudiantes filtrados
        $estudiantes = Estudiante::with(['persona', 'programa', 'sucursal', 'profesor', 'profesor.persona'])
            ->when($search, function($query, $search) {
                return $query->whereHas('persona', function($q) use ($search) {
                    // Filtrar solo por Nombre y Apellido
                    $q->where('Nombre', 'like', "%$search%")
                    ->orWhere('Apellido', 'like', "%$search%")
                    ->orWhere('Cod_estudiante', 'like', "%$search%");
                });
            })
            ->orderBy('Id_estudiantes', 'desc')
            ->paginate(10);  // Paginación de resultados

        // Retornar la vista con estudiantes y otras relaciones necesarias
        return view('administrador.estudiantesAdministrador', compact('estudiantes'));
    }


    public function mostrarFormulario()
    {
        // Obtener programas, sucursales y tutores para el formulario de registro
        $programas = Programa::all();
        $sucursales = Sucursal::all();
        $tutores = Tutores::with('persona')->get();  // Incluye la relación persona de tutor
        return view('administrador.registrarEstudiante', compact('programas', 'sucursales', 'tutores'));
    }

    public function registrar(Request $request)
    {
        // Validaciones de los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'celular' => 'required|string',
            'direccion_domicilio' => 'required|string',
            'codigo_estudiante' => 'required|string|unique:estudiantes,Cod_estudiante',
            'programa' => 'required|exists:programas,Id_programas',
            'sucursal' => 'required|exists:sucursales,Id_Sucursales',
            'tutor_estudiante' => 'required|exists:tutores,Id_tutores',
        ]);

        // Obtener el rol de estudiante
        $rolEstudiante = Rol::where('Nombre_rol', 'Estudiante')->first();

        if (!$rolEstudiante) {
            return back()->withErrors(['error' => 'Rol de estudiante no encontrado.']);
        }

        // Crear la persona
        $persona = Persona::create([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Direccion_domicilio' => $request->direccion_domicilio,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Fecha_registro' => now(),
            'Celular' => $request->celular,
            'Id_roles' => $rolEstudiante->Id_roles,
        ]);

        // Verifica si el profesor con Id_profesores = 1 existe
        $profesorId = 1;
        $profesorExiste = \App\Models\Profesor::where('Id_profesores', $profesorId)->exists();

        // Crear el estudiante
        Estudiante::create([
            'Cod_estudiante' => $request->codigo_estudiante,
            'Estado' => 'Activo',
            'Id_personas' => $persona->Id_personas,
            'Id_programas' => $request->programa,
            'Id_sucursales' => $request->sucursal,
            'Id_profesores' => $profesorExiste ? $profesorId : null, // Usa null si no existe
            'Id_tutores' => $request->tutor_estudiante,
        ]);

        return redirect()->back()->with('success', 'Estudiante registrado correctamente.');
    }

    public function editar($id)
    {
        // Buscar al estudiante por su ID y cargar relaciones
        $estudiante = Estudiante::with(['persona', 'programa', 'sucursal', 'tutor.persona'])->find($id);

        if (!$estudiante) {
            return redirect()->route('estudiantes.index')->with('error', 'Estudiante no encontrado.');
        }

        // Obtener programas, sucursales y tutores para el formulario de edición
        $programas = Programa::all();
        $sucursales = Sucursal::all();
        $tutores = Tutores::with('persona')->get();

        // Retornar la vista con los datos del estudiante y las otras variables necesarias
        return view('administrador.editarEstudiante', compact('estudiante', 'programas', 'sucursales', 'tutores'));
    }

    public function actualizar(Request $request, $id)
    {
        // Validaciones para actualizar los datos
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'celular' => 'required|string',
            'direccion_domicilio' => 'required|string',
            'programa' => 'required|exists:programas,Id_programas',
            'sucursal' => 'required|exists:sucursales,Id_Sucursales',
            'tutor_estudiante' => 'required|exists:tutores,Id_tutores',
        ]);

        // Buscar el estudiante
        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            return back()->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        // Actualizar los datos del estudiante
        $estudiante->update([
            'Id_programas' => $request->programa,
            'Id_sucursales' => $request->sucursal,
            'Id_tutores' => $request->tutor_estudiante,
        ]);

        // Actualizar los datos de la persona relacionada
        $estudiante->persona->update([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Celular' => $request->celular,
            'Direccion_domicilio' => $request->direccion_domicilio,
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
    }

    public function eliminar($id)
    {
        // Buscar el estudiante
        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            return back()->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        // Eliminar el estudiante
        $estudiante->delete();

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado correctamente.');
    }

    public function ver($id)
    {
        // Buscar el estudiante para mostrar sus detalles
        $estudiante = Estudiante::with(['persona', 'programa', 'sucursal', 'tutor.persona'])->find($id);

        if (!$estudiante) {
            return back()->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        return view('administrador.verEstudiante', compact('estudiante'));
    }

    // Método para cambiar el estado de un estudiante (Activo/Inactivo)
public function cambiarEstado($id)
    {
        // Buscar al estudiante por su ID
        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            return back()->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        // Cambiar el estado
        $nuevoEstado = $estudiante->Estado === 'Activo' ? 'Inactivo' : 'Activo';
        $estudiante->update(['Estado' => $nuevoEstado]);

        return redirect()->route('estudiantes.index')->with('success', 'Estado del estudiante actualizado.');
    }

}
