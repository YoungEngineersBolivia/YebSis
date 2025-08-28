<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\RegistroAdministradorController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\TutoresController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\GraduadoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\EgresosController;   // <- asegurarse que este controlador existe
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\RegistroCombinadoController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\PubNot;
use App\Http\Controllers\EstudiantesInactivosController;


/* -------------------Home pagina <web-------------------------*/

Route::get('/', function () {
    return view('/paginaWeb/home'); // Retorna la vista welcome.blade.php
});



/* ----------------- HOME / BASE Administrador  ----------------- */
//Route::get('/', fn () => view('/administrador/baseAdministrador'));

/* ----------------- DASHBOARD ----------------- */
Route::get('/administrador/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard'); // usa SIEMPRE este nombre

/* ----------------- ADMINISTRADORES ----------------- */
Route::post('/administradores/registrar', [AdministradorController::class, 'registrarAdmin'])->name('administrador.registrar');
Route::post('/administradores/registrarC', [AdministradorController::class, 'registrarComercial'])->name('administrador.registrarC');
Route::post('/administradores/registrarT', [AdministradorController::class, 'registrarTutor'])->name('administrador.registrarT');
Route::post('/administradores/registrarP', [AdministradorController::class, 'registrarProfesor'])->name('administrador.registrarP');

Route::get('/administrador/registrarProfesor', fn () => view('/administrador/registrarProfesor'));
Route::get('/administrador/registrarTutor', fn () => view('/administrador/registrarTutor'));
Route::get('/administrador/registrarComercial', fn () => view('/administrador/registrarComercial'));
Route::get('/administrador/inicioAdministrador', fn () => view('/administrador/inicioAdministrador'));
Route::get('/administrador/registrosAdministrador', fn () => view('/administrador/registrosAdministradores'));

/* ----------------- TUTORES ----------------- */
Route::get('/administrador/tutoresAdministrador', [TutoresController::class, 'index'])->name('tutores.index');
Route::get('/administrador/tutores/{id}', [TutoresController::class, 'show'])->name('tutores.show');
Route::get('/administrador/tutores/{id}/edit', [TutoresController::class, 'edit'])->name('tutores.edit');
Route::put('/administrador/tutores/{id}', [TutoresController::class, 'update'])->name('tutores.update');
Route::delete('/administrador/tutores/{id}', [TutoresController::class, 'destroy'])->name('tutores.destroy');

