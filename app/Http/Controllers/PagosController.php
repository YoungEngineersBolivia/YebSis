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
        $cuota = Cuota::find($request->cuota_id);
        if ($cuota) {
            $cuota->pagado = true;
            $cuota->fecha_pago = $request->fecha_pago ?? now();
            $cuota->Descripcion = $request->descripcion;
            $cuota->Comprobante = $request->comprobante;
            $cuota->Monto_pagado = $request->monto_pago;
            $cuota->Id_planes_pagos = $request->id_planes_pagos;
            $cuota->save();

            return back()->with('success', 'Pago registrado correctamente');
        }

        return back()->with('error', 'Cuota no encontrada');
    }

    public function registrar(Request $request)
    {
        try {
            $request->validate([
                'cuota_id' => 'required',
                'descripcion' => 'required|string',
                'comprobante' => 'required|string',
                'monto_pago' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'id_planes_pagos' => 'required'
            ]);

            $cuota = Cuota::where('Id_cuotas', $request->cuota_id)
                          ->orWhere('id', $request->cuota_id)
                          ->first();

            if (!$cuota) {
                return redirect()->back()->with('error', 'Cuota no encontrada (ID: ' . $request->cuota_id . ')');
            }

            \DB::beginTransaction();

            $cuota->Monto_pagado = $request->monto_pago;
            $cuota->Estado_cuota = 'Pagado';
            $cuota->fecha_pago = $request->fecha_pago;
            $cuota->pagado = true;
            $cuota->save();

            Pago::create([
                'Descripcion' => $request->descripcion,
                'Comprobante' => $request->comprobante,
                'Monto_pago' => $request->monto_pago,
                'Fecha_pago' => $request->fecha_pago,
                'Id_planes_pagos' => $request->id_planes_pagos,
                'Id_cuotas' => $request->cuota_id
            ]);

            \DB::commit();
            return redirect()->back()->with('success', "¡Pago registrado exitosamente! Monto: {$request->monto_pago} Bs.");

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el pago: ' . $e->getMessage())->withInput();
        }
    }
}
