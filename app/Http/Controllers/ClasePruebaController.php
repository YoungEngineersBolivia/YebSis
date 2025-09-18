<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClasePrueba;

class ClasePruebaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Nombre_Estudiante' => 'required|string',
            'Fecha_clase' => 'required|date',
            'Hora_clase' => 'required',
            'Comentarios' => 'nullable|string',
            'Id_prospectos' => 'required|exists:prospectos,Id_prospectos',
        ]);

        ClasePrueba::create($validated);

        // Cambiar estado del prospecto a 'clase de prueba'
        $prospecto = \App\Models\Prospecto::find($validated['Id_prospectos']);
        if ($prospecto) {
            $prospecto->Estado_prospecto = 'clase de prueba';
            $prospecto->save();
        }

        return redirect()->back()->with('status', 'Clase de prueba guardada correctamente.');
    }
}