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
        $to = $request->input('to');   // YYYY-MM-DD
        $programa = $request->input('programa');
        $sucursal = $request->input('sucursal');
        $search = $request->input('search');

        // ====== TABLA ======
        $inactivosQuery = DB::table('estudiantes')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->leftJoin('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_sucursales')
            ->leftJoin('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->leftJoin('tutores', 'estudiantes.Id_tutores', '=', 'tutores.Id_tutores')
            ->leftJoin('personas as tutor_persona', 'tutores.Id_personas', '=', 'tutor_persona.Id_personas')
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
            $inactivosQuery->where(function ($query) use ($search) {
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
                personas.Celular                                                    AS celular_estudiante,
                COALESCE(tutor_persona.Celular, "Sin celular")                     AS celular_tutor,
                COALESCE(tutor_persona.Nombre, "")                                 AS nombre_tutor,
                COALESCE(tutor_persona.Apellido, "")                               AS apellido_tutor,
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
            'fechasInactivacion' => $fechasInactivacion,
            'mesesDisponibles' => $mesesDisponibles,
            'programas' => $programas,
            'sucursales' => $sucursales,
            'from' => $from,
            'to' => $to,
            'programa' => $programa,
            'sucursal' => $sucursal,
            'search' => $search,
        ]);
    }

    public function exportar(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $programa = $request->input('programa');
        $sucursal = $request->input('sucursal');
        $search = $request->input('search');

        $inactivosQuery = DB::table('estudiantes')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->leftJoin('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_sucursales')
            ->leftJoin('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->leftJoin('tutores', 'estudiantes.Id_tutores', '=', 'tutores.Id_tutores')
            ->leftJoin('personas as tutor_persona', 'tutores.Id_personas', '=', 'tutor_persona.Id_personas')
            ->where('estudiantes.Estado', 'Inactivo');

        if ($from && $to)
            $inactivosQuery->whereBetween('estudiantes.Fecha_estado', [$from, $to]);
        if ($programa)
            $inactivosQuery->where('estudiantes.Id_programas', $programa);
        if ($sucursal)
            $inactivosQuery->where('estudiantes.Id_sucursales', $sucursal);
        if ($search) {
            $inactivosQuery->where(function ($query) use ($search) {
                $query->where('personas.Nombre', 'LIKE', '%' . $search . '%')
                    ->orWhere('personas.Apellido', 'LIKE', '%' . $search . '%');
            });
        }

        $datos = $inactivosQuery->selectRaw('
            personas.Nombre, personas.Apellido, personas.Celular as celular_estudiante,
            tutor_persona.Nombre as nombre_tutor, tutor_persona.Celular as celular_tutor,
            sucursales.Nombre as sucursal, programas.Nombre as programa,
            DATE_FORMAT(estudiantes.Fecha_estado, "%d/%m/%Y") as fecha_inactivacion
        ')->get();

        $filename = "estudiantes_inactivos_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($datos) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Nombre', 'Apellido', 'Celular Padres/Alumno', 'Contacto Ref.', 'Programa', 'Sucursal', 'Fecha Inactivacion']);

            foreach ($datos as $row) {
                $celular = ($row->celular_tutor && $row->celular_tutor != 'Sin celular') ? $row->celular_tutor : ($row->celular_estudiante ?? 'No registrado');
                $ref = ($row->celular_tutor && $row->celular_tutor != 'Sin celular') ? 'Tutor: ' . $row->nombre_tutor : 'Estudiante';

                fputcsv($file, [$row->Nombre, $row->Apellido, $celular, $ref, $row->programa, $row->sucursal, $row->fecha_inactivacion]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
                'Estado' => 'Activo',
                'Fecha_estado' => now()->format('Y-m-d'),
            ]);

        if ($affected === 0) {
            return back()->withErrors(['error' => 'Estudiante no encontrado o ya estaba activo.']);
        }

        return back()
            ->with('success', 'Estudiante reactivado correctamente.');
    }
}