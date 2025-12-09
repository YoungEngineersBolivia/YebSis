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
use Illuminate\Support\Facades\Log;

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
        $asignacionActiva = MotorAsignacionActiva::where('Id_motores', $motor->Id_motores)
            ->whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada'])
            ->first();
        
        if ($asignacionActiva) {
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
            ->whereDoesntHave('asignacionActiva')
            ->with('sucursal')
            ->get();
        
        $tecnicos = Profesor::where('Rol_componentes', 'Tecnico')
            ->with('persona')
            ->get();
        
        // ✅ CORREGIDO: Solicitudes pendientes usando Nombre_tecnico
        $solicitudesPendientes = MotorMovimiento::where('Tipo_movimiento', 'Salida')
            ->where('Nombre_tecnico', 'Solicitud Pendiente')
            ->whereHas('motor', function($query) {
                $query->where('Ubicacion_actual', 'Inventario')
                      ->whereNull('Id_tecnico_actual');
            })
            ->with(['motor'])
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
                ->whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada'])
                ->first();
            
            if ($asignacionExistente) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Este motor ya tiene una asignación activa.');
            }
            
            // ✅ Construir nombre completo del técnico
            $nombreTecnico = $profesor->persona->Nombre . ' ' . $profesor->persona->Apellido;
            
            // Registrar movimiento de salida
            $movimiento = MotorMovimiento::create([
                'Id_motores' => $motor->Id_motores,
                'Tipo_movimiento' => 'Salida',
                'Fecha_movimiento' => now(),
                'Id_sucursales' => $motor->Id_sucursales,
                'Id_profesores' => $profesor->Id_profesores,
                'Nombre_tecnico' => $nombreTecnico, // ✅ AGREGADO
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
            
            // ✅ Actualizar solicitudes pendientes
            MotorMovimiento::where('Id_motores', $motor->Id_motores)
                ->where('Tipo_movimiento', 'Salida')
                ->where('Nombre_tecnico', 'Solicitud Pendiente')
                ->update([
                    'Id_profesores' => $profesor->Id_profesores,
                    'Nombre_tecnico' => $nombreTecnico
                ]);
            
            DB::commit();
            
            return redirect()->route('admin.componentes.salida')
                ->with('success', 'Salida registrada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar salida: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }
    
    /**
     * Entrada de Componentes - Lista motores pendientes de entrada
     */
    public function entradaComponentes()
    {
        // Asignaciones activas que están listas para entrega
        $asignacionesActivas = MotorAsignacionActiva::whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada'])
            ->with(['motor', 'profesor.persona', 'reportesProgreso' => function($query) {
                $query->latest('Fecha_reporte');
            }])
            ->orderByRaw("FIELD(Estado_asignacion, 'Pendiente Entrada', 'Activa')")
            ->orderBy('Fecha_salida', 'asc')
            ->get();
        
        return view('componentes.entradaComponentes', compact('asignacionesActivas'));
    }
    
    /**
     * Registrar entrada de motor (ADMINISTRADOR)
     * Confirma la entrada después de que el técnico la propuso
     */
    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'Id_asignacion' => 'required|exists:motores_asignaciones_activas,Id_asignacion',
            'Estado_entrada' => 'required|in:Disponible,Funcionando,Descompuesto',
            'Trabajo_realizado' => 'nullable|string',
            'Observaciones' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            $asignacion = MotorAsignacionActiva::with(['motor', 'profesor.persona'])
                ->findOrFail($request->Id_asignacion);
            
            // Verificar que esté pendiente de entrada o activa
            if (!in_array($asignacion->Estado_asignacion, ['Activa', 'Pendiente Entrada'])) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Esta asignación ya ha sido procesada.');
            }
            
            // Verificar que no exista otra asignación activa para el mismo motor
            $otraAsignacionActiva = MotorAsignacionActiva::where('Id_motores', $asignacion->Id_motores)
                ->whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada'])
                ->where('Id_asignacion', '!=', $asignacion->Id_asignacion)
                ->exists();
            
            if ($otraAsignacionActiva) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Este motor tiene otra asignación activa. No se puede procesar.');
            }
            
            // Usar el trabajo realizado por el técnico si no se proporciona uno nuevo
            $trabajoRealizado = $request->Trabajo_realizado ?: $asignacion->Trabajo_realizado;
            $observaciones = $request->Observaciones ?: $asignacion->Observaciones_entrega;
            
            // ✅ Construir nombre completo del técnico
            $nombreTecnico = $asignacion->profesor->persona->Nombre . ' ' . $asignacion->profesor->persona->Apellido;
            
            // Registrar movimiento de entrada
            $movimiento = MotorMovimiento::create([
                'Id_motores' => $asignacion->Id_motores,
                'Tipo_movimiento' => 'Entrada',
                'Fecha_movimiento' => now(),
                'Id_sucursales' => $asignacion->motor->Id_sucursales,
                'Id_profesores' => $asignacion->Id_profesores,
                'Nombre_tecnico' => $nombreTecnico, // ✅ AGREGADO
                'Estado_entrada' => $request->Estado_entrada,
                'Trabajo_realizado' => $trabajoRealizado,
                'Observaciones' => $observaciones,
                'Id_usuarios' => Auth::id()
            ]);
            
            // Actualizar motor primero (liberar la relación)
            $asignacion->motor->update([
                'Estado' => $request->Estado_entrada,
                'Ubicacion_actual' => 'Inventario',
                'Id_tecnico_actual' => null
            ]);
            
            // Finalizar asignación (usando update para mejor control)
            $asignacion->update([
                'Estado_asignacion' => 'Finalizada',
                'Fecha_entrada_admin' => now(),
                'Id_usuario_entrada' => Auth::id()
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.componentes.entrada')
                ->with('success', 'Entrada registrada y confirmada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar entrada: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
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
        $asignaciones = MotorAsignacionActiva::whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada'])
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
        
        // ✅ Crear solicitud sin técnico asignado
        MotorMovimiento::create([
            'Id_motores' => $motor->Id_motores,
            'Tipo_movimiento' => 'Salida',
            'Fecha_movimiento' => now(),
            'Id_sucursales' => $motor->Id_sucursales,
            'Id_profesores' => null,
            'Nombre_tecnico' => 'Solicitud Pendiente', // ✅ AGREGADO
            'Estado_salida' => $motor->Estado,
            'Motivo_salida' => $request->Motivo_salida,
            'Id_usuarios' => Auth::id()
        ]);
        
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
            ->whereIn('Estado_asignacion', ['Activa', 'Pendiente Entrada'])
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
     * Entregar motor reparado (PROFESOR)
     * El motor queda pendiente de aprobación del administrador
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
                    ->with('error', 'Esta asignación ya ha sido procesada.');
            }
            
            // Guardar información de entrega en la asignación
            $asignacion->update([
                'Estado_asignacion' => 'Pendiente Entrada',
                'Estado_final_propuesto' => $request->Estado_final,
                'Trabajo_realizado' => $request->Trabajo_realizado,
                'Observaciones_entrega' => $request->Observaciones,
                'Fecha_entrega_tecnico' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('profesor.componentes.motores-asignados')
                ->with('success', 'Motor marcado para entrega. El administrador revisará y confirmará la entrada al inventario.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al entregar motor: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error al entregar motor: ' . $e->getMessage());
        }
    }
}