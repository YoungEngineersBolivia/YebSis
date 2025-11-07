<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Cuota;
use App\Models\Pago;
use Illuminate\Http\Request;

class PagosController extends Controller
{
    public function form(Request $request)
    {
        $estudiantes = Estudiante::with([
            'persona',
            'tutor.persona',
            'planesPago.cuotas'
        ])->get();

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
            $query->whereHas('persona', function($q) use ($request) {
                $q->where('Nombre', 'like', '%' . $request->nombre . '%')
                  ->orWhere('Apellido', 'like', '%' . $request->nombre . '%');
            });
        }

        $estudiantes = $query->get();
        return view('administrador.pagosAdministrador', compact('estudiantes'));
    }

    public function registrarPago(Request $request)
    {
        // Validación
        $request->validate([
            'cuota_id' => 'required|exists:cuotas,Id_cuotas',
            'descripcion' => 'required|string|max:255',
            'comprobante' => 'required|string|max:255',
            'monto_pago' => 'required|numeric|min:0',
            'fecha_pago' => 'nullable|date',
            'id_planes_pagos' => 'required|exists:planes_pagos,Id_planes_pagos',
        ]);

        try {
            \DB::beginTransaction();

            // Obtener la cuota
            $cuota = Cuota::findOrFail($request->cuota_id);
            
            // Verificar que la cuota no esté ya pagada
            if ($cuota->Estado_cuota === 'Pagado') {
                return back()->with('error', 'Esta cuota ya está pagada');
            }

            // Verificar que el monto no exceda el monto de la cuota
            $montoPago = (float) $request->monto_pago;
            $montoCuota = (float) $cuota->Monto_cuota;
            $montoPagadoAnterior = (float) ($cuota->Monto_pagado ?? 0);
            
            if (($montoPagadoAnterior + $montoPago) > $montoCuota) {
                return back()->with('error', 'El monto del pago excede el monto pendiente de la cuota');
            }

            // Actualizar cuota
            $nuevoMontoPagado = $montoPagadoAnterior + $montoPago;
            $cuota->Monto_pagado = $nuevoMontoPagado;
            
            // Actualizar estado de la cuota
            if ($nuevoMontoPagado >= $montoCuota) {
                $cuota->Estado_cuota = 'Pagado';
            } else {
                $cuota->Estado_cuota = 'Parcial';
            }
            
            $cuota->save();

            // Registrar pago (SIN Id_cuotas porque no existe en la tabla)
            $pago = Pago::create([
                'Descripcion' => $request->descripcion . ' - Cuota #' . $cuota->Nro_de_cuota,
                'Comprobante' => $request->comprobante,
                'Monto_pago' => $montoPago,
                'Fecha_pago' => $request->fecha_pago ?? now(),
                'Id_planes_pagos' => $request->id_planes_pagos,
            ]);

            \DB::commit();
            
            return back()->with('success', 'Pago registrado correctamente. Cuota #' . $cuota->Nro_de_cuota . ' actualizada.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al registrar pago: ' . $e->getMessage());
        }
    }
}