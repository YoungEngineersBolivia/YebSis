<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\RegistroAdministradorController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\GraduadoController;
use App\Http\Controllers\SucursalController;

// POST ROUTES

Route::post('/administradores/registrar', [AdministradorController::class, 'registrarAdmin'])->name('administrador.registrar');

Route::post('/administradores/registrarC', [AdministradorController::class, 'registrarComercial'])->name('administrador.registrarC');

Route::post('/administradores/registrarT', [AdministradorController::class, 'registrarTutor'])->name('administrador.registrarT');

Route::post('/administradores/registrarP', [AdministradorController::class, 'registrarProfesor'])->name('administrador.registrarP');

Route::get('/estudiantes/registrar', [EstudianteController::class, 'mostrarFormulario'])->name('estudiantes.formulario');
Route::post('/estudiantes/registrar', [EstudianteController::class, 'registrar'])->name('estudiantes.registrar');

//GET ROUTES

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
Route::get('/administrador/egresosAdministrador', function () {
    return view('/administrador/egresosAdministrador');
});

Route::get('/administrador/registrosAdministrador', function () {
    return view('/administrador/registrosAdministradores'); 
});

Route::get('/administrador/tutoresAdministrador', function () {
    return view('/administrador/tutoresAdministrador'); 
});

Route::get('/administrador/usuariosAdministrador', function () {
    return view('/administrador/usuariosAdministrador'); 
});

Route::get('/administrador/horariosAdministrador', function () {
    return view('/administrador/horariosAdministrador'); 
});


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

Route::get('/administrador/pagosAdministrador',function () {
    return view('/administrador/pagosAdministrador');
});

Route::get('/administrador/sucursalesAdministrador', [SucursalController::class, 'index'])->name('sucursales.index');
Route::post('/administrador/sucursalesAdministrador', [SucursalController::class, 'store'])->name('sucursales.store');


Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');