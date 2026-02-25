<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

// HAPUS atau KOMENTARI route default lama:
// Route::get('/', function () {
//     return view('welcome');
// });

// CATCH-ALL ROUTE - HARUS PALING AKHIR!
// Jangan tangkap /api/* routes - gunakan negative lookahead
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api/).*');

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