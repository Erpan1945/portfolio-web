<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // PENTING: Tambahkan CORS middleware
        $middleware->statefulApi();
        
        // Exclude public API routes dari CSRF protection
        $middleware->validateCsrfTokens(except: [
            'api/send-message',
            'api/login',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// LOGIKA VERCEL (Agresif) - DISABLE ROUTE CACHING
if (isset($_SERVER['VERCEL']) || env('VERCEL') == '1') {
    $app->useStoragePath('/tmp/storage');
    
    // DISABLE ROUTE CACHE - PENTING!
    putenv('APP_ROUTES_CACHE=');
    $_ENV['APP_ROUTES_CACHE'] = '';
    
    $paths = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/logs',
    ];

    foreach ($paths as $path) {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}

return $app;