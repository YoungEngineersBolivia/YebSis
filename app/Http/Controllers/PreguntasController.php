<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Programa;
use Illuminate\Http\Request;

class PreguntasController extends Controller
{
    /**
     * Mostrar lista de preguntas de un programa
     */
    public function index($programaId)
    {
        $programa = Programa::findOrFail($programaId);
        $preguntas = Pregunta::porPrograma($programaId)->ordenadas()->get();

        return view('administrador.preguntasPrograma', compact('programa', 'preguntas'));
    }

    /**
     * Guardar nueva pregunta
     */
    public function store(Request $request, $programaId)
    {
        $request->validate([
            'pregunta' => 'required|string|max:255|min:5',
        ], [
            'pregunta.required' => 'La pregunta es obligatoria',
            'pregunta.min' => 'La pregunta debe tener al menos 5 caracteres',
            'pregunta.max' => 'La pregunta no puede exceder 255 caracteres',
        ]);

        try {
            Pregunta::create([
                'Pregunta' => $request->pregunta,
                'Id_programas' => $programaId,
            ]);

            return redirect()
                ->route('admin.preguntas.index', $programaId)
                ->with('success', 'Pregunta agregada exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al agregar la pregunta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Actualizar pregunta existente
     */
    public function update(Request $request, $programaId, $id)
    {
        $request->validate([
            'pregunta' => 'required|string|max:255|min:5',
        ], [
            'pregunta.required' => 'La pregunta es obligatoria',
            'pregunta.min' => 'La pregunta debe tener al menos 5 caracteres',
            'pregunta.max' => 'La pregunta no puede exceder 255 caracteres',
        ]);

        try {
            $pregunta = Pregunta::where('Id_preguntas', $id)
                ->where('Id_programas', $programaId)
                ->firstOrFail();

            $pregunta->update([
                'Pregunta' => $request->pregunta,
            ]);

            return redirect()
                ->route('admin.preguntas.index', $programaId)
                ->with('success', 'Pregunta actualizada exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al actualizar la pregunta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar pregunta
     */
    public function destroy($programaId, $id)
    {
        try {
            $pregunta = Pregunta::where('Id_preguntas', $id)
                ->where('Id_programas', $programaId)
                ->firstOrFail();

            // Verificar si tiene evaluaciones asociadas
            if ($pregunta->evaluaciones()->count() > 0) {
                return back()->with('warning', 
                    'No se puede eliminar la pregunta porque tiene evaluaciones asociadas');
            }

            $pregunta->delete();

            return redirect()
                ->route('admin.preguntas.index', $programaId)
                ->with('success', 'Pregunta eliminada exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la pregunta: ' . $e->getMessage());
        }
    }
}
