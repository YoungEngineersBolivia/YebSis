<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteTalleresController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el período seleccionado (por defecto 6 meses)
        $periodo = $request->get('periodo', 6);
        
        // Fechas para comparación
        $fechaActual = Carbon::now();
        $fechaInicioActual = $fechaActual->copy()->subMonths($periodo);
        $fechaInicioAnterior = $fechaActual->copy()->subYears(1)->subMonths($periodo);
        $fechaFinAnterior = $fechaActual->copy()->subYears(1);

        // Datos del período actual
        $datosActuales = $this->obtenerDatosEstudiantes($fechaInicioActual, $fechaActual);
        
        // Datos del período anterior (mismo período del año pasado)
        $datosAnteriores = $this->obtenerDatosEstudiantes($fechaInicioAnterior, $fechaFinAnterior);

        // Preparar datos para la gráfica
        $datosGrafica = $this->prepararDatosGrafica($datosActuales, $datosAnteriores, $periodo);

        // Obtener datos detallados para la tabla
        $detalleTabla = $this->obtenerDetalleTabla($fechaInicioActual, $fechaActual);

        return view('comercial.talleresComercial', compact('datosGrafica', 'detalleTabla', 'periodo'));
    }

    private function obtenerDatosEstudiantes($fechaInicio, $fechaFin)
    {
        return DB::table('estudiantes')
            ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->whereBetween('estudiantes.created_at', [$fechaInicio, $fechaFin])
            ->select(
                'programas.Nombre as programa',
                DB::raw('COUNT(*) as total_estudiantes'),
                DB::raw('MONTH(estudiantes.created_at) as mes'),
                DB::raw('YEAR(estudiantes.created_at) as año')
            )
            ->groupBy('programas.Id_programas', 'programas.Nombre', 'mes', 'año')
            ->orderBy('mes')
            ->get();
    }

    private function prepararDatosGrafica($datosActuales, $datosAnteriores, $periodo)
    {
        // Obtener todos los programas únicos
        $programas = collect($datosActuales)->merge($datosAnteriores)
            ->pluck('programa')->unique()->sort()->values();

        // Preparar meses
        $meses = [];
        $fechaActual = Carbon::now();
        
        for ($i = $periodo - 1; $i >= 0; $i--) {
            $fecha = $fechaActual->copy()->subMonths($i);
            $meses[] = [
                'nombre' => $fecha->format('M'),
                'numero' => $fecha->month,
                'año_actual' => $fecha->year,
                'año_anterior' => $fecha->copy()->subYear()->year
            ];
        }

        // Organizar datos por mes y programa
        $datosOrganizados = [];
        
        foreach ($meses as $mes) {
            $datosOrganizados[$mes['nombre']] = [
                'actual' => [],
                'anterior' => []
            ];

            // Datos actuales
            foreach ($programas as $programa) {
                $cantidad = $datosActuales->where('programa', $programa)
                    ->where('mes', $mes['numero'])
                    ->where('año', $mes['año_actual'])
                    ->sum('total_estudiantes');
                
                $datosOrganizados[$mes['nombre']]['actual'][$programa] = $cantidad;
            }

            // Datos anteriores
            foreach ($programas as $programa) {
                $cantidad = $datosAnteriores->where('programa', $programa)
                    ->where('mes', $mes['numero'])
                    ->where('año', $mes['año_anterior'])
                    ->sum('total_estudiantes');
                
                $datosOrganizados[$mes['nombre']]['anterior'][$programa] = $cantidad;
            }
        }

        return [
            'meses' => $meses,
            'programas' => $programas,
            'datos' => $datosOrganizados
        ];
    }

    private function obtenerDetalleTabla($fechaInicio, $fechaFin)
    {
        return DB::table('estudiantes')
            ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->join('tutores', 'estudiantes.Id_tutores', '=', 'tutores.Id_tutores')
            ->join('personas as persona_tutor', 'tutores.Id_personas', '=', 'persona_tutor.Id_personas')
            ->whereBetween('estudiantes.created_at', [$fechaInicio, $fechaFin])
            ->select(
                'programas.Nombre as taller',
                'persona_tutor.Celular as telefono',
                'estudiantes.created_at as fecha_registro',
                'persona_tutor.Celular as contactado', // Puedes ajustar esta lógica
                'estudiantes.Id_estudiantes'
            )
            ->orderBy('programas.Nombre')
            ->orderBy('estudiantes.created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->contactado = rand(0, 1) ? 'Contactado' : 'No Contactado'; // Ejemplo
                return $item;
            });
    }

    public function exportar(Request $request)
    {
        // Lógica para exportar datos
        // Puedes implementar exportación a Excel, PDF, etc.
    }
}