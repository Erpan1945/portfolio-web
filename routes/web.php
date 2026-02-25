<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

// Jangan load web routes untuk /api/* requests
// Ini penting untuk Vercel PHP di api/index.php
if (strpos(request()->getPathInfo(), '/api/') !== 0) {
    
    // Route untuk menjalankan migrasi di Vercel
    Route::get('/deploy/migrate/{secret}', function ($secret) {
        // Validasi kunci rahasia agar tidak sembarang orang bisa mereset DB Anda
        if ($secret !== env('MIGRATE_SECRET')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Menjalankan perintah php artisan migrate --force
            Artisan::call('migrate', ['--force' => true]);

            // Menjalankan seeder (untuk isi data awal project & sertifikat)
            Artisan::call('db:seed', ['--force' => true]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Database migration completed.',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    // CATCH-ALL ROUTE UNTUK SPA - HANYA UNTUK WEB (BUKAN API)
    Route::fallback(function () {
        return view('welcome');
    });
}