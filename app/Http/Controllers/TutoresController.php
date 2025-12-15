<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutores;

class TutoresController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $tutores = Tutores::with(['persona', 'usuario'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('persona', function ($p) use ($search) {
                        $p->where('Nombre', 'like', "%{$search}%")
                          ->orWhere('Apellido', 'like', "%{$search}%")
                          ->orWhere('Celular', 'like', "%{$search}%")
                          ->orWhere('Direccion_domicilio', 'like', "%{$search}%");
                    })->orWhereHas('usuario', function ($u) use ($search) {
                        $u->where('Correo', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('Id_tutores', 'desc')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('administrador.tutoresAdministrador', compact('tutores', 'search'));
    }

    // Nuevo método para mostrar la vista de detalles
    public function detalles($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);
        return view('administrador.detallesTutor', compact('tutor'));
    }

    public function edit($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

        return response()->json([
            'Id_tutores'  => $tutor->Id_tutores,
            'Parentesco'  => $tutor->Parentesco,
            'Descuento'   => $tutor->Descuento,
            'Nit'         => $tutor->Nit,
            'persona'     => [
                'Nombre'              => $tutor->persona->Nombre ?? '',
                'Apellido'            => $tutor->persona->Apellido ?? '',
                'Celular'             => $tutor->persona->Celular ?? '',
                'Direccion_domicilio' => $tutor->persona->Direccion_domicilio ?? '',
            ],
            'usuario'     => [
                'Correo' => $tutor->usuario->Correo ?? '',
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

        // Validación básica
        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'apellido'           => 'required|string|max:255',
            'celular'            => 'nullable|string|max:50',
            'direccion_domicilio'=> 'nullable|string|max:255',
            'correo'             => 'required|email|max:255',
            'parentezco'         => 'required|string|max:100',
            'descuento'          => 'nullable|numeric|min:0|max:100',
            'nit'                => 'nullable|string|max:50',
        ]);

        // Actualizar persona
        $tutor->persona->Nombre               = $validated['nombre'];
        $tutor->persona->Apellido             = $validated['apellido'];
        $tutor->persona->Celular              = $validated['celular'] ?? null;
        $tutor->persona->Direccion_domicilio  = $validated['direccion_domicilio'] ?? null;
        $tutor->persona->save();

        // Actualizar usuario
        $tutor->usuario->Correo = $validated['correo'];
        $tutor->usuario->save();

        // Actualizar tutor
        $tutor->Parentesco = $validated['parentezco'];
        $tutor->Descuento  = $validated['descuento'] ?? null;
        $tutor->Nit        = $validated['nit'] ?? null;
        $tutor->save();

        // Redirigir de vuelta a la vista de detalles con mensaje de éxito
        return redirect()->route('tutores.detalles', $id)->with('success', 'Tutor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $tutor = Tutores::findOrFail($id);
        $tutor->delete();

        return response()->json(['success' => true]);
    }
}