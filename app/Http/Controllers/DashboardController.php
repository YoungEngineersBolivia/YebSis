<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Pago;
use App\Models\Egreso;
use App\Models\ClasePrueba;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Configurar Carbon en español (opcional)
        Carbon::setLocale('es');

        // Obtener clases de prueba pendientes o no vistas por el admin
        $clasesPruebaPendientes = ClasePrueba::with(['prospecto', 'usuarioAsistencia.persona'])
            ->where('Visto_admin', false)
            ->orderBy('Fecha_clase', 'desc')
            ->orderBy('Hora_clase', 'desc')
            ->get();

        $sucursales = Sucursal::all();

        // Alumnos por programa por sucursal
        $alumnosPorSucursal = [];
        foreach ($sucursales as $sucursal) {
            $alumnosPorSucursal[$sucursal->Id_sucursales] = DB::table('estudiantes')
                ->join('programas', 'estudiantes.Id_programas', '=', 'programas.Id_programas')
                ->select('programas.Nombre as programa', DB::raw('COUNT(*) as total'))
                ->where('estudiantes.Id_sucursales', $sucursal->Id_sucursales)
                ->groupBy('programas.Nombre')
                ->get();
        }

        // Totales de alumnos por sucursal
        $totalAlumnosPorSucursal = DB::table('estudiantes')
            ->join('sucursales', 'estudiantes.Id_sucursales', '=', 'sucursales.Id_sucursales')
            ->select('sucursales.Nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('sucursales.Nombre')
            ->get();

        // === MÉTRICAS CON CARBON ===

        // === FILTRO DE FECHAS ===
        $mesSeleccionado = request('mes', Carbon::now()->month);
        $anioSeleccionado = request('anio', Carbon::now()->year);
        $fechaFiltro = Carbon::createFromDate($anioSeleccionado, $mesSeleccionado, 1);

        $hoy = Carbon::now();
        $inicioMes = $fechaFiltro->copy()->startOfMonth();
        $finMes = $fechaFiltro->copy()->endOfMonth();
        $mesAnterior = $fechaFiltro->copy()->subMonth();
        $inicioAño = $fechaFiltro->copy()->startOfYear();

        // Ingresos totales
        $ingresosTotales = Pago::sum('Monto_pago') ?? 0;

        // Ingresos del mes actual
        $ingresosMesActual = Pago::whereBetween('Fecha_pago', [$inicioMes, $finMes])
            ->sum('Monto_pago') ?? 0;

        // Ingresos del mes anterior
        $ingresosMesAnterior = Pago::whereBetween('Fecha_pago', [
            $mesAnterior->copy()->startOfMonth(),
            $mesAnterior->copy()->endOfMonth()
        ])->sum('Monto_pago') ?? 0;

        // Crecimiento porcentual
        $crecimientoIngresos = 0;
        if ($ingresosMesAnterior > 0) {
            $crecimientoIngresos = (($ingresosMesActual - $ingresosMesAnterior) / $ingresosMesAnterior) * 100;
        }

        // Ingresos por día del mes seleccionado
        $ingresosPorDia = Pago::select(
            DB::raw('DATE(Fecha_pago) as fecha'),
            DB::raw('SUM(Monto_pago) as total')
        )
            ->whereBetween('Fecha_pago', [$inicioMes, $finMes])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->map(function ($item) {
                return [
                    'fecha' => Carbon::parse($item->fecha)->format('d/m'),
                    'fecha_completa' => Carbon::parse($item->fecha)->format('d M Y'),
                    'total' => $item->total
                ];
            });

        // Ingresos por mes en el año seleccionado
        $ingresosPorMes = Pago::select(
            DB::raw('MONTH(Fecha_pago) as mes'),
            DB::raw('SUM(Monto_pago) as total')
        )
            ->whereYear('Fecha_pago', $anioSeleccionado)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => Carbon::create()->month($item->mes)->format('M'),
                    'mes_nombre' => Carbon::create()->month($item->mes)->monthName,
                    'total' => $item->total
                ];
            });

        // Top 5 días con más ingresos este mes
        $topDiasIngresos = Pago::select(
            DB::raw('DATE(Fecha_pago) as fecha'),
            DB::raw('SUM(Monto_pago) as total')
        )
            ->whereBetween('Fecha_pago', [$inicioMes, $finMes])
            ->groupBy('fecha')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'fecha' => Carbon::parse($item->fecha)->format('d M Y'),
                    'dia_semana' => Carbon::parse($item->fecha)->dayName,
                    'total' => $item->total
                ];
            });

        // Estadísticas de tiempo del mes seleccionado
        $estadisticasTiempo = [
            'fecha_actual' => $hoy->format('d M Y'),
            'mes_nombre' => $fechaFiltro->monthName,
            'mes_num' => (int) $mesSeleccionado,
            'anio_actual' => (int) $anioSeleccionado,
            'dias_transcurridos_mes' => ($mesSeleccionado == $hoy->month && $anioSeleccionado == $hoy->year) ? $hoy->day : $fechaFiltro->daysInMonth,
            'dias_totales_mes' => $fechaFiltro->daysInMonth,
            'trimestre_actual' => $fechaFiltro->quarter,
            'semana_año' => $fechaFiltro->week
        ];

        $estadisticasTiempo['dias_restantes_mes'] = $estadisticasTiempo['dias_totales_mes'] - $estadisticasTiempo['dias_transcurridos_mes'];


        // Egresos del mes actual
        $egresosMesActual = Egreso::whereBetween('Fecha_egreso', [$inicioMes, $finMes])
            ->sum('Monto_egreso') ?? 0;

        // Egresos totales históricos
        $egresosTotales = Egreso::sum('Monto_egreso') ?? 0;

        // Balance del mes actual
        $balanceMesActual = $ingresosMesActual - $egresosMesActual;

        // Egresos por mes en el año seleccionado
        $egresosPorMesRaw = Egreso::select(
            DB::raw('MONTH(Fecha_egreso) as mes'),
            DB::raw('SUM(Monto_egreso) as total')
        )
            ->whereYear('Fecha_egreso', $anioSeleccionado)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Mapear ingresos y egresos por todos los meses del año para el gráfico
        $meses = collect(range(1, 12))->map(function ($m) {
            return Carbon::create()->month($m)->monthName;
        });

        $ingresosData = collect(range(1, 12))->map(function ($m) use ($ingresosPorMes) {
            $item = collect($ingresosPorMes)->firstWhere('mes', $m);
            return $item ? $item['total'] : 0;
        });

        $egresosData = collect(range(1, 12))->map(function ($m) use ($egresosPorMesRaw) {
            $item = $egresosPorMesRaw->firstWhere('mes', $m);
            return $item ? $item->total : 0;
        });

        // Reemplazar ingresosPorMes con una estructura más completa si es necesario o mantener compact
        $graficoMensual = [
            'labels' => $meses,
            'ingresos' => $ingresosData,
            'egresos' => $egresosData
        ];

        // Obtener el año más antiguo de pagos y egresos para el dropdown dinámico
        $anioMinPagos = Pago::min(DB::raw('YEAR(Fecha_pago)'));
        $anioMinEgresos = Egreso::min(DB::raw('YEAR(Fecha_egreso)'));
        $anioMinimo = min(array_filter([$anioMinPagos, $anioMinEgresos, Carbon::now()->year]));

        return view('administrador.dashboard', compact(
            'sucursales',
            'alumnosPorSucursal',
            'totalAlumnosPorSucursal',
            'ingresosTotales',
            'ingresosMesActual',
            'ingresosMesAnterior',
            'crecimientoIngresos',
            'ingresosPorDia',
            'ingresosPorMes',
            'topDiasIngresos',
            'estadisticasTiempo',
            'egresosTotales',
            'egresosMesActual',
            'balanceMesActual',
            'graficoMensual',
            'clasesPruebaPendientes',
            'anioMinimo'
        ));
    }
}