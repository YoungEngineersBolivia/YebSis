<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Cuota;
use App\Models\Pago;
use App\Models\PlanesPago;
use Illuminate\Http\Request;

class PagosController extends Controller
{
    public function form(Request $request)
    {
        $search = $request->input('search');

        $query = Estudiante::with([
            'persona',
            'tutor.persona',
            'planesPago.cuotas',
            'planesPago.programa',
            'planesPago.pagos'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Buscar en Estudiantes (Nombre + Apellido)
                $q->whereHas('persona', function ($q2) use ($search) {
                    $q2->whereRaw("CONCAT(Nombre, ' ', Apellido) LIKE ?", ["%{$search}%"])
                        ->orWhere('Nombre', 'like', "%{$search}%")
                        ->orWhere('Apellido', 'like', "%{$search}%");
                })
                    // O buscar en Tutores (Nombre + Apellido)
                    ->orWhereHas('tutor.persona', function ($q2) use ($search) {
                        $q2->whereRaw("CONCAT(Nombre, ' ', Apellido) LIKE ?", ["%{$search}%"])
                            ->orWhere('Nombre', 'like', "%{$search}%")
                            ->orWhere('Apellido', 'like', "%{$search}%");
                    });
            });
        }

        $estudiantes = $query
            ->orderBy('Id_estudiantes', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return view('administrador.partials.pagos_lista', compact('estudiantes'))->render();
        }

        $mesSeleccionado = null;
        $anioSeleccionado = null;

