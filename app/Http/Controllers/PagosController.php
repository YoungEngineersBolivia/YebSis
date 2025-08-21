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
            'planPago.cuotas'
        ])->get();

        return view('administrador.pagosAdministrador', compact('estudiantes'));
    }

    public function index(Request $request)
    {
        $query = Estudiante::query()->with([
            'persona',
            'tutor.persona',
            'planPago.cuotas'
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
        try {
            \DB::beginTransaction();

            $cuota = Cuota::where('Id_cuotas', $request->cuota_id)->first();
            if (!$cuota) {
                return back()->with('error', 'Cuota no encontrada');
            }

            // Actualizar cuota
            $cuota->Monto_pagado = $request->monto_pago;
            $cuota->Estado_cuota = 'Pagado';
            $cuota->save();

            // Registrar pago
            Pago::create([
                'Descripcion' => $request->descripcion,
                'Comprobante' => $request->comprobante,
                'Monto_pago' => $request->monto_pago,
                'Fecha_pago' => $request->fecha_pago ?? now(),
                'Id_planes_pagos' => $request->id_planes_pagos,
                'Id_cuotas' => $request->cuota_id
            ]);

            \DB::commit();
            return back()->with('success', 'Pago registrado correctamente');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al registrar pago: ' . $e->getMessage());
        }
    }
}
