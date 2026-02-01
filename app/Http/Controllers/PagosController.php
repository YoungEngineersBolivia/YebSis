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

        return view('administrador.pagosAdministrador', compact('estudiantes'));
    }

    public function index(Request $request)
    {
        $query = Estudiante::query()->with([
            'persona',
            'tutor.persona',
            'planesPago.cuotas'
        ]);

        if ($request->has('nombre') && $request->nombre != '') {
            $query->whereHas('persona', function ($q) use ($request) {
                $q->where('Nombre', 'like', '%' . $request->nombre . '%')
                    ->orWhere('Apellido', 'like', '%' . $request->nombre . '%');
            });
        }

        $estudiantes = $query
            ->orderBy('Id_estudiantes', 'desc')   // <-- correcto
            ->get();

        return view('administrador.pagosAdministrador', compact('estudiantes'));
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
}