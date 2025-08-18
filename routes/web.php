<?php

use App\Http\Controllers\PlanesPagoController;
use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistroCombinadoController;


use App\Http\Controllers\PubNot;
use App\Http\Controllers\PagosController;
// POST ROUTES

Route::post('/administradores/registrar', [AdministradorController::class, 'registrarAdmin'])->name('administrador.registrar');

Route::post('/administradores/registrarC', [AdministradorController::class, 'registrarComercial'])->name('administrador.registrarC');

Route::post('/administradores/registrarT', [AdministradorController::class, 'registrarTutor'])->name('administrador.registrarT');

Route::post('/administradores/registrarP', [AdministradorController::class, 'registrarProfesor'])->name('administrador.registrarP');

Route::get('/estudiantes/registrar', [EstudianteController::class, 'mostrarFormulario'])->name('estudiantes.formulario');
Route::post('/estudiantes/registrar', [EstudianteController::class, 'registrar'])->name('estudiantes.registrar');

//GET ROUTES

Route::get('/administrador/dashboardAdministrador', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::get('/administrador/registrarProfesor', function(){
    return view('/administrador/registrarProfesor');
});

Route::get('/administrador/registrarTutor', function(){
    return view('/administrador/registrarTutor');
});

Route::get('/administrador/registrarComercial', function() {
    return view('/administrador/registrarComercial');
});

Route::get('/', function () {
    return view('/administrador/baseAdministrador');
});

Route::get('/administrador/inicioAdministrador', function () {
    return view('/administrador/inicioAdministrador'); 
});


Route::get('/administrador/registrosAdministrador', function () {
    return view('/administrador/registrosAdministradores'); 
});

//TUTORES

Route::get('/administrador/tutoresAdministrador', [TutoresController::class, 'index'])->name('tutores.index');
Route::get('/administrador/tutores/{id}', [TutoresController::class, 'show'])->name('tutores.show');
Route::get('/administrador/tutores/{id}/edit', [TutoresController::class, 'edit'])->name('tutores.edit');
Route::put('/administrador/tutores/{id}', [TutoresController::class, 'update'])->name('tutores.update');
Route::delete('/administrador/tutores/{id}', [TutoresController::class, 'destroy'])->name('tutores.destroy');

//USUARIOS
Route::get('/administrador/usuariosAdministrador', [UsuariosController::class, 'index'])->name('usuarios.index');

Route::get('/administrador/usuarios/{id}', [UsuariosController::class, 'show'])->name('usuarios.show');
Route::get('/administrador/usuarios/{id}/edit', [UsuariosController::class, 'edit'])->name('usuarios.edit');
Route::put('/administrador/usuarios/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
Route::delete('/administrador/usuarios/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');


//

Route::get('/administrador/horariosAdministrador', [HorariosController::class, 'index'])->name('horarios.index');

Route::get('/administrador/horarios/create', [HorariosController::class, 'create'])->name('horarios.create');

Route::post('/administrador/horarios', [HorariosController::class, 'store'])->name('horarios.store');

Route::get('/administrador/horarios/{id}', [HorariosController::class, 'show'])->name('horarios.show');

Route::get('/administrador/horarios/{id}/edit', [HorariosController::class, 'edit'])->name('horarios.edit');

Route::put('/administrador/horarios/{id}', [HorariosController::class, 'update'])->name('horarios.update');

Route::delete('/administrador/horarios/{id}', [HorariosController::class, 'destroy'])->name('horarios.destroy');

Route::post('/administrador/horarios/asignar', [HorariosController::class, 'asignar'])->name('horarios.asignar');

//


Route::get('administrador/estudiantesAdministrador', function () {
    return view('/administrador/estudiantesAdministrador');
});

Route::get('/administrador/estudiantesAdministrador', [EstudianteController::class, 'index'])->name('admin.estudiantes');

Route::get('administrador/pubnotAdministrador', function () {
    return view('/administrador/pubnotAdministrador');
});

Route::get('/administrador/programasAdministrador', [ProgramaController::class, 'index']);

 Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
    
    // Ruta para crear un nuevo programa
    Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');
    
    // Ruta para mostrar un programa específico
    Route::get('/programas/{id}', [ProgramaController::class, 'show'])->name('programas.show');
    
    // Ruta para mostrar el formulario de edición
     Route::get('/admin/programas/{id}/edit', [ProgramaController::class, 'edit'])->name('programas.edit');

    
    // Ruta para actualizar un programa
    Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('programas.update');
    
    // Ruta para eliminar un programa
    Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('programas.destroy');


Route::get('administrador/nuevosProgramasAdministrador', function(){
    return view('administrador.nuevoProgramaAdministrador');
});

Route::get('/administrador/graduadosAdministrador', [GraduadoController::class, 'index'])->name('graduados.index');

Route::get('/administrador/pagosAdministrador', [PagosController::class, 'form'])->name('pagosAdministrador');
Route::post('/administrador/pagosAdministrador', [PagosController::class, 'registrarPago'])->name('pagos.registrar');
Route::get('/administrador/pagos', [PagosController::class, 'index'])->name('pagos.index');

Route::get('/administrador/sucursalesAdministrador', [SucursalController::class, 'index'])->name('sucursales.index');
Route::post('/administrador/sucursalesAdministrador', [SucursalController::class, 'store'])->name('sucursales.store');

Route::get('/administrador/egresosAdministrador',[EgresosController::class,'index'])->name('egresos.index');
Route::post('/administrador/egresosAdministrador',[EgresosController::class, 'store'])->name('egresos.store');

Route::get('/egresos/crear', [EgresoController::class, 'create'])->name('egreso.crear'); 
Route::post('/egresos/registrar', [EgresoController::class, 'store'])->name('egreso.registrar'); 



Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');


Route::get('/administrador/pubnotAdministrador', [PubNot::class, 'index'])->name('publicaciones.index');
Route::post('/administrador/pubnotAdministrador', [PubNot::class, 'store'])->name('publicaciones.store');
Route::delete('/administrador/pubnotAdministrador/{id}', [PubNot::class, 'destroy'])->name('publicaciones.destroy');

//para despues//
Route::middleware(['auth', 'role:admin'])->group(function () {
   
});
Route::get('/administrador/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'mostrarFormulario'])->name('registroCombinado.form');
Route::post('/administrador/tutorEstudianteAdministrador', [RegistroCombinadoController::class, 'registrar'])->name('registroCombinado.registrar');
Route::post('/planes-pago/registrar', [PlanesPagoController::class, 'registrar'])->name('planes-pago.registrar');
// Notificaciones a tutores
Route::post('/administrador/notificaciones', [PubNot::class, 'store'])->name('notificaciones.store');


// DASHBOARD
Route::get('/administrador/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/administrador/dashboard', [DashboardController::class, 'index'])->name('dashboard');


//ESTUDIANTES
Route::get('/estudiantes/{id}/editar', [EstudianteController::class, 'editar'])->name('estudiantes.editar');
Route::put('/estudiantes/{id}', [EstudianteController::class, 'actualizar'])->name('estudiantes.actualizar');
Route::delete('/estudiantes/{id}', [EstudianteController::class, 'eliminar'])->name('estudiantes.eliminar');
Route::get('/estudiantes/{id}', [EstudianteController::class, 'ver'])->name('estudiantes.ver');

