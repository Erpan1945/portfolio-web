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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// LOGIKA VERCEL
if (env('VERCEL') == '1') {
    // Tentukan folder storage di /tmp
    $app->useStoragePath('/tmp/storage');
    
    // Buat subfolder yang dibutuhkan Laravel untuk menyimpan cache views
    $storageFolders = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/sessions',
    ];

    foreach ($storageFolders as $folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
    }
}

return $app;