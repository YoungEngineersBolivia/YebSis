<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// --- Crear directorio temporal para cache ---
$tmpCachePath = '/tmp/bootstrap/cache';
if (!is_dir($tmpCachePath)) {
    mkdir($tmpCachePath, 0777, true);
}

// Redirigir storage y cache de Laravel a /tmp
putenv("VIEW_COMPILED_PATH=/tmp/storage/framework/views");
putenv("APP_CONFIG_CACHE={$tmpCachePath}/config.php");
putenv("APP_SERVICES_CACHE={$tmpCachePath}/services.php");
putenv("APP_PACKAGES_CACHE={$tmpCachePath}/packages.php");
putenv("APP_ROUTES_CACHE={$tmpCachePath}/routes-v7.php");
$appStoragePath = '/tmp/storage';

// --- Crear la aplicación ---
return Application::configure(basePath: dirname(__DIR__))
    ->useStoragePath($appStoragePath)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
