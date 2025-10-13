<?php
// Muestra errores temporalmente
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Carga el autoloader de Composer
require __DIR__ . '/../vendor/autoload.php';

// Carga la aplicación de Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Maneja la request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
$response->send();
$kernel->terminate($request, $response);
