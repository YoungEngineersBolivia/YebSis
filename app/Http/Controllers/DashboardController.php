<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $porProgramaSucursal = DB::table('estudiantes as e')
            ->join('programas as p', 'p.Id_programas', '=', 'e.Id_programas')
            ->join('sucursales as s', 's.Id_Sucursales', '=', 'e.Id_sucursales')
            ->select('p.Nombre as programa', 's.Nombre as sucursal', DB::raw('COUNT(*) as total'))
            ->groupBy('p.Nombre', 's.Nombre')
            ->orderBy('p.Nombre')
            ->get()
            ->groupBy('sucursal');

        $conteoEstados = DB::table('estudiantes')
            ->select('Estado', DB::raw('COUNT(*) as total'))
            ->groupBy('Estado')
            ->pluck('total', 'Estado');

        $activos   = $conteoEstados['ACTIVO']   ?? $conteoEstados['Activo']   ?? 0;
        $inactivos = $conteoEstados['INACTIVO'] ?? $conteoEstados['Inactivo'] ?? 0;

        $porSucursal = DB::table('estudiantes as e')
            ->join('sucursales as s', 's.Id_Sucursales', '=', 'e.Id_sucursales')
            ->select('s.Nombre as sucursal', DB::raw('COUNT(*) as total'))
            ->groupBy('s.Nombre')
            ->orderBy('s.Nombre')
            ->get();

        $ingresosTotales = (float) DB::table('pagos')->sum('Monto_pago');
        $egresosTotales  = (float) DB::table('egresos')->sum('Monto_egreso');

        return view('administrador.dashboardAdministrador', compact(
            'porProgramaSucursal',
            'activos',
            'inactivos',
            'porSucursal',
            'ingresosTotales',
            'egresosTotales'
        ));
    }
}
