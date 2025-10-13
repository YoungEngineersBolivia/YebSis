<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Directorios temporales
$tmpCachePath = '/tmp/bootstrap/cache';
$tmpStoragePath = '/tmp/storage';

if (!is_dir($tmpCachePath)) mkdir($tmpCachePath, 0777, true);
if (!is_dir($tmpStoragePath)) mkdir($tmpStoragePath, 0777, true);

// Variables de entorno para serverless
putenv("VIEW_COMPILED_PATH=/tmp/storage/framework/views");
putenv("APP_CONFIG_CACHE={$tmpCachePath}/config.php");
putenv("APP_PACKAGES_CACHE={$tmpCachePath}/packages.php");
putenv("APP_ROUTES_CACHE={$tmpCachePath}/routes-v7.php");
putenv("STORAGE_PATH={$tmpStoragePath}");

// Crear la aplicación
$app = Application::configure(basePath: dirname(__DIR__))
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

// Sobrescribir PackageManifest (solo 3 argumentos en Laravel 12)
$app->singleton(
    Illuminate\Foundation\PackageManifest::class,
    function ($app) use ($tmpCachePath) {
        return new Illuminate\Foundation\PackageManifest(
            $app->basePath(),
            $app->configPath(),
            $tmpCachePath . '/packages.php'
        );
    }
);

return $app;
