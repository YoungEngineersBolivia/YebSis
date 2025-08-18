<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
{
    // Tomamos la primera sucursal como ejemplo
   $sucursales = Sucursal::all(); // colección de todas las sucursales

    $alumnosPorSucursal = [];

    foreach ($sucursales as $sucursal) {
        $alumnosPorSucursal[$sucursal->Id_Sucursales] = DB::table('estudiantes')
            ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->select('programas.Nombre as programa', DB::raw('count(*) as total'))
            ->where('estudiantes.Id_sucursales', $sucursal->Id_Sucursales)
            ->groupBy('programas.Nombre')
            ->get();
    }

    return view('administrador.dashboard', compact('sucursales', 'alumnosPorSucursal'));


    // Total de alumnos por todas las sucursales
    $sucursales = DB::table('estudiantes')
        ->join('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_Sucursales')
        ->select('sucursales.Nombre', DB::raw('count(*) as total'))
        ->groupBy('sucursales.Nombre')
        ->get();

    return view('administrador.dashboard', compact('sucursal', 'alumnos', 'sucursales'));
}

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
