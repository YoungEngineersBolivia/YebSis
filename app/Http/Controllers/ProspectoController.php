<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prospecto;
use App\Models\ClasePrueba;

class ProspectoController extends Controller
{
    public function index(Request $request)
{
    $query = Prospecto::query();

    // Filtro rápido
    if ($request->filled('filtro_rapido')) {
        switch ($request->filtro_rapido) {
            case 'ultimos7':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case 'ayer':
                $query->whereDate('created_at', now()->subDay());
                break;
            case 'mespasado':
                $query->whereMonth('created_at', now()->subMonth()->month)
                      ->whereYear('created_at', now()->subMonth()->year);
                break;
            case 'ultimos3meses':
                $query->where('created_at', '>=', now()->subMonths(3));
                break;
            case 'esteano':
                $query->whereYear('created_at', now()->year);
                break;
            case 'anopasado':
                $query->whereYear('created_at', now()->subYear()->year);
                break;
        }
    }

    // Filtro personalizado por fechas
    if ($request->filled('desde')) {
        $query->whereDate('created_at', '>=', $request->desde);
    }
    if ($request->filled('hasta')) {
        $query->whereDate('created_at', '<=', $request->hasta);
    }

    $prospectos = $query->orderBy('created_at', 'desc')->get();
    $clasesPrueba = ClasePrueba::all();
    return view('comercial.prospectosComercial', compact('prospectos', 'clasesPrueba'));
}

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        // Crear prospecto
        Prospecto::create([
            'Nombre' => $request->nombres,
            'Apellido' => $request->apellidos,
            'Celular' => $request->telefono,
            'Estado_prospecto' => 'nuevo', // por defecto
            'Id_roles' => 5, // ejemplo, puedes ajustar
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', '¡Gracias! Nos contactaremos contigo pronto.');
    }
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'Estado_prospecto' => 'required|in:nuevo,contactado,clase de prueba',
        ]);

        $prospecto = Prospecto::findOrFail($id);
        $prospecto->Estado_prospecto = $request->Estado_prospecto;
        $prospecto->save();

        return redirect()->back()->with('status', 'Estado actualizado correctamente.');
    }
}
