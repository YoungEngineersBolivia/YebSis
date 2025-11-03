<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Programa;

class HorariosController extends Controller
{
    public function index()
    {
        $horarios = Horario::with(['estudiante.persona', 'profesor.persona', 'programa'])
            ->orderBy('Dia')
            ->orderBy('Hora')
            ->paginate(10);

        $estudiantes = Estudiante::with('persona')->get();
        $profesores = Profesor::with('persona')->get();
        $programas  = Programa::all();

        return view('administrador.horariosAdministrador', compact('horarios', 'estudiantes', 'profesores', 'programas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'Id_profesores'  => 'required|exists:profesores,Id_profesores',
            'Id_programas'   => 'required|exists:programas,Id_programas',
            'Dia'            => 'required|string',
            'Hora'           => 'required'
        ]);

        Horario::create($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'Id_profesores'  => 'required|exists:profesores,Id_profesores',
            'Id_programas'   => 'required|exists:programas,Id_programas',
            'Dia'            => 'required|string',
            'Hora'           => 'required'
        ]);

        $horario = Horario::findOrFail($id);
        $horario->update($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente.');
    }
}
