<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Citas;
use App\Models\Tutores;
use App\Models\Estudiante;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CitasController extends Controller
{
    /**
     * Mostrar la vista de administración de citas
     */
    public function index()
    {
        try {
            $hoy = Carbon::today();
            
            // Obtener todas las citas con relaciones
            $citas = Citas::with([
                'tutor.persona', 
                'estudiante.persona'
            ])
                ->orderBy('Fecha', 'desc')
                ->orderBy('Hora', 'desc')
                ->get();
            
            // Separar citas próximas y pasadas
            $citasProximas = $citas->filter(function($cita) use ($hoy) {
                return Carbon::parse($cita->Fecha) >= $hoy;
            })->sortBy('Fecha')->sortBy('Hora');
            
            $citasPasadas = $citas->filter(function($cita) use ($hoy) {
                return Carbon::parse($cita->Fecha) < $hoy;
            })->sortByDesc('Fecha')->sortByDesc('Hora');
            
            // Estadísticas
            $totalCitas = $citas->count();
            $pendientes = $citas->where('estado', 'pendiente')->count();
            $completadas = $citas->where('estado', 'completada')->count();
            $canceladas = $citas->where('estado', 'cancelada')->count();
            
            // Citas de esta semana
            $inicioSemana = $hoy->copy()->startOfWeek();
            $finSemana = $hoy->copy()->endOfWeek();
            $citasSemana = $citas->filter(function($cita) use ($inicioSemana, $finSemana) {
                $fechaCita = Carbon::parse($cita->Fecha);
                return $fechaCita->between($inicioSemana, $finSemana);
            })->count();
            
            // Obtener estudiantes y tutores para el formulario
            $estudiantes = Estudiante::with('persona')
                ->whereHas('persona')
                ->get();
            $tutores = Tutores::with('persona')
                ->whereHas('persona')
                ->get();
            
            return view('administrador.citasAdministrador', compact(
                'citasProximas',
                'citasPasadas',
                'totalCitas',
                'pendientes',
                'completadas',
                'canceladas',
                'citasSemana',
                'estudiantes',
                'tutores'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error en CitasController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error al cargar las citas: ' . $e->getMessage());
        }
    }

    /**
     * Almacenar una nueva cita
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'estudiante_id' => 'required|exists:estudiantes,Id_estudiantes',
                'tutor_id' => 'required|exists:tutores,Id_tutores',
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => 'required|date_format:H:i',
                'motivo' => 'nullable|string|max:500'
            ], [
                'estudiante_id.required' => 'Debe seleccionar un estudiante',
                'estudiante_id.exists' => 'El estudiante seleccionado no existe',
                'tutor_id.required' => 'Debe seleccionar un tutor',
                'tutor_id.exists' => 'El tutor seleccionado no existe',
                'fecha.required' => 'La fecha es obligatoria',
                'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior',
                'hora.required' => 'La hora es obligatoria',
                'hora.date_format' => 'Formato de hora inválido (HH:MM)'
            ]);

            // Verificar disponibilidad del tutor
            $citaExistente = Citas::where('Id_tutores', $validated['tutor_id'])
                ->where('Fecha', $validated['fecha'])
                ->where('Hora', $validated['hora'])
                ->whereIn('estado', ['pendiente', 'completada'])
                ->first();

            if ($citaExistente) {
                return redirect()->back()
                    ->with('error', 'El tutor ya tiene una cita agendada en esta fecha y hora.')
                    ->withInput();
            }

            // Verificar disponibilidad del estudiante
            $citaEstudiante = Citas::where('Id_estudiantes', $validated['estudiante_id'])
                ->where('Fecha', $validated['fecha'])
                ->where('Hora', $validated['hora'])
                ->whereIn('estado', ['pendiente', 'completada'])
                ->first();

            if ($citaEstudiante) {
                return redirect()->back()
                    ->with('error', 'El estudiante ya tiene una cita agendada en esta fecha y hora.')
                    ->withInput();
            }

            // Crear la cita
            $cita = Citas::create([
                'Fecha' => $validated['fecha'],
                'Hora' => $validated['hora'],
                'Id_tutores' => $validated['tutor_id'],
                'Id_estudiantes' => $validated['estudiante_id'],
                'motivo' => $validated['motivo'] ?? null,
                'estado' => 'pendiente'
            ]);

            Log::info('Cita creada por administrador', [
                'cita_id' => $cita->Id_citas,
                'tutor_id' => $validated['tutor_id'],
                'estudiante_id' => $validated['estudiante_id'],
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora']
            ]);

            return redirect()->route('citas.index')
                ->with('success', 'Cita agendada exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error al crear cita: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error al agendar la cita: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Marcar cita como completada
     */
    public function completar($id)
    {
        try {
            $cita = Citas::findOrFail($id);
            
            if ($cita->estado == 'completada') {
                return response()->json([
                    'success' => false,
                    'message' => 'La cita ya está completada.'
                ], 400);
            }
            
            if ($cita->estado == 'cancelada') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede completar una cita cancelada.'
                ], 400);
            }
            
            $estadoAnterior = $cita->estado;
            $cita->estado = 'completada';
            $cita->save();

            Log::info('Cita marcada como completada', [
                'cita_id' => $id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => 'completada'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cita marcada como completada exitosamente.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La cita no existe.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al completar cita: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la cita.'
            ], 500);
        }
    }

    /**
     * Cancelar cita
     */
    public function cancelar($id)
    {
        try {
            $cita = Citas::findOrFail($id);
            
            if ($cita->estado == 'cancelada') {
                return response()->json([
                    'success' => false,
                    'message' => 'La cita ya está cancelada.'
                ], 400);
            }
            
            if ($cita->estado == 'completada') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede cancelar una cita completada.'
                ], 400);
            }
            
            $estadoAnterior = $cita->estado;
            $cita->estado = 'cancelada';
            $cita->save();

            Log::info('Cita cancelada', [
                'cita_id' => $id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => 'cancelada'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cita cancelada exitosamente.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'La cita no existe.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al cancelar cita: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la cita.'
            ], 500);
        }
    }

    /**
     * Obtener citas por filtros (AJAX)
     */
    public function filtrar(Request $request)
    {
        try {
            $query = Citas::with(['tutor.persona', 'estudiante.persona']);
            
            // Filtrar por fecha
            if ($request->has('filtroFecha') && $request->filtroFecha != 'todas') {
                switch ($request->filtroFecha) {
                    case 'hoy':
                        $query->whereDate('Fecha', Carbon::today());
                        break;
                    case 'semana':
                        $query->whereBetween('Fecha', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                    case 'mes':
                        $query->whereMonth('Fecha', Carbon::now()->month)
                              ->whereYear('Fecha', Carbon::now()->year);
                        break;
                    case 'futuras':
                        $query->where('Fecha', '>=', Carbon::today());
                        break;
                    case 'pasadas':
                        $query->where('Fecha', '<', Carbon::today());
                        break;
                }
            }
            
            // Filtrar por estado
            if ($request->has('filtroEstado') && $request->filtroEstado != 'todos') {
                $query->where('estado', $request->filtroEstado);
            }
            
            // Filtrar por búsqueda
            if ($request->has('busqueda') && !empty($request->busqueda)) {
                $busqueda = $request->busqueda;
                $query->where(function($q) use ($busqueda) {
                    $q->whereHas('estudiante.persona', function($subq) use ($busqueda) {
                        $subq->where('Nombre', 'like', "%{$busqueda}%")
                             ->orWhere('Apellido', 'like', "%{$busqueda}%");
                    })->orWhereHas('tutor.persona', function($subq) use ($busqueda) {
                        $subq->where('Nombre', 'like', "%{$busqueda}%")
                             ->orWhere('Apellido', 'like', "%{$busqueda}%");
                    })->orWhere('motivo', 'like', "%{$busqueda}%");
                });
            }
            
            $citas = $query->orderBy('Fecha', 'desc')
                          ->orderBy('Hora', 'desc')
                          ->get();
            
            return response()->json([
                'success' => true,
                'citas' => $citas,
                'total' => $citas->count(),
                'pendientes' => $citas->where('estado', 'pendiente')->count(),
                'completadas' => $citas->where('estado', 'completada')->count(),
                'canceladas' => $citas->where('estado', 'cancelada')->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al filtrar citas: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar citas.'
            ], 500);
        }
    }
    
    /**
     * Mostrar formulario para editar cita
     */
    public function editar($id)
    {
        try {
            $cita = Citas::with(['estudiante.persona', 'tutor.persona'])->findOrFail($id);
            $estudiantes = Estudiante::with('persona')->whereHas('persona')->get();
            $tutores = Tutores::with('persona')->whereHas('persona')->get();
            
            return view('administrador.editarCita', compact('cita', 'estudiantes', 'tutores'));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return redirect()->route('citas.index')
                ->with('error', 'Error al cargar la cita para editar.');
        }
    }
    
    /**
     * Actualizar cita existente
     */
    public function actualizar(Request $request, $id)
    {
        try {
            $cita = Citas::findOrFail($id);
            
            $validated = $request->validate([
                'estudiante_id' => 'required|exists:estudiantes,Id_estudiantes',
                'tutor_id' => 'required|exists:tutores,Id_tutores',
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i',
                'motivo' => 'nullable|string|max:500',
                'estado' => 'required|in:pendiente,completada,cancelada'
            ], [
                'estudiante_id.required' => 'Debe seleccionar un estudiante',
                'tutor_id.required' => 'Debe seleccionar un tutor',
                'fecha.required' => 'La fecha es obligatoria',
                'hora.required' => 'La hora es obligatoria',
                'estado.required' => 'El estado es obligatorio'
            ]);
            
            // Verificar conflictos solo si cambió la fecha, hora o tutor
            if ($cita->Fecha != $validated['fecha'] || 
                $cita->Hora != $validated['hora'] || 
                $cita->Id_tutores != $validated['tutor_id']) {
                
                $citaExistente = Citas::where('Id_tutores', $validated['tutor_id'])
                    ->where('Fecha', $validated['fecha'])
                    ->where('Hora', $validated['hora'])
                    ->where('Id_citas', '!=', $id)
                    ->whereIn('estado', ['pendiente', 'completada'])
                    ->first();

                if ($citaExistente) {
                    return redirect()->back()
                        ->with('error', 'El tutor ya tiene una cita agendada en esta fecha y hora.')
                        ->withInput();
                }
            }
            
            $cita->update([
                'Fecha' => $validated['fecha'],
                'Hora' => $validated['hora'],
                'Id_tutores' => $validated['tutor_id'],
                'Id_estudiantes' => $validated['estudiante_id'],
                'motivo' => $validated['motivo'],
                'estado' => $validated['estado']
            ]);
            
            Log::info('Cita actualizada', [
                'cita_id' => $id,
                'cambios' => $validated
            ]);
            
            return redirect()->route('citas.index')
                ->with('success', 'Cita actualizada exitosamente.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error al actualizar cita: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar la cita: ' . $e->getMessage())
                ->withInput();
        }
    }
}