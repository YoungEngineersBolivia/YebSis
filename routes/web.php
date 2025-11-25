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
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\ProfesorInventarioController;


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
    
    // Vistas base
    Route::get('/inicioAdministrador', fn() => view('/administrador/inicioAdministrador'));
    Route::get('/registrosAdministrador', fn() => view('/administrador/registrosAdministradores'));
    
  
    /* ----------------- GESTIÓN DE ADMINISTRADORES Y PERSONAL ----------------- */
Route::prefix('administrador')->name('administrador.')->group(function () {

    // Rutas POST (procesan los formularios)
    Route::post('/registrar', [AdministradorController::class, 'registrarAdmin'])->name('registrar');
    Route::post('/registrarC', [AdministradorController::class, 'registrarComercial'])->name('registrarC');
    Route::post('/registrarT', [AdministradorController::class, 'registrarTutor'])->name('registrarT');
    Route::post('/registrarP', [AdministradorController::class, 'registrarProfesor'])->name('registrarP');

    // Rutas GET (muestran formularios)
    Route::get('/registrarProfesor', fn() => view('administrador.registrarProfesor'))->name('formProfesor');
    Route::get('/registrarTutor', fn() => view('administrador.registrarTutor'))->name('formTutor');
    Route::get('/registrarComercial', fn() => view('administrador.registrarComercial'))->name('formComercial');
});

    
    /* ----------------- GESTIÓN DE TUTORES ----------------- */
    Route::prefix('tutores')->name('tutores.')->group(function () {
        Route::get('/', [TutoresController::class, 'index'])->name('index');
        Route::get('/{id}/detalles', [TutoresController::class, 'detalles'])->name('detalles'); // Nueva ruta
        Route::get('/{id}/edit', [TutoresController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TutoresController::class, 'update'])->name('update');
        Route::delete('/{id}', [TutoresController::class, 'destroy'])->name('destroy');
    });
    Route::get('/tutoresAdministrador', [TutoresController::class, 'index']);


   /* ----------------- GESTIÓN DE USUARIOS ----------------- */
Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');

// Actualizar usuario (desde modal)
Route::put('/usuarios/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');

