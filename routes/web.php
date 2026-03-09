<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

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

// jika Vercel menghapus prefiks "/api" pada request POST, kita
// tetap butuh rute login bekerja. Tambahkan rute POST login di sisi web
// (tanpa middleware api) sehingga path /login diterima.
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

// CATCH-ALL ROUTE UNTUK SPA - HARUS PALING AKHIR!
// Tangkap semua request yang bukan static files atau API
Route::fallback(function () {
    return view('welcome');
});