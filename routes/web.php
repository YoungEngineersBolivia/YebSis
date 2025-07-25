<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroAdministradorController;
use App\Http\Controllers\EstudianteController;

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

// POST ROUTES
Route::post('/administrador/registrosAdministrador', [RegistroAdministradorController::class, 'registrarAdmin'])
    ->name('registroAdmin.registrarAdmin');

Route::get('administrador/estudiantesAdministrador', function () {
    return view('/administrador/estudiantesAdministrador');
});

Route::get('/administrador/estudiantesAdministrador', [EstudianteController::class, 'index'])->name('admin.estudiantes');

Route::get('administrador/pubnotAdministrador', function () {
    return view('/administrador/pubnotAdministrador');
});
Route::get('/administrador/programasAdministrador', function () {
    return view('/administrador/programasAdministrador');
});
Route::get('/administrador/programasAdministrador', function () {
    return view('/administrador/programasAdministrador');
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

