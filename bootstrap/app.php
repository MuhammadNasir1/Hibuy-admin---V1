<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\customMiddleware;
use App\Http\Middleware\ForceNumericJson; // ✅ Make sure to import the middleware
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ Register middleware alias if needed
        $middleware->alias([
            'custom_auth' => customMiddleware::class,
            // 'check_privileges' => CheckPrivileges::class,
        ]);

        // ✅ Append global middleware
        $middleware->append(ForceNumericJson::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
