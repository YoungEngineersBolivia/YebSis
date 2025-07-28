<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\RegistroAdministradorController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AdministradorController;

// POST ROUTES
    
Route::post('/administradores/registrar', [AdministradorController::class, 'registrarAdmin'])->name('administrador.registrar');

//GET ROUTES

Route::get('/administradores/registro', [AdministradorController::class, 'index'])->name('administrador.formulario');

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
Route::get('administrador/nuevosProgramasAdministrador', function(){
    return view('administrador.nuevoProgramaAdministrador');
});
Route::get('/administrador/graduadosAdministrador', function () {
    return view('/administrador/graduadosAdministrador'); 
});

Route::get('/administrador/pagosAdministrador',function () {
    return view('/administrador/pagosAdministrador');
});

Route::get('/administrador/sucursalesAdministrador',function (){
    return view('/administrador/sucursalesAdministrador');
});

