<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\RegistroAdministradorController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\TutoresController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\GraduadoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\EgresosController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\RegistroCombinadoController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\PubNot;
use App\Http\Controllers\EstudiantesInactivosController;
use App\Http\Controllers\EstudiantesActivosController;
use App\Http\Controllers\ReporteTalleresController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\InscripcionEstudianteController;
use App\Http\Controllers\PaginaWebController;
use App\Http\Controllers\ProspectoController;
use App\Http\Controllers\ClasePruebaController;
use App\Http\Controllers\TutorHomeController;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\ProfesoresController;
use App\Http\Controllers\MotoresAsignadosController;

/* ============================================
   RUTAS PÚBLICAS (Sin autenticación)
   ============================================ */

// Página principal pública
Route::get('/', [PaginaWebController::class, 'home'])->name('home');

// Registro de prospectos (formulario público)
Route::post('/prospectos', [ProspectoController::class, 'store'])->name('prospectos.store');

// Autenticación
Route::get('/login', fn() => view('paginaWeb.login'))->name('login');
Route::post('/login', [CustomLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');


/* ============================================
   RUTAS PARA ADMINISTRADOR
   ============================================ */

Route::middleware(['auth', 'role:administrador'])->prefix('administrador')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Vista base
    Route::get('/inicioAdministrador', fn() => view('/administrador/inicioAdministrador'));
    Route::get('/registrosAdministrador', fn() => view('/administrador/registrosAdministradores'));
    
    /* ----------------- GESTIÓN DE ADMINISTRADORES ----------------- */
    Route::post('/administradores/registrar', [AdministradorController::class, 'registrarAdmin'])->name('administrador.registrar');
    Route::post('/administradores/registrarC', [AdministradorController::class, 'registrarComercial'])->name('administrador.registrarC');
    Route::post('/administradores/registrarT', [AdministradorController::class, 'registrarTutor'])->name('administrador.registrarT');
    Route::post('/administradores/registrarP', [AdministradorController::class, 'registrarProfesor'])->name('administrador.registrarP');
    
    Route::get('/registrarProfesor', fn() => view('/administrador/registrarProfesor'));
    Route::get('/registrarTutor', fn() => view('/administrador/registrarTutor'));
    Route::get('/registrarComercial', fn() => view('/administrador/registrarComercial'));
    
    /* ----------------- GESTIÓN DE TUTORES ----------------- */
    Route::get('/tutoresAdministrador', [TutoresController::class, 'index'])->name('tutores.index');
    Route::get('/tutores/{id}', [TutoresController::class, 'show'])->name('tutores.show');
    Route::get('/tutores/{id}/edit', [TutoresController::class, 'edit'])->name('tutores.edit');
    Route::put('/tutores/{id}', [TutoresController::class, 'update'])->name('tutores.update');
    Route::delete('/tutores/{id}', [TutoresController::class, 'destroy'])->name('tutores.destroy');
    
    /* ----------------- GESTIÓN DE USUARIOS ----------------- */
    Route::get('/usuariosAdministrador', [UsuariosController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{id}', [UsuariosController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{id}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    
    /* ----------------- GESTIÓN DE HORARIOS ----------------- */
    Route::get('/horariosAdministrador', [HorariosController::class, 'index'])->name('horarios.index');
    Route::get('/horarios/create', [HorariosController::class, 'create'])->name('horarios.create');
    Route::post('/horarios', [HorariosController::class, 'store'])->name('horarios.store');
    Route::get('/horarios/{id}', [HorariosController::class, 'show'])->name('horarios.show');
    Route::get('/horarios/{id}/edit', [HorariosController::class, 'edit'])->name('horarios.edit');
    Route::put('/horarios/{id}', [HorariosController::class, 'update'])->name('horarios.update');
    Route::delete('/horarios/{id}', [HorariosController::class, 'destroy'])->name('horarios.destroy');
    Route::post('/horarios/asignar', [HorariosController::class, 'asignar'])->name('horarios.asignar');
    
    /* ----------------- GESTIÓN DE ESTUDIANTES ----------------- */
    Route::get('/estudiantesAdministrador', [EstudianteController::class, 'index'])->name('admin.estudiantes');
    
    // Inscripción de estudiantes antiguos
    Route::get('/registrarEstudianteAntiguo', [InscripcionEstudianteController::class, 'mostrarFormulario'])->name('inscripcionEstudiante.mostrar');
    Route::post('/registrarEstudianteAntiguo/registrar', [InscripcionEstudianteController::class, 'inscribir'])->name('inscripcionEstudiante.registrar');
    Route::post('/registrarEstudianteAntiguo/buscar-codigo', [InscripcionEstudianteController::class, 'buscarPorCodigo'])->name('inscripcionEstudiante.buscarCodigo');
    Route::post('/registrarEstudianteAntiguo/buscar-nombre', [InscripcionEstudianteController::class, 'buscarPorNombre'])->name('inscripcionEstudiante.buscarNombre');
    Route::post('/registrarEstudianteAntiguo/obtener-por-tipo', [InscripcionEstudianteController::class, 'obtenerPorTipo'])->name('inscripcionEstudiante.obtenerPorTipo');
    
    /* ----------------- GESTIÓN DE PROGRAMAS ----------------- */
    Route::get('/programasAdministrador', [ProgramaController::class, 'index']);
    Route::get('/nuevosProgramasAdministrador', fn() => view('administrador.nuevoProgramaAdministrador'));
    
    /* ----------------- GESTIÓN DE GRADUADOS ----------------- */
    Route::get('/graduadosAdministrador', [GraduadoController::class, 'index'])->name('graduados.index');
    
    /* ----------------- GESTIÓN DE PAGOS ----------------- */
    Route::get('/pagosAdministrador', [PagosController::class, 'form'])->name('pagos.form');
    Route::get('/pagos', [PagosController::class, 'index'])->name('pagos.index');
    Route::post('/pagosAdministrador', [PagosController::class, 'registrarPago'])->name('pagos.registrar');
    
    /* ----------------- GESTIÓN DE SUCURSALES ----------------- */
    Route::get('/sucursalesAdministrador', [SucursalController::class, 'index'])->name('sucursales.index');
    Route::post('/sucursalesAdministrador', [SucursalController::class, 'store'])->name('sucursales.store');
    
    /* ----------------- GESTIÓN DE EGRESOS ----------------- */
    Route::get('/egresosAdministrador', [EgresosController::class, 'index'])->name('egresos.index');
    Route::post('/egresosAdministrador', [EgresosController::class, 'store'])->name('egresos.store');
    Route::get('/egresos/crear', [EgresosController::class, 'create'])->name('egresos.crear');
    Route::post('/egresos/registrar', [EgresosController::class, 'store'])->name('egresos.registrar');
    
    /* ----------------- PUBLICACIONES Y NOTIFICACIONES ----------------- */
    Route::get('/pubnotAdministrador', [PubNot::class, 'index'])->name('publicaciones.index');
    Route::post('/pubnotAdministrador', [PubNot::class, 'store'])->name('publicaciones.store');
    Route::delete('/pubnotAdministrador/{id}', [PubNot::class, 'destroy'])->name('publicaciones.destroy');
    Route::post('/notificaciones', [PubNot::class, 'storeNotificacion'])->name('notificaciones.store');
    
    /* ----------------- REGISTRO COMBINADO (TUTOR + ESTUDIANTE) ----------------- */
    Route::get('/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'mostrarFormulario'])->name('registroCombinado.form');
    Route::post('/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'registrar'])->name('registroCombinado.registrar');
    
    /* ----------------- COMPONENTES/MOTORES ----------------- */
    Route::get('/componentes', [ComponentesController::class, 'index'])->name('componentes.index');
    Route::post('/componentes/nuevo', [ComponentesController::class, 'store'])->name('componentes.store');
    Route::post('/componentes/entrada', [ComponentesController::class, 'registrarEntrada'])->name('componentes.registrarEntrada');
    Route::post('/componentes/salida', [ComponentesController::class, 'registrarSalida'])->name('componentes.registrarSalida');
    Route::delete('/componentes/{motor}', [ComponentesController::class, 'destroy'])->name('componentes.destroy');
    Route::get('/componentes/{id}/historial', [ComponentesController::class, 'historial'])->name('componentes.historial');
    Route::put('/componentes/{id}', [ComponentesController::class, 'update'])->name('componentes.update');
    
    /* ----------------- GESTIÓN DE PROFESORES ----------------- */
    Route::get('/profesores', [ProfesoresController::class, 'index'])->name('profesores.index');
    Route::get('/profesores/{id}', [ProfesoresController::class, 'show'])->name('profesores.show');
    Route::get('/profesores/{id}/edit', [ProfesoresController::class, 'edit'])->name('profesores.edit');
    Route::post('/profesores', [ProfesoresController::class, 'store'])->name('profesores.store');
    Route::put('/profesores/{id}', [ProfesoresController::class, 'update'])->name('profesores.update');
    Route::delete('/profesores/{id}', [ProfesoresController::class, 'destroy'])->name('profesores.destroy');
    
    /* ----------------- MOTORES ASIGNADOS ----------------- */
    Route::prefix('motores')->group(function () {
        Route::get('/asignaciones', [MotoresAsignadosController::class, 'index'])->name('motores.asignaciones.index');
        Route::get('/asignar', [MotoresAsignadosController::class, 'create'])->name('motores.asignar.create');
        Route::post('/asignar', [MotoresAsignadosController::class, 'store'])->name('motores.asignar.store');
        Route::post('/entrada/{id}', [MotoresAsignadosController::class, 'registrarEntrada'])->name('motores.registrar.entrada');
        Route::post('/reporte/{id}', [MotoresAsignadosController::class, 'storeReporte'])->name('motores.reporte.store');
    });
});


/* ============================================
   RUTAS COMPARTIDAS: ADMINISTRADOR Y COMERCIAL
   ============================================ */

Route::middleware(['auth', 'role:administrador,comercial'])->group(function () {
    
    /* ----------------- ESTUDIANTES CRUD ----------------- */
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::put('/estudiantes/editar/{id}', [EstudianteController::class, 'editar'])->name('estudiantes.editar');
    Route::delete('/estudiantes/{id}', [EstudianteController::class, 'eliminar'])->name('estudiantes.eliminar');
    Route::get('/estudiantes/{id}', [EstudianteController::class, 'ver'])->name('estudiantes.ver');
    Route::put('/estudiantes/{id}/cambiar-estado', [EstudianteController::class, 'cambiarEstado'])->name('estudiantes.cambiarEstado');
    
    /* ----------------- PROGRAMAS CRUD ----------------- */
    Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
    Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');
    Route::get('/programas/{id}', [ProgramaController::class, 'show'])->name('programas.show');
    Route::get('/admin/programas/{id}/edit', [ProgramaController::class, 'edit'])->name('programas.edit');
    Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('programas.update');
    Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('programas.destroy');
    
    /* ----------------- PLANES DE PAGO ----------------- */
    Route::post('/planes-pago/registrar', [App\Http\Controllers\PlanesPagoController::class, 'registrar'])->name('planes-pago.registrar');
});


/* ============================================
   RUTAS PARA COMERCIAL
   ============================================ */

Route::middleware(['auth', 'role:administrador'])->prefix('comercial')->group(function () {
    
    /* ----------------- ESTUDIANTES ACTIVOS ----------------- */
    Route::get('/estudiantesActivos', [EstudiantesActivosController::class, 'index'])->name('estudiantesActivos');
    Route::get('/estudiantesActivos/exportar', [EstudiantesActivosController::class, 'exportar'])->name('estudiantesActivos.exportar');
    Route::put('/estudiantes/desactivar/{id}', [EstudiantesActivosController::class, 'desactivar'])->name('estudiantes.desactivar');
    Route::get('/estudianteActivoComercial', fn() => view('comercial.estudianteActivoComercial'))->name('comercial.estudianteActivoComercial');
    
    /* ----------------- ESTUDIANTES INACTIVOS ----------------- */
    Route::get('/estudiantesNoActivos', [EstudiantesInactivosController::class, 'index'])->name('estudiantesNoActivos');
    Route::put('/estudiantes/activar/{id}', [EstudiantesInactivosController::class, 'reactivar'])->name('estudiantes.reactivar');
    
    /* ----------------- REPORTES DE TALLERES ----------------- */
    Route::get('/talleresComercial', [ReporteTalleresController::class, 'index'])->name('reportes.talleres');
    Route::get('/reportes/talleres/exportar', [ReporteTalleresController::class, 'exportar'])->name('reportes.talleres.exportar');
    Route::get('/api/reportes/talleres/datos', [ReporteTalleresController::class, 'obtenerDatos'])->name('api.reportes.talleres.datos');
    
    /* ----------------- PROSPECTOS ----------------- */
    Route::get('/prospectosComercial', [ProspectoController::class, 'index'])->name('prospectos.comercial');
    Route::put('/prospectos/{id}/estado', [ProspectoController::class, 'updateEstado'])->name('prospectos.updateEstado');
    
    /* ----------------- CLASES DE PRUEBA ----------------- */
    Route::post('/claseprueba/store', [ClasePruebaController::class, 'store'])->name('claseprueba.store');
});


/* ============================================
   RUTAS PARA PROFESOR
   ============================================ */

Route::middleware(['auth', 'role:profesor'])->prefix('profesor')->name('profesor.')->group(function () {
    
    // Home del profesor
    Route::get('/homeProfesor', function () {
        return view('profesor.homeProfesor', ['usuario' => auth()->user()]);
    })->name('home');
    
    // Menú de alumnos (3 botones principales)
    Route::get('/menu-alumnos', [ProfesorController::class, 'menuAlumnosProfesor'])->name('menu-alumnos');
    Route::get('/menuAlumnosProfesor', [ProfesorController::class, 'menuAlumnosProfesor'])->name('alumnos');
    
    // Listado de alumnos según tipo
    Route::get('/listado-alumnos/{tipo}', [ProfesorController::class, 'listadoAlumnos'])
        ->name('listado-alumnos')
        ->where('tipo', 'evaluar|asignados|recuperatoria');
    
    // Detalle de estudiante
    Route::get('/estudiante/{id}', [ProfesorController::class, 'detalleEstudiante'])->name('detalle-estudiante');
    
    // Editar estudiante
    Route::get('/estudiante/{id}/editar', [ProfesorController::class, 'editarEstudiante'])->name('editar-estudiante');
    
    // Evaluaciones
    Route::get('/evaluarAlumno', [ProfesorController::class, 'evaluarAlumno'])->name('evaluarAlumno');
    Route::get('/estudiante/{id}/evaluar', [ProfesorController::class, 'evaluarEstudiante'])->name('evaluar-estudiante');
    Route::post('/evaluacion/guardar', [ProfesorController::class, 'guardarEvaluacion'])->name('guardar-evaluacion');
    Route::put('/evaluacion/{id}/actualizar', [ProfesorController::class, 'actualizarEvaluacion'])->name('actualizar-evaluacion');
});


/* ============================================
   RUTAS PARA TUTOR
   ============================================ */

Route::middleware(['auth', 'role:tutor'])->prefix('tutor')->name('tutor.')->group(function () {
    
    // Home del tutor
    Route::get('/home', [TutorHomeController::class, 'index'])->name('home');
    Route::get('/homeTutor', fn() => view('tutor.homeTutor'));
    
    // Información de estudiantes
    Route::get('/estudiante/{id}', [TutorHomeController::class, 'getEstudianteDetails']);
    Route::get('/evaluaciones/{id}', [TutorHomeController::class, 'getEvaluaciones']);
    
    // Agendar citas
    Route::post('/agendar-cita', [TutorHomeController::class, 'agendarCita']);
});


/* ============================================
   RUTAS ADICIONALES (Sin clasificar específicamente)
   ============================================ */

// Ruta base del administrador (ajustar según necesites)
Route::get('/administrador/baseAdministrador', fn() => view('/administrador/baseAdministrador'));