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

        // Búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nombre', 'like', "%{$search}%")
                    ->orWhere('Apellido', 'like', "%{$search}%")
                    ->orWhere('Celular', 'like', "%{$search}%");
            });
        }

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

        $prospectos = $query->orderBy('created_at', 'desc')->paginate(6);
        $clasesPrueba = ClasePrueba::with(['profesor.persona', 'prospecto'])->get();
        $profesores = \App\Models\Profesor::with('persona')->get();
        return view('comercial.prospectosComercial', compact('prospectos', 'clasesPrueba', 'profesores'));
    }

    public function store(Request $request)
    {
        // Honeypot: si el campo oculto está lleno, es un bot
        if ($request->filled('website')) {
            return redirect()->back()->with('status', '¡Muchas gracias! Lo contactaremos a la brevedad.');
        }

        $request->validate([
            'nombres'   => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'apellidos' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.]+$/u'],
            'telefono'  => ['required', 'string', 'max:20', 'regex:/^[0-9\+\-\s\(\)]{7,20}$/'],
        ], [
            'nombres.regex'   => 'El nombre solo puede contener letras y espacios.',
            'apellidos.regex' => 'El apellido solo puede contener letras y espacios.',
            'telefono.regex'  => 'El teléfono solo puede contener números y los caracteres + - ( ).',
        ]);

        Prospecto::create([
            'Nombre'            => strip_tags(trim($request->nombres)),
            'Apellido'          => strip_tags(trim($request->apellidos)),
            'Celular'           => preg_replace('/[^\d\+\-\s\(\)]/', '', $request->telefono),
            'Estado_prospecto'  => 'nuevo',
            'Id_roles'          => 5,
        ]);

        return redirect()->back()->with('status', '¡Muchas gracias! Lo contactaremos a la brevedad.');
    }
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'Estado_prospecto' => 'required|in:nuevo,contactado,clase de prueba,para inscripcion,no asistio',
        ]);

        $prospecto = Prospecto::findOrFail($id);
        $prospecto->Estado_prospecto = $request->Estado_prospecto;
        $prospecto->save();

        return redirect()->back()->with('status', 'Estado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $prospecto = Prospecto::findOrFail($id);

        // Eliminar clases de prueba asociadas
        $prospecto->clasesPrueba()->delete();

        $prospecto->delete();

        return redirect()->back()->with('status', 'Prospecto eliminado correctamente.');
    }
}
