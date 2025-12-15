<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsistenciaAdminController extends Controller
{
    //

    public function index(Request $request)
    {
        $asistencias = $this->obtenerAsistenciasFiltradas($request)->paginate(20);

        $profesores = \App\Models\Profesor::with('persona')->get();
        $programas = \App\Models\Programa::all();

        return view('administrador.asistenciaAdministrador', compact('asistencias', 'profesores', 'programas'));
    }

    public function exportarPDF(Request $request)
    {
        $asistencias = $this->obtenerAsistenciasFiltradas($request)->get();
        
        $pdf = \PDF::loadView('administrador.pdf.asistenciaReporte', compact('asistencias'));
        return $pdf->download('reporte_asistencia_' . date('Y-m-d_H-i') . '.pdf');
    }

    public function exportarExcel(Request $request) 
    {
        $asistencias = $this->obtenerAsistenciasFiltradas($request)->get();
        $filename = "reporte_asistencia_" . date('Y-m-d_H-i') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($asistencias) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8 compatibility
            fputs($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, ['Fecha', 'Estudiante', 'Código', 'Profesor', 'Programa', 'Estado', 'Observación', 'Reprogramado Para']);

            foreach ($asistencias as $row) {
                fputcsv($file, [
                    $row->Fecha,
                    $row->estudiante->persona->Nombre . ' ' . $row->estudiante->persona->Apellido,
                    $row->estudiante->Cod_estudiante,
                    $row->profesor->persona->Nombre . ' ' . $row->profesor->persona->Apellido,
                    $row->programa->Nombre ?? '',
                    $row->Estado,
                    $row->Observacion ?? '',
                    $row->Fecha_reprogramada ?? ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function obtenerAsistenciasFiltradas(Request $request)
    {
        $query = \App\Models\Asistencia::with(['estudiante.persona', 'profesor.persona', 'programa']);

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('Fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('Fecha', '<=', $request->fecha_fin);
        }
        if ($request->filled('profesor_id')) {
            $query->where('Id_profesores', $request->profesor_id);
        }
        if ($request->filled('programa_id')) {
            $query->where('Id_programas', $request->programa_id);
        }
        if ($request->filled('estudiante_nombre')) {
            $query->whereHas('estudiante.persona', function($q) use ($request) {
                $q->where('Nombre', 'like', '%' . $request->estudiante_nombre . '%')
                  ->orWhere('Apellido', 'like', '%' . $request->estudiante_nombre . '%');
            });
        }
        
        return $query->orderBy('Fecha', 'desc');
    }
}
