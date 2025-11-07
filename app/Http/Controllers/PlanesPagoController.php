<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanesPago;
use App\Models\Pago;
use App\Models\Cuota;

class PlanesPagoController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'Monto_total' => 'required|numeric',
            'Nro_cuotas' => 'required|integer',
            'fecha_plan_pagos' => 'required|date',
            'Estado_plan' => 'required|string',
            'Id_estudiantes' => 'required|integer',
            'Id_programas' => 'required|integer',
        ]);

        \DB::beginTransaction();
        try {
            // 1. Crear plan de pagos primero
            $plan = PlanesPago::create([
                'Monto_total' => $request->Monto_total,
                'Nro_cuotas' => $request->Nro_cuotas,
                'fecha_plan_pagos' => $request->fecha_plan_pagos,
                'Estado_plan' => $request->Estado_plan,
                'Id_programas' => $request->Id_programas,
                'Id_estudiantes' => $request->Id_estudiantes,
            ]);

            // 2. Crear pago inicial si se proporcionó (ahora con el Id del plan)
            if ($request->filled('Monto_pago')) {
                Pago::create([
                    'Descripcion' => $request->Descripcion,
                    'Comprobante' => $request->Comprobante ?? 'N/A',
                    'Monto_pago' => $request->Monto_pago,
                    'Fecha_pago' => $request->Fecha_pago ?? now(),
                    'Id_planes_pagos' => $plan->Id_planes_pagos, // ✅ Correcto
                ]);
            }

            // 3. Generar cuotas automáticamente
            $montoCuota = $request->Monto_total / $request->Nro_cuotas;
            $fechaInicio = \Carbon\Carbon::parse($request->fecha_plan_pagos);

            for ($i = 1; $i <= $request->Nro_cuotas; $i++) {
                Cuota::create([
                    'Nro_de_cuota' => $i,
                    'Fecha_vencimiento' => $fechaInicio->copy()->addMonths($i - 1),
                    'Monto_cuota' => $montoCuota,
                    'Monto_pagado' => null,
                    'Estado_cuota' => 'Pendiente',
                    'Id_planes_pagos' => $plan->Id_planes_pagos,
                ]);
            }

            \DB::commit();
            return redirect()->back()->with('success_planes', 'Plan de pagos registrado correctamente.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
