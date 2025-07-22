<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/administrador/HomeAdministrador');
});

Route::get('/administrador/inicioAdministrador', function () {
    return view('/administrador/inicioAdministrador'); 
});