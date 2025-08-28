<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstudiantesInactivosController extends Controller
{
    /**
     * Listado y gráfica de estudiantes inactivos (MySQL/MariaDB),
     * filtrable por rango usando la columna Fecha_estado.
     */
    public function index(Request $request)
    {
        $from = $request->input('from'); // YYYY-MM-DD
        $to   = $request->input('to');   // YYYY-MM-DD

        // ====== TABLA ======
        $inactivosQuery = DB::table('estudiantes')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->where('estudiantes.Estado', 'Inactivo');

        if ($from && $to) {
            $inactivosQuery->whereBetween('estudiantes.Fecha_estado', [$from, $to]);
        } elseif ($from) {
            $inactivosQuery->whereDate('estudiantes.Fecha_estado', '>=', $from);
        } elseif ($to) {
            $inactivosQuery->whereDate('estudiantes.Fecha_estado', '<=', $to);
        }

        $estudiantesInactivos = $inactivosQuery
            ->selectRaw('
                estudiantes.Id_estudiantes                                         AS id,
                personas.Nombre                                                     AS nombre,
                personas.Apellido                                                   AS apellido,
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

        // mes_iso = primer día del mes, para formatear en JS
        $fechasInactivacion = $serieQuery
            ->selectRaw('
                DATE_FORMAT(Fecha_estado, "%Y-%m-01") AS mes_iso,
                COUNT(*)                                AS cantidad
            ')
            ->groupBy('mes_iso')
            ->orderBy('mes_iso')
            ->get();

        return view('comercial.estudiantesNoActivos', [
            'estudiantesInactivos' => $estudiantesInactivos,
            'fechasInactivacion'   => $fechasInactivacion,
            'from'                 => $from,
            'to'                   => $to,
        ]);
    }

    /**
     * Cambia el estado de Inactivo a Activo.
     * Si no quieres tocar Fecha_estado al reactivar, elimina la línea correspondiente.
     */
    public function reactivar($id)
    {
        $affected = DB::table('estudiantes')
            ->where('Id_estudiantes', $id)
            ->update([
                'Estado'       => 'Activo',
                'Fecha_estado' => now()->format('Y-m-d'), // quita esto si no debe cambiar
            ]);

        if ($affected === 0) {
            return back()->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        return redirect()
            ->route('estudiantesNoActivos')
            ->with('success', 'Estudiante reactivado correctamente.');
    }
}
