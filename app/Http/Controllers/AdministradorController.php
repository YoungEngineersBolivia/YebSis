<?php
namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClaveGeneradaAdmin;

class AdministradorController extends Controller
{
    public function index()
    {
        return view('administrador.registrosAdministrador');
    }

    public function registrarAdmin(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,Correo',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'celular' => 'required|string|max:20',
            'direccion_domicilio' => 'required|string|max:255',
        ]);

        $rolAdmin = Rol::where('Nombre_rol', 'Administrador')->first();

        if (!$rolAdmin) {
            return back()->withErrors(['Rol de administrador no encontrado']);
        }

        $clave = Str::random(10);

        $persona = Persona::create([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Direccion_domicilio' => $request->direccion_domicilio,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Fecha_registro' => now(),
            'Celular' => $request->celular,
            'Id_roles' => $rolAdmin->Id_roles,
        ]);

        Usuario::create([
            'Correo' => $request->correo,
            'Contrasania' => bcrypt($clave),
            'Id_personas' => $persona->Id_personas,
        ]);

        // Enviar correo con la contraseña generada
        Mail::to($request->correo)->send(new ClaveGeneradaAdmin(
            $request->nombre,
            $request->correo,
            $clave
        ));

        return redirect()->back()->with('success', 'Administrador registrado correctamente. La contraseña fue enviada al correo.');
    }

    public function registrarComercial(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,Correo',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'celular' => 'required|string|max:20',
            'direccion_domicilio' => 'required|string|max:255',
        ]);

        $rolComercial = Rol::where('Nombre_rol', 'Comercial')->first();

        if (!$rolComercial) {
            return back()->withErrors(['Rol de comercial no encontrado']);
        }

        $clave = Str::random(10);

        $persona = Persona::create([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Direccion_domicilio' => $request->direccion_domicilio,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Fecha_registro' => now(),
            'Celular' => $request->celular,
            'Id_roles' => $rolComercial->Id_roles,
        ]);

        Usuario::create([
            'Correo' => $request->correo,
            'Contrasania' => bcrypt($clave),
            'Id_personas' => $persona->Id_personas,
        ]);

        Mail::to($request->correo)->send(new ClaveGeneradaAdmin(
            $request->nombre,
            $request->correo,
            $clave
        ));

        return redirect()->back()->with('success', 'Comercial registrado correctamente. La contraseña fue enviada al correo.');
    }
}