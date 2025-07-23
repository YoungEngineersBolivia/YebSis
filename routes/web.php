<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/administrador/baseAdministrador');
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