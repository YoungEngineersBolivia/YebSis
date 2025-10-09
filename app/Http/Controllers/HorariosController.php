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
        $horarios    = Horario::with(['estudiante.persona', 'profesor.persona', 'programa'])->paginate(10);
        $estudiantes = Estudiante::with('persona')->orderBy('Id_estudiantes')->get();
        $profesores  = Profesor::with('persona')->orderBy('Id_profesores')->get();
        $programas   = Programa::orderBy('Id_programas')->get(); // <-- nuevo

        return view('administrador.horariosAdministrador', compact('horarios','estudiantes','profesores','programas'));
    }

    public function create()
    {
        $estudiantes = Estudiante::with('persona')->get();
        $profesores = Profesor::with('persona')->get();
        $programas = Programa::all();
        return view('administrador.horariosCreate', compact('estudiantes', 'profesores', 'programas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'Id_programas' => 'required|exists:programas,Id_programas',
            'Dia_clase_uno' => 'required|string',
            'Horario_clase_uno' => 'required|string',
            'Dia_clase_dos' => 'required|string',
            'Horario_clase_dos' => 'required|string',
        ]);

        Horario::create($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario registrado correctamente.');
    }

    public function show($id)
    {
        $horario = Horario::with(['estudiante.persona', 'profesor.persona', 'programa'])->findOrFail($id);
        return view('administrador.horariosShow', compact('horario'));
    }

    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        $estudiantes = Estudiante::with('persona')->get();
        $profesores = Profesor::with('persona')->get();
        $programas = Programa::all();

        return view('administrador.horariosEdit', compact('horario', 'estudiantes', 'profesores', 'programas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'Id_programas' => 'required|exists:programas,Id_programas',
            'Dia_clase_uno' => 'required|string',
            'Horario_clase_uno' => 'required|string',
            'Dia_clase_dos' => 'required|string',
            'Horario_clase_dos' => 'required|string',
        ]);

        $horario = Horario::findOrFail($id);
        $horario->update($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function asignar(Request $request)
    {
        $request->validate([
        'Id_estudiantes'     => 'required|exists:estudiantes,Id_estudiantes',
        'Id_profesores'      => 'required|exists:profesores,Id_profesores',
        'Id_programas'       => 'required|exists:programas,Id_programas',
        'Dia_clase_uno'      => 'required|string',
        'Horario_clase_uno'  => 'required|string',
        'Dia_clase_dos'      => 'required|string',
        'Horario_clase_dos'  => 'required|string',
        'horario_id'         => 'nullable|exists:horarios,Id_horarios',
    ]);

    $horario = $request->filled('horario_id')
        ? Horario::findOrFail($request->horario_id)
        : Horario::where('Id_estudiantes', $request->Id_estudiantes)->first();

    if (!$horario) {
        $horario = new Horario();
        $horario->Id_estudiantes = $request->Id_estudiantes;
    }

    $horario->Id_profesores      = $request->Id_profesores;
    $horario->Id_programas       = $request->Id_programas;
    $horario->Dia_clase_uno      = $request->Dia_clase_uno;
    $horario->Horario_clase_uno  = $request->Horario_clase_uno;
    $horario->Dia_clase_dos      = $request->Dia_clase_dos;
    $horario->Horario_clase_dos  = $request->Horario_clase_dos;

    $horario->save();

    return redirect()->route('horarios.index')->with('success', 'Horario y profesor asignados correctamente.');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente.');
    }
}
