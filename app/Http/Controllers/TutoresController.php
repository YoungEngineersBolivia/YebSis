<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutores; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutoresController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $tutores = Tutores::query()
            ->whereHas('persona', function ($query) use ($search) {
                $query->where('Nombre', 'like', "%$search%")
                    ->orWhere('Apellido', 'like', "%$search%")
                    ->orWhere('Celular', 'like', "%$search%")
                    ->orWhere('Direccion_domicilio', 'like', "%$search%");
            })
            ->orWhereHas('usuario', function ($query) use ($search) {
                $query->where('Correo', 'like', "%$search%");
            })
            ->paginate(10);

        return view('administrador.tutoresAdministrador', compact('tutores'));
    }

    // Nuevo método para ver detalles
    public function verDetalles($id)
    {
        $tutor = Tutores::with([
            'persona',
            'usuario',
            'estudiantes.persona',
            'estudiantes.programa',
            'estudiantes.sucursal',
            'estudiantes.planPago.cuotas' => function($query) {
                $query->orderBy('Fecha_vencimiento', 'asc');
            },
            'estudiantes.planPago.programa'
        ])->findOrFail($id);

        return view('administrador.detallesTutor', compact('tutor'));
    }

    public function show($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);
        return response()->json([
            'persona' => $tutor->persona,
            'usuario' => $tutor->usuario,
            'Parentesco' => $tutor->Parentesco,
            'Descuento' => $tutor->Descuento,
            'Nit' => $tutor->Nit,
        ]);
    }

    public function edit($id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);
        return response()->json([
            'persona' => $tutor->persona,
            'usuario' => $tutor->usuario,
            'Parentesco' => $tutor->Parentesco,
            'Descuento' => $tutor->Descuento,
            'Nit' => $tutor->Nit,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tutor = Tutores::with(['persona', 'usuario'])->findOrFail($id);

        $tutor->persona->Nombre = $request->nombre;
        $tutor->persona->Apellido = $request->apellido;
        $tutor->persona->Celular = $request->celular;
        $tutor->persona->Direccion_domicilio = $request->direccion_domicilio;
        $tutor->persona->save();

        $tutor->usuario->Correo = $request->correo;
        $tutor->usuario->save();

        $tutor->Parentesco = $request->parentezco;
        $tutor->Descuento = $request->descuento;
        $tutor->Nit = $request->nit;
        $tutor->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $tutor = Tutores::findOrFail($id);
        $tutor->delete();
        return response()->json(['success' => true]);
    }
}