// Eliminar usuario
Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');


    /* ----------------- GESTIÓN DE HORARIOS ----------------- */
    Route::prefix('horarios')->name('horarios.')->group(function () {
        Route::get('/create', [HorariosController::class, 'create'])->name('create');
        Route::post('/', [HorariosController::class, 'store'])->name('store');
        Route::get('/{id}', [HorariosController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [HorariosController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HorariosController::class, 'update'])->name('update');
        Route::delete('/{id}', [HorariosController::class, 'destroy'])->name('destroy');
        Route::post('/asignar', [HorariosController::class, 'asignar'])->name('asignar');
    });
    Route::get('/horariosAdministrador', [HorariosController::class, 'index'])->name('horarios.index');
    Route::get('/horarios/buscar-profesor/{idEstudiante}', [HorariosController::class, 'buscarProfesor']);

    /* ----------------- GESTIÓN DE ESTUDIANTES ----------------- */
    Route::get('/estudiantesAdministrador', [EstudianteController::class, 'index'])->name('admin.estudiantes');

    Route::put('/estudiantes/{id}/actualizar', [EstudianteController::class, 'actualizar'])->name('estudiantes.actualizar');
    Route::get('/estudiantes/{id}/planes-pago', [EstudianteController::class, 'planesPago'])->name('estudiantes.planesPago');
    Route::get('/estudiantes/{id}/evaluaciones', [EstudianteController::class, 'evaluaciones'])->name('estudiantes.evaluaciones');
    Route::get('/estudiantes/{id}/horarios', [EstudianteController::class, 'horarios'])->name('estudiantes.horarios');
    Route::put('/cuotas/{id}/registrar-pago', [CuotaController::class, 'registrarPago'])->name('cuotas.registrarPago');
    
    // Estudiantes Activos
    Route::get('/estudiantesActivos', [EstudiantesActivosController::class, 'index'])->name('estudiantesActivos');
    Route::get('/estudiantes-activos/exportar', [EstudiantesActivosController::class, 'exportar'])->name('estudiantesActivos.exportar');
    Route::put('/estudiantes/desactivar/{id}', [EstudiantesActivosController::class, 'desactivar'])->name('estudiantes.desactivar');
    
    // Estudiantes Inactivos
    Route::get('/estudiantesNoActivos', [EstudiantesInactivosController::class, 'index'])->name('estudiantesNoActivos');
    Route::put('/estudiantes/activar/{id}', [EstudiantesInactivosController::class, 'reactivar'])->name('estudiantes.reactivar');
    
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


Route::get('/graduadosAdministrador', [GraduadoController::class, 'mostrarGraduados'])->name('graduados.mostrar');
Route::post('/graduados', [GraduadoController::class, 'agregarGraduado'])->name('graduados.agregar');
Route::put('/graduados/{id}', [GraduadoController::class, 'actualizarGraduado'])->name('graduados.actualizar');
Route::delete('/graduados/{id}', [GraduadoController::class, 'eliminarGraduado'])->name('graduados.eliminar');


  
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
    Route::put('/egresos/{Id_egreso}', [EgresosController::class, 'update'])->name('egresos.update');
    Route::delete('/egresos/{Id_egreso}', [EgresosController::class, 'destroy'])->name('egresos.destroy');
    
    /* ----------------- PUBLICACIONES Y NOTIFICACIONES ----------------- */
    Route::get('/pubnotAdministrador', [PubNot::class, 'index'])->name('publicaciones.index');
    Route::post('/pubnotAdministrador', [PubNot::class, 'store'])->name('publicaciones.store');
    Route::delete('/pubnotAdministrador/{id}', [PubNot::class, 'destroy'])->name('publicaciones.destroy');
    Route::post('/notificaciones', [PubNot::class, 'storeNotificacion'])->name('notificaciones.store');
    
    /* ----------------- REGISTRO COMBINADO (TUTOR + ESTUDIANTE) ----------------- */
    Route::get('/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'mostrarFormulario'])->name('registroCombinado.form');
    Route::post('/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'registrar'])->name('registroCombinado.registrar');
    
    /* ----------------- COMPONENTES/MOTORES ----------------- */
    Route::prefix('componentes')->name('componentes.')->group(function () {
        Route::get('/', [ComponentesController::class, 'index'])->name('index');
        Route::post('/nuevo', [ComponentesController::class, 'store'])->name('store');
        Route::post('/entrada', [ComponentesController::class, 'registrarEntrada'])->name('registrarEntrada');
        Route::post('/salida', [ComponentesController::class, 'registrarSalida'])->name('registrarSalida');
        Route::get('/{id}/historial', [ComponentesController::class, 'historial'])->name('historial');
        Route::put('/{id}', [ComponentesController::class, 'update'])->name('update');
        Route::delete('/{motor}', [ComponentesController::class, 'destroy'])->name('destroy');
    });
    
 Route::prefix('profesores')->name('profesores.')->group(function () {
    Route::get('/', [ProfesorController::class, 'index'])->name('index');
    Route::post('/', [ProfesorController::class, 'store'])->name('store');
    Route::get('/create', [ProfesorController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [ProfesorController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProfesorController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProfesorController::class, 'destroy'])->name('destroy');

  
});

    
    /* ----------------- MOTORES ASIGNADOS ----------------- */
    // Rutas para Gestión de Componentes (Inventario)
    Route::prefix('componentes')->name('componentes.')->group(function () {
        Route::get('/', [ComponentesController::class, 'index'])->name('index');
        Route::post('/nuevo', [ComponentesController::class, 'store'])->name('store');
        Route::post('/entrada', [ComponentesController::class, 'registrarEntrada'])->name('registrarEntrada');
        Route::post('/salida', [ComponentesController::class, 'registrarSalida'])->name('registrarSalida');
        Route::get('/{id}/historial', [ComponentesController::class, 'historial'])->name('historial');
        Route::put('/{id}', [ComponentesController::class, 'update'])->name('update');
        Route::delete('/{motor}', [ComponentesController::class, 'destroy'])->name('destroy');
    });

    // Rutas para Motores Asignados a Técnicos
    Route::prefix('motores')->name('motores.')->group(function () {
        // Lista de motores asignados (solo en proceso)
        Route::get('/asignaciones', [MotoresAsignadosController::class, 'index'])->name('asignaciones.index');
        
        // Formulario para asignar motor a técnico
        Route::get('/asignar', [MotoresAsignadosController::class, 'create'])->name('asignar.create');
        
        // Guardar asignación de motor
        Route::post('/asignar', [MotoresAsignadosController::class, 'store'])->name('asignar.store');
        
        // Registrar entrada del motor (devolución al inventario)
        Route::post('/registrar-entrada/{id}', [MotoresAsignadosController::class, 'registrarEntrada'])->name('registrar.entrada');
        
        // Guardar reporte de mantenimiento
        Route::post('/reporte/{id}', [MotoresAsignadosController::class, 'storeReporte'])->name('reporte.store');
        
        // Historial de asignaciones completadas (opcional)
        Route::get('/historial', [MotoresAsignadosController::class, 'historial'])->name('historial');
    });
    
    /* ----------------- REPORTES DE TALLERES ----------------- */
    Route::get('/talleresComercial', [ReporteTalleresController::class, 'index'])->name('reportes.talleres');
    Route::get('/reportes/talleres/exportar', [ReporteTalleresController::class, 'exportar'])->name('reportes.talleres.exportar');
    Route::get('/api/reportes/talleres/datos', [ReporteTalleresController::class, 'obtenerDatos'])->name('api.reportes.talleres.datos');
    
    /* ----------------- PROSPECTOS (ÁREA COMERCIAL) ----------------- */
    Route::get('/prospectosComercial', [ProspectoController::class, 'index'])->name('prospectos.comercial');
    Route::put('/prospectos/{id}/estado', [ProspectoController::class, 'updateEstado'])->name('prospectos.updateEstado');
    
    /* ----------------- CLASES DE PRUEBA ----------------- */
    Route::post('/claseprueba/store', [ClasePruebaController::class, 'store'])->name('claseprueba.store');
    
    /* ----------------- ESTUDIANTES CRUD (compartido) ----------------- */
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::put('/estudiantes/editar/{id}', [EstudianteController::class, 'editar'])->name('estudiantes.editar');
    Route::delete('/estudiantes/{id}', [EstudianteController::class, 'eliminar'])->name('estudiantes.eliminar');
    Route::get('/estudiantes/{id}', [EstudianteController::class, 'ver'])->name('estudiantes.ver');
    Route::put('/estudiantes/{id}/cambiar-estado', [EstudianteController::class, 'cambiarEstado'])->name('estudiantes.cambiarEstado');
    
    /* ----------------- PROGRAMAS ----------------- */
    
    Route::prefix('programa')->group(function () {
        Route::get('/', [ProgramaController::class, 'index'])->name('programas.index');
        Route::post('/', [ProgramaController::class, 'store'])->name('programas.store');
        Route::get('/{id}', [ProgramaController::class, 'show'])->name('programas.show');
        Route::get('/{id}/edit', [ProgramaController::class, 'edit']);
        Route::put('/{id}', [ProgramaController::class, 'update'])->name('programas.update');
        Route::delete('/{id}', [ProgramaController::class, 'destroy'])->name('programas.destroy');
    });

    // Rutas de modelos
    Route::prefix('programas/{programaId}/modelos')->group(function () {
        Route::get('/', [ModeloController::class, 'index'])->name('modelos.index');
        Route::post('/', [ModeloController::class, 'store'])->name('modelos.store');
        Route::get('/{modeloId}/edit', [ModeloController::class, 'edit'])->name('modelos.edit');
        Route::put('/{modeloId}', [ModeloController::class, 'update'])->name('modelos.update');
        Route::delete('/{modeloId}', [ModeloController::class, 'destroy'])->name('modelos.destroy');
    });
    
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

    // ========== RUTAS DE INVENTARIO DE COMPONENTES ==========
    
    // Vista principal del inventario de componentes asignados
    Route::get('/inventario-componentes', [ProfesorInventarioController::class, 'index'])
        ->name('inventario.index');
    
    // Solicitar salida de motor
    Route::post('/inventario-componentes/solicitar', [ProfesorInventarioController::class, 'solicitarSalida'])
        ->name('inventario.solicitar');
    
    // Asignar componente a profesor
    Route::post('/inventario-componentes/asignar', [ProfesorInventarioController::class, 'asignarComponente'])
        ->name('inventario.asignar');
    
    // Devolver componente
    Route::post('/inventario-componentes/{id}/devolver', [ProfesorInventarioController::class, 'devolverComponente'])
        ->name('inventario.devolver');
    
    // Ver detalle de asignación
    Route::get('/inventario-componentes/{id}/detalle', [ProfesorInventarioController::class, 'verDetalle'])
        ->name('inventario.detalle');
    
    // Obtener estadísticas
    Route::get('/inventario-componentes/estadisticas', [ProfesorInventarioController::class, 'estadisticas'])
        ->name('inventario.estadisticas');
    
    Route::get('/mis-motores-asignados', [ProfesorInventarioController::class, 'misMotoresAsignados'])
    ->name('inventario.mis-motores');
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
   RUTAS ANTIGUAS (mantener por compatibilidad)
   ============================================ */

// Rutas comercial antiguas (redirigen a administrador)
Route::middleware(['auth', 'role:administrador'])->prefix('comercial')->group(function () {
    Route::get('/estudiantesActivos', fn() => redirect('/administrador/estudiantesActivos'));
    Route::get('/estudiantesNoActivos', fn() => redirect('/administrador/estudiantesNoActivos'));
    Route::get('/talleresComercial', fn() => redirect('/administrador/talleresComercial'));
    Route::get('/prospectosComercial', fn() => redirect('/administrador/prospectosComercial'));
    Route::get('/estudianteActivoComercial', fn() => redirect('/administrador/estudiantesActivos'));
});

// Rutas sin prefijo para profesores (acceso directo)
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/profesores', fn() => redirect('/administrador/profesores'));
});

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

