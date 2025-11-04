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

        // ====== TABLA ======
        $activosQuery = DB::table('estudiantes')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->where('estudiantes.Estado', 'Activo');

        if ($from && $to) {
            $activosQuery->whereBetween('estudiantes.Fecha_estado', [$from, $to]);
        } elseif ($from) {
            $activosQuery->whereDate('estudiantes.Fecha_estado', '>=', $from);
        } elseif ($to) {
            $activosQuery->whereDate('estudiantes.Fecha_estado', '<=', $to);
        }

        $estudiantesActivos = $activosQuery
            ->selectRaw('
                estudiantes.Id_estudiantes                                         AS id,
                personas.Nombre                                                     AS nombre,
                personas.Apellido                                                   AS apellido,
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

        return view('comercial.estudiantesActivosComercial', [
            'estudiantesActivos' => $estudiantesActivos,
            'fechasActivacion'   => $fechasActivacion,
            'mesesDisponibles'   => $mesesDisponibles,
            'from'               => $from,
            'to'                 => $to,
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