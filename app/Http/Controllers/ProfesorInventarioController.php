<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfesorInventarioController extends Controller
{
    /**
     * Mostrar el inventario de motores asignados a profesores
     */
    public function index(Request $request)
    {
        $profesorId = $request->get('profesor_id');
        $estadoAsignacion = $request->get('estado');
        $busqueda = $request->get('busqueda');

        // Query principal para motores asignados
        $query = DB::table('motores_asignados as ma')
            ->join('motores as m', 'ma.Id_motores', '=', 'm.Id_motores')
            ->join('profesores as prof', 'ma.Id_profesores', '=', 'prof.Id_profesores')
            ->join('personas as pers', 'prof.Id_personas', '=', 'pers.Id_personas')
            ->leftJoin('sucursales as s', 'm.Id_sucursales', '=', 's.Id_Sucursales')
            ->select(
                'ma.Id_motores_asignados',
                'ma.Estado_asignacion',
                'ma.Fecha_asignacion',
                'ma.Fecha_entrega',
                'ma.Observacion_inicial',
                'm.Id_motor',
                'm.Estado as Estado_motor',
                'm.Observacion as Observacion_motor',
                's.Nombre as Nombre_sucursal',
                'prof.Id_profesores',
                'prof.Rol_componentes',
                DB::raw("CONCAT(pers.Nombre, ' ', pers.Apellido) as Nombre_profesor")
            );

        // Aplicar filtros
        if ($profesorId) {
            $query->where('ma.Id_profesores', $profesorId);
        }

        if ($estadoAsignacion) {
            $query->where('ma.Estado_asignacion', $estadoAsignacion);
        }

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('m.Id_motor', 'like', "%{$busqueda}%")
                  ->orWhere(DB::raw("CONCAT(pers.Nombre, ' ', pers.Apellido)"), 'like', "%{$busqueda}%");
            });
        }

        $asignaciones = $query->orderBy('ma.Fecha_asignacion', 'desc')->get();

        // Obtener lista de profesores para el filtro
        $profesores = DB::table('profesores as prof')
            ->join('personas as pers', 'prof.Id_personas', '=', 'pers.Id_personas')
            ->select(
                'prof.Id_profesores',
                DB::raw("CONCAT(pers.Nombre, ' ', pers.Apellido) as Nombre_completo"),
                'prof.Rol_componentes'
            )
            ->get();

        // Obtener motores disponibles para asignar (solo los que están funcionando y no asignados)
        $motoresDisponibles = DB::table('motores as m')
            ->leftJoin('motores_asignados as ma', function($join) {
                $join->on('m.Id_motores', '=', 'ma.Id_motores')
                     ->where('ma.Estado_asignacion', '=', 'En Proceso');
            })
            ->leftJoin('sucursales as s', 'm.Id_sucursales', '=', 's.Id_Sucursales')
            ->whereNull('ma.Id_motores_asignados')
            ->select(
                'm.Id_motores',
                'm.Id_motor',
                'm.Estado',
                's.Nombre as Nombre_sucursal'
            )
            ->get();

        // Obtener sucursales
        $sucursales = DB::table('sucursales')->get();

        return view('profesor.inventarioProfesor', compact('asignaciones', 'profesores', 'motoresDisponibles', 'sucursales'));
    }

    /**
     * Asignar motor a profesor
     */
    public function asignarComponente(Request $request)
    {
        $request->validate([
            'Id_motores' => 'required|exists:motores,Id_motores',
            'Id_profesores' => 'required|exists:profesores,Id_profesores',
            'observaciones' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Verificar que el motor no esté ya asignado
            $asignacionExistente = DB::table('motores_asignados')
                ->where('Id_motores', $request->Id_motores)
                ->where('Estado_asignacion', 'En Proceso')
                ->first();

            if ($asignacionExistente) {
                return redirect()->back()->with('error', 'Este motor ya está asignado a otro profesor');
            }

            // Obtener información del motor
            $motor = DB::table('motores')->where('Id_motores', $request->Id_motores)->first();

            // Crear asignación
            DB::table('motores_asignados')->insert([
                'Id_motores' => $request->Id_motores,
                'Id_profesores' => $request->Id_profesores,
                'Estado_asignacion' => 'En Proceso',
                'Fecha_asignacion' => now(),
                'Observacion_inicial' => $request->observaciones,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Registrar movimiento
            DB::table('motores_movimientos')->insert([
                'Id_motores' => $request->Id_motores,
                'Tipo_movimiento' => 'Salida',
                'Fecha' => now(),
                'Id_sucursales' => $motor->Id_sucursales,
                'Estado_ubicacion' => 'Asignado a Profesor',
                'Ultimo_tecnico' => $request->Id_profesores,
                'Observacion' => 'Asignación a profesor: ' . $request->observaciones,
                'Id_usuarios' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Actualizar estado del motor si es necesario
            DB::table('motores')
                ->where('Id_motores', $request->Id_motores)
                ->update([
                    'Estado' => 'En Proceso',
                    'updated_at' => now(),
                ]);

            DB::commit();
            return redirect()->back()->with('success', 'Motor asignado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al asignar motor: ' . $e->getMessage());
        }
    }

    /**
     * Devolver/Completar asignación de motor
     */
    public function devolverComponente(Request $request, $id)
    {
        $request->validate([
            'Id_sucursales' => 'required|exists:sucursales,Id_Sucursales',
            'estado_final' => 'required|in:Funcionando,Descompuesto,En proceso',
            'observaciones_devolucion' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Obtener la asignación
            $asignacion = DB::table('motores_asignados')
                ->where('Id_motores_asignados', $id)
                ->where('Estado_asignacion', 'En Proceso')
                ->first();

            if (!$asignacion) {
                return redirect()->back()->with('error', 'Asignación no encontrada o ya completada');
            }

            // Actualizar asignación
            DB::table('motores_asignados')
                ->where('Id_motores_asignados', $id)
                ->update([
                    'Estado_asignacion' => 'Completado',
                    'Fecha_entrega' => now(),
                    'updated_at' => now(),
                ]);

            // Crear reporte de mantenimiento
            DB::table('reportes_mantenimiento')->insert([
                'Id_motores_asignados' => $id,
                'Estado_final' => $request->estado_final,
                'Observaciones' => $request->observaciones_devolucion,
                'Fecha_reporte' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Actualizar estado del motor
            DB::table('motores')
                ->where('Id_motores', $asignacion->Id_motores)
                ->update([
                    'Estado' => $request->estado_final,
                    'Observacion' => $request->observaciones_devolucion,
                    'Id_sucursales' => $request->Id_sucursales,
                    'updated_at' => now(),
                ]);

            // Registrar movimiento de entrada
            DB::table('motores_movimientos')->insert([
                'Id_motores' => $asignacion->Id_motores,
                'Tipo_movimiento' => 'Entrada',
                'Fecha' => now(),
                'Id_sucursales' => $request->Id_sucursales,
                'Estado_ubicacion' => 'Devuelto - ' . $request->estado_final,
                'Ultimo_tecnico' => $asignacion->Id_profesores,
                'Observacion' => 'Devolución: ' . $request->observaciones_devolucion,
                'Id_usuarios' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Motor devuelto y reporte registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al devolver motor: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de asignación
     */
    public function verDetalle($id)
    {
        $asignacion = DB::table('motores_asignados as ma')
            ->join('motores as m', 'ma.Id_motores', '=', 'm.Id_motores')
            ->join('profesores as prof', 'ma.Id_profesores', '=', 'prof.Id_profesores')
            ->join('personas as pers', 'prof.Id_personas', '=', 'pers.Id_personas')
            ->leftJoin('sucursales as s', 'm.Id_sucursales', '=', 's.Id_Sucursales')
            ->leftJoin('reportes_mantenimiento as rm', 'ma.Id_motores_asignados', '=', 'rm.Id_motores_asignados')
            ->where('ma.Id_motores_asignados', $id)
            ->select(
                'ma.*',
                'm.Id_motor',
                'm.Estado as Estado_motor',
                'm.Observacion as Observacion_motor',
                's.Nombre as Nombre_sucursal',
                'prof.Profesion',
                'prof.Rol_componentes',
                DB::raw("CONCAT(pers.Nombre, ' ', pers.Apellido) as Nombre_profesor"),
                'pers.Celular',
                'rm.Estado_final',
                'rm.Observaciones as Observaciones_reporte',
                'rm.Fecha_reporte'
            )
            ->first();

        if (!$asignacion) {
            return response()->json(['error' => 'Asignación no encontrada'], 404);
        }

        // Obtener historial de movimientos
        $movimientos = DB::table('motores_movimientos as mm')
            ->leftJoin('sucursales as s', 'mm.Id_sucursales', '=', 's.Id_Sucursales')
            ->leftJoin('usuarios as u', 'mm.Id_usuarios', '=', 'u.Id_usuarios')
            ->leftJoin('personas as p', 'u.Id_personas', '=', 'p.Id_personas')
            ->where('mm.Id_motores', $asignacion->Id_motores)
            ->select(
                'mm.*',
                's.Nombre as Nombre_sucursal',
                DB::raw("CONCAT(p.Nombre, ' ', p.Apellido) as Usuario")
            )
            ->orderBy('mm.Fecha', 'desc')
            ->get();

        return response()->json([
            'asignacion' => $asignacion,
            'movimientos' => $movimientos
        ]);
    }

    /**
     * Crear solicitud de salida de motor
     */
    public function solicitarSalida(Request $request)
    {
        $request->validate([
            'Id_motores' => 'required|exists:motores,Id_motores',
            'motivo' => 'required|string|min:10',
            'observaciones' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Verificar que no exista una solicitud pendiente del mismo motor
            $solicitudExistente = DB::table('solicitudes_salida')
                ->where('Id_motores', $request->Id_motores)
                ->where('Estado_solicitud', 'Pendiente')
                ->first();

            if ($solicitudExistente) {
                return redirect()->back()->with('error', 'Ya existe una solicitud pendiente para este motor');
            }

            // Crear la solicitud
            DB::table('solicitudes_salida')->insert([
                'Id_motores' => $request->Id_motores,
                'Id_usuarios' => auth()->id(),
                'Fecha_solicitud' => now(),
                'Estado_solicitud' => 'Pendiente',
                'Motivo' => $request->motivo . ($request->observaciones ? ' | ' . $request->observaciones : ''),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Solicitud enviada correctamente. Espera la aprobación del administrador.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al enviar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas
     */
    public function estadisticas()
    {
        $stats = [
            'total_asignados' => DB::table('motores_asignados')
                ->where('Estado_asignacion', 'En Proceso')
                ->count(),
            
            'profesores_con_motores' => DB::table('motores_asignados')
                ->where('Estado_asignacion', 'En Proceso')
                ->distinct('Id_profesores')
                ->count('Id_profesores'),
            
            'motores_funcionando' => DB::table('motores')
                ->where('Estado', 'Funcionando')
                ->count(),
            
            'completados_mes' => DB::table('motores_asignados')
                ->where('Estado_asignacion', 'Completado')
                ->whereMonth('Fecha_entrega', now()->month)
                ->count(),
        ];

        return response()->json($stats);
    }

    public function misMotoresAsignados()
    {
        // Obtener el usuario autenticado
        $usuario = auth()->user();
        
        // Buscar el profesor asociado al usuario
        $profesor = DB::table('profesores')
            ->where('Id_usuarios', $usuario->Id_usuarios)
            ->first();

        // Si no encuentra el profesor, crear datos vacíos
        if (!$profesor) {
            $asignaciones = collect([]);
            $sucursales = DB::table('sucursales')->get();
            
            return view('profesor.motoresAsignados', compact('asignaciones', 'sucursales'))
                ->with('error', 'No se encontró el perfil de profesor. ID Usuario: ' . $usuario->Id_usuarios);
        }

        // Obtener las asignaciones del profesor
        $asignaciones = DB::table('motores_asignados as ma')
            ->join('motores as m', 'ma.Id_motores', '=', 'm.Id_motores')
            ->leftJoin('sucursales as s', 'm.Id_sucursales', '=', 's.Id_Sucursales')
            ->where('ma.Id_profesores', $profesor->Id_profesores)
            ->select(
                'ma.Id_motores_asignados',
                'ma.Estado_asignacion',
                'ma.Fecha_asignacion',
                'ma.Fecha_entrega',
                'ma.Observacion_inicial',
                'm.Id_motores',
                'm.Id_motor',
                'm.Estado as Estado_motor',
                'm.Observacion as Observacion_motor',
                's.Nombre as Nombre_sucursal'
            )
            ->orderBy('ma.Fecha_asignacion', 'desc')
            ->get();

        // Obtener sucursales para el modal de devolución
        $sucursales = DB::table('sucursales')->get();

        return view('profesor.motoresAsignados', compact('asignaciones', 'sucursales'));
    }
}