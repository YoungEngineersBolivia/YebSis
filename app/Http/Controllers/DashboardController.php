<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener todas las sucursales
        $sucursales = Sucursal::all();

        // Contar alumnos por programa en cada sucursal
        $alumnosPorSucursal = [];
        foreach ($sucursales as $sucursal) {
            $alumnosPorSucursal[$sucursal->Id_Sucursales] = DB::table('estudiantes')
                ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
                ->select('programas.Nombre as programa', DB::raw('count(*) as total'))
                ->where('estudiantes.Id_sucursales', $sucursal->Id_Sucursales)
                ->groupBy('programas.Nombre')
                ->get();
        }

        // Total de alumnos por todas las sucursales
        $totalAlumnosPorSucursal = DB::table('estudiantes')
            ->join('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_Sucursales')
            ->select('sucursales.Nombre', DB::raw('count(*) as total'))
            ->groupBy('sucursales.Nombre')
            ->get();

        // Calcular ingresos totales desde la tabla pagos
        $ingresosTotales = DB::table('pagos')->sum('Monto_pago') ?? 0;

        // Calcular egresos totales (asumiendo que existe una tabla egresos)
        $egresosTotales = DB::table('egresos')->sum('Monto_egreso') ?? 0;

        return view('administrador.dashboard', compact(
            'sucursales', 
            'alumnosPorSucursal', 
            'totalAlumnosPorSucursal',
            'ingresosTotales',
            'egresosTotales'
        ));
    }

    public function dashboard($sucursalId)
    {
        // Sucursal seleccionada
        $sucursal = Sucursal::findOrFail($sucursalId);

        // Alumnos por programa en esa sucursal
        $alumnos = DB::table('estudiantes')
            ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->select('programas.Nombre as programa', DB::raw('count(*) as total'))
            ->where('estudiantes.Id_sucursales', $sucursalId)
            ->groupBy('programas.Nombre')
            ->get();

        // Total de alumnos por todas las sucursales
        $sucursales = DB::table('estudiantes')
            ->join('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_Sucursales')
            ->select('sucursales.Nombre', DB::raw('count(*) as total'))
            ->groupBy('sucursales.Nombre')
            ->get();

        $ingresosTotales = Pago::sum('Monto_pago');

    return view('administrador.dashboard', compact('sucursales', 'alumnosPorSucursal', 'totalAlumnosPorSucursal', 'ingresosTotales'));

    }
}
<<<<<<< HEAD

public function dashboard($sucursalId)
{
    // sucursal seleccionada
    $sucursal = Sucursal::findOrFail($sucursalId);

    // alumnos por programa en esa sucursal
    $alumnos = DB::table('estudiantes')
        ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
        ->select('programas.Nombre as programa', DB::raw('count(*) as total'))
        ->where('estudiantes.Id_sucursales', $sucursalId)
        ->groupBy('programas.Nombre')
        ->get();

    // todas las sucursales con total de alumnos
    $sucursales = DB::table('estudiantes')
        ->join('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_Sucursales')
        ->select('sucursales.Nombre', DB::raw('count(*) as total'))
        ->groupBy('sucursales.Nombre')
        ->get();

    return view('administrador.dashboard', compact('sucursal', 'alumnos', 'sucursales'));
}

}
=======
>>>>>>> 16f888cbb9339c9b68084012e1379ce5293c8212
