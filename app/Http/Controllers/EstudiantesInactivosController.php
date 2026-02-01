<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstudiantesInactivosController extends Controller
{
    /**
     * Muestra estudiantes inactivos filtrable por rango usando la columna Fecha_estado.
     */
    public function index(Request $request)
    {
        $from = $request->input('from'); // YYYY-MM-DD
        $to   = $request->input('to');   // YYYY-MM-DD
        $programa = $request->input('programa');
        $sucursal = $request->input('sucursal');
        $search = $request->input('search');

        // ====== TABLA ======
        $inactivosQuery = DB::table('estudiantes')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->leftJoin('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_sucursales')
            ->leftJoin('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->where('estudiantes.Estado', 'Inactivo');

        // Filtros de fecha
        if ($from && $to) {
            $inactivosQuery->whereBetween('estudiantes.Fecha_estado', [$from, $to]);
        } elseif ($from) {
            $inactivosQuery->whereDate('estudiantes.Fecha_estado', '>=', $from);
        } elseif ($to) {
            $inactivosQuery->whereDate('estudiantes.Fecha_estado', '<=', $to);
        }

        // Filtro de programa
        if ($programa) {
            $inactivosQuery->where('estudiantes.Id_programas', $programa);
        }

        // Filtro de sucursal
        if ($sucursal) {
            $inactivosQuery->where('estudiantes.Id_sucursales', $sucursal);
        }

        // Filtro de búsqueda por nombre y apellido
        if ($search) {
            $inactivosQuery->where(function($query) use ($search) {
                $query->where('personas.Nombre', 'LIKE', '%' . $search . '%')
                      ->orWhere('personas.Apellido', 'LIKE', '%' . $search . '%')
                      ->orWhereRaw("CONCAT(personas.Nombre, ' ', personas.Apellido) LIKE ?", ['%' . $search . '%']);
            });
        }

        $estudiantesInactivos = $inactivosQuery
            ->selectRaw('
                estudiantes.Id_estudiantes                                         AS id,
                personas.Nombre                                                     AS nombre,
                personas.Apellido                                                   AS apellido,
                COALESCE(sucursales.Nombre, "Sin asignar")                         AS sucursal,
                COALESCE(programas.Nombre, "Sin asignar")                          AS programa,
                estudiantes.Fecha_estado                                            AS fecha_inactivacion_iso,
                DATE_FORMAT(estudiantes.Fecha_estado, "%d/%m/%Y")                   AS fecha_inactivacion_fmt
            ')
            ->orderByDesc('estudiantes.Fecha_estado')
            ->get();

        // ====== SERIE PARA EL GRÁFICO (agrupado por mes) ======
        $serieQuery = DB::table('estudiantes')
            ->where('Estado', 'Inactivo');

        if ($from && $to) {
            $serieQuery->whereBetween('Fecha_estado', [$from, $to]);
        } elseif ($from) {
            $serieQuery->whereDate('Fecha_estado', '>=', $from);
        } elseif ($to) {
            $serieQuery->whereDate('Fecha_estado', '<=', $to);
        }

        if ($programa) {
            $serieQuery->where('Id_programas', $programa);
        }

        if ($sucursal) {
            $serieQuery->where('Id_sucursales', $sucursal);
        }

        $fechasInactivacion = $serieQuery
            ->selectRaw('
                DATE_FORMAT(Fecha_estado, "%Y-%m-01") AS mes_iso,
                COUNT(*) AS cantidad
            ')
            ->groupBy('mes_iso')
            ->orderBy('mes_iso')
            ->get();

        // Si no hay datos, mostrar mes actual con 0
        if ($fechasInactivacion->isEmpty()) {
            $fechasInactivacion = collect([
                ['mes_iso' => now()->format('Y-m-01'), 'cantidad' => 0]
            ]);
        }

        // Calcular estadísticas para los dropdowns
        $mesesDisponibles = DB::table('estudiantes')
            ->where('Estado', 'Inactivo')
            ->selectRaw('
                DATE_FORMAT(Fecha_estado, "%Y-%m") AS mes,
                DATE_FORMAT(Fecha_estado, "%M %Y") AS mes_nombre,
                COUNT(*) AS total
            ')
            ->groupBy('mes', 'mes_nombre')
            ->orderByDesc('mes')
            ->limit(12)
            ->get();

        // Obtener lista de programas
        $programas = DB::table('programas')
            ->select('Id_programas', 'Nombre')
            ->orderBy('Nombre')
            ->get();

        // Obtener lista de sucursales
        $sucursales = DB::table('sucursales')
            ->select('Id_sucursales', 'Nombre')
            ->orderBy('Nombre')
            ->get();

        return view('comercial.estudiantesNoActivos', [
            'estudiantesInactivos' => $estudiantesInactivos,
            'fechasInactivacion'   => $fechasInactivacion,
            'mesesDisponibles'     => $mesesDisponibles,
            'programas'            => $programas,
            'sucursales'           => $sucursales,
            'from'                 => $from,
            'to'                   => $to,
            'programa'             => $programa,
            'sucursal'             => $sucursal,
            'search'               => $search,
        ]);
    }

    /**
     * Cambia el estado de Inactivo a Activo.
     */
    public function reactivar($id)
    {
        $affected = DB::table('estudiantes')
            ->where('Id_estudiantes', $id)
            ->where('Estado', 'Inactivo') // Solo reactivar si está inactivo
            ->update([
                'Estado'       => 'Activo',
                'Fecha_estado' => now()->format('Y-m-d'),
            ]);

        if ($affected === 0) {
            return back()->withErrors(['error' => 'Estudiante no encontrado o ya estaba activo.']);
        }

        return redirect()
            ->route('estudiantesNoActivos')
            ->with('success', 'Estudiante reactivado correctamente.');
    }
}