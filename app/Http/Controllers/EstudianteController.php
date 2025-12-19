<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Programa;
use App\Models\Sucursal;
use App\Models\Tutores;
use App\Models\Rol;
use App\Models\Persona;
use App\Models\PlanesPago;
use App\Models\Evaluacion;
use App\Models\Horario;
use App\Models\Profesor;
use Barryvdh\DomPDF\Facade\Pdf;


class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda desde el request
        $search = $request->input('search');  

        // Realizar la consulta para obtener los estudiantes filtrados
        $estudiantes = Estudiante::with(['persona', 'programa', 'sucursal', 'profesor', 'profesor.persona'])
            ->when($search, function($query, $search) {
                return $query->whereHas('persona', function($q) use ($search) {
                    $q->where('Nombre', 'like', "%$search%")
                    ->orWhere('Apellido', 'like', "%$search%");
                })
                ->orWhere('Cod_estudiante', 'like', "%$search%");
            })
            ->orderBy('Id_estudiantes', 'desc')
            ->paginate(10);  // Paginación de resultados

        // Obtener las listas adicionales necesarias para los formularios de edición
        $programas = Programa::all();
        $sucursales = Sucursal::all();
        $tutores = Tutores::with('persona')->get();

        // Retornar la vista con los estudiantes y relaciones
        return view('administrador.estudiantesAdministrador', compact('estudiantes', 'programas', 'sucursales', 'tutores'));
    }


    public function mostrarFormulario()
    {
        // Obtener programas, sucursales y tutores para el formulario de registro
        $programas = Programa::all();
        $sucursales = Sucursal::all();
        $tutores = Tutores::with('persona')->get();  // Incluye la relación persona de tutor
        return view('administrador.registrarEstudiante', compact('programas', 'sucursales', 'tutores'));
    }

    public function registrar(Request $request)
    {
        // Validaciones de los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'celular' => 'required|string',
            'direccion_domicilio' => 'required|string',
            'codigo_estudiante' => 'required|string|unique:estudiantes,Cod_estudiante',
            'programa' => 'required|exists:programas,Id_programas',
            'sucursal' => 'required|exists:sucursales,Id_sucursales',
            'tutor_estudiante' => 'required|exists:tutores,Id_tutores',
        ]);

        // Obtener el rol de estudiante
        $rolEstudiante = Rol::where('Nombre_rol', 'Estudiante')->first();

        if (!$rolEstudiante) {
            return back()->withErrors(['error' => 'Rol de estudiante no encontrado.']);
        }

        // Crear la persona
        $persona = Persona::create([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Direccion_domicilio' => $request->direccion_domicilio,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Fecha_registro' => now(),
            'Celular' => $request->celular,
            'Id_roles' => $rolEstudiante->Id_roles,
        ]);

        // Verifica si el profesor con Id_profesores = 1 existe
        $profesorId = 1;
        $profesorExiste = \App\Models\Profesor::where('Id_profesores', $profesorId)->exists();

        // Crear el estudiante
        Estudiante::create([
            'Cod_estudiante' => $request->codigo_estudiante,
            'Estado' => 'Activo',
            'Fecha_estado' => now(),
            'Id_personas' => $persona->Id_personas,
            'Id_programas' => $request->programa,
            'Id_sucursales' => $request->sucursal,
            'Id_profesores' => $profesorExiste ? $profesorId : null,
            'Id_tutores' => $request->tutor_estudiante,
        ]);

        return redirect()->back()->with('success', 'Estudiante registrado correctamente.');
    }

    public function editar($id)
    {
        // Buscar al estudiante por su ID y cargar relaciones
        $estudiante = Estudiante::with(['persona', 'programa', 'sucursal', 'tutor.persona'])->find($id);

        if (!$estudiante) {
            return redirect()->route('estudiantes.index')->with('error', 'Estudiante no encontrado.');
        }

        // Obtener programas, sucursales y tutores para el formulario de edición
        $programas = Programa::all();
        $sucursales = Sucursal::all();
        $tutores = Tutores::with('persona')->get();

        // Retornar la vista con los datos del estudiante y las otras variables necesarias
        return view('administrador.editarEstudiante', compact('estudiante', 'programas', 'sucursales', 'tutores'));
    }

    public function actualizar(Request $request, $id)
    {
        // Validaciones para actualizar los datos
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'genero' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'celular' => 'required|string',
            'direccion_domicilio' => 'required|string',
            'programa' => 'required|exists:programas,Id_programas',
            'sucursal' => 'required|exists:sucursales,Id_sucursales',
            'tutor_estudiante' => 'required|exists:tutores,Id_tutores',
        ]);

        // Buscar el estudiante
        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            return back()->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        // Actualizar los datos del estudiante
        $estudiante->update([
            'Id_programas' => $request->programa,
            'Id_sucursales' => $request->sucursal,
            'Id_tutores' => $request->tutor_estudiante,
        ]);

        // Actualizar los datos de la persona relacionada
        $estudiante->persona->update([
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Genero' => $request->genero,
            'Fecha_nacimiento' => $request->fecha_nacimiento,
            'Celular' => $request->celular,
            'Direccion_domicilio' => $request->direccion_domicilio,
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
    }

    public function eliminar($id)
    {
        // Buscar el estudiante
        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            return back()->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        // Eliminar el estudiante
        $estudiante->delete();

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado correctamente.');
    }

    public function ver($id)
{
    // Cargar el estudiante con todas sus relaciones
    $estudiante = Estudiante::with([
        'persona',
        'programa',
        'sucursal',
        'profesor.persona',
        'tutor.persona',
        'horarios' => function($query) {
            $query->orderBy('Dia', 'asc')->orderBy('Hora', 'asc');
        },
        'horarios.programa',
        'horarios.profesor.persona',
        'planesPago' => function($query) {
            $query->latest('fecha_plan_pagos')->limit(3);
        },
        'planesPago.programa',
        'evaluaciones' => function($query) {
            $query->latest('fecha_evaluacion')->limit(5);
        },
        'evaluaciones.programa',
        'evaluaciones.profesor.persona',
        'evaluaciones.modelo',
    ])->findOrFail($id);

    // Obtener el último modelo evaluado
    $ultimaEvaluacion = Evaluacion::where('Id_estudiantes', $id)
        ->with('modelo')
        ->orderBy('fecha_evaluacion', 'desc')
        ->first();

    $ultimoModelo = $ultimaEvaluacion ? $ultimaEvaluacion->modelo : null;
    $proximoModelo = null;

    // Si hay un último modelo, buscar el próximo
    if ($ultimoModelo && $estudiante->programa) {
        // Obtener todos los modelos del programa ordenados
        $modelosPrograma = \App\Models\Modelo::where('Id_programa', $estudiante->Id_programas)
            ->orderBy('Id_modelos', 'asc')
            ->get();

        // Encontrar el índice del último modelo evaluado
        $indiceUltimoModelo = $modelosPrograma->search(function($modelo) use ($ultimoModelo) {
            return $modelo->Id_modelos == $ultimoModelo->Id_modelos;
        });

        // Si se encontró el último modelo y hay un siguiente
        if ($indiceUltimoModelo !== false && $indiceUltimoModelo < $modelosPrograma->count() - 1) {
            $proximoModelo = $modelosPrograma[$indiceUltimoModelo + 1];
        }
    } else if ($estudiante->programa) {
        // Si no hay evaluaciones previas, el próximo modelo es el primero del programa
        $proximoModelo = \App\Models\Modelo::where('Id_programa', $estudiante->Id_programas)
            ->orderBy('Id_modelos', 'asc')
            ->first();
    }

    // Calcular estadísticas adicionales
    $estadisticas = [
        'total_horarios'   => $estudiante->horarios->count(),
        'total_planes_pago' => $estudiante->planesPago->count(),
        'total_evaluaciones' => $estudiante->evaluaciones()->count(),
        'planes_activos'   => $estudiante->planesPago->where('Estado_plan', 'Activo')->count(),
    ];

    return view('administrador.detallesEstudiante', compact('estudiante', 'estadisticas', 'ultimoModelo', 'proximoModelo', 'ultimaEvaluacion'));
}


    /**
     * Cambiar estado del estudiante (Activo/Inactivo)
     */
    public function cambiarEstado($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        // Cambiar el estado
        $nuevoEstado = $estudiante->Estado === 'Activo' ? 'Inactivo' : 'Activo';
        
        $estudiante->update([
            'Estado' => $nuevoEstado,
            'Fecha_estado' => now()
        ]);

        return redirect()
            ->route('estudiantes.ver', $id)
            ->with('success', "Estado del estudiante actualizado a: {$nuevoEstado}");
    }

    /**
     * Ver planes de pago del estudiante
     */
    public function planesPago($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa'])
            ->findOrFail($id);

        $planesPago = PlanesPago::where('Id_estudiantes', $id)
            ->with([
                'programa',
                'cuotas' => function($query) {
                    $query->orderBy('Nro_de_cuota', 'asc');
                }
            ])
            ->orderBy('fecha_plan_pagos', 'desc')
            ->get();

        return view('administrador.planesPagoEstudiante', compact('estudiante', 'planesPago'));
    }

    /**
     * Ver evaluaciones del estudiante
     */
    public function evaluaciones($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa'])
            ->findOrFail($id);

        $evaluaciones = Evaluacion::where('Id_estudiantes', $id)
            ->with([
                'pregunta',
                'respuesta',
                'modelo',
                'profesor.persona',
                'programa'
            ])
            ->orderBy('fecha_evaluacion', 'desc')
            ->get();

        // Agrupar evaluaciones por fecha
        $evaluacionesAgrupadas = $evaluaciones->groupBy('fecha_evaluacion');

        return view('administrador.evaluacionesEstudiante', compact('estudiante', 'evaluacionesAgrupadas'));
    }

    /**
     * Ver horarios del estudiante
     */
    public function horarios($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa'])
            ->findOrFail($id);

        $horarios = Horario::where('Id_estudiantes', $id)
            ->with(['programa', 'profesor.persona'])
            ->get();

        return view('administrador.horariosEstudiante', compact('estudiante', 'horarios'));
    }

    /**
     * Exportar lista de estudiantes a PDF
     */
    public function exportarPDF()
    {
        // Obtener todos los estudiantes con sus relaciones
        $estudiantes = Estudiante::with(['persona', 'programa', 'sucursal'])
            ->orderBy('Cod_estudiante', 'asc')
            ->get();

        // Cargar la vista y generar el PDF
        $pdf = Pdf::loadView('administrador.estudiantes_pdf', compact('estudiantes'));

        // Configurar el PDF
        $pdf->setPaper('letter', 'portrait');

        // Descargar el PDF
        return $pdf->download('estudiantes_lista_' . date('Y-m-d') . '.pdf');
    }
}