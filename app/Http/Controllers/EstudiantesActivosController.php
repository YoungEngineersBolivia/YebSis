<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstudiantesActivosController extends Controller
{
    /**
     * Muestra estudiantes activos filtrable por rango usando la columna Fecha_estado.
     */
    public function index(Request $request)
    {
        $from = $request->input('from'); // YYYY-MM-DD
        $to   = $request->input('to');   // YYYY-MM-DD
        $programa = $request->input('programa');
        $sucursal = $request->input('sucursal');
        $search = $request->input('search');

        // ====== TABLA ======
        $activosQuery = DB::table('estudiantes')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->leftJoin('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_sucursales')
            ->leftJoin('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->where('estudiantes.Estado', 'Activo');

        // Filtros de fecha
        if ($from && $to) {
            $activosQuery->whereBetween('estudiantes.Fecha_estado', [$from, $to]);
        } elseif ($from) {
            $activosQuery->whereDate('estudiantes.Fecha_estado', '>=', $from);
        } elseif ($to) {
            $activosQuery->whereDate('estudiantes.Fecha_estado', '<=', $to);
        }

        // Filtro de programa
        if ($programa) {
            $activosQuery->where('estudiantes.Id_programas', $programa);
        }

        // Filtro de sucursal
        if ($sucursal) {
            $activosQuery->where('estudiantes.Id_sucursales', $sucursal);
        }

        // Filtro de búsqueda por nombre y apellido
        if ($search) {
            $activosQuery->where(function($query) use ($search) {
                $query->where('personas.Nombre', 'LIKE', '%' . $search . '%')
                      ->orWhere('personas.Apellido', 'LIKE', '%' . $search . '%')
                      ->orWhereRaw("CONCAT(personas.Nombre, ' ', personas.Apellido) LIKE ?", ['%' . $search . '%']);
            });
        }

        $estudiantesActivos = $activosQuery
            ->selectRaw('
                estudiantes.Id_estudiantes                                         AS id,
                personas.Nombre                                                     AS nombre,
                personas.Apellido                                                   AS apellido,
                COALESCE(sucursales.Nombre, "Sin asignar")                         AS sucursal,
                COALESCE(programas.Nombre, "Sin asignar")                          AS programa,
                estudiantes.Fecha_estado                                            AS fecha_activacion_iso,
                DATE_FORMAT(estudiantes.Fecha_estado, "%d/%m/%Y")                   AS fecha_activacion_fmt
            ')
            ->orderByDesc('estudiantes.Fecha_estado')
            ->get();

        // ====== SERIE PARA EL GRÁFICO (agrupado por mes) ======
        $serieQuery = DB::table('estudiantes')
            ->where('Estado', 'Activo');

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

        $fechasActivacion = $serieQuery
            ->selectRaw('
                DATE_FORMAT(Fecha_estado, "%Y-%m-01") AS mes_iso,
                COUNT(*) AS cantidad
            ')
            ->groupBy('mes_iso')
            ->orderBy('mes_iso')
            ->get();

        // Si no hay datos, mostrar mes actual con 0
        if ($fechasActivacion->isEmpty()) {
            $fechasActivacion = collect([
                ['mes_iso' => now()->format('Y-m-01'), 'cantidad' => 0]
            ]);
        }

        // Calcular estadísticas para los dropdowns
        $mesesDisponibles = DB::table('estudiantes')
            ->where('Estado', 'Activo')
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

        return view('comercial.estudiantesActivosComercial', [
            'estudiantesActivos' => $estudiantesActivos,
            'fechasActivacion'   => $fechasActivacion,
            'mesesDisponibles'   => $mesesDisponibles,
            'programas'          => $programas,
            'sucursales'         => $sucursales,
            'from'               => $from,
            'to'                 => $to,
            'programa'           => $programa,
            'sucursal'           => $sucursal,
            'search'             => $search,
        ]);
    }

    /**
     * Cambia el estado de Activo a Inactivo.
     */
    public function desactivar($id)
    {
        $affected = DB::table('estudiantes')
            ->where('Id_estudiantes', $id)
            ->where('Estado', 'Activo') // Solo desactivar si está activo
            ->update([
                'Estado'       => 'Inactivo',
                'Fecha_estado' => now()->format('Y-m-d'),
            ]);

        if ($affected === 0) {
            return back()->withErrors(['error' => 'Estudiante no encontrado o ya estaba inactivo.']);
        }

        return redirect()
            ->route('estudiantesActivos')
            ->with('success', 'Estudiante desactivado correctamente.');
    }
}