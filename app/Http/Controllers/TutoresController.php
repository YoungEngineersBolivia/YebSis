<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutores;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Rol;

class TutoresController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Apellido' => 'required|string|max:255',
            'Genero' => 'nullable|string|max:20',
            'Direccion_domicilio' => 'nullable|string|max:255',
            'Fecha_nacimiento' => 'nullable|date',
            'Celular' => 'nullable|string|max:20',
            'Correo' => 'required|email|unique:usuarios,Correo',
            'Contrasenia' => 'required|min:6',
            'Descuento' => 'nullable|numeric',
            'Parentesco' => 'nullable|string|max:255',
            'Nit' => 'nullable|string|max:20',
            'Nombre_factura' => 'nullable|string|max:255',
        ]);

        $rolTutor = Rol::where('Nombre_rol', 'Tutor')->first();
        if (!$rolTutor) {
            return redirect()->back()->with('error', 'El rol Tutor no existe');
        }

        $persona = Persona::create([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Direccion_domicilio' => $request->direccion_domicilio,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Fecha_registro' => now(),
            'Celular' => $request->celular,
            'Id_roles' => $rolTutor->Id_roles,
        ]);

        $usuario = Usuario::create([
            'Correo' => $request->correo,
            'Id_personas' => $persona->Id_personas,
        ]);

        Tutores::create([
            'Descuento' => $request->descuento,
            'Parentesco' => $request->parentesco,
            'Nit' => $request->nit,
            'Nombre_factura' => $request->nombre_factura,
            'Id_personas' => $persona->Id_personas,
            'Id_usuarios' => $usuario->Id_usuarios,
        ]);

        return redirect()->route('tutores.index')->with('success', 'Tutor registrado correctamente con su rol');
    }

    public function index()
    {
        $tutores = Tutores::orderBy('Id_tutores', 'desc')->paginate(10);
        return view('administrador.tutoresAdministrador', compact('tutores'));
    }
}
