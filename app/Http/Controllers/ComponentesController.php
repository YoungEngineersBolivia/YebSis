<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\MotorMovimiento;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComponentesController extends Controller
{
    public function index()
    {
        // Obtener todos los componentes con su última ubicación
        $componentes = Motor::with(['sucursal', 'ultimoMovimiento'])
            ->orderBy('Id_motor', 'asc')
            ->get();

        $sucursales = Sucursal::all();

        return view('componentes.inventarioComponentes', compact('componentes', 'sucursales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_motor' => 'required|string|unique:motores,Id_motor',
            'estado' => 'required|in:Funcionando,Descompuesto,En Proceso',
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'observacion' => 'nullable|string',
        ]);

        try {
            Motor::create([
                'Id_motor' => $request->id_motor,
                'Estado' => $request->estado,
                'Id_sucursales' => $request->Id_sucursales,
                'Observacion' => $request->observacion,
            ]);

            return redirect()->route('componentes.index')
                ->with('success', 'Componente creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el componente: ' . $e->getMessage());
        }
    }

    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'id_motor' => 'required|string|unique:motores,Id_motor',
            'estado' => 'required|in:Funcionando,Descompuesto,En Proceso',
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'fecha' => 'required|date',
            'ultimo_tecnico' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Crear el motor
            $motor = Motor::create([
                'Id_motor' => $request->id_motor,
                'Estado' => $request->estado,
                'Id_sucursales' => $request->Id_sucursales,
                'Observacion' => $request->observacion,
            ]);

            // Registrar el movimiento de entrada
            MotorMovimiento::create([
                'Id_motores' => $motor->Id_motores,
                'Tipo_movimiento' => 'Entrada',
                'Fecha' => $request->fecha,
                'Id_sucursales' => $request->Id_sucursales,
                'Estado_ubicacion' => 'Entrada',
                'Ultimo_tecnico' => $request->ultimo_tecnico,
                'Observacion' => $request->observacion,
                'Id_usuarios' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('componentes.index')
                ->with('success', 'Componente registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar el componente: ' . $e->getMessage());
        }
    }

    public function registrarSalida(Request $request)
    {
        $request->validate([
            'Id_componentes' => 'required|exists:motores,Id_motores',
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'fecha' => 'required|date',
            'ultimo_tecnico' => 'nullable|string',
            'observacion' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $motor = Motor::findOrFail($request->Id_componentes);

            // Actualizar la sucursal del motor
            $motor->update([
                'Id_sucursales' => $request->Id_sucursales,
            ]);

            // Registrar el movimiento de salida
            MotorMovimiento::create([
                'Id_motores' => $motor->Id_motores,
                'Tipo_movimiento' => 'Salida',
                'Fecha' => $request->fecha,
                'Id_sucursales' => $request->Id_sucursales,
                'Estado_ubicacion' => 'Salida',
                'Ultimo_tecnico' => $request->ultimo_tecnico,
                'Observacion' => $request->observacion,
                'Id_usuarios' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('componentes.index')
                ->with('success', 'Salida de componente registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar la salida: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $motor = Motor::findOrFail($id);
            $motor->delete();

            return redirect()->route('componentes.index')
                ->with('success', 'Componente eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el componente: ' . $e->getMessage());
        }
    }

    public function historial($id)
    {
        $motor = Motor::with(['movimientos.sucursal', 'movimientos.usuario.persona'])
            ->findOrFail($id);

        return view('componentes.historial', compact('motor'));
    }
}