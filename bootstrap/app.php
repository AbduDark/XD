<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware globally for authenticated routes
        $middleware->web(append: [
            \App\Http\Middleware\ResolveCurrentStore::class,
        ]);
        
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'store.resolve' => \App\Http\Middleware\ResolveCurrentStore::class,
            'store.access' => \App\Http\Middleware\CheckStoreAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
