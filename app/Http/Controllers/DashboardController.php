<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Configurar Carbon en español (opcional)
        Carbon::setLocale('es');
        
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
        
        // Fechas importantes
        $hoy = Carbon::now();
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        $mesAnterior = Carbon::now()->subMonth();
        $inicioAño = Carbon::now()->startOfYear();
        
        // Ingresos totales
        $ingresosTotales = Pago::sum('Monto_pago') ?? 0;
        
        // Ingresos del mes actual
        $ingresosMesActual = Pago::whereBetween('created_at', [$inicioMes, $finMes])
            ->sum('Monto_pago') ?? 0;
            
        // Ingresos del mes anterior
        $ingresosMesAnterior = Pago::whereBetween('created_at', [
            $mesAnterior->copy()->startOfMonth(),
            $mesAnterior->copy()->endOfMonth()
        ])->sum('Monto_pago') ?? 0;
        
        // Crecimiento porcentual
        $crecimientoIngresos = 0;
        if ($ingresosMesAnterior > 0) {
            $crecimientoIngresos = (($ingresosMesActual - $ingresosMesAnterior) / $ingresosMesAnterior) * 100;
        }
        
        // Ingresos por día en los últimos 30 días
        $ingresosPorDia = Pago::select(
            DB::raw('DATE(created_at) as fecha'),
            DB::raw('SUM(Monto_pago) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
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
        
        // Ingresos por mes en el año actual
        $ingresosPorMes = Pago::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('SUM(Monto_pago) as total')
        )
        ->whereYear('created_at', $hoy->year)
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
            DB::raw('DATE(created_at) as fecha'),
            DB::raw('SUM(Monto_pago) as total')
        )
        ->whereBetween('created_at', [$inicioMes, $finMes])
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
        
        // Estadísticas de tiempo
        $estadisticasTiempo = [
            'fecha_actual' => $hoy->format('d M Y'),
            'mes_actual' => $hoy->monthName,
            'año_actual' => $hoy->year,
            'dias_transcurridos_mes' => $hoy->day,
            'dias_restantes_mes' => $hoy->daysInMonth - $hoy->day,
            'trimestre_actual' => $hoy->quarter,
            'semana_año' => $hoy->week
        ];
        
        // Proyección de ingresos del mes
        $proyeccionMes = 0;
        if ($hoy->day > 0) {
            $promedioDisario = $ingresosMesActual / $hoy->day;
            $proyeccionMes = $promedioDisario * $hoy->daysInMonth;
        }

        // Egresos (mantener como estaba)
        $egresosTotales = 0;

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
            'proyeccionMes',
            'egresosTotales'
        ));
    }
}