/* ----------------- USUARIOS ----------------- */
Route::get('/administrador/usuariosAdministrador', [UsuariosController::class, 'index'])->name('usuarios.index');
Route::get('/administrador/usuarios/{id}', [UsuariosController::class, 'show'])->name('usuarios.show');
Route::get('/administrador/usuarios/{id}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit');
Route::put('/administrador/usuarios/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
Route::delete('/administrador/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');

/* ----------------- HORARIOS ----------------- */
Route::get('/administrador/horariosAdministrador', [HorariosController::class, 'index'])->name('horarios.index');
Route::get('/administrador/horarios/create', [HorariosController::class, 'create'])->name('horarios.create');
Route::post('/administrador/horarios', [HorariosController::class, 'store'])->name('horarios.store');
Route::get('/administrador/horarios/{id}', [HorariosController::class, 'show'])->name('horarios.show');
Route::get('/administrador/horarios/{id}/edit', [HorariosController::class, 'edit'])->name('horarios.edit');
Route::put('/administrador/horarios/{id}', [HorariosController::class, 'update'])->name('horarios.update');
Route::delete('/administrador/horarios/{id}', [HorariosController::class, 'destroy'])->name('horarios.destroy');
Route::post('/administrador/horarios/asignar', [HorariosController::class, 'asignar'])->name('horarios.asignar');

/* ----------------- ESTUDIANTES ----------------- */

Route::get('/administrador/estudiantesAdministrador', [EstudianteController::class, 'index'])->name('admin.estudiantes');

Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
Route::put('/estudiantes/editar/{id}', [EstudianteController::class, 'editar'])->name('estudiantes.editar');
Route::delete('/estudiantes/{id}', [EstudianteController::class, 'eliminar'])->name('estudiantes.eliminar');
Route::get('/estudiantes/{id}', [EstudianteController::class, 'ver'])->name('estudiantes.ver');
Route::put('/estudiantes/{id}/cambiar-estado', [EstudianteController::class, 'cambiarEstado'])->name('estudiantes.cambiarEstado');


/* ----------------- PROGRAMAS ----------------- */
Route::get('/administrador/programasAdministrador', [ProgramaController::class, 'index']);
Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');
Route::get('/programas/{id}', [ProgramaController::class, 'show'])->name('programas.show');
Route::get('/admin/programas/{id}/edit', [ProgramaController::class, 'edit'])->name('programas.edit');
Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('programas.update');
Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('programas.destroy');
Route::get('administrador/nuevosProgramasAdministrador', fn () => view('administrador.nuevoProgramaAdministrador'));

/* ----------------- GRADUADOS ----------------- */
Route::get('/administrador/graduadosAdministrador', [GraduadoController::class, 'index'])->name('graduados.index');

/* ----------------- PAGOS ----------------- */
Route::get('/administrador/pagosAdministrador', [PagosController::class, 'form'])->name('pagos.form');  
Route::get('/administrador/pagos', [PagosController::class, 'index'])->name('pagos.index'); // filtro por nombre
Route::post('/administrador/pagosAdministrador', [PagosController::class, 'registrarPago'])->name('pagos.registrar');

/* ----------------- SUCURSALES ----------------- */
Route::get('/administrador/sucursalesAdministrador', [SucursalController::class, 'index'])->name('sucursales.index');
Route::post('/administrador/sucursalesAdministrador', [SucursalController::class, 'store'])->name('sucursales.store');

/* ----------------- EGRESOS ----------------- */
Route::get('/administrador/egresosAdministrador', [EgresosController::class, 'index'])->name('egresos.index');
Route::post('/administrador/egresosAdministrador', [EgresosController::class, 'store'])->name('egresos.store');
// Si tienes vistas para crear/registrar egresos, mantén el MISMO controlador:
Route::get('/administrador/egresos/crear', [EgresosController::class, 'create'])->name('egresos.crear');
Route::post('/administrador/egresos/registrar', [EgresosController::class, 'store'])->name('egresos.registrar');

/* ----------------- PUBLICACIONES / NOTIFICACIONES ----------------- */
Route::get('/administrador/pubnotAdministrador', [PubNot::class, 'index'])->name('publicaciones.index');
Route::post('/administrador/pubnotAdministrador', [PubNot::class, 'store'])->name('publicaciones.store');
Route::delete('/administrador/pubnotAdministrador/{id}', [PubNot::class, 'destroy'])->name('publicaciones.destroy');
Route::post('/administrador/notificaciones', [PubNot::class, 'store'])->name('notificaciones.store');

/* ----------------- REGISTRO COMBINADO / PLANES ----------------- */
Route::get('/administrador/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'mostrarFormulario'])->name('registroCombinado.form');
Route::post('/administrador/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'registrar'])->name('registroCombinado.registrar');
Route::post('/planes-pago/registrar', [App\Http\Controllers\PlanesPagoController::class, 'registrar'])->name('planes-pago.registrar');

/* ----------------- MIDDLEWARE (reserva para después) ----------------- */
// Route::middleware(['auth', 'role:admin'])->group(function () { /* ... */ });

/*-------------------ESTUDIANTES NO ACTIVOS-----------------*/
Route::get('/comercial/estudiantesNoActivos', [EstudiantesInactivosController::class, 'index'])->name('estudiantesNoActivos');
Route::put('/estudiantes/activar/{id}', [EstudiantesInactivosController::class, 'reactivar'])->name('estudiantes.reactivar');


