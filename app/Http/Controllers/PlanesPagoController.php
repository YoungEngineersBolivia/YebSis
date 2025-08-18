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
        // Validar datos básicos
        $request->validate([
            'Monto_total' => 'required|numeric',
            'Nro_cuotas' => 'required|integer',
            'fecha_plan_pagos' => 'required|date',
            'Estado_plan' => 'required|string',
            'Id_estudiantes' => 'required|integer',
        ]);

        // Crear pago inicial si se proporcionó
        $pago = null;
        if ($request->filled('Monto_pago')) {
            $pago = Pago::create([
                'Descripcion' => $request->Descripcion,
                'Monto_pago' => $request->Monto_pago,
                'Fecha_pago' => $request->Fecha_pago,
            ]);
        }

        // Crear plan de pagos
        $plan = PlanesPago::create([
            'Monto_total' => $request->Monto_total,
            'Nro_cuotas' => $request->Nro_cuotas,
            'fecha_plan_pagos' => $request->fecha_plan_pagos,
            'Estado_plan' => $request->Estado_plan,
            'Id_programas' => $request->Id_programas,
            'Id_pagos' => $pago ? $pago->Id_pagos : null,
            'Id_estudiantes' => $request->Id_estudiantes,
        ]);

        // Crear cuota si se proporcionó
        if ($request->filled('Nro_de_cuota')) {
            Cuota::create([
                'Nro_de_cuota' => $request->Nro_de_cuota,
                'Fecha_vencimiento' => $request->Fecha_vencimiento,
                'Monto_cuota' => $request->Monto_cuota,
                'Estado_cuota' => $request->Estado_cuota,
                'Id_planes_pagos' => $plan->Id_planes_pagos,
            ]);
        }

        return redirect()->back()->with('success_planes', 'Plan de pagos registrado correctamente.');
    }
}
