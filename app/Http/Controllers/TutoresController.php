<?php

namespace App\Http\Controllers;

use App\Models\Tutores;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Http\Request;

class TutoresController extends Controller
{
    /**
     * Mostrar todos los tutores con relaciones: persona y usuario
     */
    public function index()
    {
        $tutores = Tutores::with(['persona', 'usuario'])
                    ->orderBy('Id_tutores', 'desc')
                    ->paginate(10);

        return view('administrador.tutoresAdministrador', compact('tutores'));
    }

    /**
     * Mostrar los detalles de un tutor específico (AJAX)
     */
    public function show($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

        return response()->json([
            'Nombre' => $tutor->persona->Nombre,
            'Apellido' => $tutor->persona->Apellido,
            'Celular' => $tutor->persona->Celular,
            'Direccion_domicilio' => $tutor->persona->Direccion_domicilio,
            'Parentesco' => $tutor->Parentesco,
            'Correo' => $tutor->usuario->Correo,
            'Nit' => $tutor->Nit,
            'Descuento' => $tutor->Descuento,
        ]);
    }

    /**
     * Devuelve los datos del tutor para editar (AJAX)
     */
    public function edit($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

        return response()->json([
            'Id_tutores' => $tutor->Id_tutores,
            'Nombre' => $tutor->persona->Nombre,
            'Apellido' => $tutor->persona->Apellido,
            'Celular' => $tutor->persona->Celular,
            'Direccion_domicilio' => $tutor->persona->Direccion_domicilio,
            'Parentesco' => $tutor->Parentesco,
            'Correo' => $tutor->usuario->Correo,
            'Nit' => $tutor->Nit,
            'Descuento' => $tutor->Descuento,
        ]);
    }

    /**
     * Actualiza los datos de persona, tutor y opcionalmente usuario
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'direccion_domicilio' => 'required|string|max:255',
            'parentezco' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'descuento' => 'nullable|numeric|min:0|max:100',
            'nit' => 'nullable|string|max:255',
        ]);

        try {
            $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

            $tutor->persona->update([
                'Nombre' => $request->nombre,
                'Apellido' => $request->apellido,
                'Celular' => $request->celular,
                'Direccion_domicilio' => $request->direccion_domicilio,
            ]);

            $tutor->update([
                'Parentesco' => $request->parentezco,
                'Descuento' => $request->descuento,
                'Nit' => $request->nit,
            ]);

            $tutor->usuario->update([
                'Correo' => $request->correo,
            ]);

            return redirect()->back()->with('success', 'Datos del tutor actualizados correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar tutor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el tutor.');
        }
    }

    /**
     * Elimina el tutor, su usuario y persona relacionada
     */
    public function destroy($id)
    {
        try {
            $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

            $persona = $tutor->persona;
            $usuario = $tutor->usuario;

            $tutor->delete();
            if ($usuario) $usuario->delete();
            if ($persona) $persona->delete();

            return response()->json(['success' => true, 'message' => 'Tutor eliminado correctamente.']);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar tutor: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el tutor.']);
        }
    }
}
 