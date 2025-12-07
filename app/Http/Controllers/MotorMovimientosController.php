<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\MotorMovimiento;
use App\Models\MotorAsignacionActiva;
use App\Models\Profesor;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MotorMovimientosController extends Controller
{
    // ============================================
    // SECCIÓN: CONTROL DE INVENTARIO (Admin)
    // ============================================
    
    /**
     * Ver inventario completo de motores
     */
    public function inventario()
    {
        $motores = Motor::with(['sucursal', 'tecnicoActual.persona', 'asignacionActiva'])
            ->orderBy('Id_motor', 'asc')
            ->get();

        $sucursales = Sucursal::all();
        $tecnicos = Profesor::with('persona')
            ->where('Rol_componentes', 'Tecnico')
            ->get();

        return view('componentes.inventario', compact('motores', 'sucursales', 'tecnicos'));
    }

    /**
     * Registrar SALIDA de motor (Admin asigna a técnico)
     */
    public function registrarSalida(Request $request)
    {
        $request->validate([
            'Id_motores' => 'required|exists:motores,Id_motores',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'fecha_salida' => 'required|date',
            'motivo_salida' => 'required|string|min:10',
            'observaciones' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $motor = Motor::with('sucursal')->findOrFail($request->Id_motores);
            $profesor = Profesor::with('persona')->findOrFail($request->Id_profesores);

            // Validar que el motor esté disponible
            if (!$motor->estaDisponible()) {
                return back()->with('error', 'El motor no está disponible para salida.');
            }

            // Nombre completo del técnico
            $nombreTecnico = $profesor->persona->Nombre . ' ' . 
                            $profesor->persona->Apellido_paterno . ' ' . 
                            ($profesor->persona->Apellido_materno ?? '');

            // 1. Crear registro de SALIDA en movimientos
            $movimiento = MotorMovimiento::create([
                'Id_motores' => $motor->Id_motores,
                'Tipo_movimiento' => 'Salida',
                'Fecha_movimiento' => $request->fecha_salida,
                'Id_sucursales' => $motor->Id_sucursales,
                'Id_profesores' => $profesor->Id_profesores,
                'Nombre_tecnico' => $nombreTecnico,
                'Estado_salida' => $motor->Estado,
                'Motivo_salida' => $request->motivo_salida,
                'Observaciones' => $request->observaciones,
                'Id_usuarios' => Auth::id(),
            ]);

            // 2. Crear asignación activa
            MotorAsignacionActiva::create([
                'Id_motores' => $motor->Id_motores,
                'Id_profesores' => $profesor->Id_profesores,
                'Id_movimiento_salida' => $movimiento->Id_movimientos,
                'Fecha_salida' => $request->fecha_salida,
                'Estado_motor_salida' => $motor->Estado,
                'Motivo_salida' => $request->motivo_salida,
                'Estado_asignacion' => 'Activa',
            ]);

            // 3. Actualizar estado del motor
            $motor->update([
                'Ubicacion_actual' => 'Con Tecnico',
                'Estado' => 'En Reparacion',
                'Id_tecnico_actual' => $profesor->Id_profesores,
                'Observacion' => $request->motivo_salida,
            ]);

            DB::commit();
            return back()->with('success', "Motor {$motor->Id_motor} entregado a {$nombreTecnico} exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }

    /**
     * Ver historial de salidas
     */
    public function historialSalidas(Request $request)
    {
        $query = MotorMovimiento::with(['motor', 'sucursal', 'profesor.persona', 'usuario.persona'])
            ->where('Tipo_movimiento', 'Salida')
            ->orderBy('Fecha_movimiento', 'desc');

        // Filtros
        if ($request->filled('tecnico_id')) {
            $query->where('Id_profesores', $request->tecnico_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('Fecha_movimiento', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('Fecha_movimiento', '<=', $request->fecha_hasta);
        }

        $salidas = $query->paginate(20);
        $tecnicos = Profesor::with('persona')->where('Rol_componentes', 'Tecnico')->get();
        
        // Obtener motores disponibles para el modal
        $motoresDisponibles = Motor::with('sucursal')
            ->where('Ubicacion_actual', 'Inventario')
            ->get();

        return view('componentes.historialSalidas', compact('salidas', 'tecnicos', 'motoresDisponibles'));
    }

    // ============================================
    // SECCIÓN: TÉCNICOS (Profesores)
    // ============================================

    /**
     * Ver motores asignados al técnico autenticado
     */
    public function misMotores()
    {
        $usuario = Auth::user();
        
        // Buscar el profesor asociado
        $profesor = Profesor::where('Id_usuarios', $usuario->Id_usuarios)->first();

        if (!$profesor) {
            return view('tecnico.misMotores')
                ->with('error', 'No tienes perfil de técnico asignado.')
                ->with('asignaciones', collect([]));
        }

        // Obtener asignaciones activas
        $asignaciones = MotorAsignacionActiva::with([
            'motor.sucursal',
            'movimientoSalida',
            'reportesProgreso' => fn($q) => $q->latest()
        ])
        ->where('Id_profesores', $profesor->Id_profesores)
        ->where('Estado_asignacion', 'Activa')
        ->orderBy('Fecha_salida', 'desc')
        ->get();

        $sucursales = Sucursal::all();

        return view('profesor.motoresAsignados', compact('asignaciones', 'sucursales'));
    }

    /**
     * Registrar ENTRADA de motor (Técnico devuelve)
     */
    public function registrarEntrada(Request $request, $idAsignacion)
    {
        $request->validate([
            'fecha_entrada' => 'required|date',
            'estado_entrada' => 'required|in:Funcionando,Descompuesto,Requiere Revision',
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'trabajo_realizado' => 'required|string|min:20',
            'observaciones' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $asignacion = MotorAsignacionActiva::with(['motor', 'profesor.persona'])
                ->findOrFail($idAsignacion);

            // Validar que la asignación esté activa
            if ($asignacion->Estado_asignacion !== 'Activa') {
                return back()->with('error', 'Esta asignación ya fue finalizada.');
            }

            // Validar que el técnico autenticado sea el dueño
            $usuarioProfesor = Profesor::where('Id_usuarios', Auth::id())->first();
            if ($usuarioProfesor && $asignacion->Id_profesores !== $usuarioProfesor->Id_profesores) {
                return back()->with('error', 'No tienes permiso para devolver este motor.');
            }

            $motor = $asignacion->motor;
            $nombreTecnico = $asignacion->profesor->persona->Nombre . ' ' . 
                            $asignacion->profesor->persona->Apellido_paterno;

            // 1. Crear registro de ENTRADA en movimientos
            MotorMovimiento::create([
                'Id_motores' => $motor->Id_motores,
                'Tipo_movimiento' => 'Entrada',
                'Fecha_movimiento' => $request->fecha_entrada,
                'Id_sucursales' => $request->Id_sucursales,
                'Id_profesores' => $asignacion->Id_profesores,
                'Nombre_tecnico' => $nombreTecnico,
                'Estado_entrada' => $request->estado_entrada,
                'Trabajo_realizado' => $request->trabajo_realizado,
                'Observaciones' => $request->observaciones,
                'Id_usuarios' => Auth::id(),
            ]);

            // 2. Finalizar asignación
            $asignacion->update([
                'Estado_asignacion' => 'Finalizada',
            ]);

            // 3. Actualizar motor
            $motor->update([
                'Ubicacion_actual' => 'Inventario',
                'Estado' => $request->estado_entrada,
                'Id_sucursales' => $request->Id_sucursales,
                'Id_tecnico_actual' => null,
                'Observacion' => $request->trabajo_realizado,
            ]);

            DB::commit();
            return back()->with('success', "Motor {$motor->Id_motor} devuelto al inventario exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar entrada: ' . $e->getMessage());
        }
    }

    /**
     * Agregar reporte de progreso mientras el técnico tiene el motor
     */
    public function agregarReporteProgreso(Request $request, $idAsignacion)
    {
        $request->validate([
            'fecha_reporte' => 'required|date',
            'estado_actual' => 'required|in:En Diagnostico,En Reparacion,Reparado,Irreparable',
            'descripcion_trabajo' => 'required|string|min:20',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $asignacion = MotorAsignacionActiva::with('motor')
                ->findOrFail($idAsignacion);

            if ($asignacion->Estado_asignacion !== 'Activa') {
                return back()->with('error', 'Solo puedes agregar reportes a asignaciones activas.');
            }

            ReporteProgreso::create([
                'Id_asignacion' => $asignacion->Id_asignacion,
                'Fecha_reporte' => $request->fecha_reporte,
                'Estado_actual' => $request->estado_actual,
                'Descripcion_trabajo' => $request->descripcion_trabajo,
                'Observaciones' => $request->observaciones,
            ]);

            return back()->with('success', 'Reporte de progreso agregado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al agregar reporte: ' . $e->getMessage());
        }
    }

    // ============================================
    // SECCIÓN: REPORTES Y ESTADÍSTICAS
    // ============================================

    /**
     * Historial completo de entradas
     */
    public function historialEntradas(Request $request)
    {
        $query = MotorMovimiento::with(['motor', 'sucursal', 'profesor.persona'])
            ->where('Tipo_movimiento', 'Entrada')
            ->orderBy('Fecha_movimiento', 'desc');

        // Filtros
        if ($request->filled('estado')) {
            $query->where('Estado_entrada', $request->estado);
        }

        if ($request->filled('tecnico_id')) {
            $query->where('Id_profesores', $request->tecnico_id);
        }

        $entradas = $query->paginate(20);
        $tecnicos = Profesor::with('persona')->where('Rol_componentes', 'Tecnico')->get();

        return view('componentes.historialEntradas', compact('entradas', 'tecnicos'));
    }

    /**
     * Ver detalle completo de un motor (historial)
     */
    public function detalleMotor($id)
    {
        $motor = Motor::with([
            'sucursal',
            'tecnicoActual.persona',
            'movimientos' => fn($q) => $q->with(['sucursal', 'profesor.persona', 'usuario.persona'])
                                          ->orderBy('Fecha_movimiento', 'desc'),
            'asignacionActiva.reportesProgreso'
        ])->findOrFail($id);

        return view('componentes.detalleMotor', compact('motor'));
    }

    /**
     * Estadísticas del sistema
     */
    public function estadisticas()
    {
        $stats = [
            'total_motores' => Motor::count(),
            'en_inventario' => Motor::where('Ubicacion_actual', 'Inventario')->count(),
            'con_tecnicos' => Motor::where('Ubicacion_actual', 'Con Tecnico')->count(),
            'funcionando' => Motor::where('Estado', 'Funcionando')->count(),
            'descompuestos' => Motor::where('Estado', 'Descompuesto')->count(),
            'en_reparacion' => Motor::where('Estado', 'En Reparacion')->count(),
            
            // Estadísticas del mes actual
            'salidas_mes' => MotorMovimiento::where('Tipo_movimiento', 'Salida')
                ->whereMonth('Fecha_movimiento', now()->month)
                ->count(),
            'entradas_mes' => MotorMovimiento::where('Tipo_movimiento', 'Entrada')
                ->whereMonth('Fecha_movimiento', now()->month)
                ->count(),
            
            // Técnicos activos
            'tecnicos_activos' => MotorAsignacionActiva::where('Estado_asignacion', 'Activa')
                ->distinct('Id_profesores')
                ->count('Id_profesores'),
        ];

        return response()->json($stats);
    }
}