        return view('administrador.pagosAdministrador', compact('estudiantes', 'mesSeleccionado', 'anioSeleccionado'));
    }

    public function index(Request $request)
    {
        $mesSeleccionado = $request->input('mes');
        $anioSeleccionado = $request->input('anio');

        $query = Estudiante::query()->with([
            'persona',
            'tutor.persona',
            'planesPago' => function ($q) use ($mesSeleccionado, $anioSeleccionado) {
                $q->with([
                    'pagos' => function ($pq) use ($mesSeleccionado, $anioSeleccionado) {
                        if ($mesSeleccionado)
                            $pq->whereMonth('Fecha_pago', $mesSeleccionado);
                        if ($anioSeleccionado)
                            $pq->whereYear('Fecha_pago', $anioSeleccionado);
                    },
                    'cuotas'
                ]);
            }
        ]);

        if ($request->has('nombre') && $request->nombre != '') {
            $query->whereHas('persona', function ($q) use ($request) {
                $q->where('Nombre', 'like', '%' . $request->nombre . '%')
                    ->orWhere('Apellido', 'like', '%' . $request->nombre . '%');
            });
        }

        // Si filtramos por fecha, solo mostrar estudiantes que tengan pagos en ese rango
        if ($mesSeleccionado || $anioSeleccionado) {
            $query->whereHas('planesPago.pagos', function ($q) use ($mesSeleccionado, $anioSeleccionado) {
                if ($mesSeleccionado)
                    $q->whereMonth('Fecha_pago', $mesSeleccionado);
                if ($anioSeleccionado)
                    $q->whereYear('Fecha_pago', $anioSeleccionado);
            });
        }

        $estudiantes = $query->orderBy('Id_estudiantes', 'desc')->paginate(10);

        return view('administrador.pagosAdministrador', compact('estudiantes', 'mesSeleccionado', 'anioSeleccionado'));
    }


    public function registrarPago(Request $request)
    {
        // Validación simplificada - solo necesitamos plan_id, no cuota_id
        $request->validate([
            'plan_id' => 'required|exists:planes_pagos,Id_planes_pagos',
            'descripcion' => 'required|string|max:255',
            'comprobante' => 'nullable|string|max:255',
            'monto_pago' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date',
        ], [
            'plan_id.required' => 'El plan de pago es obligatorio',
            'monto_pago.min' => 'El monto debe ser mayor a 0',
        ]);

        try {
            \DB::beginTransaction();

            // Obtener el plan
            $plan = PlanesPago::with('pagos')->findOrFail($request->plan_id);

            // Calcular total pagado
            $totalPagado = $plan->pagos->sum('Monto_pago');
            $montoPago = (float) $request->monto_pago;

            // Crear el pago directamente (sin restricción de monto)
            Pago::create([
                'Descripcion' => $request->descripcion,
                'Comprobante' => $request->comprobante,
                'Monto_pago' => $montoPago,
                'Fecha_pago' => $request->fecha_pago,
                'Id_planes_pagos' => $request->plan_id,
            ]);

            \DB::commit();

            $mensaje = 'Pago registrado correctamente por Bs. ' . number_format($montoPago, 2);

            return back()->with('success', $mensaje);

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al registrar pago: ' . $e->getMessage());
        }
    }

    public function pagarPlanCompleto(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:planes_pagos,Id_planes_pagos',
        ]);

        try {
            \DB::beginTransaction();

            // Obtener todas las cuotas pendientes del plan
            $cuotasPendientes = Cuota::where('Id_planes_pagos', $request->plan_id)
                ->where('Estado_cuota', '!=', 'Pagado')
                ->get();

            if ($cuotasPendientes->isEmpty()) {
                return back()->with('error', 'No hay cuotas pendientes en este plan.');
            }

            $totalPagado = 0;
            $cuotasPagadas = 0;

            foreach ($cuotasPendientes as $cuota) {
                $montoPendiente = $cuota->Monto_cuota - ($cuota->Monto_pagado ?? 0);

                // Actualizar cuota
                $cuota->Monto_pagado = $cuota->Monto_cuota;
                $cuota->Estado_cuota = 'Pagado';
                $cuota->save();

                // Registrar pago
                Pago::create([
                    'Descripcion' => 'Pago Completo Plan - Cuota #' . $cuota->Nro_de_cuota,
                    'Comprobante' => 'AUTO-PLAN-' . $request->plan_id . '-' . time(),
                    'Monto_pago' => $montoPendiente,
                    'Fecha_pago' => now(),
                    'Id_planes_pagos' => $request->plan_id,
                ]);

                $totalPagado += $montoPendiente;
                $cuotasPagadas++;
            }

            \DB::commit();

            return back()->with('success', "Plan de pago completado exitosamente. {$cuotasPagadas} cuotas pagadas por un total de Bs. " . number_format($totalPagado, 2));

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al pagar plan completo: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto_pago' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date',
            'comprobante' => 'nullable|string|max:255',
        ]);

        try {
            $pago = Pago::findOrFail($id);
            $pago->update([
                'Descripcion' => $request->descripcion,
                'Monto_pago' => $request->monto_pago,
                'Fecha_pago' => $request->fecha_pago,
                'Comprobante' => $request->comprobante,
            ]);

            return back()->with('success', 'Pago actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el pago: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pago = Pago::findOrFail($id);
            $pago->delete();
            return back()->with('success', 'Pago eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }

    public function reporteMensual(Request $request)
    {
        $anioSeleccionado = $request->input('anio', \Carbon\Carbon::now()->year);

        $query = Pago::select(
            \DB::raw('YEAR(Fecha_pago) as anio'),
            \DB::raw('MONTH(Fecha_pago) as mes'),
            \DB::raw('SUM(Monto_pago) as total'),
            \DB::raw('COUNT(*) as cantidad_pagos'),
            \DB::raw('MAX(Fecha_pago) as ultima_fecha')
        );

        $query->whereYear('Fecha_pago', $anioSeleccionado);

        $ingresosMensuales = $query->groupBy('anio', 'mes')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get()
            ->map(function ($item) {
                $item->nombre_mes = \Carbon\Carbon::create()->month($item->mes)->monthName;
                return $item;
            });

        // Obtener el año más antiguo registrado para el dropdown dinámico
        $anioMinimo = Pago::min(\DB::raw('YEAR(Fecha_pago)')) ?? \Carbon\Carbon::now()->year;

        return view('administrador.pagosMensuales', compact('ingresosMensuales', 'anioSeleccionado', 'anioMinimo'));
    }

    /**
     * Descarga el formato CSV para la carga histórica de pagos.
     */
    public function descargarFormato()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="formato_carga_pagos.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM para UTF-8 (Excel friendly)
            fputcsv($file, ['Año', 'Mes', 'Fecha (DD/MM/YYYY)', 'Descripción', 'Comprobante', 'Monto (Bs)']);

            // Ejemplo
            fputcsv($file, ['2023', 'Enero', '31/01/2023', 'Ingresos Totales Enero', 'S/N', '15000.50']);
            fputcsv($file, ['2023', 'Febrero', '28/02/2023', 'Ingresos Totales Febrero', 'S/N', '12500.00']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Importa los datos desde el archivo CSV.
     */
    public function importarDatos(Request $request)
    {
        $request->validate([
            'archivo_csv' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $file = $request->file('archivo_csv');
            $path = $file->getRealPath();
            $handle = fopen($path, 'r');

            // Ignorar la primera línea (cabeceras)
            fgetcsv($handle);

            $importados = 0;
            \DB::beginTransaction();

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Mapeo: 0:Año, 1:Mes, 2:Fecha, 3:Descripción, 4:Comprobante, 5:Monto
                if (count($data) < 6)
                    continue;

                $fechaRaw = $data[2];
                try {
                    $fecha = \Carbon\Carbon::createFromFormat('d/m/Y', $fechaRaw)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Si falla el formato d/m/Y, intentar con el año y mes
                    $mes = $this->obtenerMesNumero($data[1]);
                    $fecha = $data[0] . '-' . sprintf('%02d', $mes) . '-01';
                }

                Pago::create([
                    'Descripcion' => $data[3],
                    'Comprobante' => $data[4],
                    'Monto_pago' => (float) $data[5],
                    'Fecha_pago' => $fecha,
                    'Id_planes_pagos' => null, // Ingreso histórico/global
                ]);

                $importados++;
            }

            fclose($handle);
            \DB::commit();

            return back()->with('success', "Se han importado {$importados} registros de ingresos correctamente.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al importar archivo: ' . $e->getMessage());
        }
    }

    private function obtenerMesNumero($nombre)
    {
        $meses = [
            'Enero' => 1,
            'Febrero' => 2,
            'Marzo' => 3,
            'Abril' => 4,
            'Mayo' => 5,
            'Junio' => 6,
            'Julio' => 7,
            'Agosto' => 8,
            'Septiembre' => 9,
            'Octubre' => 10,
            'Noviembre' => 11,
            'Diciembre' => 12
        ];
        return $meses[ucfirst(strtolower($nombre))] ?? 1;
    }
}