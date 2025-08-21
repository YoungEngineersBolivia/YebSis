<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Pago;              // <-- importa Pago
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $sucursales = Sucursal::all();

        // Alumnos por programa por sucursal
        $alumnosPorSucursal = [];
        foreach ($sucursales as $sucursal) {
            $alumnosPorSucursal[$sucursal->Id_Sucursales] = DB::table('estudiantes')
                ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
                ->select('programas.Nombre as programa', DB::raw('COUNT(*) as total'))
                ->where('estudiantes.Id_sucursales', $sucursal->Id_Sucursales)
                ->groupBy('programas.Nombre')
                ->get();
        }

        // Totales de alumnos por sucursal
        $totalAlumnosPorSucursal = DB::table('estudiantes')
            ->join('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_Sucursales')
            ->select('sucursales.Nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('sucursales.Nombre')
            ->get();

        // KPIs (ingresos reales desde pagos)
        $ingresosTotales = Pago::sum('Monto_pago') ?? 0;

        // Si aún no tienes egresos, déjalo en 0
        $egresosTotales  = 0;
        // Si ya tienes el modelo Egreso:
        // use App\Models\Egreso;
        // $egresosTotales = Egreso::sum('Monto_egreso') ?? 0;

        return view('administrador.dashboard', compact(
            'sucursales',
            'alumnosPorSucursal',
            'totalAlumnosPorSucursal',
            'ingresosTotales',
            'egresosTotales'
        ));
    }
}
