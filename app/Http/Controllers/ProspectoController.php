<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prospecto;

class ProspectoController extends Controller
{
    public function index()
{
    $prospectos = Prospecto::all();
    return view('comercial.prospectosComercial', compact('prospectos'));
}

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        // Crear prospecto
        Prospecto::create([
            'Nombre' => $request->nombres,
            'Apellido' => $request->apellidos,
            'Celular' => $request->telefono,
            'Estado_prospecto' => 'nuevo', // por defecto
            'Id_roles' => 5, // ejemplo, puedes ajustar
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', '¡Gracias! Nos contactaremos contigo pronto.');
    }
}
