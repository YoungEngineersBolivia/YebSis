<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsistenciaProfesorController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        $profesor = \App\Models\Profesor::where('Id_personas', $user->Id_personas)->first();

        if (!$profesor) {
            return redirect()->back()->with('error', 'No se encontró el perfil de profesor.');
        }

        // Por defecto no enviamos estudiantes para que el profesor los busque "añadiendo"
        // Opcionalmente podemos enviar los asignados hoy, pero el usuario pidió "ir añadiendo"
        $estudiantes = collect();

        return view('profesor.asistenciaProfesor', compact('profesor', 'estudiantes'));
    }

    public function buscarEstudiantes(Request $request)
    {
        $query = $request->get('q');

        $estudiantes = \App\Models\Estudiante::where('Estado', 'Activo')
            ->whereHas('persona', function ($q) use ($query) {
                $q->where('Nombre', 'LIKE', "%$query%")
                    ->orWhere('Apellido', 'LIKE', "%$query%");
            })
            ->orWhere('Cod_estudiante', 'LIKE', "%$query%")
            ->with(['persona', 'programa'])
            ->limit(10)
            ->get();

        return response()->json($estudiantes->map(function ($e) {
            return [
                'id' => $e->Id_estudiantes,
                'nombre' => $e->persona->Nombre . ' ' . $e->persona->Apellido,
                'codigo' => $e->Cod_estudiante,
                'programa' => $e->programa->Nombre ?? 'Sin programa',
                'programa_id' => $e->Id_programas
            ];
        }));
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
        $estudianteIds = array_keys($request->asistencia);

        // Eliminar registros de alumnos que fueron quitados de la lista para esta fecha
        \App\Models\Asistencia::where('Id_profesores', $profesorId)
            ->where('Fecha', $fecha)
            ->whereNotIn('Id_estudiantes', $estudianteIds)
            ->delete();

        foreach ($request->asistencia as $estudianteId => $estado) {
            $data = [
                'Id_estudiantes' => $estudianteId,
                'Id_profesores' => $profesorId,
                'Id_programas' => $request->programa_id[$estudianteId] ?? null,
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

    public function historial()
    {
        $user = auth()->user();
        $profesor = \App\Models\Profesor::where('Id_personas', $user->Id_personas)->first();

        if (!$profesor) {
            return redirect()->back()->with('error', 'No se encontró el perfil de profesor.');
        }

        // Obtenemos las fechas únicas y contamos estados
        $historial = \App\Models\Asistencia::where('Id_profesores', $profesor->Id_profesores)
            ->select('Fecha')
            ->selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(CASE WHEN Estado = 'Asistio' THEN 1 ELSE 0 END) as asistieron")
            ->selectRaw("SUM(CASE WHEN Estado = 'Falta' THEN 1 ELSE 0 END) as faltaron")
            ->groupBy('Fecha')
            ->orderBy('Fecha', 'desc')
            ->paginate(20);

        return view('profesor.historialAsistencia', compact('profesor', 'historial'));
    }

    public function obtenerAsistenciaPorFecha(Request $request)
    {
        $fecha = $request->get('fecha');
        $user = auth()->user();
        $profesor = \App\Models\Profesor::where('Id_personas', $user->Id_personas)->first();

        if (!$profesor) {
            return response()->json(['error' => 'Profesor no encontrado'], 404);
        }

        $asistencias = \App\Models\Asistencia::where('Id_profesores', $profesor->Id_profesores)
            ->where('Fecha', $fecha)
            ->with(['estudiante.persona', 'estudiante.programa'])
            ->get();

        return response()->json($asistencias->map(function ($a) {
            return [
                'id' => $a->Id_estudiantes,
                'nombre' => $a->estudiante->persona->Nombre . ' ' . $a->estudiante->persona->Apellido,
                'codigo' => $a->estudiante->Cod_estudiante,
                'programa' => $a->estudiante->programa->Nombre ?? 'Sin programa',
                'programa_id' => $a->Id_programas,
                'estado' => $a->Estado,
                'observacion' => $a->Observacion,
                'fecha_reprogramada' => $a->Fecha_reprogramada
            ];
        }));
    }
}
