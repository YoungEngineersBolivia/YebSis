<?php

namespace App\Http\Controllers;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClaveGeneradaAdmin;
use Illuminate\Http\Request;

class RegistroAdministradorController extends Controller
{
    public function registrarAdmin(Request $request){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,Correo',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'celular' => 'required|string|max:20',
            'direccion_domicilio' => 'required|string|max:255',
        ]);
    
        $rolAdmin = Rol::where('Nombre_rol', 'Administrador')->firstOrFail();
    
        // Generar contraseña aleatoria
        $clave = Str::random(10);
    
        // Crear persona
        $persona = Rersona::create([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Direccion_domicilio' => $request->direccion_domicilio,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Fecha_registro' => now(),
            'Celular' => $request->celular,
            'Id_Rol' => $rolAdmin->Id_Rol,
        ]);
    
        // Crear usuario
        Usuario::create([
            'Correo' => $request->correo,
            'Contrasania' => bcrypt($clave),
            'Id_Persona' => $persona->Id_Persona
        ]);
    
        // Enviar correo con clave
        Mail::to($request->correo)->send(new ClaveGeneradaAdmin(
            $request->nombre,
            $request->correo,
            $clave
        ));
    
        return redirect()->back()->with('success', 'Administrador registrado y correo enviado.');
    }

    public function index()
    {
        return view('administrador.registrosAdministradores');
    }
}

