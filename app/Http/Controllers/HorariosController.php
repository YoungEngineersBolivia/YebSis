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
            ->paginate(6);

        // Necesitamos estudiantes y profesores para las vistas
        $estudiantes = Estudiante::with(['persona', 'profesor.persona', 'programa'])->get();
        $profesores = Profesor::with('persona')->get();

        return view('administrador.horariosAdministrador', compact('horarios', 'estudiantes', 'profesores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Id_estudiantes' => 'required|exists:estudiantes,Id_estudiantes',
            'Id_profesores'  => 'required|exists:profesores,Id_profesores',
            'horarios'       => 'required|array|min:1',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora'=> 'required'
        ]);

        // Obtener el estudiante para extraer su programa
        $estudiante = Estudiante::findOrFail($request->Id_estudiantes);

        // Validar que el estudiante tenga programa asignado
        if (!$estudiante->Id_programas) {
            return redirect()->back()
                ->withErrors(['error' => 'El estudiante seleccionado no tiene un programa asignado.'])
                ->withInput();
        }

        // Solo asignar profesor al estudiante si NO tiene ninguno aún
        // Esto permite que diferentes horarios tengan diferentes profesores
        if (!$estudiante->Id_profesores) {
            $estudiante->Id_profesores = $request->Id_profesores;
            $estudiante->save();
        }

        // Crear múltiples horarios (cada uno puede tener un profesor diferente)
        $horariosCreados = 0;
        foreach ($request->horarios as $horario) {
            Horario::create([
                'Id_estudiantes' => $request->Id_estudiantes,
                'Id_profesores'  => $request->Id_profesores, // Profesor específico de ESTE horario
                'Id_programas'   => $estudiante->Id_programas,
                'Dia'            => $horario['dia'],
                'Hora'           => $horario['hora']
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
            'Id_profesores'  => 'required|exists:profesores,Id_profesores',
            'Dia'            => 'required|string',
            'Hora'           => 'required'
        ]);

        // Obtener el estudiante para extraer su programa
        $estudiante = Estudiante::findOrFail($request->Id_estudiantes);

        // Validar que el estudiante tenga programa asignado
        if (!$estudiante->Id_programas) {
            return redirect()->back()
                ->withErrors(['error' => 'El estudiante seleccionado no tiene un programa asignado.'])
                ->withInput();
        }

        // Si el estudiante no tiene profesor asignado, asignárselo
        if (!$estudiante->Id_profesores) {
            $estudiante->Id_profesores = $request->Id_profesores;
            $estudiante->save();
        }

        $horario = Horario::findOrFail($id);
        $horario->update([
            'Id_estudiantes' => $request->Id_estudiantes,
            'Id_profesores'  => $request->Id_profesores,
            'Id_programas'   => $estudiante->Id_programas,
            'Dia'            => $request->Dia,
            'Hora'           => $request->Hora
        ]);

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente.');
    }

    // Endpoint para obtener datos del estudiante via AJAX (opcional)
    public function buscarEstudiante($idEstudiante)
    {
        $estudiante = Estudiante::with(['profesor.persona', 'programa'])->find($idEstudiante);

        if (!$estudiante) {
            return response()->json(['error' => 'Estudiante no encontrado'], 404);
        }

        return response()->json([
            'Id_profesores' => $estudiante->Id_profesores,
            'Id_programas'  => $estudiante->Id_programas,
            'profesor_nombre' => $estudiante->profesor ? 
                $estudiante->profesor->persona->Nombre . ' ' . $estudiante->profesor->persona->Apellido : 
                'Sin profesor',
            'programa_nombre' => $estudiante->programa ? $estudiante->programa->Nombre : 'Sin programa'
        ]);
    }
}