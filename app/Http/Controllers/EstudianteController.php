<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Programa;
use App\Models\Sucursal;
use App\Models\Rol;
use App\Models\Tutores;

class EstudianteController extends Controller
{
    // Listar estudiantes
    public function index()
    {
        $estudiantes = Estudiante::with('persona')->get();
        return view('administrador.estudiantesAdministrador', compact('estudiantes'));
    }

    public function mostrarFormulario()
    {
        $programas = Programa::all();
        $sucursales = Sucursal::all();
        $tutores = Tutores::with('persona')->get(); 
        return view('administrador.registrarEstudiante', compact('programas', 'sucursales', 'tutores'));
    }

    public function registrar(Request $request)
    {
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

        $rolEstudiante = Rol::where('Nombre_rol', 'Estudiante')->first();

        if (!$rolEstudiante) {
            return back()->withErrors(['error' => 'Rol de estudiante no encontrado.']);
        }

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

        // Verifica que el profesor con Id_profesores = 1 exista
        $profesorId = 1;
        $profesorExiste = \App\Models\Profesor::where('Id_profesores', $profesorId)->exists();

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
}
