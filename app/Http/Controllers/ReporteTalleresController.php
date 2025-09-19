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

        // Fechas para obtener talleres de 2024 y 2025
        $fechaInicio2024 = Carbon::createFromDate(2024, 1, 1);
        $fechaFin2024 = Carbon::createFromDate(2024, 12, 31);

        $fechaInicio2025 = Carbon::createFromDate(2025, 1, 1);
        $fechaFin2025 = Carbon::createFromDate(2025, 12, 31);

        // Obtener datos de los estudiantes inscritos en los talleres de cada año
        $talleres2024 = $this->obtenerDatosTalleres($fechaInicio2024, $fechaFin2024);
        $talleres2025 = $this->obtenerDatosTalleres($fechaInicio2025, $fechaFin2025);

        // Preparar datos para la gráfica
        $datosGrafica = $this->prepararDatosGrafica($talleres2024, $talleres2025, $periodo);

        // Pasar los datos a la vista
        return view('comercial.talleresComercial', compact('datosGrafica', 'talleres2024', 'talleres2025', 'periodo'));
    }

    public function obtenerDatosTalleres($fechaInicio, $fechaFin)
    {
        return DB::table('estudiantes_talleres')
            ->join('estudiantes', 'estudiantes_talleres.Id_estudiantes', '=', 'estudiantes.Id_estudiantes')
            ->join('programas', 'estudiantes_talleres.Id_programas', '=', 'programas.Id_programas')
            ->join('personas', 'estudiantes.Id_personas', '=', 'personas.Id_personas')
            ->whereBetween('estudiantes_talleres.Fecha_inscripcion', [$fechaInicio, $fechaFin])
            ->select(
                'programas.Nombre as taller',
                'personas.Nombre as nombre_estudiante',
                'estudiantes_talleres.Fecha_inscripcion'
            )
            ->orderBy('estudiantes_talleres.Fecha_inscripcion')
            ->get();
    }

    private function prepararDatosGrafica($talleres2024, $talleres2025, $periodo)
    {
        // Obtener todos los talleres únicos de ambos años
        $programas = collect($talleres2024)->merge($talleres2025)
            ->pluck('taller')->unique()->sort()->values();

        // Preparar meses basados en el período seleccionado
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

        // Procesar datos de 2024 por mes y taller
        $datosAgrupados2024 = collect($talleres2024)->groupBy(function($item) {
            return Carbon::parse($item->Fecha_inscripcion)->format('n'); // mes como número
        })->map(function($grupo) {
            return $grupo->groupBy('taller')->map->count();
        });

        // Procesar datos de 2025 por mes y taller
        $datosAgrupados2025 = collect($talleres2025)->groupBy(function($item) {
            return Carbon::parse($item->Fecha_inscripcion)->format('n'); // mes como número
        })->map(function($grupo) {
            return $grupo->groupBy('taller')->map->count();
        });

        // Organizar datos por mes y programa para la gráfica
        $datosOrganizados = [];
        
        foreach ($meses as $mes) {
            $datosOrganizados[$mes['nombre']] = [
                'actual' => [],
                'anterior' => []
            ];

            foreach ($programas as $programa) {
                // Datos actuales (2025 si estamos en 2025, sino 2024)
                if ($mes['año_actual'] == 2025) {
                    $cantidad = $datosAgrupados2025->get($mes['numero'], collect())->get($programa, 0);
                } else {
                    $cantidad = $datosAgrupados2024->get($mes['numero'], collect())->get($programa, 0);
                }
                $datosOrganizados[$mes['nombre']]['actual'][$programa] = $cantidad;

                // Datos anteriores (año anterior)
                if ($mes['año_anterior'] == 2024) {
                    $cantidad = $datosAgrupados2024->get($mes['numero'], collect())->get($programa, 0);
                } else {
                    $cantidad = 0; // Si no hay datos del año anterior
                }
                $datosOrganizados[$mes['nombre']]['anterior'][$programa] = $cantidad;
            }
        }

        return [
            'meses' => $meses,
            'programas' => $programas->toArray(),
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
                'persona_tutor.Celular as contactado',
                'estudiantes.Id_estudiantes'
            )
            ->orderBy('programas.Nombre')
            ->orderBy('estudiantes.created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->contactado = rand(0, 1) ? 'Contactado' : 'No Contactado';
                return $item;
            });
    }

    public function exportar(Request $request)
    {
        // Lógica para exportar datos
        // Puedes implementar exportación a Excel, PDF, etc.
    }
}