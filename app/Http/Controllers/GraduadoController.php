<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Graduados;
use App\Models\Estudiante;
use App\Models\Programa;
use App\Models\Profesor;

class GraduadoController extends Controller
{
    // Mostrar todos los graduados
    public function mostrarGraduados()
    {
        $graduados = Graduados::with(['estudiante.persona', 'programa', 'profesor.persona'])->get();
        $estudiantes = Estudiante::with('persona')->get();
        $programas   = Programa::all();
        $profesores  = Profesor::with('persona')->get();

        return view('administrador.graduadosAdministrador', compact('graduados', 'estudiantes', 'programas', 'profesores'));
    }

    // Agregar un graduado
    public function agregarGraduado(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,Id_estudiantes',
            'programa_id'   => 'required|exists:programas,Id_programas',
            'profesor_id'   => 'required|exists:profesores,Id_profesores',
            'Fecha_graduado'=> 'required|date',
        ]);

        Graduados::create([
            'Id_estudiantes' => $request->estudiante_id,
            'Id_programas'   => $request->programa_id,
            'Id_profesores'  => $request->profesor_id,
            'Fecha_graduado' => $request->Fecha_graduado,
        ]);

        return redirect()->route('graduados.mostrar')->with('success', 'Graduado agregado correctamente');
    }

    // Actualizar un graduado
    public function actualizarGraduado(Request $request, $id)
    {
        $graduado = Graduados::findOrFail($id);

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,Id_estudiantes',
            'programa_id'   => 'required|exists:programas,Id_programas',
            'profesor_id'   => 'required|exists:profesores,Id_profesores',
            'Fecha_graduado'=> 'required|date',
        ]);

        $graduado->update([
            'Id_estudiantes' => $request->estudiante_id,
            'Id_programas'   => $request->programa_id,
            'Id_profesores'  => $request->profesor_id,
            'Fecha_graduado' => $request->Fecha_graduado,
        ]);

        return redirect()->route('graduados.mostrar')->with('success', 'Graduado actualizado correctamente');
    }

    // Eliminar un graduado
    public function eliminarGraduado($id)
    {
        $graduado = Graduados::findOrFail($id);
        $graduado->delete();

        return redirect()->route('graduados.mostrar')->with('success', 'Graduado eliminado correctamente');
    }

    // Ver un graduado
    public function verGraduado($id) {
    return redirect()->route('graduados.mostrar');
}

}
