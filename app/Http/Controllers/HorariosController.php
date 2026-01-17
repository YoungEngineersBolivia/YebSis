<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Programa;

class HorariosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Horario::with(['estudiante.persona', 'profesor.persona', 'programa'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('estudiante.persona', function ($qp) use ($search) {
                    $qp->where('Nombre', 'like', "%{$search}%")
                        ->orWhere('Apellido', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT_WS(' ', Nombre, Apellido) LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('Dia')
            ->orderBy('Hora');

        $horarios = $query->paginate(6);

        // Si es petición AJAX, devolvemos solo la tabla
        if ($request->ajax()) {
            return view('administrador.partials.horarios_table', compact('horarios'))->render();
        }

        $estudiantes = Estudiante::activos()
            ->with(['persona', 'profesor.persona', 'programa'])
            ->withCount('horarios')
            ->get();

        $profesores = Profesor::with('persona')->get();

        return view('administrador.horariosAdministrador', compact('horarios', 'estudiantes', 'profesores', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'horarios' => 'required|array|min:1',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora' => 'required'
        ]);

        $estudiante = Estudiante::findOrFail($request->Id_estudiantes);

        if (!$estudiante->Id_programas) {
            return redirect()->back()
                ->withErrors(['error' => 'El estudiante seleccionado no tiene un programa asignado.'])
                ->withInput();
        }

        if (!$estudiante->Id_profesores) {
            $estudiante->Id_profesores = $request->Id_profesores;
            $estudiante->save();
        }

        $horariosCreados = 0;
        foreach ($request->horarios as $horario) {
            Horario::create([
                'Id_estudiantes' => $request->Id_estudiantes,
                'Id_profesores' => $request->Id_profesores,
                'Id_programas' => $estudiante->Id_programas,
                'Dia' => $horario['dia'],
                'Hora' => $horario['hora']
            ]);
            $horariosCreados++;
        }

        return redirect()->route('horarios.index')
            ->with('success', "Se crearon {$horariosCreados} horario(s) correctamente.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'Dia' => 'required|string',
            'Hora' => 'required'
        ]);

        $estudiante = Estudiante::findOrFail($request->Id_estudiantes);

        if (!$estudiante->Id_programas) {
            return redirect()->back()
                ->withErrors(['error' => 'El estudiante seleccionado no tiene un programa asignado.'])
                ->withInput();
        }

        if (!$estudiante->Id_profesores) {
            $estudiante->Id_profesores = $request->Id_profesores;
            $estudiante->save();
        }

        $horario = Horario::findOrFail($id);
        $horario->update([
            'Id_estudiantes' => $request->Id_estudiantes,
            'Id_profesores' => $request->Id_profesores,
            'Id_programas' => $estudiante->Id_programas,
            'Dia' => $request->Dia,
            'Hora' => $request->Hora
        ]);

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente.');
    }

    public function buscarEstudiante($idEstudiante)
    {
        $estudiante = Estudiante::with(['profesor.persona', 'programa'])->find($idEstudiante);

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        return response()->json([
            'Id_profesores' => $estudiante->Id_profesores,
            'Id_programas' => $estudiante->Id_programas,
            'profesor_nombre' => $estudiante->profesor ?
                $estudiante->profesor->persona->Nombre . ' ' . $estudiante->profesor->persona->Apellido :
                'Sin profesor',
            'programa_nombre' => $estudiante->programa ? $estudiante->programa->Nombre : 'Sin programa'
        ]);
    }
}