<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsistenciaProfesorController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        if (!$user->Id_personas) {
            return redirect()->back()->with('error', 'Usuario no tiene persona asociada.');
        }

        $profesor = \App\Models\Profesor::where('Id_personas', $user->Id_personas)->first();

        if (!$profesor) {
            return redirect()->back()->with('error', 'No se encontró el perfil de profesor.');
        }

        // Obtener estudiantes que tienen horarios con este profesor
        $estudiantesIds = \App\Models\Horario::where('Id_profesores', $profesor->Id_profesores)
            ->distinct()
            ->pluck('Id_estudiantes')
            ->toArray();

        $estudiantes = \App\Models\Estudiante::whereIn('Id_estudiantes', $estudiantesIds)
            ->with(['persona', 'programa'])
            ->where('Estado', 'Activo')
            ->get();

        return view('profesor.asistenciaProfesor', compact('profesor', 'estudiantes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'asistencia' => 'required|array',
            'asistencia.*' => 'required|in:Asistio,Falta,Licencia,Reprogramado',
        ]);

        $fecha = $request->fecha;
        $profesorId = $request->profesor_id;

        foreach ($request->asistencia as $estudianteId => $estado) {
            $data = [
                'Id_estudiantes' => $estudianteId,
                'Id_profesores' => $profesorId,
                'Id_programas' => $request->programa_id[$estudianteId] ?? null, // Asumimos que viene del form
                'Fecha' => $fecha,
                'Estado' => $estado,
                'Observacion' => $request->observacion[$estudianteId] ?? null,
            ];

            if ($estado === 'Reprogramado') {
                $data['Fecha_reprogramada'] = $request->fecha_reprogramada[$estudianteId] ?? null;
            }

            \App\Models\Asistencia::updateOrCreate(
                [
                    'Id_estudiantes' => $estudianteId,
                    'Id_profesores' => $profesorId,
                    'Fecha' => $fecha
                ],
                $data
            );
        }

        return redirect()->back()->with('success', 'Asistencia registrada correctamente.');
    }
}
