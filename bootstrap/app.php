<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Spatie\HttpLogger\Middlewares\HttpLogger;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::name('nanomdm.')
                ->group(base_path('routes/nanomdm.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(HttpLogger::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
