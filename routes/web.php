<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroAdministradorController;

Route::get('/', function () {
    return view('/administrador/baseAdministrador');
});

// Cambia esta ruta para que coincida con la POST
Route::get('/administrador/registrosAdministrador', [RegistroAdministradorController::class, 'index']);

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