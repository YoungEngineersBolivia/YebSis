<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClasePrueba;
use Illuminate\Support\Facades\Log;

class ProfesorTrialClassController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();
        $profesor = $usuario->profesor;

        if (!$profesor) {
            return redirect()->route('profesor.home')
                ->with('error', 'No se encontró el perfil de profesor asociado.');
        }

        // Obtener TODAS las clases de prueba pendientes
        $clases = ClasePrueba::with('prospecto')
            ->where('Asistencia', 'pendiente')
            ->orderBy('Fecha_clase', 'asc')
            ->orderBy('Hora_clase', 'asc')
            ->paginate(10);

        return view('profesor.clasesPrueba', compact('clases', 'profesor'));
    }

    public function updateAttendance(Request $request, $id)
    {
        $request->validate([
            'asistencia' => 'required|in:pendiente,asistio,no_asistio'
        ]);

        try {
            $clase = ClasePrueba::findOrFail($id);
            $user = auth()->user();
            $profesorId = $user->profesor?->Id_profesores;

            // Asignar el profesor que está tomando la asistencia (solo si es profesor)
            $updateData = ['Asistencia' => $request->asistencia];
            if ($profesorId) {
                $updateData['Id_profesores'] = $profesorId;
            }
            
            $clase->update($updateData);

            // Actualizar estado del prospecto automáticamente
            if ($clase->prospecto) {
                if ($request->asistencia === 'asistio') {
                    $clase->prospecto->update(['Estado_prospecto' => 'para inscripcion']);
                } elseif ($request->asistencia === 'no_asistio') {
                    $clase->prospecto->update(['Estado_prospecto' => 'no asistio']);
                }
            }

            return response()->json(['success' => true, 'message' => 'Asistencia actualizada correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al actualizar asistencia: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function updateComments(Request $request, $id)
    {
        try {
            $request->validate([
                'comentarios' => 'nullable|string'
            ]);

            $clase = ClasePrueba::findOrFail($id);

            $clase->update([
                'Comentarios' => $request->comentarios
            ]);

            return response()->json(['success' => true, 'message' => 'Comentarios actualizados correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al actualizar comentarios: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()], 500);
        }
    }
}
