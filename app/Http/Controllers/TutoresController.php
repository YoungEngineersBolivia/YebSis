<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutores; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutoresController extends Controller
{
    public function index()
    {
        $tutores = Tutores::with(['persona', 'usuario'])->orderBy('Id_tutores', 'desc')->paginate(10);
        return view('administrador.tutoresAdministrador', compact('tutores'));
    }

    public function show($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);
        return response()->json([
            'persona' => $tutor->persona,
            'usuario' => $tutor->usuario,
            'Parentesco' => $tutor->Parentesco,
            'Descuento' => $tutor->Descuento,
            'Nit' => $tutor->Nit,
        ]);
    }

    public function edit($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);
        return response()->json([
            'persona' => $tutor->persona,
            'usuario' => $tutor->usuario,
            'Parentesco' => $tutor->Parentesco,
            'Descuento' => $tutor->Descuento,
            'Nit' => $tutor->Nit,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

        // Actualiza los datos relacionados
        $tutor->persona->Nombre = $request->nombre;
        $tutor->persona->Apellido = $request->apellido;
        $tutor->persona->Celular = $request->celular;
        $tutor->persona->Direccion_domicilio = $request->direccion_domicilio;
        $tutor->persona->save();

        $tutor->usuario->Correo = $request->correo;
        $tutor->usuario->save();

        $tutor->Parentesco = $request->parentezco;
        $tutor->Descuento = $request->descuento;
        $tutor->Nit = $request->nit;
        $tutor->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $tutor = Tutores::findOrFail($id);
        $tutor->delete();
        return response()->json(['success' => true]);
    }
}