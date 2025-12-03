<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Modelo;
use Illuminate\Support\Facades\DB;

class ModeloController extends Controller
{
    /**
     * Mostrar los modelos de un programa específico
     */
    public function index($programaId)
    {
        try {
            $programa = Programa::findOrFail($programaId);
            $modelos = Modelo::where('Id_programa', $programaId)
                            ->orderBy('created_at', 'desc')
                            ->get();
            
            return view('administrador.modelosPrograma', compact('programa', 'modelos'));
        } catch (\Exception $e) {
            return redirect()->route('programas.index')
                           ->with('error', 'Programa no encontrado');
        }
    }

    /**
     * Guardar un nuevo modelo
     */
    public function store(Request $request, $programaId)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:255',
        ]);

        try {
            $programa = Programa::findOrFail($programaId);
            
            $modelo = new Modelo();
            $modelo->Nombre_modelo = $request->nombre_modelo;
            $modelo->Id_programa = $programaId;
            $modelo->save();

            return redirect()->route('programas.index')
                           ->with('success', 'Modelo creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al crear el modelo: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Actualizar un modelo
     */
    public function update(Request $request, $programaId, $modeloId)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:255',
        ]);

        try {
            $modelo = Modelo::where('Id_programa', $programaId)
                          ->where('Id_modelos', $modeloId)
                          ->firstOrFail();
            
            $modelo->Nombre_modelo = $request->nombre_modelo;
            $modelo->save();

            return redirect()->route('modelos.index', $programaId)
                           ->with('success', 'Modelo actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar el modelo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un modelo
     */
    public function destroy($programaId, $modeloId)
    {
        try {
            $modelo = Modelo::where('Id_programa', $programaId)
                          ->where('Id_modelos', $modeloId)
                          ->firstOrFail();
            
            $modelo->delete();

            return redirect()->route('programas.index')
                           ->with('success', 'Modelo eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al eliminar el modelo: ' . $e->getMessage());
        }
    }

    /**
     * Obtener datos de un modelo para editar
     */
    public function edit($programaId, $modeloId)
    {
        try {
            $modelo = Modelo::where('Id_programa', $programaId)
                          ->where('Id_modelos', $modeloId)
                          ->firstOrFail();
            
            return response()->json([
                'success' => true,
                'modelo' => $modelo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Modelo no encontrado'
            ], 404);
        }
    }
}