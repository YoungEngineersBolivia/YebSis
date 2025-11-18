<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\MotorAsignado;
use App\Models\MotorMovimiento;
use App\Models\ReporteMantenimiento;
use App\Models\Profesor;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MotoresAsignadosController extends Controller
{
    // Lista de motores asignados (SOLO EN PROCESO)
    public function index()
    {
        // Solo mostrar asignaciones en proceso (motores que aún no han sido devueltos)
        $asignaciones = MotorAsignado::with(['motor.sucursal', 'profesor.persona', 'reportes'])
            ->where('Estado_asignacion', 'En Proceso')
            ->orderBy('Fecha_asignacion', 'desc')
            ->get();

        $sucursales = Sucursal::all();

        return view('componentes.listaAsignaciones', compact('asignaciones', 'sucursales'));
    }

    // Formulario para asignar motor
    public function create()
    {
        // Solo motores que NO estén asignados actualmente
        $motoresDisponibles = Motor::with('sucursal')
            ->whereDoesntHave('asignaciones', function($query) {
                $query->where('Estado_asignacion', 'En Proceso');
            })
            ->whereIn('Estado', ['Funcionando', 'Descompuesto', 'En Proceso'])
            ->get();

        // Solo profesores/técnicos con Rol_componentes = 'Tecnico'
        $tecnicos = Profesor::with('persona')
            ->where('Rol_componentes', 'Tecnico')
            ->get();

        return view('componentes.asignarMotor', compact('motoresDisponibles', 'tecnicos'));
    }

    // Guardar asignación de motor (SALIDA del inventario)
    public function store(Request $request)
    {
        $request->validate([
            'Id_motores' => 'required|exists:motores,Id_motores',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'fecha_asignacion' => 'required|date',
            'estado_motor' => 'required|in:Funcionando,Descompuesto,En Proceso',
            'observacion_inicial' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $motor = Motor::findOrFail($request->Id_motores);
            $profesor = Profesor::with('persona')->findOrFail($request->Id_profesores);

            // Validar que el motor no esté asignado actualmente
            $asignacionActiva = MotorAsignado::where('Id_motores', $motor->Id_motores)
                ->where('Estado_asignacion', 'En Proceso')
                ->exists();

            if ($asignacionActiva) {
                return redirect()->back()
                    ->with('error', 'Este motor ya está asignado a un técnico.')
                    ->withInput();
            }

            // Actualizar estado del motor
            $motor->update([
                'Estado' => $request->estado_motor,
            ]);

            // Crear asignación (SIN fecha de entrega todavía)
            $asignacion = MotorAsignado::create([
                'Id_motores' => $request->Id_motores,
                'Id_profesores' => $request->Id_profesores,
                'Estado_asignacion' => 'En Proceso',
                'Fecha_asignacion' => $request->fecha_asignacion,
                'Fecha_entrega' => null,
                'Observacion_inicial' => $request->observacion_inicial,
            ]);

            // Registrar movimiento de SALIDA
            MotorMovimiento::create([
                'Id_motores' => $motor->Id_motores,
                'Tipo_movimiento' => 'Salida',
                'Fecha' => $request->fecha_asignacion,
                'Id_sucursales' => $motor->Id_sucursales,
                'Estado_ubicacion' => 'Salida',
                'Ultimo_tecnico' => $profesor->persona->Nombre . ' ' . $profesor->persona->Apellido_paterno,
                'Observacion' => $request->observacion_inicial,
                'Id_usuarios' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('motores.asignaciones.index')
                ->with('success', 'Motor asignado exitosamente al técnico.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al asignar el motor: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Registrar entrada de motor (devuelto por técnico)
    public function registrarEntrada(Request $request, $id)
    {
        $request->validate([
            'fecha_entrega' => 'required|date|after_or_equal:' . MotorAsignado::findOrFail($id)->Fecha_asignacion,
            'estado_final' => 'required|in:Funcionando,Descompuesto,En Proceso',
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'observaciones' => 'nullable|string',
        ], [
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior a la fecha de asignación.',
        ]);

        DB::beginTransaction();

        try {
            $asignacion = MotorAsignado::with('motor', 'profesor.persona')->findOrFail($id);

            // Validar que la asignación esté en proceso
            if ($asignacion->Estado_asignacion !== 'En Proceso') {
                return redirect()->back()
                    ->with('error', 'Esta asignación ya fue completada o cancelada.');
            }

            // Actualizar asignación con la fecha de entrega real
            $asignacion->update([
                'Estado_asignacion' => 'Completado',
                'Fecha_entrega' => $request->fecha_entrega,
            ]);

            // Actualizar motor
            $asignacion->motor->update([
                'Estado' => $request->estado_final,
                'Id_sucursales' => $request->Id_sucursales,
                'Observacion' => $request->observaciones,
            ]);

            // Registrar movimiento de ENTRADA
            MotorMovimiento::create([
                'Id_motores' => $asignacion->motor->Id_motores,
                'Tipo_movimiento' => 'Entrada',
                'Fecha' => $request->fecha_entrega,
                'Id_sucursales' => $request->Id_sucursales,
                'Estado_ubicacion' => 'Entrada',
                'Ultimo_tecnico' => $asignacion->profesor->persona->Nombre . ' ' . 
                                    $asignacion->profesor->persona->Apellido_paterno,
                'Observacion' => $request->observaciones,
                'Id_usuarios' => Auth::id(),
            ]);

            // Crear reporte final de mantenimiento
            ReporteMantenimiento::create([
                'Id_motores_asignados' => $asignacion->Id_motores_asignados,
                'Estado_final' => $request->estado_final,
                'Observaciones' => $request->observaciones ?? 'Motor devuelto al inventario',
                'Fecha_reporte' => $request->fecha_entrega,
            ]);

            DB::commit();

            return redirect()->route('motores.asignaciones.index')
                ->with('success', 'Entrada de motor registrada exitosamente el ' . \Carbon\Carbon::parse($request->fecha_entrega)->format('d/m/Y') . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar la entrada: ' . $e->getMessage());
        }
    }

    // Guardar reporte de mantenimiento (durante el proceso)
    public function storeReporte(Request $request, $id)
    {
        $request->validate([
            'fecha_reporte' => 'required|date',
            'estado_final' => 'required|in:Funcionando,Descompuesto,En Proceso',
            'observaciones' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $asignacion = MotorAsignado::with('motor')->findOrFail($id);

            // Validar que la asignación esté en proceso
            if ($asignacion->Estado_asignacion !== 'En Proceso') {
                return redirect()->back()
                    ->with('error', 'Solo se pueden agregar reportes a asignaciones en proceso.');
            }

            // Crear reporte de mantenimiento
            ReporteMantenimiento::create([
                'Id_motores_asignados' => $asignacion->Id_motores_asignados,
                'Estado_final' => $request->estado_final,
                'Observaciones' => $request->observaciones,
                'Fecha_reporte' => $request->fecha_reporte,
            ]);

            // Actualizar estado del motor según el último reporte
            $asignacion->motor->update([
                'Estado' => $request->estado_final,
            ]);

            DB::commit();

            return redirect()->route('motores.asignaciones.index')
                ->with('success', 'Reporte de mantenimiento registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al guardar el reporte: ' . $e->getMessage());
        }
    }

    // Ver historial completo de asignaciones completadas
    public function historial()
    {
        $asignaciones = MotorAsignado::with(['motor.sucursal', 'profesor.persona', 'reportes'])
            ->whereIn('Estado_asignacion', ['Completado', 'Cancelado'])
            ->orderBy('Fecha_entrega', 'desc')
            ->get();

        return view('componentes.historialAsignaciones', compact('asignaciones'));
    }
}