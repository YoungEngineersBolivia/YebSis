<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\MotorMovimiento;
use App\Models\MotorAsignacionActiva;
use App\Models\ReporteProgreso;
use App\Models\Profesor;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComponentesController extends Controller
{
    // ==========================================
    // VISTAS ADMINISTRADOR
    // ==========================================
    
    /**
     * Inventario - Muestra todos los motores/componentes
     */
    public function inventario()
    {
        $motores = Motor::with(['sucursal', 'tecnicoActual.persona'])
            ->orderBy('Id_motor', 'asc')
            ->get();
        
        $sucursales = Sucursal::all();
        
        return view('componentes.inventarioComponentes', compact('motores', 'sucursales'));
    }
    
    /**
     * Crear nuevo motor/componente
     */
    public function storeMotor(Request $request)
    {
        $request->validate([
            'Id_motor' => 'required|unique:motores,Id_motor',
            'Estado' => 'required|in:Disponible,En Reparacion,Funcionando,Descompuesto',
            'Id_sucursales' => 'nullable|exists:sucursales,Id_Sucursales',
            'Observacion' => 'nullable|string'
        ]);
        
        Motor::create([
            'Id_motor' => $request->Id_motor,
            'Estado' => $request->Estado,
            'Ubicacion_actual' => 'Inventario',
            'Id_sucursales' => $request->Id_sucursales,
            'Observacion' => $request->Observacion
        ]);
        
        return redirect()->route('admin.componentes.inventario')
            ->with('success', 'Motor registrado exitosamente');
    }
    
    /**
     * Actualizar motor/componente
     */
    public function updateMotor(Request $request, $id)
    {
        $motor = Motor::findOrFail($id);
        
        $request->validate([
            'Id_motor' => 'required|unique:motores,Id_motor,' . $id . ',Id_motores',
            'Estado' => 'required|in:Disponible,En Reparacion,Funcionando,Descompuesto',
            'Id_sucursales' => 'nullable|exists:sucursales,Id_Sucursales',
            'Observacion' => 'nullable|string'
        ]);
        
        $motor->update([
            'Id_motor' => $request->Id_motor,
            'Estado' => $request->Estado,
            'Id_sucursales' => $request->Id_sucursales,
            'Observacion' => $request->Observacion
        ]);
        
        return redirect()->route('admin.componentes.inventario')
            ->with('success', 'Motor actualizado exitosamente');
    }
    
    /**
     * Eliminar motor/componente
     */
    public function deleteMotor($id)
    {
        $motor = Motor::findOrFail($id);
        
        // Verificar si tiene asignaciones activas
        if ($motor->asignacionActiva) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar un motor con asignación activa');
        }
        
        $motor->delete();
        
        return redirect()->route('admin.componentes.inventario')
            ->with('success', 'Motor eliminado exitosamente');
    }
    
    /**
     * Salida de Componentes - Lista motores pendientes de salir
     */
    public function salidaComponentes()
    {
        // Solo motores disponibles en inventario
        $motores = Motor::where('Ubicacion_actual', 'Inventario')
            ->where('Estado', '!=', 'Funcionando')
            ->whereDoesntHave('asignacionActiva') // No tiene asignación activa
            ->with('sucursal')
            ->get();
        
        $tecnicos = Profesor::where('Rol_componentes', 'Tecnico')
            ->with('persona')
            ->get();
        
        // Solicitudes pendientes: motores que NO tienen asignación activa
        // Y que el motor esté en inventario (no asignado)
        $solicitudesPendientes = MotorMovimiento::where('Tipo_movimiento', 'Salida')
            ->where('Nombre_tecnico', 'Solicitud Pendiente')
            ->whereHas('motor', function($query) {
                $query->where('Ubicacion_actual', 'Inventario')
                      ->whereNull('Id_tecnico_actual');
            })
            ->with(['motor', 'profesor.persona'])
            ->latest('Fecha_movimiento')
            ->get();
        
        return view('componentes.salidaComponentes', compact('motores', 'tecnicos', 'solicitudesPendientes'));
    }
    
    /**
     * Registrar salida de motor
     */
    public function registrarSalida(Request $request)
    {
        $request->validate([
            'Id_motores' => 'required|exists:motores,Id_motores',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'Estado_salida' => 'required|string',
            'Motivo_salida' => 'required|string',
            'Observaciones' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            $motor = Motor::findOrFail($request->Id_motores);
            $profesor = Profesor::with('persona')->findOrFail($request->Id_profesores);
            
            // Verificar que el motor tenga sucursal asignada
            if (!$motor->Id_sucursales) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'El motor no tiene una sucursal asignada. Por favor, asigna una sucursal al motor primero.');
            }
            
            // Verificar que el motor no tenga asignación activa
            $asignacionExistente = MotorAsignacionActiva::where('Id_motores', $motor->Id_motores)
                ->where('Estado_asignacion', 'Activa')
                ->first();
            
            if ($asignacionExistente) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Este motor ya tiene una asignación activa.');
            }
            
            // Registrar movimiento de salida
            $movimiento = MotorMovimiento::create([
                'Id_motores' => $motor->Id_motores,
                'Tipo_movimiento' => 'Salida',
                'Fecha_movimiento' => now(),
                'Id_sucursales' => $motor->Id_sucursales,
                'Id_profesores' => $profesor->Id_profesores,
                'Nombre_tecnico' => $profesor->persona->Nombre . ' ' . $profesor->persona->Apellido,
                'Estado_salida' => $request->Estado_salida,
                'Motivo_salida' => $request->Motivo_salida,
                'Observaciones' => $request->Observaciones,
                'Id_usuarios' => Auth::id()
            ]);
            
            // Crear asignación activa
            MotorAsignacionActiva::create([
                'Id_motores' => $motor->Id_motores,
                'Id_profesores' => $profesor->Id_profesores,
                'Id_movimiento_salida' => $movimiento->Id_movimientos,
                'Fecha_salida' => now(),
                'Estado_motor_salida' => $request->Estado_salida,
                'Motivo_salida' => $request->Motivo_salida,
                'Estado_asignacion' => 'Activa'
            ]);
            
            // Actualizar motor
            $motor->update([
                'Estado' => 'En Reparacion',
                'Ubicacion_actual' => 'Con Tecnico',
                'Id_tecnico_actual' => $profesor->Id_profesores
            ]);
            
            // Actualizar solicitudes pendientes relacionadas (marcarlas como procesadas)
            MotorMovimiento::where('Id_motores', $motor->Id_motores)
                ->where('Tipo_movimiento', 'Salida')
                ->where('Nombre_tecnico', 'Solicitud Pendiente')
                ->update(['Nombre_tecnico' => 'Procesada - ' . $profesor->persona->Nombre . ' ' . $profesor->persona->Apellido]);
            
            DB::commit();
            
            return redirect()->route('admin.componentes.salida')
                ->with('success', 'Salida registrada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }
    
    /**
     * Entrada de Componentes - Lista motores pendientes de entrada
     */
    public function entradaComponentes()
    {
        $asignacionesActivas = MotorAsignacionActiva::where('Estado_asignacion', 'Activa')
            ->with(['motor', 'profesor.persona', 'reportesProgreso' => function($query) {
                $query->latest('Fecha_reporte');
            }])
            ->get();
        
        return view('componentes.entradaComponentes', compact('asignacionesActivas'));
    }
    
    /**
     * Registrar entrada de motor
     */
    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'Id_asignacion' => 'required|exists:motores_asignaciones_activas,Id_asignacion',
            'Estado_entrada' => 'required|in:Disponible,Funcionando,Descompuesto',
            'Trabajo_realizado' => 'required|string',
            'Observaciones' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            $asignacion = MotorAsignacionActiva::with(['motor', 'profesor.persona'])
                ->findOrFail($request->Id_asignacion);
            
            // Registrar movimiento de entrada
            $movimiento = MotorMovimiento::create([
                'Id_motores' => $asignacion->Id_motores,
                'Tipo_movimiento' => 'Entrada',
                'Fecha_movimiento' => now(),
                'Id_sucursales' => $asignacion->motor->Id_sucursales,
                'Id_profesores' => $asignacion->Id_profesores,
                'Nombre_tecnico' => $asignacion->profesor->persona->Nombre . ' ' . $asignacion->profesor->persona->Apellido,
                'Estado_entrada' => $request->Estado_entrada,
                'Trabajo_realizado' => $request->Trabajo_realizado,
                'Observaciones' => $request->Observaciones,
                'Id_usuarios' => Auth::id()
            ]);
            
            // Actualizar motor
            $asignacion->motor->update([
                'Estado' => $request->Estado_entrada,
                'Ubicacion_actual' => 'Inventario',
                'Id_tecnico_actual' => null
            ]);
            
            // Finalizar asignación - CORREGIDO
            $asignacion->Estado_asignacion = 'Finalizada';
            $asignacion->save();
            
            DB::commit();
            
            return redirect()->route('admin.componentes.entrada')
                ->with('success', 'Entrada registrada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar entrada: ' . $e->getMessage());
        }
    }
    
    /**
     * Historial de movimientos de un motor específico
     */
    public function historialMovimientos($id_motor)
    {
        $motor = Motor::with('sucursal')->findOrFail($id_motor);
        
        $movimientos = MotorMovimiento::where('Id_motores', $id_motor)
            ->with('profesor.persona')
            ->orderBy('Fecha_movimiento', 'desc')
            ->get();
        
        return view('componentes.historialAsignaciones', compact('motor', 'movimientos'));
    }
    
    /**
     * Historial de salidas
     */
    public function historialSalidas()
    {
        $salidas = MotorMovimiento::where('Tipo_movimiento', 'Salida')
            ->with(['motor', 'profesor.persona', 'sucursal'])
            ->orderBy('Fecha_movimiento', 'desc')
            ->paginate(20);
        
        return view('componentes.historialSalidas', compact('salidas'));
    }
    
    /**
     * Historial de entradas
     */
    public function historialEntradas()
    {
        $entradas = MotorMovimiento::where('Tipo_movimiento', 'Entrada')
            ->with(['motor', 'profesor.persona', 'sucursal'])
            ->orderBy('Fecha_movimiento', 'desc')
            ->paginate(20);
        
        return view('componentes.historialEntradas', compact('entradas'));
    }
    
    /**
     * Lista de asignaciones activas
     */
    public function listaAsignaciones()
    {
        $asignaciones = MotorAsignacionActiva::where('Estado_asignacion', 'Activa')
            ->with(['motor', 'profesor.persona', 'reportesProgreso'])
            ->orderBy('Fecha_salida', 'desc')
            ->get();
        
        return view('componentes.listaAsignaciones', compact('asignaciones'));
    }
    
    // ==========================================
    // VISTAS PROFESOR
    // ==========================================
    
    /**
     * Profesor Inventario - Vista para solicitar salidas (rol: Inventario)
     */
    public function inventarioProfesor()
    {
        $motores = Motor::where('Ubicacion_actual', 'Inventario')
            ->with('sucursal')
            ->get();
        
        // Obtener el profesor actual de forma segura
        $usuario = Auth::user();
        
        // Verificar que el usuario tenga persona
        if (!$usuario->persona) {
            return redirect()->route('profesor.home')
                ->with('error', 'No se encontró información de persona asociada.');
        }
        
        // Buscar el profesor por Id_personas
        $profesor = Profesor::with('persona')
            ->where('Id_personas', $usuario->persona->Id_personas)
            ->firstOrFail();
        
        // Verificar que tenga rol de inventario
        if ($profesor->Rol_componentes !== 'Inventario') {
            return redirect()->route('profesor.home')
                ->with('error', 'No tienes permisos para acceder al inventario.');
        }
        
        return view('profesor.inventarioProfesor', compact('motores', 'profesor'));
    }
    
    /**
     * Solicitar salida de motor (Profesor con rol Inventario)
     */
    public function solicitarSalida(Request $request)
    {
        $request->validate([
            'Id_motores' => 'required|exists:motores,Id_motores',
            'Motivo_salida' => 'required|string|min:10'
        ]);
        
        $motor = Motor::findOrFail($request->Id_motores);
        
        // Obtener el profesor actual de forma segura
        $usuario = Auth::user();
        
        if (!$usuario->persona) {
            return redirect()->back()
                ->with('error', 'No se encontró información de persona asociada.');
        }
        
        $profesor = Profesor::where('Id_personas', $usuario->persona->Id_personas)->first();
        
        if (!$profesor) {
            return redirect()->back()
                ->with('error', 'No se encontró información de profesor.');
        }
        
        // Crear solicitud de salida (sin asignar técnico aún)
        // El motor permanece en Inventario hasta que el admin lo asigne
        MotorMovimiento::create([
            'Id_motores' => $motor->Id_motores,
            'Tipo_movimiento' => 'Salida',
            'Fecha_movimiento' => now(),
            'Id_sucursales' => $motor->Id_sucursales,
            'Id_profesores' => $profesor->Id_profesores,
            'Nombre_tecnico' => 'Solicitud Pendiente',
            'Estado_salida' => $motor->Estado,
            'Motivo_salida' => $request->Motivo_salida,
            'Id_usuarios' => Auth::id()
        ]);
        
        // NO actualizar el motor aquí, solo crear la solicitud
        // El motor se actualiza cuando el admin asigna un técnico en registrarSalida()
        
        return redirect()->route('profesor.componentes.inventario')
            ->with('success', 'Solicitud enviada exitosamente. El administrador la revisará y asignará un técnico.');
    }
    
    /**
     * Profesor Técnico - Motores asignados (rol: Tecnico)
     */
    public function motoresAsignados()
    {
        // Obtener el profesor actual de forma segura
        $usuario = Auth::user();
        
        // Verificar que el usuario tenga persona
        if (!$usuario->persona) {
            return redirect()->route('profesor.home')
                ->with('error', 'No se encontró información de persona asociada.');
        }
        
        // Buscar el profesor por Id_personas
        $profesor = Profesor::with('persona')
            ->where('Id_personas', $usuario->persona->Id_personas)
            ->firstOrFail();
        
        // Verificar que tenga rol de técnico
        if ($profesor->Rol_componentes !== 'Tecnico') {
            return redirect()->route('profesor.home')
                ->with('error', 'No tienes permisos para acceder a reparaciones.');
        }
        
        $asignaciones = MotorAsignacionActiva::where('Id_profesores', $profesor->Id_profesores)
            ->where('Estado_asignacion', 'Activa')
            ->with(['motor.sucursal', 'reportesProgreso' => function($query) {
                $query->latest('Fecha_reporte')->limit(1);
            }])
            ->get();
        
        return view('profesor.motoresAsignados', compact('asignaciones', 'profesor'));
    }
    
    /**
     * Actualizar estado de reparación
     */
    public function actualizarEstadoReparacion(Request $request)
    {
        $request->validate([
            'Id_asignacion' => 'required|exists:motores_asignaciones_activas,Id_asignacion',
            'Estado_actual' => 'required|in:En Diagnostico,En Reparacion,Reparado,Irreparable',
            'Descripcion_trabajo' => 'required|string',
            'Observaciones' => 'nullable|string'
        ]);
        
        ReporteProgreso::create([
            'Id_asignacion' => $request->Id_asignacion,
            'Fecha_reporte' => now(),
            'Estado_actual' => $request->Estado_actual,
            'Descripcion_trabajo' => $request->Descripcion_trabajo,
            'Observaciones' => $request->Observaciones
        ]);
        
        return redirect()->route('profesor.componentes.motores-asignados')
            ->with('success', 'Estado actualizado exitosamente');
    }
    
    /**
     * Entregar motor reparado
     */
    public function entregarMotor(Request $request)
    {
        $request->validate([
            'Id_asignacion' => 'required|exists:motores_asignaciones_activas,Id_asignacion',
            'Estado_final' => 'required|in:Disponible,Funcionando,Descompuesto',
            'Trabajo_realizado' => 'required|string',
            'Observaciones' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            $asignacion = MotorAsignacionActiva::with(['motor', 'profesor.persona'])
                ->findOrFail($request->Id_asignacion);
            
            // Verificar que la asignación esté activa
            if ($asignacion->Estado_asignacion !== 'Activa') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Esta asignación ya ha sido finalizada.');
            }
            
            // Registrar movimiento de entrada
            MotorMovimiento::create([
                'Id_motores' => $asignacion->Id_motores,
                'Tipo_movimiento' => 'Entrada',
                'Fecha_movimiento' => now(),
                'Id_sucursales' => $asignacion->motor->Id_sucursales,
                'Id_profesores' => $asignacion->Id_profesores,
                'Nombre_tecnico' => $asignacion->profesor->persona->Nombre . ' ' . $asignacion->profesor->persona->Apellido,
                'Estado_entrada' => $request->Estado_final,
                'Trabajo_realizado' => $request->Trabajo_realizado,
                'Observaciones' => $request->Observaciones,
                'Id_usuarios' => Auth::id()
            ]);
            
            // Actualizar motor
            $asignacion->motor->update([
                'Estado' => $request->Estado_final,
                'Ubicacion_actual' => 'Inventario',
                'Id_tecnico_actual' => null
            ]);
            
            // Finalizar asignación - CORREGIDO: usar save() en lugar de update()
            $asignacion->Estado_asignacion = 'Finalizada';
            $asignacion->save();
            
            DB::commit();
            
            return redirect()->route('profesor.componentes.motores-asignados')
                ->with('success', 'Motor entregado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al entregar motor: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al entregar motor: ' . $e->getMessage());
        }
    }
}