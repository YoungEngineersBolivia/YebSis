<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use Illuminate\Http\Request;

class EgresosController extends Controller
{
    public function index()
    {
        $egresos = Egreso::orderBy('Fecha_egreso', 'desc')->get();
        return view('administrador.egresosAdministrador', compact('egresos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Tipo' => 'required|string|max:255',
            'Descripcion_egreso' => 'required|string',
            'Fecha_egreso' => 'required|date',
            'Monto_egreso' => 'required|numeric|min:0',
        ]);

        Egreso::create($request->all());

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso registrado exitosamente');
    }

    // Método para editar
    public function update(Request $request, $id_egreso)
    {
        $request->validate([
            'Tipo' => 'required|string|max:255',
            'Descripcion_egreso' => 'required|string',
            'Fecha_egreso' => 'required|date',
            'Monto_egreso' => 'required|numeric|min:0',
        ]);

        $egreso = Egreso::findOrFail($id_egreso);
        $egreso->update($request->all());

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso actualizado exitosamente');
    }

    // Método para eliminar
    public function destroy($id_egreso)
    {
        $egreso = Egreso::findOrFail($id_egreso);
        $egreso->delete();

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso eliminado exitosamente');
    }
}
