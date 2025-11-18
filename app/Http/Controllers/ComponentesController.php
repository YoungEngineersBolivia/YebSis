<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class ComponentesController extends Controller
{
    public function index()
    {
        $componentes = Motor::with('sucursal')
            ->orderBy('Id_motor', 'asc')
            ->get();

        $sucursales = Sucursal::all();

        return view('componentes.inventarioComponentes', compact('componentes', 'sucursales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_motor' => 'required|string|unique:motores,Id_motor',
            'estado' => 'required|in:Funcionando,Descompuesto,En Proceso',
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'observacion' => 'nullable|string',
        ]);

        try {
            Motor::create([
                'Id_motor' => $request->id_motor,
                'Estado' => $request->estado,
                'Id_sucursales' => $request->Id_sucursales,
                'Observacion' => $request->observacion,
            ]);

            return redirect()->route('componentes.index')
                ->with('success', 'Componente registrado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el componente: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // Cambia 'Id_componentes' por 'Id_motores'
            'id_motor' => 'required|string|unique:motores,Id_motor,' . $id . ',Id_motores',
            'estado' => 'required|in:Funcionando,Descompuesto,En Proceso',
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'observacion' => 'nullable|string',
        ]);

        try {
            $motor = Motor::findOrFail($id);
            $motor->update([
                'Id_motor' => $request->id_motor,
                'Estado' => $request->estado,
                'Id_sucursales' => $request->Id_sucursales,
                'Observacion' => $request->observacion,
            ]);

            return redirect()->route('componentes.index')
                ->with('success', 'Componente actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el componente: ' . $e->getMessage());
        }
    }

    public function destroy(Motor $motor)
    {
        try {
            $motor->delete();

            return redirect()->route('componentes.index')
                ->with('success', 'Componente eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el componente: ' . $e->getMessage());
        }
    }
}